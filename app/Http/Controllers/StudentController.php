<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Student;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index(){
        return Student::orderBy('name')->get()->makeHidden('courses_count');
    }

    public function show(Student $student){
        //return $student->makeVisible('courses');

        $data = [
            'name' => $student->name,
            'id' => $student->id,
            'courses' => []
        ];

        $courses = $student->courses;

        foreach($courses as $course){
            $data['courses'] [] = [
                'id' => $course->id,
                'name' => $course->name,
                'max_grade' => $course->max_grade,
                'grade' => $course->grades->where('student_id', $student->id)->first()->grade 
            ];
        }

        return $data;
    }

    public function store(Request $request){
        Student::create($request->input('name'));

        return response("{'added': true}", 220);
    }

    public function destroy(Student $student){
        $student->grades()->delete();

        DB::table('course_student')->where('student_id', $student->id)->delete();

        $student->delete();

        return response('{"deleted":true}', 221);
    }
}
