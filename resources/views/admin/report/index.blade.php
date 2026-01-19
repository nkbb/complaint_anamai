@extends('layouts.app-admin')

@section('pageTitle', 'รายงาน | ระบบบริหารจัดการข้อคิดเห็นข้อร้องเรียน กรมสุขภาพจิต')

@section('content')
  <div class="pt-6 pb-11 px-3 md:px-7 bg-white mb-[80px] mx-4 md:mx-8 lg:mx-16 2xl:mx-[326px] border shadow-md">
    <div class="text-2xl text-[#3fbbc0] ml-11 mb-6">
      <i class="fas fa-chart-bar"></i> รายงาน
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 xl:grid-cols-5 content-start gap-3 md:gap-5">
      <a href="/admin/report/summary" class="border border-b-[8px] border-[#13849c] hover:text-[#13849c] flex flex-col gap-5 text-center py-4 hover:cursor-pointer">
        <i class="fas fa-tachometer-alt text-3xl"></i>
        <div>ภาพรวมข้อร้องเรียน</div>
      </a>

      <a href="/admin/report/type" class="border border-b-[8px] border-[#13849c] hover:text-[#13849c] flex flex-col gap-5 text-center py-4 hover:cursor-pointer">
        <i class="fas fa-chart-bar text-3xl"></i>
        <div>รายงานแบ่งตามประเภทข้อร้องเรียน</div>
      </a>

      <a href="/admin/report/methods" class="border border-b-[8px] border-[#13849c] hover:text-[#13849c] flex flex-col gap-5 text-center py-4 hover:cursor-pointer">
        <i class="fas fa-paper-plane text-3xl"></i>
        <div>รายงานแบ่งตามช่องทางการร้องเรียน</div>
      </a>

      <a href="/admin/report/person" class="border border-b-[8px] border-[#13849c] hover:text-[#13849c] flex flex-col gap-5 text-center py-4 hover:cursor-pointer">
        <i class="fas fa-users text-3xl"></i>
        <div>รายงานแบ่งตามบุคคลที่ถูกร้องเรียน</div>
      </a>

      <a href="/admin/report/severity" class="border border-b-[8px] border-[#13849c] hover:text-[#13849c] flex flex-col gap-5 text-center py-4 hover:cursor-pointer">
        <i class="fas fa-level-up-alt text-3xl"></i>
        <div>รายงานแบ่งระดับความรุนแรง</div>
      </a>

      @if(Auth::user()->level == 'root')
      <a href="/admin/report/office" class="border border-b-[8px] border-[#13849c] hover:text-[#13849c] flex flex-col gap-5 text-center py-4 hover:cursor-pointer">
        <i class="fas fa-university text-3xl"></i>
        <div>รายงานแบ่งตามหน่วยรับผิดชอบ</div>
      </a>

      <a href="/admin/report/history" class="border border-b-[8px] border-[#13849c] hover:text-[#13849c] flex flex-col gap-5 text-center py-4 hover:cursor-pointer">
        <i class="fas fa-chart-line text-3xl"></i>
        <div>สถิติผู้เข้าชม</div>
      </a>

      <a href="/admin/report/questionnaire" class="border border-b-[8px] border-[#13849c] hover:text-[#13849c] flex flex-col gap-5 text-center py-4 hover:cursor-pointer">
        <i class="fas fa-comment-dots text-3xl"></i>
        <div>แบบประเมินความพึงพอใจ</div>
      </a>
      @endif
      
      
    </div>
  </div>
@endsection