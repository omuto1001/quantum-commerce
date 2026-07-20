<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <header class="w-full lg:max-w-4xl max-w-[335px] mb-6 not-has-[nav]:hidden">
    @if (Route::has('login'))
        <div class="flex items-center justify-center gap-2 mb-4">
            <x-application-logo class="w-8 h-8 text-green-600 dark:text-green-400" />
            <span class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Quantum Commerce</span>
        </div>

        <nav class="flex flex-col items-center gap-3">
            @auth
                <a href="{{ url('/dashboard') }}" class="inline-block px-6 py-2 bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1b1b18] rounded-md text-sm font-medium">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="inline-block px-6 py-2 bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1b1b18] rounded-md text-sm font-medium">
                    Log in
                </a>

                <div class="flex flex-wrap items-center justify-center gap-2 text-sm">
                    <span class="text-[#706f6c] dark:text-[#A1A09A]">New here?</span>
                    <a href="{{ route('register.customer') }}" class="text-[#1b1b18] dark:text-[#EDEDEC] underline underline-offset-4">
                        Customer
                    </a>
                    <span class="text-[#706f6c] dark:text-[#A1A09A]">·</span>
                    <a href="{{ route('register.vendor') }}" class="text-[#1b1b18] dark:text-[#EDEDEC] underline underline-offset-4">
                        Vendor
                    </a>
                    <span class="text-[#706f6c] dark:text-[#A1A09A]">·</span>
                    <a href="{{ route('register.rider') }}" class="text-[#1b1b18] dark:text-[#EDEDEC] underline underline-offset-4">
                        Rider
                    </a>
                </div>
            @endauth
        </nav>
    @endif
</header>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
    @if (Route::has('login'))
        <nav class="flex items-center justify-end gap-4">
            @auth
                <a href="{{ url('/dashboard') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal">
                    Log in
                </a>

                <a href="{{ route('register.customer') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                    Register as Customer
                </a>

                <a href="{{ route('register.vendor') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                    Register as Vendor
                </a>

                <a href="{{ route('register.rider') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                    Register as Rider
                </a>
            @endauth
        </nav>
    @endif
</header>
        <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
    <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">

        {{-- Left side: welcome text and role explanations --}}
        <div class="text-[13px] leading-[20px] flex-1 p-6 pb-6 lg:p-20 lg:pb-10 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">

            <h1 class="mb-2 text-2xl font-semibold text-green-700 dark:text-green-400">
                🛒 Quantum Commerce
            </h1>
            <p class="mb-6 text-[#706f6c] dark:text-[#A1A09A]">
                A multi-vendor marketplace connecting customers, sellers, and delivery agents in one platform.
            </p>

            {{-- Three role cards explaining what each account type can do --}}
            <ul class="flex flex-col mb-6 gap-4">
                <li class="flex items-start gap-3">
                    <span class="text-xl">🛍️</span>
                    <div>
                        <p class="font-semibold">Customers</p>
                        <p class="text-[#706f6c] dark:text-[#A1A09A]">Browse products from multiple vendors, order, and track deliveries in real time.</p>
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-xl">🏷️</span>
                    <div>
                        <p class="font-semibold">Vendors</p>
                        <p class="text-[#706f6c] dark:text-[#A1A09A]">List your products and manage orders. Accounts are reviewed by an admin before going live.</p>
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-xl">🏍️</span>
                    <div>
                        <p class="font-semibold">Delivery Agents</p>
                        <p class="text-[#706f6c] dark:text-[#A1A09A]">Accept and fulfill delivery requests once your application is approved.</p>
                    </div>
                </li>
            </ul>

            

           
        </div>

        
      {{-- Right side: hero photo with branding text overlaid --}}
<div class="relative lg:-ml-px -mb-px lg:mb-0 rounded-t-lg lg:rounded-t-none lg:rounded-r-lg aspect-[335/364] lg:aspect-auto w-full lg:w-[438px] shrink-0 overflow-hidden">

    {{-- Background photo - object-bottom shows the laptop/cart instead of empty top space --}}
    <img src="{{ asset('images/shop-now-hero.jpg') }}"
     alt="Quantum Commerce - Shop Now"
     class="absolute inset-0 w-full h-full object-contain bg-white">

    {{-- Light overlay strip behind the text so black text stays readable --}}
    <div class="absolute top-8 left-0 right-0 flex justify-center">
        <div class="bg-white/80 rounded-lg px-6 py-4 text-center">
            <p class="text-2xl font-bold text-[#1b1b18]">Quantum Commerce</p>
            <p class="mt-1 text-[#706f6c] text-sm">Shop. Sell. Deliver.</p>
        </div>
    </div>

    <div class="absolute inset-0 rounded-t-lg lg:rounded-t-none lg:rounded-r-lg shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]"></div>
</div>

@if (Route::has('login'))
    <div class="h-14.5 hidden lg:block"></div>
@endif
    </body>
</html>
