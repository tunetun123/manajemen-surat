<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

    <!-- Page header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-8">

        <!-- Left: Title -->
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Pengguna</h1>
        </div>

        <!-- Right: Actions -->
        <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
            <!-- Add button -->
            @can('create Pengguna')
            <button wire:click="create" class="flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700">
                <svg class="fill-current shrink-0" width="16" height="16" viewBox="0 0 16 16">
                    <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                </svg>
                <span>Tambah Pengguna</span>
            </button>
            @endcan
        </div>

    </div>

    @if (session()->has('message'))
        <div class="mb-4 px-4 py-2 bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <!-- Search & Filter -->
    <div class="mb-4 flex flex-col md:flex-row gap-4 justify-between items-center">
        <!-- Search -->
        <div class="w-full md:w-1/3 relative">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari pengguna..." class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-2.5 pl-10 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600" />
            <div class="absolute left-4 top-3">
                <svg class="fill-current w-5 h-5 text-gray-500" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M21.71 20.29L18 16.61A9 9 0 1 0 16.61 18l3.68 3.68a1 1 0 0 0 1.42 0 1 1 0 0 0 0-1.39zM11 18a7 7 0 1 1 7-7 7 7 0 0 1-7 7z"/></svg>
            </div>
        </div>

        <!-- Filter -->
        <div class="w-full md:w-auto">
            <select wire:model.live="filterRole" class="w-full sm:w-auto rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-2.5 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600 appearance-none">
                <option value="">Semua Hak Akses</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] mb-8">
        <div class="max-w-full overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50 text-left dark:bg-gray-900">
                        <th class="py-4 px-4 font-medium text-gray-900 dark:text-white xl:pl-6 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition" wire:click="sortBy('name')">
                            <div class="flex items-center gap-2">
                                Nama
                                @if($sortField === 'name')
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="{{ $sortDirection === 'asc' ? 'M10 5l-5 5h10l-5-5z' : 'M10 15l5-5H5l5 5z' }}"/></svg>
                                @else
                                    <svg class="w-4 h-4 fill-current text-gray-400 opacity-0 group-hover:opacity-100" viewBox="0 0 20 20"><path d="M10 5l-5 5h10l-5-5z"/></svg>
                                @endif
                            </div>
                        </th>
                        <th class="py-4 px-4 font-medium text-gray-900 dark:text-white cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition" wire:click="sortBy('email')">
                            <div class="flex items-center gap-2">
                                Email
                                @if($sortField === 'email')
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="{{ $sortDirection === 'asc' ? 'M10 5l-5 5h10l-5-5z' : 'M10 15l5-5H5l5 5z' }}"/></svg>
                                @endif
                            </div>
                        </th>
                        <th class="py-4 px-4 font-medium text-gray-900 dark:text-white">
                            Jabatan / Unit
                        </th>
                        <th class="py-4 px-4 font-medium text-gray-900 dark:text-white">
                            Role
                        </th>
                        <th class="py-4 px-4 font-medium text-gray-900 dark:text-white text-right xl:pr-6">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="border-t border-gray-200 dark:border-gray-800">
                        <td class="py-4 px-4 xl:pl-6">
                            <p class="text-gray-800 dark:text-gray-300 font-medium">{{ $user->name }}</p>
                        </td>
                        <td class="py-4 px-4">
                            <p class="text-gray-500">{{ $user->email }}</p>
                        </td>
                        <td class="py-4 px-4">
                            <p class="text-gray-800 dark:text-gray-300">{{ $user->jabatan ?: '-' }}</p>
                            <p class="text-sm text-gray-500">{{ $user->unitKerja->nama_unit ?? '-' }}</p>
                        </td>
                        <td class="py-4 px-4">
                            <div>
                                @foreach($user->roles as $role)
                                    <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-0.5 text-sm font-medium text-blue-700 dark:bg-blue-500/15 dark:text-blue-500">{{ $role->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="py-4 px-4 text-right xl:pr-6">
                            <div class="flex items-center justify-end space-x-3.5">
                                @can('edit Pengguna')
                                <button wire:click="edit({{ $user->id }})" class="hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 17.0001H5.657L16.2 6.45706L14.543 4.80006L4 15.3431V17.0001ZM21.707 5.29306L15.707 -0.706938L14.293 0.707062L20.293 6.70706L21.707 5.29306ZM2.00004 19.0001H7.00004L21.707 4.29306C22.098 3.90206 22.098 3.26906 21.707 2.87906L18.121 -0.706938C17.934 -0.894938 17.678 -1.00094 17.414 -1.00094C17.15 -1.00094 16.894 -0.894938 16.707 -0.706938L2.00004 14.0001V19.0001Z"></path>
                                    </svg>
                                </button>
                                @endcan
                                @can('delete Pengguna')
                                <button wire:click="deleteConfirm({{ $user->id }})" class="hover:text-red-600 dark:text-gray-400 dark:hover:text-white">
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
                        <td colspan="4" class="py-4 px-4 text-center text-gray-500">
                            Tidak ada data pengguna.
                        </td>
                    </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">
                {{ $users->links() }}
            </div>
        </div>
        

    <!-- Form Modal -->
    <x-dialog-modal wire:model.live="isOpen">
        <x-slot name="title">
            {{ $userId ? 'Edit Pengguna' : 'Tambah Pengguna' }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <x-label for="name" value="Nama" />
                    <x-input id="name" type="text" class="mt-1 block w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600" wire:model="name" />
                    <x-input-error for="name" class="mt-2" />
                </div>
                <div>
                    <x-label for="email" value="Email" />
                    <x-input id="email" type="email" class="mt-1 block w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600" wire:model="email" />
                    <x-input-error for="email" class="mt-2" />
                </div>
                <div>
                    <x-label for="role_name" value="Role" />
                    <select id="role_name" wire:model="role_name" class="mt-1 block w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600">
                        <option value="">Pilih Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="role_name" class="mt-2" />
                </div>
                <div>
                    <x-label for="jabatan" value="Jabatan" />
                    <x-input id="jabatan" type="text" class="mt-1 block w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600" wire:model="jabatan" />
                    <x-input-error for="jabatan" class="mt-2" />
                </div>
                <div>
                    <x-label for="id_unit" value="Unit Kerja" />
                    <select id="id_unit" wire:model="id_unit" class="mt-1 block w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600">
                        <option value="">Pilih Unit Kerja</option>
                        @foreach($unitKerjas as $unit)
                            <option value="{{ $unit->id_unit }}">{{ $unit->nama_unit }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="id_unit" class="mt-2" />
                </div>
                <div>
                    <x-label for="password" value="Password (Kosongkan jika tidak ingin mengubah)" />
                    <x-input id="password" type="password" class="mt-1 block w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600" wire:model="password" />
                    <x-input-error for="password" class="mt-2" />
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
            Hapus Pengguna
        </x-slot>

        <x-slot name="content">
            Apakah Anda yakin ingin menghapus pengguna ini?
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

</div>
