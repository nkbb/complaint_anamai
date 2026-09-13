@extends('layouts.app')

@section('pageTitle', 'ศูนย์รับข้อร้องเรียนและข้อชมเชย กรมอนามัย')

@section('content')

    <div class="py-6 px-7 mb-[80px] mx-4 md:mx-8 lg:mx-16 2xl:mx-[326px] rounded-3xl border border-slate-100 bg-white shadow-soft">
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div  class="px-4 md:px-[280px] xl:px-[420px]">

            <div class="text-center text-2xl text-brand-600 font-bold mb-4">เข้าสู่ระบบสำหรับเจ้าหน้าที่</div>
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

            <div class="flex items-center justify-center mt-11 mb-6">
                <button class="w-full text-center px-7 py-3.5 text-white bg-brand-600 border border-brand-600 rounded-md hover:cursor-pointer">
                    {{ __('เข้าสู่ระบบ') }}
                </button>
            </div>
        </div>
    </form>
</div>
    @endsection
