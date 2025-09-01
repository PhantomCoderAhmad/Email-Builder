@extends('templates::tailwind.layouts.master')

@section('content')
    <h1>Templates Module</h1>

    <p>Module: {!! config('templates.name') !!}</p>
@endsection
