<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TableOfSpecification;
use Illuminate\Support\Facades\Auth;

class TopicController extends Controller
{
    public function store(Request $request)
    {
        $user_id = auth()->id(); // assuming user is authenticated

        // Calculate the sum of all no_of_hours_a
        $sum_of_hours_a = array_sum($request->no_of_hours);

        foreach($request->main_topic as $key => $topic) {
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

    private function roundValue($value)
    {
        return ($value - floor($value) >= 0.5) ? ceil($value) : floor($value);
    }
}
