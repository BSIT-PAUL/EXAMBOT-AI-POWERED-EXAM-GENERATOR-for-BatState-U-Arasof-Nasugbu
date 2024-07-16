<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TableOfSpecification;
use App\Models\Remembering;
use App\Models\Understanding;
use App\Models\Applying;
use App\Models\Analyzing;
use App\Models\Evaluating;
use App\Models\Creating;
use App\Models\CourseChapters;
use Illuminate\Support\Facades\Auth;
use App\Services\OpenAIService;

class TopicController extends Controller
{
    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    public function store(Request $request)
    {
        $user_id = auth()->id(); // assuming user is authenticated

        // Calculate the sum of all no_of_hours_a
        $sum_of_hours_a = array_sum($request->no_of_hours);

        foreach ($request->main_topic as $key => $topic) {
            $no_of_hours_a = $request->no_of_hours[$key];
            $no_of_hours_b = $no_of_hours_a / $sum_of_hours_a; // Calculate the proportion
            $weight = $no_of_hours_b * 100; // Calculate the weight
            $total_no_of_points = $request->overall_points * $no_of_hours_b; 

            // Calculate the unrounded values
            $remembering = $total_no_of_points * 0.05;
            $understanding = $total_no_of_points * 0.10;
            $applying = $total_no_of_points * 0.20;
            $analyzing = $total_no_of_points * 0.25;
            $evaluating = $total_no_of_points * 0.20;
            $creating = $total_no_of_points * 0.20;

            // Round the values
            $rounded_remembering = $this->roundValue($remembering);
            $rounded_understanding = $this->roundValue($understanding);
            $rounded_applying = $this->roundValue($applying);
            $rounded_analyzing = $this->roundValue($analyzing);
            $rounded_evaluating = $this->roundValue($evaluating);
            $rounded_creating = $this->roundValue($creating);

            // Adjust the values if the sum is not equal to total_no_of_points
            $rounded_total = $rounded_remembering + $rounded_understanding + $rounded_applying + $rounded_analyzing + $rounded_evaluating + $rounded_creating;
            $difference = $this->roundValue($total_no_of_points) - $rounded_total;

            if ($difference != 0) {
                // Adjust the values by adding the difference to one of the categories
                $rounded_creating += $difference;
            }

            // Calculate percentages
            $remembering_percentage = $rounded_remembering / $request->overall_points * 100;
            $understanding_percentage = $rounded_understanding / $request->overall_points * 100;
            $applying_percentage = $rounded_applying / $request->overall_points * 100;
            $analyzing_percentage = $rounded_analyzing / $request->overall_points * 100;
            $evaluating_percentage = $rounded_evaluating / $request->overall_points * 100;
            $creating_percentage = $rounded_creating / $request->overall_points * 100;

            TableOfSpecification::create([
                'user_id' => $user_id,
                'examination_type' => $request->assessment_type,
                'course_code' => $request->course_code, 
                'topic' => $topic,
                'no_of_hours_a' => $no_of_hours_a,
                'no_of_hours_b' => $no_of_hours_b,
                'weight' => $weight, 
                'remembering' => $rounded_remembering, 
                'remembering_percentage' =>$remembering_percentage, 
                'understanding' =>  $rounded_understanding, 
                'understanding_percentage' => $understanding_percentage, 
                'applying' => $rounded_applying, 
                'applying_percentage' => $applying_percentage, 
                'analyzing' => $rounded_analyzing, 
                'analyzing_percentage' => $analyzing_percentage, 
                'evaluating' => $rounded_evaluating, 
                'evaluating_percentage' => $evaluating_percentage, 
                'creating' => $rounded_creating, 
                'creating_percentage' => $creating_percentage,
                'total_no_of_points' => $this->roundValue($total_no_of_points), 
                'overall_points' => $request->overall_points,
            ]);
        }

        return redirect()->back()->with('success', 'Table of Specification Generated Successfully!');
    }
    public function generateExamTypeTable(Request $request)
    {
        $user_id = auth()->id();
        
        // Get the latest TableOfSpecification entry for the current user
        $latestTOS = TableOfSpecification::where('user_id', $user_id)
            ->latest() // Order by creation date
            ->first(); // Get the latest one
    
        if (!$latestTOS) {
            return redirect()->route('examtype')->with('error', 'No Table of Specification found for the user.');
        }
    
        // Get the created_at timestamp of the latest TableOfSpecification entry
        $createdAt = $latestTOS->created_at;
    
        // Filter TableOfSpecification entries based on the exact creation timestamp
        $TOSinfo = TableOfSpecification::where('user_id', $user_id)
            ->where('created_at', $createdAt)
            ->get();
    
        $examTypes = [];
    
        foreach ($TOSinfo as $tos) {
            $topic = $tos->topic;
            $bloomsLevels = $this->getBloomsLevels($tos);
    
            // Fetch the topic outcomes from the course_chapters table
            $chapter = CourseChapters::where('main_topic', $topic)->first();
            $topicOutcomes = $chapter ? $chapter->topic_outcomes : 'No outcomes specified';
    
            foreach ($bloomsLevels as $level => $items) {
                if ($items > 0) {
                    $examType = $this->openAIService->classifyExamType($level, $items, $topicOutcomes, 'college');
    
                    $examTypes[$topic][] = [
                        'level' => $level,
                        'items' => $items,
                        'exam_type' => $examType,
                        'topic_outcomes' => $topicOutcomes,
                    ];
    
                    // Save to database
                    $modelClass = 'App\Models\\' . ucfirst($level);
                    $modelClass::create([
                        'user_id' => $user_id,
                        'topic' => $topic,
                        'items' => $items,
                        'exam_type' => $examType,
                        'topic_outcomes' => $topicOutcomes,
                    ]);
                }
            }
        }
    
        // Redirect to the exam type page
        return redirect()->route('examtype')->with('success', 'Exam Type Generated Successfully.');
    }
    
    


    private function getBloomsLevels($tos)
    {
        // Include all levels and their corresponding items
        return [
            'remembering' => $tos->remembering,
            'understanding' => $tos->understanding,
            'applying' => $tos->applying,
            'analyzing' => $tos->analyzing,
            'evaluating' => $tos->evaluating,
            'creating' => $tos->creating,
        ];
    }
    private function roundValue($value)
    {
        return ($value - floor($value) >= 0.5) ? ceil($value) : floor($value);
    }
    public function editExamType(Request $request)
    {
        $user_id = auth()->id();
        $topic = $request->input('topic');
        $level = $request->input('level');
        $items = $request->input('items');
        $examType = $request->input('exam_type');
    
        $modelClass = 'App\Models\\' . ucfirst($level);
        $model = $modelClass::where('user_id', $user_id)
            ->where('topic', $topic)
            ->latest() // Order by creation date
            ->first(); // Get the latest one
    
        if ($model) {
            $model->items = $items;
            $model->exam_type = $examType;
            $model->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Record not found.']);
    }
    

}
