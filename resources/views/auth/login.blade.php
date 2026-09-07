<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name', 'Safepedia') }}</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-2xl border border-slate-200/80 p-8 shadow-sm">

        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                    </path>
                </svg>
            </div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">
                Selamat Datang Kembali
            </h1>
            <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
                Masuk ke akun Anda untuk mengakses sistem pengajuan gudang.
            </p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div
                class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs font-medium">
                {{ session('status') }}
            </div>
        @endif

        <!-- Global Errors -->
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-100 text-rose-700 text-xs">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Login -->
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email"
                    class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                    Email
                </label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-900 text-sm placeholder:text-slate-400 focus:bg-white focus:border-indigo-600 focus:outline-none transition-all duration-200"
                    placeholder="nama@safepedia.co.id" required autofocus autocomplete="username">
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">
                        Password
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-xs font-medium text-indigo-600 hover:text-indigo-700 transition duration-150">
                            Lupa password?
                        </a>
                    @endif
                </div>
                <input type="password" id="password" name="password"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-900 text-sm placeholder:text-slate-400 focus:bg-white focus:border-indigo-600 focus:outline-none transition-all duration-200"
                    placeholder="••••••••" required autocomplete="current-password">
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between pt-1">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="remember_me" name="remember"
                        class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 transition duration-150">
                    <span class="ms-2 text-xs text-slate-600 font-medium">Ingat saya</span>
                </label>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit"
                    class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-semibold text-xs rounded-xl transition duration-200 ease-in-out cursor-pointer active:scale-[0.99]">
                    Masuk ke Akun
                </button>
            </div>
        </form>

    </div>

</body>

</html>
