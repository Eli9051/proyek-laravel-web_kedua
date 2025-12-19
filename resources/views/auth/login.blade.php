<x-guest-layout>
    <div class="fixed inset-0 min-h-screen w-full flex flex-col md:flex-row bg-white overflow-hidden">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .swiper-slide { background-position: center; background-size: cover; }
    </style>

    <div class="min-h-screen w-full flex flex-col md:flex-row bg-white overflow-hidden">
        
        <div class="hidden md:block w-1/2 relative overflow-hidden">
            <div class="swiper mySwiper h-full w-full">
                <div class="swiper-wrapper">
                    <div class="swiper-slide" style="background-image: url('https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=2069&auto=format&fit=crop')">
                        <div class="absolute inset-0 bg-gradient-to-b from-blue-900/40 to-black/70 flex items-end p-16">
                            <div class="text-white max-w-lg">
                                <h2 class="text-4xl font-bold mb-4">Efisiensi Tanpa Batas</h2>
                                <p class="text-lg opacity-80">Kelola kehadiran dan performa tim Anda dalam satu platform cerdas.</p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" style="background-image: url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=2070&auto=format&fit=crop')">
                        <div class="absolute inset-0 bg-gradient-to-b from-blue-900/40 to-black/70 flex items-end p-16">
                            <div class="text-white max-w-lg">
                                <h2 class="text-4xl font-bold mb-4">Analisis Prediktif AI</h2>
                                <p class="text-lg opacity-80">Teknologi masa depan untuk memantau kesehatan organisasi Anda.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>

        <div class="w-full md:w-1/2 flex items-center justify-center p-8 md:p-16 bg-gray-50">
            <div class="w-full max-w-md bg-white p-10 rounded-3xl shadow-2xl shadow-blue-100 border border-gray-100">
                
                <div class="text-center mb-8">
                    <div class="inline-flex p-3 bg-blue-50 rounded-2xl mb-4">
                        <x-application-logo class="w-12 h-12 fill-current text-blue-600" />
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800">Selamat Datang Kembali</h1>
                    <p class="text-gray-500 text-sm">Silakan masuk untuk mengakses dashboard HRMS</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />
                {!! NoCaptcha::renderJs() !!}

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div class="flex justify-center scale-90 origin-center">
                        {!! NoCaptcha::display() !!}
                    </div>
                    @if ($errors->has('g-recaptcha-response'))
                        <p class="text-red-500 text-xs text-center mt-1">{{ $errors->first('g-recaptcha-response') }}</p>
                    @endif

                    <div>
                        <label class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Alamat Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="block w-full mt-1 px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Kata Sandi</label>
                        <input id="password" type="password" name="password" required
                            class="block w-full mt-1 px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ms-2 text-sm text-gray-600">Ingat saya</span>
                        </label>
                        @if (session('status') == 'locked')
                            <a href="{{ route('password.request') }}" class="text-xs font-bold text-red-500 hover:underline">Akun Terkunci?</a>
                        @endif
                    </div>

                    <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 transition-all active:scale-95">
                        Masuk Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        new Swiper(".mySwiper", {
            autoplay: { delay: 4000, disableOnInteraction: false },
            effect: "fade",
            pagination: { el: ".swiper-pagination", clickable: true },
        });
    </script>
</x-guest-layout>