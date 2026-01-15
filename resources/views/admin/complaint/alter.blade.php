@extends('layouts.app-admin')

@section('pageTitle', 'ติดตามเรื่องร้องเรียน | ระบบบริหารจัดการข้อคิดเห็นข้อร้องเรียน กรมสุขภาพจิต')

@section('content')
<div class="pt-6 pb-11 px-3 md:px-7 bg-white mb-[80px] mx-4 md:mx-8 lg:mx-16 2xl:mx-[326px] border shadow-md">
  @php
    $title_name = '<div class="text-2xl text-[#3fbbc0] md:ml-11 ml-0  mb-6"><i class="far fa-calendar-check"></i> ดำเนินการ</div>';
  @endphp

  <admin-list-complaint
    type="44"
    title_name="{{ $title_name }}"
    unit="{{ json_encode($unit) }}"
    user_level="{{ Auth::user()->level }}"
    is_search="0"
    >
  </admin-list-complaint>

 

</div>
@endsection