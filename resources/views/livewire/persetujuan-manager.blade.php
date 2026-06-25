<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-title-md2 font-bold text-black dark:text-white">Persetujuan Surat Keluar</h2>
            <p class="text-sm font-medium text-gray-500">Proses persetujuan (approval) surat keluar sebelum dikirim.</p>
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
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nomor, perihal, atau tujuan..." class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-2.5 pl-10 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600" />
            <div class="absolute left-4 top-3">
                <svg class="fill-current w-5 h-5 text-gray-500" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M21.71 20.29L18 16.61A9 9 0 1 0 16.61 18l3.68 3.68a1 1 0 0 0 1.42 0 1 1 0 0 0 0-1.39zM11 18a7 7 0 1 1 7-7 7 7 0 0 1-7 7z"/></svg>
            </div>
        </div>
        <div class="w-full md:w-1/4 relative">
            <select wire:model.live="filterStatus" class="w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-2.5 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600">
                <option value="">Semua Status Approval</option>
                <option value="Review">Menunggu Persetujuan</option>
                <option value="Disetujui">Disetujui</option>
                <option value="Ditolak">Ditolak</option>
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
                        <th class="py-4 px-4 font-medium text-gray-900 dark:text-white">Tujuan & Perihal</th>
                        <th class="py-4 px-4 font-medium text-gray-900 dark:text-white">Pembuat</th>
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
                            <p class="text-gray-800 dark:text-gray-300 font-medium">{{ $surat->tujuan }}</p>
                            <p class="text-gray-500 text-xs line-clamp-2 mt-1">{{ $surat->perihal }}</p>
                        </td>
                        <td class="py-4 px-4 text-sm">
                            <p class="text-gray-800 dark:text-gray-300">{{ $surat->creator->name ?? '-' }}</p>
                        </td>
                        <td class="py-4 px-4 text-sm">
                            @php
                                $statusColors = [
                                    'Review' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300',
                                    'Disetujui' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
                                    'Draft' => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                                ];
                                $displayStatus = [
                                    'Review' => 'Menunggu Persetujuan',
                                    'Disetujui' => 'Disetujui',
                                    'Draft' => 'Dikembalikan (Ditolak)',
                                ];
                                $color = $statusColors[$surat->status] ?? 'bg-gray-100';
                                $text = $displayStatus[$surat->status] ?? $surat->status;
                            @endphp
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $color }}">
                                {{ $text }}
                            </span>
                            @if($surat->riwayatPersetujuan->count() > 0)
                                <button wire:click="showHistory({{ $surat->id_surat_keluar }})" class="block mt-2 text-xs text-blue-600 hover:underline">Lihat Riwayat</button>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-right xl:pr-6">
                            @if($surat->status === 'Review')
                                @can('create Persetujuan')
                                    <button wire:click="openProcessModal({{ $surat->id_surat_keluar }})" class="text-sm rounded bg-blue-50 px-2 py-1 font-medium text-blue-600 hover:bg-blue-100 dark:bg-blue-500/15 dark:text-blue-500 dark:hover:bg-blue-500/30">
                                        Proses Approval
                                    </button>
                                @endcan
                            @elseif($surat->status === 'Disetujui' && $surat->riwayatPersetujuan->count() > 0)
                                @can('create Persetujuan')
                                    <button wire:click="openProcessModal({{ $surat->id_surat_keluar }})" class="text-sm rounded bg-gray-50 px-2 py-1 font-medium text-gray-600 border border-gray-200 hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700">
                                        Ubah Keputusan
                                    </button>
                                @endcan
                            @else
                                <span class="text-xs text-gray-400">Sudah Diproses</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr class="border-t border-gray-200 dark:border-gray-800">
                        <td colspan="6" class="py-4 px-4 text-center text-gray-500">
                            Tidak ada surat yang menunggu persetujuan.
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

    <!-- Process Approval Modal -->
    <x-dialog-modal wire:model.live="isProcessOpen">
        <x-slot name="title">
            Proses Persetujuan Surat
        </x-slot>

        <x-slot name="content">
            @if (session()->has('error'))
                <div class="mb-4 flex w-full border-l-[6px] border-red-500 bg-red-50 px-4 py-3 shadow-sm dark:bg-gray-800 dark:border-red-500">
                    <div class="w-full">
                        <h5 class="text-sm font-semibold text-red-600 dark:text-red-400">Gagal Memproses</h5>
                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ session('error') }}</p>
                    </div>
                </div>
            @endif
            <div class="space-y-4">
                @if($suratId)
                    @php $s = \App\Models\SuratKeluar::find($suratId); @endphp
                    @if($s)
                        <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg mb-4 text-sm">
                            <div class="grid grid-cols-2 gap-2">
                                <div><span class="font-semibold text-gray-700 dark:text-gray-300">Nomor:</span> {{ $s->nomor_surat }}</div>
                                <div><span class="font-semibold text-gray-700 dark:text-gray-300">Tanggal:</span> {{ \Carbon\Carbon::parse($s->tanggal_surat)->format('d/M/Y') }}</div>
                                <div><span class="font-semibold text-gray-700 dark:text-gray-300">Tujuan:</span> {{ $s->tujuan }}</div>
                                <div><span class="font-semibold text-gray-700 dark:text-gray-300">Pembuat:</span> {{ $s->creator->name ?? '-' }}</div>
                                <div class="col-span-2"><span class="font-semibold text-gray-700 dark:text-gray-300">Perihal:</span> {{ $s->perihal }}</div>
                            </div>
                        </div>
                    @endif
                @endif

                <div>
                    <x-label for="status_persetujuan" value="Keputusan *" />
                    <select id="status_persetujuan" wire:model="status_persetujuan" class="mt-1 block w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600">
                        <option value="Approve">Setujui</option>
                        <option value="Reject">Tolak</option>
                    </select>
                    <x-input-error for="status_persetujuan" class="mt-2" />
                </div>

                <div>
                    <x-label for="catatan" value="Catatan / Alasan Penolakan" />
                    <textarea id="catatan" wire:model="catatan" class="mt-1 block w-full rounded-lg border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-gray-700 outline-none transition focus:border-blue-600 active:border-blue-600 disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-600" rows="3" placeholder="Tambahkan catatan jika perlu..."></textarea>
                    <x-input-error for="catatan" class="mt-2" />
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeProcessModal" wire:loading.attr="disabled">
                Batal
            </x-secondary-button>

            <x-button class="ms-3 bg-blue-600 hover:bg-blue-700" wire:click="processApproval" wire:loading.attr="disabled">
                Proses
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- History Modal -->
    <x-dialog-modal wire:model.live="isHistoryOpen">
        <x-slot name="title">
            Riwayat Persetujuan
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                @forelse($historyData as $history)
                    <div class="p-4 border rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $history->approver->name ?? 'Unknown User' }}</p>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($history->tanggal_approval)->format('d M Y, H:i') }}</p>
                            </div>
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $history->status === 'Approve' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">
                                {{ $history->status === 'Approve' ? 'Disetujui' : 'Ditolak' }}
                            </span>
                        </div>
                        @if($history->catatan)
                            <div class="mt-2 text-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-900 p-2 rounded border border-gray-200 dark:border-gray-700">
                                <span class="font-semibold text-xs text-gray-500 mb-1 block">Catatan:</span>
                                {{ $history->catatan }}
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Belum ada riwayat persetujuan.</p>
                @endforelse
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeHistoryModal" wire:loading.attr="disabled">
                Tutup
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>
</div>
