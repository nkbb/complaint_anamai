@extends('layouts.app')

@section('pageTitle', 'ระบบบริหารจัดการข้อคิดเห็นข้อร้องเรียน กรมสุขภาพจิต')

@section('content')
<div>
    
    <image-carouse-component isload="true"></image-carouse-component>

    <home-popup-component image="{{$banner}}"></home-popup-component>

    <!-- <home-type-component></home-type-component> -->

    <home-follow-component></home-follow-component>


    <!-- <image-carouse-component isload="true"></image-carouse-component> -->
    <!-- <image-carouse-banner></image-carouse-banner> -->

    <div class="grid justify-items-center py-[68px] bg-[#13849c]">
        <h1 class="flex justify-center"><div class="border-b-[2px] pb-3 border-[#ffffff] text-[#ffffff] text-[28px]">รับเรื่องร้องเรียน</div></h1>
        
        <div class="text-center text-[#ffffff] text-base mt-8">ช่องทางการติดต่อ</div>
        <div class="flex flex-row gap-2 justify-center mt-6">
            <div class="bg-[#ffffff] rounded-[50%] py-[5px] px-[10px]"><i class="far fa-envelope text-[#1d684a] text-[24px]"></i></div>
            <div class="text-[#ffffff] text-xl" >4000@anamai.mail.go.th</div>
        </div>
    </div>
    

    <!-- <home-manual-component></home-manual-component> -->
    <!-- <home-agreement-component></home-agreement-component> -->
    <home-evaluation-component></home-evaluation-component>
    <home-cookie-component></home-cookie-component>
    <!-- <home-comments-component></home-comments-component> -->

</div>
@endsection