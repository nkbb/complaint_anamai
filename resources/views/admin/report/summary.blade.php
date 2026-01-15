@extends('layouts.app-admin')

@section('pageTitle', 'ภาพรวมข้อร้องเรียน | ระบบบริหารจัดการข้อคิดเห็นข้อร้องเรียน กรมสุขภาพจิต')

@section('content')
  <div class="pt-6 pb-11 px-3 md:px-7 bg-white mb-[80px] mx-4 md:mx-8 lg:mx-16 2xl:mx-[326px] border shadow-md">
    <div class="text-2xl text-[#3fbbc0] ml-11 mb-6">
      <i class="fas fa-tachometer-alt"></i> ภาพรวมข้อร้องเรียน
    </div>

    <report-summary 
      user_level="{{ Auth::user()->level }}"
      person="{{ $person }}"
      method="{{ $method }}"
      unit="{{ $unit }}"  
    ></report-summary>
  </div>
@endsection