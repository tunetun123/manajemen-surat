<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400..700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles        

        <script>
            if (localStorage.getItem('dark-mode') === 'false' || !('dark-mode' in localStorage)) {
                document.querySelector('html').classList.remove('dark');
                document.querySelector('html').style.colorScheme = 'light';
            } else {
                document.querySelector('html').classList.add('dark');
                document.querySelector('html').style.colorScheme = 'dark';
            }
        </script>
    </head>
    <body x-data class="font-inter antialiased bg-gray-50 dark:bg-gray-900 text-gray-600 dark:text-gray-400">
        <!-- Apply dark mode immediately to prevent flash -->
        <script>
            (function() {
                const savedTheme = localStorage.getItem('theme');
                const isDark = savedTheme === 'dark';
                if (isDark) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            })();
        </script>

        <div class="relative z-1 bg-white p-6 sm:p-0 dark:bg-gray-900">
            <div class="relative flex h-screen w-full flex-col justify-center sm:p-0 lg:flex-row dark:bg-gray-900">
                <!-- Form -->
                <div class="flex w-full flex-1 flex-col lg:w-1/2">
                    <div class="mx-auto flex w-full max-w-md flex-1 flex-col justify-center">
                        {{ $slot }}
                    </div>
                </div>

                @php
                    $setting = \App\Models\Setting::first();
                    $logoUrl = $setting && $setting->logo_path ? Storage::url($setting->logo_path) : null;
                    $appName = $setting ? $setting->app_name : 'Urmin RS Bhayangkara';
                    $appSubtitle = $setting ? $setting->app_subtitle : 'Sistem Manajemen Dokumen Rumah Sakit Bhayangkara';
                    $agencyName = $setting ? $setting->institution_name : 'RS Bhayangkara';
                @endphp

                <!-- Image / Branding -->
                <div class="bg-brand-950 relative hidden h-full w-full items-center lg:grid lg:w-1/2 dark:bg-white/5">
                    <div class="z-1 flex items-center justify-center">
                        <!-- ===== Common Grid Shape Start ===== -->
                        <div>
                            <div class="absolute right-0 top-0 -z-1 w-full max-w-[250px] xl:max-w-[450px]">
                                <svg width="450" height="450" viewBox="0 0 450 450" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M450 450L450 0L0 0L450 450Z" fill="rgba(255, 255, 255, 0.03)"/>
                                </svg>
                            </div>
                            <div class="absolute bottom-0 left-0 -z-1 w-full max-w-[250px] rotate-180 xl:max-w-[450px]">
                                <svg width="450" height="450" viewBox="0 0 450 450" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M450 450L450 0L0 0L450 450Z" fill="rgba(255, 255, 255, 0.03)"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex max-w-xs flex-col items-center text-center">
                            @if($logoUrl)
                                <img src="{{ $logoUrl }}" alt="Logo" class="mb-6 h-40 w-auto object-contain">
                            @endif
                            <h2 class="text-3xl font-bold text-white mb-2">{{ $appName }}</h2>
                            @if($agencyName)
                                <h3 class="text-xl font-medium text-white/90 mb-4">{{ $agencyName }}</h3>
                            @endif
                            <p class="text-gray-400 dark:text-white/60">
                                {{ $appSubtitle }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @livewireScripts
        @livewireScriptConfig
    </body>
</html>
