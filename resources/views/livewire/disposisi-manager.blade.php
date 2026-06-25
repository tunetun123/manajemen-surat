<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-title-md2 font-bold text-black dark:text-white">Manajemen Disposisi Surat</h2>
            <p class="text-sm font-medium text-gray-500">Lihat dan kelola disposisi surat masuk.</p>
        </div>

        <!-- Right: Actions -->
        <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
            @can('create Disposisi')
            <button wire:click="create" class="flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700">
                <svg class="fill-current shrink-0" width="16" height="16" viewBox="0 0 16 16">
                    <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                </svg>
                <span>Buat Disposisi Baru</span>
            </button>
            @endcan
        </div>
    </div>

    <!-- Alert Message -->
    @if (session()->has('message'))
        <div class="mb-6 flex w-full border-l-[6px] border-emerald-500 bg-emerald-50 px-7 py-4 shadow-md dark:bg-gray-800 dark:border-emerald-500">
            <div class="w-full">
                <h5 class="text-lg font-semibold text-emerald-600 dark:text-emerald-400">Berhasil</h5>
                <p class="text-base text-gray-600 dark:text-gray-300">{{ session('message') }}</p>
            </div>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-6 flex w-full border-l-[6px] border-red-500 bg-red-50 px-7 py-4 shadow-md dark:bg-gray-800 dark:border-red-500">
            <div class="w-full">
                <h5 class="text-lg font-semibold text-red-600 dark:text-red-400">Gagal</h5>
                <p class="text-base text-gray-600 dark:text-gray-300">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Filter & Search -->
    <div class="mb-4 flex flex-col md:flex-row gap-4 justify-between items-center">
        <div class="w-full md:w-1/3 relative">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nomor surat atau perihal..." class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-2.5 pl-10 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600" />
            <div class="absolute left-4 top-3">
                <svg class="fill-current w-5 h-5 text-gray-500" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M21.71 20.29L18 16.61A9 9 0 1 0 16.61 18l3.68 3.68a1 1 0 0 0 1.42 0 1 1 0 0 0 0-1.39zM11 18a7 7 0 1 1 7-7 7 7 0 0 1-7 7z"/></svg>
            </div>
        </div>
        <div class="w-full md:w-1/4 relative">
            <select wire:model.live="filterStatus" class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-2.5 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600">
                <option value="">Semua Status</option>
                <option value="Belum Diproses">Belum Diproses</option>
                <option value="Diproses">Diproses</option>
                <option value="Selesai">Selesai</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] mb-8">
        <div class="max-w-full overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50 text-left dark:bg-gray-900">
                        <th class="py-4 px-4 font-medium text-gray-900 dark:text-white xl:pl-6 cursor-pointer" wire:click="sortBy('tanggal_disposisi')">Tgl Disposisi</th>
                        <th class="py-4 px-4 font-medium text-gray-900 dark:text-white">Surat</th>
                        <th class="py-4 px-4 font-medium text-gray-900 dark:text-white">Dari</th>
                        <th class="py-4 px-4 font-medium text-gray-900 dark:text-white">Kepada</th>
                        <th class="py-4 px-4 font-medium text-gray-900 dark:text-white cursor-pointer" wire:click="sortBy('batas_waktu')">Batas Waktu</th>
                        <th class="py-4 px-4 font-medium text-gray-900 dark:text-white">Status</th>
                        <th class="py-4 px-4 font-medium text-gray-900 dark:text-white text-right xl:pr-6">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($disposisis as $disposisi)
                    <tr class="border-t border-gray-200 dark:border-gray-800">
                        <td class="py-4 px-4 xl:pl-6 text-sm">
                            <p class="text-gray-800 dark:text-gray-300">{{ \Carbon\Carbon::parse($disposisi->tanggal_disposisi)->format('d/M/Y H:i') }}</p>
                        </td>
                        <td class="py-4 px-4 text-sm">
                            <p class="text-gray-800 dark:text-gray-300 font-medium">{{ $disposisi->suratMasuk->nomor_surat ?? '-' }}</p>
                            <p class="text-gray-500 text-xs line-clamp-1 mt-1">{{ $disposisi->suratMasuk->perihal ?? '-' }}</p>
                        </td>
                        <td class="py-4 px-4 text-sm">
                            <p class="text-gray-800 dark:text-gray-300">{{ $disposisi->pengirim->name ?? '-' }}</p>
                        </td>
                        <td class="py-4 px-4 text-sm">
                            <p class="text-gray-800 dark:text-gray-300 font-medium">{{ $disposisi->penerima->name ?? '-' }}</p>
                            <p class="text-gray-500 text-xs">{{ $disposisi->penerima->jabatan ?? '' }}</p>
                        </td>
                        <td class="py-4 px-4 text-sm">
                            @php
                                $batas = \Carbon\Carbon::parse($disposisi->batas_waktu);
                                $isPast = $batas->isPast() && $disposisi->status !== 'Selesai';
                            @endphp
                            <p class="text-gray-800 dark:text-gray-300 {{ $isPast ? 'text-red-500 font-bold' : '' }}">
                                {{ $batas->format('d/M/Y') }}
                            </p>
                        </td>
                        <td class="py-4 px-4 text-sm">
                            @php
                                $statusColors = [
                                    'Belum Diproses' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                    'Diproses' => 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300',
                                    'Selesai' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
                                ];
                                $color = $statusColors[$disposisi->status] ?? 'bg-gray-100';
                            @endphp
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $color }}">
                                {{ $disposisi->status }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-right xl:pr-6">
                            @if(auth()->user()->hasRole('super_admin') || auth()->id() === $disposisi->kepada_user)
                                <button wire:click="openProcessModal({{ $disposisi->id_disposisi }})" class="text-sm rounded bg-blue-50 px-2 py-1 font-medium text-blue-600 hover:bg-blue-100 dark:bg-blue-500/15 dark:text-blue-500 dark:hover:bg-blue-500/30">
                                    Tindak Lanjut
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr class="border-t border-gray-200 dark:border-gray-800">
                        <td colspan="7" class="py-4 px-4 text-center text-gray-500">
                            Tidak ada data disposisi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">
            {{ $disposisis->links() }}
        </div>
    </div>

    <!-- Create Form Modal -->
    <x-dialog-modal wire:model.live="isOpen">
        <x-slot name="title">
            Buat Disposisi Baru
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <x-label for="id_surat_masuk" value="Surat Masuk *" />
                    <select id="id_surat_masuk" wire:model="id_surat_masuk" class="mt-1 block w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600">
                        <option value="">Pilih Surat</option>
                        @foreach($suratMasuks as $surat)
                            <option value="{{ $surat->id_surat_masuk }}">[{{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d/m/Y') }}] {{ $surat->nomor_surat }} - {{ Str::limit($surat->perihal, 50) }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="id_surat_masuk" class="mt-2" />
                </div>
                
                <div>
                    <x-label for="kepada_user" value="Diteruskan Kepada *" />
                    <select id="kepada_user" wire:model="kepada_user" class="mt-1 block w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600">
                        <option value="">Pilih Penerima</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} {{ $u->jabatan ? ' - '.$u->jabatan : '' }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="kepada_user" class="mt-2" />
                </div>

                <div>
                    <x-label for="batas_waktu" value="Batas Waktu *" />
                    <input id="batas_waktu" type="date" class="mt-1 block w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600" wire:model="batas_waktu" />
                    <x-input-error for="batas_waktu" class="mt-2" />
                </div>

                <div>
                    <x-label for="instruksi" value="Instruksi / Catatan *" />
                    <textarea id="instruksi" wire:model="instruksi" class="mt-1 block w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600" rows="3"></textarea>
                    <x-input-error for="instruksi" class="mt-2" />
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal" wire:loading.attr="disabled">
                Batal
            </x-secondary-button>

            <x-button class="ms-3 bg-blue-600 hover:bg-blue-700" wire:click="store" wire:loading.attr="disabled">
                Simpan & Teruskan
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Process Disposisi Modal -->
    <x-dialog-modal wire:model.live="isProcessOpen">
        <x-slot name="title">
            Tindak Lanjut Disposisi
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                @if($disposisiId)
                    @php 
                        $d = \App\Models\Disposisi::with('suratMasuk')->find($disposisiId); 
                    @endphp
                    @if($d)
                        <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg mb-4">
                            <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-2">Informasi Instruksi:</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ $d->instruksi }}</p>
                        </div>
                    @endif
                @endif

                <div>
                    <x-label for="processStatus" value="Update Status Pekerjaan" />
                    <select id="processStatus" wire:model="processStatus" class="mt-1 block w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600">
                        <option value="Belum Diproses">Belum Diproses</option>
                        <option value="Diproses">Sedang Diproses</option>
                        <option value="Selesai">Selesai</option>
                    </select>
                    <x-input-error for="processStatus" class="mt-2" />
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeProcessModal" wire:loading.attr="disabled">
                Batal
            </x-secondary-button>

            <x-button class="ms-3 bg-blue-600 hover:bg-blue-700" wire:click="processDisposisi" wire:loading.attr="disabled">
                Update Status
            </x-button>
        </x-slot>
    </x-dialog-modal>

</div>
