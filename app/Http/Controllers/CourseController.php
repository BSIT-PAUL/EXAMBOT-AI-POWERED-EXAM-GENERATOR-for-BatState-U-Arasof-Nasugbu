<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser;
use App\Models\FileUpload;

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
                    \"Topic Outcomes\": \"\",

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
}
