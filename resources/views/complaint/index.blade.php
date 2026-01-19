@extends('layouts.app')

@section('pageTitle', 'ร้องทุกข์ ร้องทุกข์ | ระบบบริหารจัดการข้อคิดเห็นข้อร้องเรียน กรมสุขภาพจิต')

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
  
  >
  </complaint-component>

  <!-- <home-manual-component></home-manual-component> -->
  <!-- <home-agreement-component></home-agreement-component> -->
  <home-evaluation-component></home-evaluation-component>

</div>
@endsection