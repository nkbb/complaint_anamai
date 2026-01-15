@extends('layouts.app-admin')

@section('pageTitle', 'เพิ่มเรื่องร้องเรียน | ระบบบริหารจัดการข้อคิดเห็นข้อร้องเรียน กรมสุขภาพจิต')

@section('content')
<div class="pt-6 pb-11 px-3 md:px-7 bg-white mb-[80px] mx-4 md:mx-8 lg:mx-16 2xl:mx-[326px] border shadow-md">
  <div class="text-2xl text-[#3fbbc0] ml-0 md:ml-11 mb-6"><i class="far fa-plus-square"></i> เพิ่มเรื่องร้องเรียน</div>
  

  <admin-input-complaint
    type="create"
    >
  </admin-input-complaint>

 

</div>
@endsection