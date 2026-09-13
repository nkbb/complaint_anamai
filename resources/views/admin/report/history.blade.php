@extends('layouts.app-admin')

@section('pageTitle', 'สถิติผู้เข้าชม | ศูนย์รับข้อร้องเรียนและข้อชมเชย กรมอนามัย')

@section('content')
  <div class="pt-6 pb-11 px-3 md:px-7 bg-white mb-[80px] mx-4 md:mx-8 lg:mx-16 2xl:mx-[326px] border shadow-md">
    <div class="text-2xl text-brand-600 ml-11 mb-6">
      <i class="fas fa-chart-line"></i> สถิติผู้เข้าชม
    </div>

    <report-history></report-history>
  </div>
@endsection