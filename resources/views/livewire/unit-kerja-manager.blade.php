<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-title-md2 font-bold text-black dark:text-white">Manajemen Unit Kerja</h2>
            <p class="text-sm font-medium text-gray-500">Kelola data unit kerja / departemen dalam sistem.</p>
        </div>

        <!-- Right: Actions -->
        <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
            <!-- View Inactive button -->
            <button wire:click="openInactiveModal" class="flex items-center gap-2 rounded-lg bg-gray-100 dark:bg-gray-800 px-4 py-2 font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700">
                <svg class="fill-current w-4 h-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8zm1-13h-2v6l5.25 3.15.75-1.23-4-2.37V7z"/></svg>
                <span>Data Nonaktif</span>
            </button>

            <!-- Add button -->
            @can('create Unit Kerja')
            <button wire:click="create" class="flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700">
                <svg class="fill-current shrink-0" width="16" height="16" viewBox="0 0 16 16">
                    <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                </svg>
                <span>Tambah Unit Kerja</span>
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

    <!-- Search -->
    <div class="mb-4 flex flex-col md:flex-row gap-4 justify-between items-center">
        <div class="w-full md:w-1/3 relative">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari unit kerja..." class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-2.5 pl-10 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600" />
            <div class="absolute left-4 top-3">
                <svg class="fill-current w-5 h-5 text-gray-500" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M21.71 20.29L18 16.61A9 9 0 1 0 16.61 18l3.68 3.68a1 1 0 0 0 1.42 0 1 1 0 0 0 0-1.39zM11 18a7 7 0 1 1 7-7 7 7 0 0 1-7 7z"/></svg>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] mb-8">
        <div class="max-w-full overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50 text-left dark:bg-gray-900">
                        <th class="py-4 px-4 font-medium text-gray-900 dark:text-white xl:pl-6 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition" wire:click="sortBy('nama_unit')">
                            <div class="flex items-center gap-2">
                                Nama Unit
                                @if($sortField === 'nama_unit')
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="{{ $sortDirection === 'asc' ? 'M10 5l-5 5h10l-5-5z' : 'M10 15l5-5H5l5 5z' }}"/></svg>
                                @else
                                    <svg class="w-4 h-4 fill-current text-gray-400 opacity-0 group-hover:opacity-100" viewBox="0 0 20 20"><path d="M10 5l-5 5h10l-5-5z"/></svg>
                                @endif
                            </div>
                        </th>
                        <th class="py-4 px-4 font-medium text-gray-900 dark:text-white cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition" wire:click="sortBy('keterangan')">
                            <div class="flex items-center gap-2">
                                Keterangan
                                @if($sortField === 'keterangan')
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="{{ $sortDirection === 'asc' ? 'M10 5l-5 5h10l-5-5z' : 'M10 15l5-5H5l5 5z' }}"/></svg>
                                @endif
                            </div>
                        </th>
                        <th class="py-4 px-4 font-medium text-gray-900 dark:text-white text-right xl:pr-6">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($units as $unit)
                    <tr class="border-t border-gray-200 dark:border-gray-800">
                        <td class="py-4 px-4 xl:pl-6">
                            <p class="text-gray-800 dark:text-gray-300 font-medium">{{ $unit->nama_unit }}</p>
                        </td>
                        <td class="py-4 px-4">
                            <p class="text-gray-500">{{ $unit->keterangan ?? '-' }}</p>
                        </td>
                        <td class="py-4 px-4 text-right xl:pr-6">
                            <div class="flex items-center justify-end space-x-3.5">
                                @can('edit Unit Kerja')
                                <button wire:click="edit({{ $unit->id_unit }})" class="hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 17.0001H5.657L16.2 6.45706L14.543 4.80006L4 15.3431V17.0001ZM21.707 5.29306L15.707 -0.706938L14.293 0.707062L20.293 6.70706L21.707 5.29306ZM2.00004 19.0001H7.00004L21.707 4.29306C22.098 3.90206 22.098 3.26906 21.707 2.87906L18.121 -0.706938C17.934 -0.894938 17.678 -1.00094 17.414 -1.00094C17.15 -1.00094 16.894 -0.894938 16.707 -0.706938L2.00004 14.0001V19.0001Z"></path>
                                    </svg>
                                </button>
                                @endcan
                                @can('delete Unit Kerja')
                                <button wire:click="deactivateConfirm({{ $unit->id_unit }})" title="Nonaktifkan" class="hover:text-yellow-600 dark:text-gray-400 dark:hover:text-yellow-500">
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 2C6.47 2 2 6.47 2 12C2 17.53 6.47 22 12 22C17.53 22 22 17.53 22 12C22 6.47 17.53 2 12 2ZM17 13H7V11H17V13Z"></path>
                                    </svg>
                                </button>
                                <button wire:click="deleteConfirm({{ $unit->id_unit }})" title="Hapus Permanen" class="hover:text-red-600 dark:text-gray-400 dark:hover:text-red-500">
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17 6H22V8H20V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V8H2V6H7V3C7 2.44772 7.44772 2 8 2H16C16.5523 2 17 2.44772 17 3V6ZM18 8H6V20H18V8ZM9 11H11V17H9V11ZM13 11H15V17H13V11ZM9 4V6H15V4H9Z"></path>
                                    </svg>
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="border-t border-gray-200 dark:border-gray-800">
                        <td colspan="3" class="py-4 px-4 text-center text-gray-500">
                            Tidak ada data unit kerja.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">
            {{ $units->links() }}
        </div>
    </div>

    <!-- Form Modal -->
    <x-dialog-modal wire:model.live="isOpen">
        <x-slot name="title">
            {{ $unitId ? 'Edit Unit Kerja' : 'Tambah Unit Kerja' }}
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
            <div class="space-y-4">
                <div>
                    <x-label for="nama_unit" value="Nama Unit" />
                    <x-input id="nama_unit" type="text" class="mt-1 block w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600" wire:model="nama_unit" />
                    <x-input-error for="nama_unit" class="mt-2" />
                </div>
                <div>
                    <x-label for="keterangan" value="Keterangan" />
                    <textarea id="keterangan" wire:model="keterangan" class="mt-1 block w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600" rows="3"></textarea>
                    <x-input-error for="keterangan" class="mt-2" />
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

    <!-- Deactivate Confirmation Modal -->
    <x-confirmation-modal wire:model.live="isConfirmDeactivateOpen">
        <x-slot name="title">
            Nonaktifkan Unit Kerja
        </x-slot>

        <x-slot name="content">
            Apakah Anda yakin ingin menonaktifkan unit kerja ini?
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('isConfirmDeactivateOpen')" wire:loading.attr="disabled">
                Batal
            </x-secondary-button>

            <x-danger-button class="ms-3 bg-yellow-600 hover:bg-yellow-700" wire:click="deactivate" wire:loading.attr="disabled">
                Nonaktifkan
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    <!-- Delete Confirmation Modal -->
    <x-confirmation-modal wire:model.live="isConfirmDeleteOpen">
        <x-slot name="title">
            Hapus Permanen Unit Kerja
        </x-slot>

        <x-slot name="content">
            Apakah Anda yakin ingin menghapus permanen unit kerja ini? Data yang dihapus tidak dapat dikembalikan.
            Jika data ini pernah digunakan oleh pengguna lain, maka sistem akan menolaknya.
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('isConfirmDeleteOpen')" wire:loading.attr="disabled">
                Batal
            </x-secondary-button>

            <x-danger-button class="ms-3 bg-red-600 hover:bg-red-700" wire:click="delete" wire:loading.attr="disabled">
                Hapus
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    <!-- Inactive Modal -->
    <x-dialog-modal wire:model.live="isInactiveModalOpen">
        <x-slot name="title">
            Data Unit Kerja Nonaktif
        </x-slot>

        <x-slot name="content">
            <div class="max-w-full overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-50 text-left dark:bg-gray-900">
                            <th class="py-2 px-4 font-medium text-gray-900 dark:text-white">Nama Unit</th>
                            <th class="py-2 px-4 font-medium text-gray-900 dark:text-white text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inactiveUnits as $iu)
                        <tr class="border-t border-gray-200 dark:border-gray-800">
                            <td class="py-2 px-4">
                                <p class="text-gray-800 dark:text-gray-300">{{ $iu->nama_unit }}</p>
                            </td>
                            <td class="py-2 px-4 text-right">
                                <button wire:click="reactivate({{ $iu->id_unit }})" class="text-sm rounded-lg bg-green-500 px-3 py-1 font-medium text-white hover:bg-green-600">
                                    Aktifkan
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr class="border-t border-gray-200 dark:border-gray-800">
                            <td colspan="2" class="py-4 px-4 text-center text-gray-500">
                                Tidak ada data yang dinonaktifkan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeInactiveModal" wire:loading.attr="disabled">
                Tutup
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>

</div>
