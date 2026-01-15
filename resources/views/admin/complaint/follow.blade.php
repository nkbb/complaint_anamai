@extends('layouts.app-admin')

@section('pageTitle', 'ติดตามเรื่องร้องเรียน | ระบบบริหารจัดการข้อคิดเห็นข้อร้องเรียน กรมสุขภาพจิต')

@section('content')
<div class="pt-6 pb-11 px-3 md:px-7 bg-white mb-[80px] mx-4 md:mx-8 lg:mx-16 2xl:mx-[326px] border shadow-md">
  @php
    if(isset($_GET['type']) && $_GET['type'] == '4'){
      $title_name = '<div class="text-2xl text-[#3fbbc0] md:ml-11 ml-0  mb-6"><i class="fas fa-bell"></i> หน่วยรับเรื่องแล้ว</div>';
    }elseif(isset($_GET['type']) && $_GET['type'] == '5'){
      $title_name = '<div class="text-2xl text-[#3fbbc0] md:ml-11 ml-0  mb-6"><i class="fas fa-calendar-check"></i> หน่วยกำลังดำเนินการ</div>';
    }elseif(isset($_GET['type']) && $_GET['type'] == '77'){
      $title_name = '<div class="text-2xl text-[#3fbbc0] md:ml-11 ml-0  mb-6"><i class="fas fa-check-circle"></i> ยุติเรื่อง/ไม่ใช่/แจ้งกลับ</div>';  
    }else{
      $title_name = '<div class="text-2xl text-[#3fbbc0] md:ml-11 ml-0  mb-6"><i class="far fa-bell"></i> ติดตามเรื่องร้องเรียน</div>';
    }
  @endphp

  <admin-list-complaint
    type="{{ $type ?? 99 }}"
    title_name="{{ $title_name }}"
    unit="{{ $unit }}"
    user_level="{{ Auth::user()->level }}"
    is_search="{{ $type ? 0 : 1 }}"

    >
  </admin-list-complaint>

 

</div>
@endsection