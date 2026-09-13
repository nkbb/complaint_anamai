@extends('layouts.app')

@section('pageTitle', 'ร้องทุกข์ ร้องทุกข์ | ศูนย์รับข้อร้องเรียนและข้อชมเชย กรมอนามัย')

@section('content')
<div>
  <complaint-component 
  key_title="{{ $company->key_title }}"
  conditions="{{ $company->conditions }}"
  province="{{ $province }}"
  unit="{{ $unit }}"
  type="{{ $type }}"
  sub="{{ $sub }}"
  person="{{ $person }}"
  sel_id="{{ $sel_id }}"
  
  >
  </complaint-component>

  <!-- <home-manual-component></home-manual-component> -->
  <!-- <home-agreement-component></home-agreement-component> -->
  {{-- <home-evaluation-component></home-evaluation-component> --}}

</div>
@endsection