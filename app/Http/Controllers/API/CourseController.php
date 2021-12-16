<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Course;
use App\Schedule;
use App\Problem;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return response()->json([
            'success'=>true,
            'courses'=>Course::all(),
            'problems'  =>  Problem::all(),
        ]);
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
        //
    }

    public function course_schedules(Request $request){
        //return $request->all();
        $course_id = $request->course_id;

        $schedules = Schedule::where([
            ['course_id','=',$course_id]
        ])->get()->toArray();

        foreach($schedules as $i=>$schedule){
            $schedules[$i]['problem_title'] = Problem::find($schedule['problem_id'])->title;
            $schedules[$i]['problem'] = Problem::find($schedule['problem_id']);
        }

        return response()->json([
            'success'   =>  true,
            'schedules'  =>  $schedules,
        ]);
    }
}
