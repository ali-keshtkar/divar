@extends('core::layouts.app')

@section('content')
    <section class="w-full h-full flex flex-col justify-between">
        <form action="{{ route('otp.authenticate.confirm-otp.post.web') }}" method="post" class="w-full h-full flex flex-col justify-between">
            @csrf
            <input type="hidden" name="phone_number" value="{{ session('phone_number') }}">
            <div class="w-full mb-[2.5rem]">
                <div class="w-full py-3.5 px-3 shadow shadow-sm select-none text-[0.87rem]">
                    <h1 class="font-medium text-gray-900">ورود به حساب کاربری</h1>
                </div>
                <div class="w-full py-3.5 px-3">
                    <h3 class="text-[0.875rem] text-slate-600">کد تایید را وارد کنید</h3>
                    <p class="text-[0.79rem] mt-5 text-gray-400">کد پیامک شده به شماره &CloseCurlyDoubleQuote;{{ session('phone_number') }}&CloseCurlyDoubleQuote;
                        را وارد کنید.</p>
                    <label class="relative w-full flex items-center mt-4">
                        <input type="text" maxlength="6" dir="ltr" name="otp_code" value="{{ old('otp_code') }}"
                               class="w-full outline outline-1 outline-slate-200 text-gray-900 text-[0.88rem] py-[0.45rem] px-2.5 text-left rounded-md placeholder:text-right placeholder:text-[0.79rem] focus:outline-primary focus:outline-2"
                               placeholder="کد تایید 6 رقمی">
                    </label>
                    @error('otp_code')
                    <div class="w-full text-right text-[0.715rem] text-primary mt-1">{{ $message }}</div>
                    @enderror
                    <div class="flex justify-end">
                        <a href="{{ route('otp.authenticate.page.login.get.web') }}"
                           class="text-[0.73rem] rounded-full bg-gray-100 mt-1.5 text-gray-600 bg-opacity-70 py-[0.2rem] px-[0.5rem] hover:bg-opacity-90">تغییر
                            شماره موبایل</a>
                    </div>
                </div>
            </div>
            <div class="py-2.5 px-3 bg-opacity-50 border-t flex flex-row">
                <div class="flex-auto w-2/4 ml-1.5">
                    <div
                        class="w-full h-full text-[0.86rem] text-gray-400 select-none flex items-center justify-center">
                        درخواست مجدد (1:25)
                    </div>
                    <button hidden
                            class="w-full text-primary border border-primary rounded-md py-2 text-center font-medium cursor-pointer">
                        درخواست کد
                    </button>
                </div>
                <div class="flex-auto w-2/4 mr-1.5">
                    <button
                        class="w-full text-white border border-primary rounded-md py-2 bg-primary text-center  font-medium transition duration-120 cursor-pointer hover:bg-primary-light">
                        ورود
                    </button>
                </div>
            </div>
        </form>
    </section>
@endsection
