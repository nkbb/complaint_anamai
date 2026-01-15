@extends('layouts.app-admin')

@section('pageTitle', 'คู่มือการใช้งาน | ระบบบริหารจัดการข้อคิดเห็นข้อร้องเรียน กรมสุขภาพจิต')

@section('content')
  <div class="pt-6 pb-11 px-3 md:px-7 bg-white mb-[80px] mx-4 md:mx-8 lg:mx-16 2xl:mx-[326px] border shadow-md">
    <div class="text-2xl text-[#3fbbc0] ml-11 mb-6">
      <i class="fas fa-book"></i> คู่มือการใช้งาน
    </div>

    <div class="gap-2 flex justify-start mb-8">
        <a href="/admin/setting" class="px-4 py-2 border rounded hover:cursor-pointer">ย้อนกลับ</a>
    </div>

    <div class="flex w-full">
      <iframe
        src="{{ asset('/manual/manual_admin.pdf') }}"
        width="100%"
        height="600px"
        style="border: none;"
    ></iframe>
    </div>
  </div>
  @endsection