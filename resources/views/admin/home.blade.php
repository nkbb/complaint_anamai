@extends('layouts.app-admin')

@section('pageTitle', 'Dashboard | ระบบบริหารจัดการข้อคิดเห็นข้อร้องเรียน กรมสุขภาพจิต')

@section('content')
<div class="pt-6 pb-11 px-3 md:px-7 bg-white mb-[80px] mx-4 md:mx-8 lg:mx-16 2xl:mx-[326px] border shadow-md">
    <!-- <div class="text-2xl text-[#3fbbc0] ml-11 mb-6">
      <i class="fas fa-home"></i> Dashboard
    </div> -->
    
    @if(Auth::user()->level == 'root')
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 p-4">
      <!-- กล่องที่ 1 -->
      <div class="bg-cyan-400 text-white p-4 rounded-md relative overflow-hidden">
        <div class="text-3xl font-bold z-10 relative">{{ $count_type2 }}</div>
        <div class="text-base z-10 relative">เรื่องร้องเรียน</div>
        <div class="mt-4 z-10 relative">
          <a href="/complaint/accept" class="text-white text-sm font-semibold hover:opacity-80">
            รายละเอียด →
          </a>
        </div>
        <i class="fas fa-paper-plane text-[100px] absolute right-2 bottom-0 opacity-20"></i>
      </div>

      <!-- กล่องที่ 2 -->
      <div class="bg-amber-500 text-white p-4 rounded-md relative overflow-hidden">
        <div class="text-3xl font-bold z-10 relative">{{ $count_type3 }}</div>
        <div class="text-base z-10 relative">หน่วยรับเรื่องแล้ว</div>
        <div class="mt-4 z-10 relative">
          <a href="/complaint/follow?type=3" class="text-white text-sm font-semibold hover:opacity-80">
            รายละเอียด →
          </a>
        </div>
        <i class="fas fa-bell text-[100px] absolute right-2 bottom-0 opacity-20"></i>
      </div>

      <!-- กล่องที่ 3 -->
      <div class="bg-red-500 text-white p-4 rounded-md relative overflow-hidden">
        <div class="text-3xl font-bold z-10 relative">{{ $count_type5 }}</div>
        <div class="text-base z-10 relative">หน่วยกำลังดำเนินการ</div>
        <div class="mt-4 z-10 relative">
          <a href="/complaint/follow?type=5" class="text-white text-sm font-semibold hover:opacity-80">
            รายละเอียด →
          </a>
        </div>
        <i class="fas fa-calendar-check text-[100px] absolute right-2 bottom-0 opacity-20"></i>
      </div>

      <!-- กล่องที่ 4 -->
      <div class="bg-green-600 text-white p-4 rounded-md relative overflow-hidden">
        <div class="text-3xl font-bold z-10 relative">{{ $count_type0 }}</div>
        <div class="text-base z-10 relative">ยุติเรื่อง/ไม่ใช่/แจ้งกลับ</div>
        <div class="mt-4 z-10 relative">
          <a href="/complaint/follow?type=77" class="text-white text-sm font-semibold hover:opacity-80">
            รายละเอียด →
          </a>
        </div>
        <i class="fas fa-check-circle text-[100px] absolute right-2 bottom-0 opacity-20"></i>
      </div>
    </div>
    @endif
    @if(Auth::user()->level == 'unit')
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 p-4">
      <div class="bg-[#f39c12] text-white p-4 rounded-md relative overflow-hidden">
        <div class="text-3xl font-bold z-10 relative">{{ $count_type3 }}</div>
        <div class="text-base z-10 relative">รับเรื่องร้องเรียน</div>
        <div class="mt-4 z-10 relative">
          <a href="/complaint/receive" class="text-white text-sm font-semibold hover:opacity-80">
            รายละเอียด →
          </a>
        </div>
        <i class="fas fa-satellite-dish text-[100px] absolute right-2 bottom-0 opacity-20"></i>
      </div>
     
      <div class="bg-[#00c0ef] text-white p-4 rounded-md relative overflow-hidden">
        <div class="text-3xl font-bold z-10 relative">{{ $count_type4 }}</div>
        <div class="text-base z-10 relative">ดำเนินการ</div>
        <div class="mt-4 z-10 relative">
          <a href="/complaint/alter" class="text-white text-sm font-semibold hover:opacity-80">
            รายละเอียด →
          </a>
        </div>
        <i class="far fa-calendar-check text-[100px] absolute right-2 bottom-0 opacity-20"></i>
      </div>

       <div class="bg-[#00bb67] text-white p-4 rounded-md relative overflow-hidden">
        <div class="text-3xl font-bold z-10 relative">{{ $count_type5 }}</div>
        <div class="text-base z-10 relative">ติดตามเรื่องร้องเรียน</div>
        <div class="mt-4 z-10 relative">
          <a href="/complaint/userfollow" class="text-white text-sm font-semibold hover:opacity-80">
            รายละเอียด →
          </a>
        </div>
        <i class="far fa-bell text-[100px] absolute right-2 bottom-0 opacity-20"></i>
      </div>

      <div class="bg-[#00bb67] text-white p-4 rounded-md relative overflow-hidden">
        <div class="text-3xl font-bold z-10 relative">Telegram</div>
        <div class="text-base z-10 relative">จัดการการแจ้งเตือน</div>
        <div class="mt-4 z-10 relative">
          <a href="/admin/setting/telegram" class="text-white text-sm font-semibold hover:opacity-80">
            รายละเอียด →
          </a>
        </div>
        <i class="fab fa-telegram-plane text-[100px] absolute right-2 bottom-0 opacity-20"></i>
      </div>

    </div>
    @endif

    

</div>
@endsection