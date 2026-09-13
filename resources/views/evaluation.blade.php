@extends('layouts.app')

@section('pageTitle', 'แบบประเมินความพึงพอใจ')

@section('content')
    <home-evaluation-component 
        :questions="{{ Illuminate\Support\Js::from($questions) }}"
        submit-url="/question"
        cancel-url="/">
    </home-evaluation-component>
@endsection