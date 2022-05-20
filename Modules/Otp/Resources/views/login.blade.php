@extends('core::layouts.app')

@section('content')
    <section class="w-full h-full flex flex-col justify-between">
        <form action="{{ route('otp.authenticate.request-otp.post.web') }}" method="post" class="w-full h-full flex flex-col justify-between">
            @csrf
            <div class="w-full mb-[2.5rem]">
                <div class="w-full py-3.5 px-3 shadow shadow-sm select-none text-[0.87rem]">
                    <h1 class="font-medium text-gray-900">ورود به حساب کاربری</h1>
                </div>
                <div class="w-full py-3.5 px-3">
                    <h3 class="text-[0.875rem] text-slate-600">شمارهٔ موبایل خود را وارد کنید</h3>
                    <p class="text-[0.79rem] mt-5 text-gray-400">برای استفاده از امکانات دیوار، لطفاً شمارهٔ موبایل خود را وارد کنید. کد تأیید به این شماره پیامک خواهد شد.</p>
                    <label class="relative w-full flex items-center mt-4">
                        <input type="text" name="phone_number" value="{{ old('phone_number') }}" maxlength="13" dir="ltr" class="w-full outline outline-1 outline-slate-200 text-gray-900 text-[0.88rem] py-[0.45rem] pl-[3.2rem] pr-2.5 text-left rounded-md placeholder:text-right placeholder:text-[0.79rem] focus:outline-primary focus:outline-2" placeholder="شماره موبایل">
                        <span class="absolute left-2 cursor-text text-[0.77rem] bg-slate-100 text-slate-600 rounded-full py-[0.1rem] px-[0.5rem] select-none" dir="ltr">+98</span>
                    </label>
                    @error('phone_number')
                    <div class="w-full text-right text-[0.715rem] text-primary mt-1">{{ $message }}</div>
                    @enderror
                    <p class="text-[0.79rem] text-gray-400 mt-[1.5rem]">شرایط استفاده از خدمات و <a href="" class="text-primary">حریم خصوصی</a> دیوار را می‌پذیرم.</p>
                </div>
            </div>
            <div class="py-2.5 px-3 bg-gray-50 bg-opacity-50 border-t">
                <button type="submit" class="w-full text-white border border-primary rounded-md py-2 bg-primary text-center text-[0.8rem] font-medium transition duration-120 cursor-pointer hover:bg-primary-light">تایید</button>
            </div>
        </form>
    </section>
@endsection
