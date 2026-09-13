@extends('layouts.app-admin')

@section('pageTitle', 'ตั้งค่า ผู้ใช้งาน | ศูนย์รับข้อร้องเรียนและข้อชมเชย กรมอนามัย')

@section('content')
  <div class="pt-6 pb-11 px-3 md:px-7 bg-white mb-[80px] mx-4 md:mx-8 lg:mx-16 2xl:mx-[326px] border shadow-md">
    <div class="text-2xl text-brand-600 ml-11 mb-6">
      <i class="fas fa-cog"></i> ตั้งค่า ผู้ใช้งาน
    </div>

    <setting-users unit="{{ json_encode($unit) }}" user_id="{{ auth()->user()->id }}" user_level="{{ auth()->user()->level }}"></setting-users>
  </div>
  @endsection