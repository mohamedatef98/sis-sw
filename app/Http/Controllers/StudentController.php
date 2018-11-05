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

    public function create(Request $request){

        Student::create($request->only('name'));

        return response('{"added": true}', 220);
    }

    public function destroy(Student $student){
        $student->grades()->delete();

        DB::table('course_student')->where('student_id', $student->id)->delete();

        $student->delete();

        return response('{"deleted":true}', 221);
    }

    public function update(Request $request, Student $student){

        $student->name = $request->input('name');

        $coursesOld = $student->courses;

        $coursesNew = $request->input('courses');

        foreach($coursesNew as $courseNew){
            $gradeCount = $student->grades()
                ->where('course_id', $courseNew['id'])
                ->count();
            if($gradeCount < 1){
                 DB::table('course_student')->insert([
                    'student_id' => $student->id,
                    'course_id' => $courseNew['id'],
                ]);
                \App\Grade::create([
                    'student_id' => $student->id,
                    'course_id' => $courseNew['id'],
                    'grade' => $courseNew['grade']
                ]);
            }

            else{
                $student->grades()
                    ->where('course_id', $courseNew['id'])
                    ->update([
                        'grade'=> $courseNew['grade']
                    ]);
            }
        }

        foreach($coursesOld as $courseOld){
            $found = false;
            foreach($coursesNew as $courseNew){
                if($courseNew['id'] == $courseOld->id)
                    $found = true;
            }

            if(! $found){
                DB::table('course_student')->where('student_id', $student->id)
                    ->where('course_id', $courseOld->id)
                    ->delete();
                \App\Grade::where('student_id', $student->id)
                    ->where('course_id', $courseOld->id)
                    ->delete();
            }
        }

        $student->save();

       return response('{"stored":true}', 222);
    }
}
