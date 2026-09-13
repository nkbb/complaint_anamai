@extends('layouts.app-admin')

@section('pageTitle', 'รับเรื่องร้องเรียน-ส่งให้หน่วยดำเนินการ | ศูนย์รับข้อร้องเรียนและข้อชมเชย กรมอนามัย')

@section('content')
<div class="pt-6 pb-11 px-3 md:px-7 bg-white mb-[80px] mx-4 md:mx-8 lg:mx-16 2xl:mx-[326px] border shadow-md">
  @php
    $title_name = '<div class="text-2xl text-brand-600 md:ml-11 ml-0  mb-6"><i class="far fa-paper-plane"></i> รับเรื่องร้องเรียน-ส่งให้หน่วยดำเนินการ</div>';
  @endphp

  <admin-list-complaint
    type="2"
    title_name="{{ $title_name }}"
    unit="{{ $unit }}"
    user_level="{{ Auth::user()->level }}"
    is_search="0"
    >
  </admin-list-complaint>

 

</div>
@endsection