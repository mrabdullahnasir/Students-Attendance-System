<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\Clas;
use App\Student;
use App\Subject;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class studentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        if (Auth::user()->role != 1) {
            return Response::HTTP_FORBIDDEN;
        }
        $students = Student::all();
        $classes = Clas::all();

        return view('students/student', compact('students', 'classes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {


        $student = Student::find($id);
        $student->delete();;

        return redirect()->back();

    }

    public function filterStudents($id){

       $class = Clas::find($id);
        $students = $class->students;

        return view('students/class-students', compact('students'));
    }

    public function filterStudents2($id){

        $class = Clas::find($id);
        $students = $class->students;

        return view('students/class-students2', compact('students'));
    }

    public function studentsAttendance(){


        $classes = Clas::all();
        $subjects = Subject::all();

        $teacher_id = Auth::user()->id;
        $teacher = User::find($teacher_id);

        $attendances = Attendance::all();
        $students = Student::all();

        return view('students/students-attendance', compact('attendances', 'students', 'subjects', 'classes', 'teacher'));
    }
}
