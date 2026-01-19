@extends('layouts.app')

@section('pageTitle', 'ระบบบริหารจัดการข้อคิดเห็นข้อร้องเรียน กรมสุขภาพจิต')

@section('content')

    <div class="py-6 px-7 bg-white mb-[80px] mx-4 md:mx-8 lg:mx-16 2xl:mx-[326px] border shadow-md">
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div  class="px-4 md:px-[280px] xl:px-[420px]">

            <div class="text-center text-2xl text-[#3fbbc0] mb-4">เข้าสู่ระบบ</div>
            <!-- Email Address -->
            <div>
                <x-input-label for="username" :value="__('ชื่อเข้าสู่ระบบ')" />
                <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('username')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('รหัสผ่าน')" />
                <x-text-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-center mt-4">
                <button class="w-full text-center px-4 py-2 text-white bg-[#13849c] border border-[#13849c] rounded-md hover:cursor-pointer">
                    {{ __('เข้าสู่ระบบ') }}
                </button>
            </div>
        </div>
    </form>
</div>
    @endsection
