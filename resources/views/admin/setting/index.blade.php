@extends('layouts.app-admin')

@section('pageTitle', 'ตั้งค่า | ระบบบริหารจัดการข้อคิดเห็นข้อร้องเรียน กรมสุขภาพจิต')

@section('content')
  <div class="pt-6 pb-11 px-3 md:px-7 bg-white mb-[80px] mx-4 md:mx-8 lg:mx-16 2xl:mx-[326px] border shadow-md">
    <div class="text-2xl text-[#3fbbc0] ml-11 mb-6">
      <i class="fas fa-cog"></i> ตั้งค่า
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 xl:grid-cols-5 content-start gap-3 md:gap-5">
      @if(Auth::user()->level == 'root')
      <a href="/admin/setting/unit" class="border border-b-[8px] border-[#1d684a] hover:text-[#1d684a] flex flex-col gap-5 text-center py-4 hover:cursor-pointer">
        <i class="fas fa-university text-3xl"></i>
        <div>หน่วยงานในสังกัด</div>
      </a>
      @endif
      <a href="/admin/setting/users" class="border border-b-[8px] border-[#1d684a] hover:text-[#1d684a] flex flex-col gap-5 text-center py-4 hover:cursor-pointer">
        <i class="far fa-user text-3xl"></i>
        <div>ผู้ใช้งาน</div>
      </a>
      <!-- <a href="/admin/setting" class="border border-b-[8px] border-[#1d684a] hover:text-[#1d684a] flex flex-col gap-5 text-center py-4 hover:cursor-pointer">
        <i class="fab fa-line text-3xl"></i>
        <div>Line กลุ่ม</div>
      </a> -->
      
      <a href="/admin/setting/telegram" class="border border-b-[8px] border-[#1d684a] hover:text-[#1d684a] flex flex-col gap-5 text-center py-4 hover:cursor-pointer">
        <i class="fas fa-comments text-3xl"></i>
        <div>Telegram Bot</div>
      </a>
      @if(Auth::user()->level == 'root')
      <a href="/admin/setting/question" class="border border-b-[8px] border-[#1d684a] hover:text-[#1d684a] flex flex-col gap-5 text-center py-4 hover:cursor-pointer">
        <i class="far fa-clipboard text-3xl"></i>
        <div>แบบประเมินความพึงพอใจ</div>
      </a>
      <a href="/admin/setting/type" class="border border-b-[8px] border-[#1d684a] hover:text-[#1d684a] flex flex-col gap-5 text-center py-4 hover:cursor-pointer">
        <i class="fas fa-layer-group text-3xl"></i>
        <div>ประเด็นการร้องเรียน</div>
      </a>
      <a href="/admin/setting/timefinish" class="border border-b-[8px] border-[#1d684a] hover:text-[#1d684a] flex flex-col gap-5 text-center py-4 hover:cursor-pointer">
        <i class="far fa-calendar-minus text-3xl"></i>
        <div>ตั้งค่าเวลา ดำเนินการ </div>
      </a>
      <!-- <a href="/admin/setting/comment" class="border border-b-[8px] border-[#1d684a] hover:text-[#1d684a] flex flex-col gap-5 text-center py-4 hover:cursor-pointer">
        <i class="far fa-calendar-minus text-3xl"></i>
        <div>ประเด็นข้อคิดเห็น </div>
      </a> -->
      <a href="/admin/setting/complaint" class="border border-b-[8px] border-[#1d684a] hover:text-[#1d684a] flex flex-col gap-5 text-center py-4 hover:cursor-pointer">
        <i class="fas fa-cog text-3xl"></i>
        <div>หลักเกณฑ์ การร้องเรียน</div>
      </a>
      <a href="/admin/setting/methods" class="border border-b-[8px] border-[#1d684a] hover:text-[#1d684a] flex flex-col gap-5 text-center py-4 hover:cursor-pointer">
        <i class="fas fa-cog text-3xl"></i>
        <div>ช่องทางการร้องเรียน</div>
      </a>
      <a href="/admin/setting/severity" class="border border-b-[8px] border-[#1d684a] hover:text-[#1d684a] flex flex-col gap-5 text-center py-4 hover:cursor-pointer">
        <i class="fas fa-cog text-3xl"></i>
        <div>ระดับความรุนแรง</div>
      </a>
      <a href="/admin/setting/person" class="border border-b-[8px] border-[#1d684a] hover:text-[#1d684a] flex flex-col gap-5 text-center py-4 hover:cursor-pointer">
        <i class="fas fa-cog text-3xl"></i>
        <div>บุคคุลที่ถูกร้องเรียน</div>
      </a>
      <a href="/admin/setting/banner" class="border border-b-[8px] border-[#1d684a] hover:text-[#1d684a] flex flex-col gap-5 text-center py-4 hover:cursor-pointer">
        <i class="fas fa-images text-3xl"></i>
        <div>ภาพสไลด์หน้าแรก</div>
      </a>
      <a href="/admin/setting/popup" class="border border-b-[8px] border-[#1d684a] hover:text-[#1d684a] flex flex-col gap-5 text-center py-4 hover:cursor-pointer">
        <i class="fas fa-image text-3xl"></i>
        <div>ภาพกิจกรรมหน้าแรก (popup)</div>
      </a>
      @endif
      <a href="/admin/setting/manual" class="border border-b-[8px] border-[#1d684a] hover:text-[#1d684a] flex flex-col gap-5 text-center py-4 hover:cursor-pointer">
        <i class="fas fa-book text-3xl"></i>
        <div>คู่มือการใช้งาน</div>
      </a>
      
    </div>
  </div>
@endsection