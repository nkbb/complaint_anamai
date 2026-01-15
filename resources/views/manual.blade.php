@extends('layouts.app')

@section('pageTitle', 'คู่มือการใช้งาน | ระบบบริหารจัดการข้อคิดเห็นข้อร้องเรียน กรมสุขภาพจิต')

@section('content')

  <div class="py-6 px-7 bg-white mt-[189px] mb-[80px] mx-4 md:mx-8 lg:mx-16 2xl:mx-[326px] border shadow-md">
      <h1 class="flex flex-row justify-center">
        @if($file == 'appeal')
        <div class="border-b-[5px] border-[#FB8C00] text-[#FB8C00] text-[32px]">คู่มือวิธีการร้องเรียน - ร้องทุกข์</div>
        @endif
        @if($file == 'trace')
        <div class="border-b-[5px] border-[#FB8C00] text-[#FB8C00] text-[32px]">คู่มือวิธีการติดตามเรื่องร้องเรียน</div>
        @endif
      </h1>

      <div class="flex w-full mt-8">
      @if($file == 'appeal')
      <iframe
        src="{{ asset('/manual/manual_appeal.pdf') }}"
        width="100%"
        height="600px"
        style="border: none;"
      ></iframe>
      @endif
      @if($file == 'trace')
      <iframe
        src="{{ asset('/manual/manual_trace.pdf') }}"
        width="100%"
        height="600px"
        style="border: none;"
      ></iframe>
      @endif
      @if($file == 'complaint')
      <iframe
        src="{{ asset('/manual/complaint.pdf') }}"
        width="100%"
        height="600px"
        style="border: none;"
      ></iframe>
      @endif
    </div>
  </div>
@endsection