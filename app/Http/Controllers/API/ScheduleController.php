<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Schedule;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        $schedule = new Schedule;
        $schedule->problem_id = $request->problem_id;
        $schedule->start_time = $request->start_time;
        $schedule->end_time = $request->end_time;
        $schedule->save();

        return response()->json([
            'success'   =>  true,
            'schedule'  =>  $schedule,
        ]);
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
    public function update(Request $request)
    {
        //
        $id = $request->schedule_id;
        $schedule = Schedule::find($id);
        $schedule->problem_id = $request->problem_id;
        $schedule->start_time = $request->start_time;
        $schedule->end_time = $request->end_time;
        $schedule->save();

        $schedule = $schedule->toArray();
        $schedule['problem_title'] = \App\Problem::find($schedule['problem_id'])->title;

        return response()->json([
            'success'   =>  true,
            'schedule'  =>  $schedule,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        //dd($request->all());
        $id = $request->schedule_id;
        $schedule = Schedule::find($id);
        $schedule->delete();
        return response()->json([
            'success'   =>  true,
            'message'  =>  'deleted',
        ]);
    }
}
