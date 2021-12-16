@extends('admin.mainlayout')
@section('rightpanel')
@if (auth()->user()->admin)
@foreach ($payload as $item)
<?php   $courses[]=[$item->id => $item->name]; ?>
@endforeach
@endif
    <v-app>
        <v-main>
            <admin-schedule></admin-schedule>
        </v-main>
    </v-app>
@stop

