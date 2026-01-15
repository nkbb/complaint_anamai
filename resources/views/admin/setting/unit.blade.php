@extends('layouts.app-admin')

@section('pageTitle', 'ตั้งค่า หน่วยงานในสังกัด | ระบบบริหารจัดการข้อคิดเห็นข้อร้องเรียน กรมสุขภาพจิต')

@section('content')
  <div class="pt-6 pb-11 px-3 md:px-7 bg-white mb-[80px] mx-4 md:mx-8 lg:mx-16 2xl:mx-[326px] border shadow-md">
    <div class="text-2xl text-[#3fbbc0] ml-11 mb-6">
      <i class="fas fa-university"></i> ตั้งค่า หน่วยงานในสังกัด
    </div>

    <setting-unit></setting-unit>
  </div>
  @endsection