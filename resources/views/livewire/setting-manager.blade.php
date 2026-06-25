<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

    <!-- Page header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-8">

        <!-- Left: Title -->
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Pengaturan Aplikasi</h1>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 px-4 py-2 bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark p-6">
        <form wire:submit.prevent="save" enctype="multipart/form-data">
            <div class="space-y-4">
                
                <div>
                    <x-label for="app_name" value="Nama Aplikasi" />
                    <x-input id="app_name" type="text" class="mt-1 block w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600" wire:model="app_name" />
                    <x-input-error for="app_name" class="mt-2" />
                </div>
                
                <div>
                    <x-label for="app_subtitle" value="Keterangan Judul" />
                    <x-input id="app_subtitle" type="text" class="mt-1 block w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600" wire:model="app_subtitle" />
                    <x-input-error for="app_subtitle" class="mt-2" />
                </div>
                
                <div>
                    <x-label for="agency_name" value="Nama Instansi" />
                    <x-input id="agency_name" type="text" class="mt-1 block w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600" wire:model="agency_name" />
                    <x-input-error for="agency_name" class="mt-2" />
                </div>

                <div>
                    <x-label for="new_logo" value="Logo" />
                    @if ($new_logo)
                        <img src="{{ $new_logo->temporaryUrl() }}" class="mt-2 h-20 w-auto object-contain">
                    @elseif ($logo)
                        <img src="{{ Storage::url($logo) }}" class="mt-2 h-20 w-auto object-contain">
                    @endif
                    <input id="new_logo" type="file" wire:model="new_logo" class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                    <div wire:loading wire:target="new_logo" class="text-sm text-gray-500 mt-2">Mengunggah...</div>
                    <x-input-error for="new_logo" class="mt-2" />
                </div>

                <div class="flex items-center pt-4">
                    <button type="submit" class="flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700" wire:loading.attr="disabled" wire:loading.class="opacity-50">
                        <span wire:loading.remove wire:target="save">Simpan Pengaturan</span>
                        <span wire:loading wire:target="save">Menyimpan...</span>
                    </button>
                </div>
                
            </div>
        </form>
    </div>

</div>
