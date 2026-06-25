<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

    <!-- Page header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Dashboard</h1>
        </div>
    </div>
    
    @php
        $isAdmin = auth()->check() && auth()->user()->hasRole('super_admin');
        $suratMasukCount = \App\Models\SuratMasuk::count();
        $suratKeluarCount = \App\Models\SuratKeluar::count();
        $disposisiCount = \App\Models\Disposisi::count();
        $userCount = $isAdmin ? \App\Models\User::count() : 0;
        
        $chartData = [$suratMasukCount, $suratKeluarCount, $disposisiCount];
        $chartLabels = ['Surat Masuk', 'Surat Keluar', 'Disposisi'];
        
        if ($isAdmin) {
            $chartData[] = $userCount;
            $chartLabels[] = 'Pengguna';
        }
    @endphp

    <!-- Colorful Cards -->
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 {{ $isAdmin ? 'xl:grid-cols-4' : 'xl:grid-cols-3' }} 2xl:gap-7.5 mb-8">

        <!-- Surat Masuk Card -->
        <div class="rounded-xl bg-blue-500 px-7.5 py-6 shadow-theme-sm dark:bg-blue-600 text-white transition-transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-title-lg font-bold">
                        {{ $suratMasukCount }}
                    </h4>
                    <span class="text-sm font-medium opacity-90">Surat Masuk</span>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-white/20">
                    <svg class="fill-current w-6 h-6" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M20 4H4c-1.103 0-2 .897-2 2v12c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V6c0-1.103-.897-2-2-2zm0 2v.511l-8 6.223-8-6.222V6h16zM4 18V9.044l7.386 5.745a.994.994 0 0 0 1.228 0L20 9.044 20.002 18H4z"/></svg>
                </div>
            </div>
        </div>

        <!-- Surat Keluar Card -->
        <div class="rounded-xl bg-emerald-500 px-7.5 py-6 shadow-theme-sm dark:bg-emerald-600 text-white transition-transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-title-lg font-bold">
                        {{ $suratKeluarCount }}
                    </h4>
                    <span class="text-sm font-medium opacity-90">Surat Keluar</span>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-white/20">
                    <svg class="fill-current w-6 h-6" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M20 4H4c-1.103 0-2 .897-2 2v12c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V6c0-1.103-.897-2-2-2zm0 2v.511l-8 6.223-8-6.222V6h16zM4 18V9.044l7.386 5.745a.994.994 0 0 0 1.228 0L20 9.044 20.002 18H4z"/></svg>
                </div>
            </div>
        </div>

        <!-- Disposisi Card -->
        <div class="rounded-xl bg-purple-500 px-7.5 py-6 shadow-theme-sm dark:bg-purple-600 text-white transition-transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-title-lg font-bold">
                        {{ $disposisiCount }}
                    </h4>
                    <span class="text-sm font-medium opacity-90">Total Disposisi</span>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-white/20">
                    <svg class="fill-current w-6 h-6" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M19 11h-6V5h-2v6H5v2h6v6h2v-6h6z"/></svg>
                </div>
            </div>
        </div>

        @if($isAdmin)
        <!-- Pengguna Card -->
        <div class="rounded-xl bg-orange-500 px-7.5 py-6 shadow-theme-sm dark:bg-orange-600 text-white transition-transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-title-lg font-bold">
                        {{ $userCount }}
                    </h4>
                    <span class="text-sm font-medium opacity-90">Total Pengguna</span>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-white/20">
                    <svg class="fill-current w-6 h-6" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                </div>
            </div>
        </div>
        @endif

    </div>

    <!-- Chart Section -->
    <div class="col-span-12 rounded-xl border border-gray-200 bg-white px-5 pb-5 pt-7 shadow-theme-sm dark:border-gray-800 dark:bg-gray-900 sm:px-7">
        <div class="mb-3 justify-between gap-4 sm:flex">
            <div>
                <h4 class="text-title-sm font-bold text-gray-800 dark:text-white/90">
                    Statistik Data
                </h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">Distribusi jumlah data pada setiap modul.</p>
            </div>
        </div>
        
        <div class="w-full relative h-[350px]">
            <canvas id="myChart"
                 data-series="{{ json_encode($chartData) }}"
                 data-labels="{{ json_encode($chartLabels) }}">
            </canvas>
        </div>
    </div>

</div>


