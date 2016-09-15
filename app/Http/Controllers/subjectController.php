<?php

namespace App\Http\Controllers;

use App\Student;
use App\Subject;
use App\User;
use App\Clas;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class subjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {

        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->role != 1) {
            return Response::HTTP_FORBIDDEN;
        }
        $subjects = Subject::all();
        return view('Subject/subject', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->role != 1) {
            return Response::HTTP_FORBIDDEN;
        }
        return view('Subject/createSubject');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->role != 1) {
            return Response::HTTP_FORBIDDEN;
        }
        $subject = new Subject();

        $subject->name = $request->get('name');
        $subject->save();

        return redirect('/subjects');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Auth::user()->role != 1) {
            return Response::HTTP_FORBIDDEN;
        }
        $subject = Subject::find($id);
        return view('Subject/showSubject');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->role != 1) {
            return Response::HTTP_FORBIDDEN;
        }
        $subject = Subject::find($id);
        return view('Subject/editSubject', compact('subject'));
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
        if (Auth::user()->role != 1) {
            return Response::HTTP_FORBIDDEN;
        }
        $subject = Subject::find($id);
        $subject->update($request->all());

        return redirect('subjects');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->role != 1) {
            return Response::HTTP_FORBIDDEN;
        }
        $subject = Subject::find($id);
        $subject->delete();

        return redirect('subjects');
    }

    public function assignStd($id)
    {
        if (Auth::user()->role != 1) {
            return Response::HTTP_FORBIDDEN;
        }
        $teachers = User::where('role', '=' , 0)->get();
        $classes = Clas::all();
        $subject = Subject::find($id);

        return view('Subject/assignStudents', compact('teachers', 'classes', 'subject'));
    }

    public function saveStudents(Request $request){
        if (Auth::user()->role != 1) {
            return Response::HTTP_FORBIDDEN;
        }
        $this->validate($request, [
            'class' => 'required|not_in:Select Class',
            'teacher' => 'required|not_in:Select Teacher',
            'name' => 'required',
            'fname' => 'required'
        ]);
        // adding students to a class
        $class = $request->get('class');

        $i = 0;
        foreach ($request->get('name') as $studentName){
            $student = new Student();
            $student->name = $studentName[0];
            $student->fname = $request->get('fname')[$i++][0];
            $student->save();

            $student->classes()->attach($class);
        }
        // adding subjects to a class
        $subject = $request->get('subject');
        $classModel = Clas::find($class);
        $classModel->subjects()->attach($subject);

        // adding teachers to a class
        $teacher = $request->get('teacher');
        $classModel->teachers()->attach($teacher);

        $subjectT = Subject::find($subject);
        $subjectT->teachers()->attach($teacher);

        return redirect()->back();
    }

    public function filterSubjects($id){

        $class = Clas::find($id);
        $teacher = User::find(Auth::user()->id);

        return view('Subject/filter-subjects', compact('class', 'teacher'));
    }
}
