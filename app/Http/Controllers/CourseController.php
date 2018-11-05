<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Course;
use Illuminate\Support\Facades\DB;


class CourseController extends Controller
{
    public function index(){
        return Course::get();
    }

    public function show(Course $course){
        $data = [
            'id' => $course->id,
            'name' => $course->name,
            'max_grade' => $course->max_grade,
            'students' => []
        ];

        $students = $course->students;
        foreach($students as $student){
            $data['students'][] = [
                'id' => $student->id,
                'name' => $student->name,
                'grade' => $student->grades->where('course_id', $course->id)->first()->grade
            ];
        }
        return $data;
    }

    public function create(Request $request){
        Course::create($request->only('name', 'max_grade'));

        return response('{"added": true}', 220);
    }

    public function destroy(Course $course){
        \App\Grade::where('course_id', $course->id)->delete();

        DB::table('course_student')->where('course_id', $course->id);

        $course->delete();

        return response('{"deleted":true}', 221);
    }
}
