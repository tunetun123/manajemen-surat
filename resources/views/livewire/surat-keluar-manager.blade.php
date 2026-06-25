<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-title-md2 font-bold text-black dark:text-white">Daftar Surat Keluar</h2>
            <p class="text-sm font-medium text-gray-500">Kelola dan lihat daftar surat keluar.</p>
        </div>

        <!-- Right: Actions -->
        <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
            @can('create Surat Keluar')
            <button wire:click="create" class="flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700">
                <svg class="fill-current shrink-0" width="16" height="16" viewBox="0 0 16 16">
                    <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                </svg>
                <span>Buat Surat Keluar</span>
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
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nomor, tujuan, perihal..." class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-2.5 pl-10 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600" />
            <div class="absolute left-4 top-3">
                <svg class="fill-current w-5 h-5 text-gray-500" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M21.71 20.29L18 16.61A9 9 0 1 0 16.61 18l3.68 3.68a1 1 0 0 0 1.42 0 1 1 0 0 0 0-1.39zM11 18a7 7 0 1 1 7-7 7 7 0 0 1-7 7z"/></svg>
            </div>
        </div>
        <div class="w-full md:w-1/4 relative">
            <select wire:model.live="filterStatus" class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-2.5 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600">
                <option value="">Semua Status</option>
                <option value="Draft">Draft</option>
                <option value="Review">Menunggu Persetujuan</option>
                <option value="Disetujui">Disetujui</option>
                <option value="Dikirim">Terkirim</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] mb-8">
        <div class="max-w-full overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50 text-left dark:bg-gray-900">
                        <th class="py-4 px-4 font-medium text-gray-900 dark:text-white xl:pl-6 cursor-pointer" wire:click="sortBy('tanggal_surat')">Tanggal</th>
                        <th class="py-4 px-4 font-medium text-gray-900 dark:text-white cursor-pointer" wire:click="sortBy('nomor_surat')">Nomor Surat</th>
                        <th class="py-4 px-4 font-medium text-gray-900 dark:text-white cursor-pointer" wire:click="sortBy('tujuan')">Tujuan</th>
                        <th class="py-4 px-4 font-medium text-gray-900 dark:text-white cursor-pointer" wire:click="sortBy('perihal')">Perihal</th>
                        <th class="py-4 px-4 font-medium text-gray-900 dark:text-white">Kategori & Lampiran</th>
                        <th class="py-4 px-4 font-medium text-gray-900 dark:text-white">Status</th>
                        <th class="py-4 px-4 font-medium text-gray-900 dark:text-white text-right xl:pr-6">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suratKeluars as $surat)
                    <tr class="border-t border-gray-200 dark:border-gray-800">
                        <td class="py-4 px-4 xl:pl-6 text-sm">
                            <p class="text-gray-800 dark:text-gray-300">{{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d/M/Y') }}</p>
                        </td>
                        <td class="py-4 px-4 text-sm">
                            <p class="text-gray-800 dark:text-gray-300 font-medium">{{ $surat->nomor_surat }}</p>
                            @if($surat->sifat_surat === 'Penting')
                                <span class="inline-block mt-1 text-xs px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded">Penting</span>
                            @elseif($surat->sifat_surat === 'Rahasia')
                                <span class="inline-block mt-1 text-xs px-2 py-0.5 bg-red-100 text-red-800 rounded">Rahasia</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-sm">
                            <p class="text-gray-800 dark:text-gray-300">{{ $surat->tujuan }}</p>
                        </td>
                        <td class="py-4 px-4 text-sm">
                            <p class="text-gray-800 dark:text-gray-300 line-clamp-2">{{ $surat->perihal }}</p>
                        </td>
                        <td class="py-4 px-4 text-sm">
                            <div class="flex flex-wrap gap-1 mb-2">
                                @foreach($surat->kategori as $kat)
                                    <span class="inline-block px-2 py-0.5 bg-gray-100 dark:bg-gray-700 rounded text-xs text-gray-700 dark:text-gray-300">{{ $kat->nama_kategori }}</span>
                                @endforeach
                            </div>
                            @if($surat->lampiran->count() > 0)
                                <span class="text-xs text-blue-600 dark:text-blue-400 font-medium">{{ $surat->lampiran->count() }} Lampiran</span>
                            @else
                                <span class="text-xs text-gray-500">Tidak ada lampiran</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-sm">
                            @php
                                $statusColors = [
                                    'Draft' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                    'Review' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300',
                                    'Disetujui' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
                                    'Dikirim' => 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300',
                                ];
                                $displayStatus = [
                                    'Draft' => 'Draft',
                                    'Review' => 'Menunggu Persetujuan',
                                    'Disetujui' => 'Disetujui',
                                    'Dikirim' => 'Terkirim',
                                ];
                                $color = $statusColors[$surat->status] ?? 'bg-gray-100';
                                $text = $displayStatus[$surat->status] ?? $surat->status;
                            @endphp
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $color }}">
                                {{ $text }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-right xl:pr-6">
                            <div class="flex items-center justify-end space-x-3.5">
                                @can('view Surat Keluar')
                                <button wire:click="view({{ $surat->id_surat_keluar }})" class="hover:text-green-600 dark:text-gray-400 dark:hover:text-white" title="Lihat Detail">
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 4C7 4 2.73 7.11 1 12C2.73 16.89 7 20 12 20C17 20 21.27 16.89 23 12C21.27 7.11 17 4 12 4ZM12 17C9.24 17 7 14.76 7 12C7 9.24 9.24 7 12 7C14.76 7 17 9.24 17 12C17 14.76 14.76 17 12 17ZM12 9C10.34 9 9 10.34 9 12C9 13.66 10.34 15 12 15C13.66 15 15 13.66 15 12C15 10.34 13.66 9 12 9Z"></path>
                                    </svg>
                                </button>
                                @endcan
                                @can('edit Surat Keluar')
                                <button wire:click="edit({{ $surat->id_surat_keluar }})" class="hover:text-blue-600 dark:text-gray-400 dark:hover:text-white" title="Edit">
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 17.0001H5.657L16.2 6.45706L14.543 4.80006L4 15.3431V17.0001ZM21.707 5.29306L15.707 -0.706938L14.293 0.707062L20.293 6.70706L21.707 5.29306ZM2.00004 19.0001H7.00004L21.707 4.29306C22.098 3.90206 22.098 3.26906 21.707 2.87906L18.121 -0.706938C17.934 -0.894938 17.678 -1.00094 17.414 -1.00094C17.15 -1.00094 16.894 -0.894938 16.707 -0.706938L2.00004 14.0001V19.0001Z"></path>
                                    </svg>
                                </button>
                                @endcan
                                @can('delete Surat Keluar')
                                <button wire:click="deleteConfirm({{ $surat->id_surat_keluar }})" class="hover:text-red-600 dark:text-gray-400 dark:hover:text-white">
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5 20C5 21.1046 5.89543 22 7 22H17C18.1046 22 19 21.1046 19 20V8H21V6H17V4C17 2.89543 16.1046 2 15 2H9C7.89543 2 7 2.89543 7 4V6H3V8H5V20ZM9 4H15V6H9V4ZM8 8H17V20H7V8H8Z"></path>
                                    </svg>
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="border-t border-gray-200 dark:border-gray-800">
                        <td colspan="7" class="py-4 px-4 text-center text-gray-500">
                            Tidak ada data surat keluar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">
            {{ $suratKeluars->links() }}
        </div>
    </div>

    <!-- Form Modal -->
    <x-dialog-modal wire:model.live="isOpen" maxWidth="4xl">
        <x-slot name="title">
            {{ $suratId ? 'Edit Surat Keluar' : 'Tambah Surat Keluar' }}
        </x-slot>

        <x-slot name="content">
            @if (session()->has('error'))
                <div class="mb-4 flex w-full border-l-[6px] border-red-500 bg-red-50 px-4 py-3 shadow-sm dark:bg-gray-800 dark:border-red-500">
                    <div class="w-full">
                        <h5 class="text-sm font-semibold text-red-600 dark:text-red-400">Gagal Menyimpan</h5>
                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ session('error') }}</p>
                    </div>
                </div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-label for="nomor_surat" value="Nomor Surat *" />
                    <input id="nomor_surat" type="text" class="mt-1 block w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600" wire:model="nomor_surat" />
                    <x-input-error for="nomor_surat" class="mt-2" />
                </div>
                <div>
                    <x-label for="tanggal_surat" value="Tanggal Surat *" />
                    <input id="tanggal_surat" type="date" class="mt-1 block w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600" wire:model="tanggal_surat" />
                    <x-input-error for="tanggal_surat" class="mt-2" />
                </div>
                <div>
                    <x-label for="tujuan" value="Tujuan *" />
                    <input id="tujuan" type="text" class="mt-1 block w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600" wire:model="tujuan" />
                    <x-input-error for="tujuan" class="mt-2" />
                </div>
                <div>
                    <x-label for="sifat_surat" value="Sifat Surat *" />
                    <select id="sifat_surat" wire:model="sifat_surat" class="mt-1 block w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600">
                        <option value="Biasa">Biasa</option>
                        <option value="Penting">Penting</option>
                        <option value="Rahasia">Rahasia</option>
                    </select>
                    <x-input-error for="sifat_surat" class="mt-2" />
                </div>
                <div class="md:col-span-2">
                    <x-label for="perihal" value="Perihal *" />
                    <input id="perihal" type="text" class="mt-1 block w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600" wire:model="perihal" />
                    <x-input-error for="perihal" class="mt-2" />
                </div>
                <div class="md:col-span-2">
                    <x-label for="ringkasan" value="Ringkasan" />
                    <textarea id="ringkasan" wire:model="ringkasan" class="mt-1 block w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600" rows="3"></textarea>
                    <x-input-error for="ringkasan" class="mt-2" />
                </div>
                
                <div class="md:col-span-2">
                    <x-label for="status" value="Status" />
                    <select id="status" wire:model="status" class="mt-1 block w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600">
                        <option value="Draft">Draft</option>
                        <option value="Review">Kirim untuk Persetujuan</option>
                        <option value="Dikirim">Terkirim</option>
                    </select>
                    <x-input-error for="status" class="mt-2" />
                </div>

                <div class="md:col-span-2">
                    <x-label value="Kategori Surat" />
                    <div class="mt-2 grid grid-cols-2 sm:grid-cols-3 gap-2">
                        @foreach($kategoris as $kat)
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" wire:model="selectedCategories" value="{{ $kat->id_kategori }}" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $kat->nama_kategori }}</span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error for="selectedCategories" class="mt-2" />
                </div>

                <div class="md:col-span-2">
                    <x-label for="upload" value="Unggah Lampiran (Bisa pilih satu per satu atau banyak sekaligus)" />
                    <input type="file" wire:model.live="upload" multiple class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-300 dark:hover:file:bg-gray-600" />
                    <div wire:loading wire:target="upload" class="text-sm text-blue-600 mt-2">Sedang memproses file...</div>
                    <x-input-error for="upload.*" class="mt-2" />
                    <x-input-error for="attachments.*" class="mt-2" />
                    
                    @if (count($attachments) > 0)
                        <div class="mt-3">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Lampiran Baru yang Dipilih:</p>
                            <ul class="space-y-2">
                                @foreach($attachments as $index => $file)
                                    <li class="flex items-center justify-between text-sm p-2 bg-blue-50 dark:bg-gray-800 rounded border border-blue-100 dark:border-gray-700">
                                        <span class="flex items-center gap-2 text-blue-700 dark:text-blue-400">
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 1.5L18.5 9H13V3.5zM18 20H6V4h5v7h7v9z"/></svg>
                                            {{ $file->getClientOriginalName() }}
                                        </span>
                                        <button type="button" wire:click="removeAttachment({{ $index }})" class="text-red-500 hover:text-red-700" title="Batal Unggah">
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M5 20C5 21.1046 5.89543 22 7 22H17C18.1046 22 19 21.1046 19 20V8H21V6H17V4C17 2.89543 16.1046 2 15 2H9C7.89543 2 7 2.89543 7 4V6H3V8H5V20ZM9 4H15V6H9V4ZM8 8H17V20H7V8H8Z"/></svg>
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if ($suratId)
                        @php $existingLampiran = \App\Models\SuratKeluar::find($suratId)->lampiran; @endphp
                        @if($existingLampiran->count() > 0)
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Lampiran Tersimpan:</p>
                                <ul class="space-y-2">
                                    @foreach($existingLampiran as $lamp)
                                    <li class="flex items-center justify-between text-sm p-2 bg-gray-50 dark:bg-gray-800 rounded">
                                        <a href="{{ Storage::url($lamp->path_file) }}" target="_blank" class="text-blue-600 hover:underline flex items-center gap-2">
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 1.5L18.5 9H13V3.5zM18 20H6V4h5v7h7v9z"/></svg>
                                            {{ $lamp->nama_file }}
                                        </a>
                                        <button wire:click="deleteLampiran({{ $lamp->id_lampiran }})" class="text-red-500 hover:text-red-700" title="Hapus Lampiran">
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M5 20C5 21.1046 5.89543 22 7 22H17C18.1046 22 19 21.1046 19 20V8H21V6H17V4C17 2.89543 16.1046 2 15 2H9C7.89543 2 7 2.89543 7 4V6H3V8H5V20ZM9 4H15V6H9V4ZM8 8H17V20H7V8H8Z"/></svg>
                                        </button>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal" wire:loading.attr="disabled">
                Batal
            </x-secondary-button>

            <x-button class="ms-3 bg-blue-600 hover:bg-blue-700" wire:click="store" wire:loading.attr="disabled">
                Simpan
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Delete Confirmation Modal -->
    <x-confirmation-modal wire:model.live="isConfirmDeleteOpen">
        <x-slot name="title">
            Hapus Surat Keluar
        </x-slot>

        <x-slot name="content">
            Apakah Anda yakin ingin menghapus surat keluar ini beserta lampirannya?
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('isConfirmDeleteOpen')" wire:loading.attr="disabled">
                Batal
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="delete" wire:loading.attr="disabled">
                Hapus
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    <!-- View Detail Modal -->
    <x-dialog-modal wire:model.live="isViewModalOpen" maxWidth="4xl">
        <x-slot name="title">
            Detail Surat Keluar
        </x-slot>

        <x-slot name="content">
            @if($viewData)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-sm font-semibold text-gray-500 mb-1">Nomor Surat</h4>
                    <p class="text-gray-900 dark:text-white">{{ $viewData->nomor_surat }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-500 mb-1">Tanggal Surat</h4>
                    <p class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($viewData->tanggal_surat)->format('d F Y') }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-500 mb-1">Tujuan</h4>
                    <p class="text-gray-900 dark:text-white">{{ $viewData->tujuan }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-500 mb-1">Sifat Surat</h4>
                    <p class="text-gray-900 dark:text-white">{{ $viewData->sifat_surat }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-500 mb-1">Status</h4>
                    <p class="text-gray-900 dark:text-white">
                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300">
                            @php
                                $displayStatus = [
                                    'Draft' => 'Draft',
                                    'Review' => 'Menunggu Persetujuan',
                                    'Disetujui' => 'Disetujui',
                                    'Dikirim' => 'Terkirim',
                                ];
                            @endphp
                            {{ $displayStatus[$viewData->status] ?? $viewData->status }}
                        </span>
                    </p>
                </div>
                <div class="md:col-span-2">
                    <h4 class="text-sm font-semibold text-gray-500 mb-1">Perihal</h4>
                    <p class="text-gray-900 dark:text-white">{{ $viewData->perihal }}</p>
                </div>
                <div class="md:col-span-2">
                    <h4 class="text-sm font-semibold text-gray-500 mb-1">Ringkasan</h4>
                    <p class="text-gray-900 dark:text-white">{{ $viewData->isi_ringkas ?? '-' }}</p>
                </div>
                <div class="md:col-span-2">
                    <h4 class="text-sm font-semibold text-gray-500 mb-1">Kategori</h4>
                    <div class="flex flex-wrap gap-2 mt-1">
                        @foreach($viewData->kategori as $kat)
                            <span class="inline-block px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded-full text-xs font-medium text-gray-700 dark:text-gray-300">{{ $kat->nama_kategori }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="md:col-span-2">
                    <h4 class="text-sm font-semibold text-gray-500 mb-2">Lampiran Dokumen</h4>
                    @if($viewData->lampiran->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($viewData->lampiran as $lamp)
                            <div class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <svg class="w-6 h-6 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 1.5L18.5 9H13V3.5zM18 20H6V4h5v7h7v9z"/></svg>
                                    <span class="text-sm text-gray-700 dark:text-gray-300 truncate">{{ $lamp->nama_file }}</span>
                                </div>
                                <a href="{{ Storage::url($lamp->path_file) }}" target="_blank" class="ml-2 flex-shrink-0 px-3 py-1 bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300 rounded text-xs font-medium hover:bg-blue-200 dark:hover:bg-blue-800 transition">
                                    Lihat
                                </a>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500">Tidak ada lampiran.</p>
                    @endif
                </div>
            </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeViewModal">
                Tutup
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>

</div>
