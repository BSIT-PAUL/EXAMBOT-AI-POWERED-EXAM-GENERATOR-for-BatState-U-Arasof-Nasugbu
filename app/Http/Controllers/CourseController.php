<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser;
use App\Models\CourseInformation;
use App\Models\CourseChapters;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\TableOfSpecification;

class CourseController extends Controller
{
    private function ocrPdf($inputPath, $outputPath)
    {
        $command = escapeshellcmd("ocrmypdf --force-ocr --output-type pdfa \"$inputPath\" \"$outputPath\"");
        $output = [];
        $returnCode = 0;

        exec($command, $output, $returnCode);

        Log::info('OCRmyPDF command output', ['output' => $output, 'return_code' => $returnCode]);

        if ($returnCode !== 0) {
            throw new \Exception("Failed to convert PDF to OCR: " . implode("\n", $output));
        }
    }
    public function uploadCIS(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:2048',
        ]);

        $file = $request->file('file');
        $inputPath = $file->store('uploads');
        $inputPath = storage_path('app/' . $inputPath);
        $outputPath = storage_path('app/uploads/ocr_' . $file->getClientOriginalName());

        try {
            $this->ocrPdf($inputPath, $outputPath);
            $pdfText = $this->extractTextFromPDF($outputPath);
            $courseData = $this->parseCourseDataWithOpenAI($pdfText);

            $jsonFilePath = storage_path('app/course_data/' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.json');
            file_put_contents($jsonFilePath, json_encode($courseData, JSON_PRETTY_PRINT));

            $this->saveCourseDataToDatabase($courseData, Auth::id());

            return redirect()->back()->with('success', 'Course information extracted and saved as JSON file successfully.');
        } catch (\Exception $e) {
            Log::error('Error in uploadCIS', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function extractTextFromPDF($filePath)
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($filePath);
        return $pdf->getText();
    }

    private function parseCourseDataWithOpenAI($text)
    {
        $prompt = "
        Extract the course information from the following text and format it in JSON with the structure provided below. Ensure all fields are included, even if some fields need to be left empty if not available in the text.:

        {
            \"Course Information\": {
                \"Course Code\": \"\",
                \"Course Title\": \"\",
                \"Semester/Year\": \"\"
            },
            \"Course Chapters\": [
                {
                    \"Main Topic number\": 0,
                    \"Main Topic\": \"\",
                    \"Topic Outcomes\": \"\"
                }
            ]
        }

        Text:
        {$text}
        ";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => 1500,
            ]);

            if ($response->failed()) {
                throw new \Exception('Failed to call OpenAI API: ' . $response->body());
            }

            $responseData = $response->json();
            Log::info('OpenAI API response', ['response' => $responseData]);

            if (!isset($responseData['choices']) || empty($responseData['choices'])) {
                throw new \Exception('Invalid response from OpenAI API: ' . json_encode($responseData));
            }

            $content = $responseData['choices'][0]['message']['content'] ?? null;

            if (!$content) {
                throw new \Exception('Invalid response format from OpenAI API: ' . json_encode($responseData));
            }

            Log::info('Extracted Content from OpenAI:', ['content' => $content]);

            $content = trim($content, "```json\n ");
            $content = trim($content, "```");

            return json_decode($content, true);
        } catch (\Exception $e) {
            Log::error('Error in parseCourseDataWithOpenAI', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    private function saveCourseDataToDatabase(array $courseData, $userId)
    {
        $courseInfo = $courseData['Course Information'];
        $course = CourseInformation::create([
            'course_code' => $courseInfo['Course Code'],
            'course_title' => $courseInfo['Course Title'],
            'semester_year' => $courseInfo['Semester/Year'],
            'user_id' => $userId,
        ]);

        foreach ($courseData['Course Chapters'] as $chapter) {
            CourseChapters::create([
                'course_information_id' => $course->id,
                'main_topic_number' => $chapter['Main Topic number'],
                'main_topic' => $chapter['Main Topic'],
                'topic_outcomes' => $chapter['Topic Outcomes'],
            ]);
        }
        
    }
    
    public function showCourseData()
{
    $userId = Auth::id(); // Get the current user's ID

    // Get the latest course information uploaded by the current user
    $courseInfo = CourseInformation::where('user_id', $userId)
        ->latest() // Order by creation date
        ->first(); // Get the latest one

    // If courseInfo is null, initialize an empty collection for courseChapters
    $courseChapters = $courseInfo ? CourseChapters::where('course_information_id', $courseInfo->id)->get() : collect();

    // Get the latest TableOfSpecification entry for the current user
    $latestTOS = TableOfSpecification::where('user_id', $userId)
        ->latest() // Order by creation date
        ->first(); // Get the latest one

    // Get the created_at timestamp of the latest TableOfSpecification entry
    $createdAt = $latestTOS ? $latestTOS->created_at : null;

    // Filter TableOfSpecification entries based on the exact creation timestamp
    $TOSinfo = TableOfSpecification::where('user_id', $userId)
        ->when($createdAt, function ($query, $createdAt) {
            return $query->where('created_at', $createdAt);
        })
        ->latest()
        ->get();

    // Calculate the sums of no_of_hours_a and no_of_hours_b
    $sum_of_hours_a = number_format($TOSinfo->sum('no_of_hours_a'));
    $sum_of_hours_b = number_format($TOSinfo->sum('no_of_hours_b'), 2);
    $sum_of_weight = number_format($TOSinfo->sum('weight'), 2);
    $sum_of_remembering = $TOSinfo->sum('remembering');
    $sum_of_understanding = $TOSinfo->sum('understanding');
    $sum_of_applying = $TOSinfo->sum('applying');
    $sum_of_analyzing = $TOSinfo->sum('analyzing');
    $sum_of_evaluating = $TOSinfo->sum('evaluating');
    $sum_of_creating = $TOSinfo->sum('creating');

    $sum_of_remembering_percentage = number_format($TOSinfo->sum('remembering_percentage'), 2);
    $sum_of_understanding_percentage = number_format($TOSinfo->sum('understanding_percentage'), 2);
    $sum_of_applying_percentage = number_format($TOSinfo->sum('applying_percentage'), 2);
    $sum_of_analyzing_percentage = number_format($TOSinfo->sum('analyzing_percentage'), 2);
    $sum_of_evaluating_percentage = number_format($TOSinfo->sum('evaluating_percentage'), 2);
    $sum_of_creating_percentage = number_format($TOSinfo->sum('creating_percentage'), 2);

    return view('faculty.tos', [
        'courseInfo' => $courseInfo,
        'courseChapters' => $courseChapters,
        'TOSinfo' => $TOSinfo,
        'sum_of_hours_a' => $sum_of_hours_a,
        'sum_of_hours_b' => $sum_of_hours_b,
        'sum_of_weight' => $sum_of_weight,
        'sum_of_remembering' => $sum_of_remembering,
        'sum_of_understanding' =>  $sum_of_understanding,
        'sum_of_applying' =>$sum_of_applying,
        'sum_of_analyzing' => $sum_of_analyzing,
        'sum_of_evaluating' =>$sum_of_evaluating,
        'sum_of_creating' =>$sum_of_creating,
        'sum_of_remembering_percentage'  =>  $sum_of_remembering_percentage,
        'sum_of_understanding_percentage'  => $sum_of_understanding_percentage,
        'sum_of_applying_percentage'  =>  $sum_of_applying_percentage,
        'sum_of_analyzing_percentage'  => $sum_of_analyzing_percentage,
        'sum_of_evaluating_percentage'  =>  $sum_of_evaluating_percentage,
        'sum_of_creating_percentage' => $sum_of_creating_percentage,
    ]);
}



}