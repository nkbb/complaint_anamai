@extends('layouts.app-admin')

@section('pageTitle', 'ตั้งค่า ภาพกิจกรรมหน้าแรก (popup) | ระบบบริหารจัดการข้อคิดเห็นข้อร้องเรียน กรมสุขภาพจิต')

@section('content')
  <div class="pt-6 pb-11 px-3 md:px-7 bg-white mb-[80px] mx-4 md:mx-8 lg:mx-16 2xl:mx-[326px] border shadow-md">
    <div class="text-2xl text-[#3fbbc0] ml-11 mb-6">
      <i class="fas fa-image"></i> ภาพกิจกรรมหน้าแรก (popup)
    </div>

    <setting-popup></setting-popup>
  </div>
  @endsection