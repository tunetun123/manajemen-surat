<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    // Dynamic redirect after login to the first accessible menu
    Route::get('/redirect', function () {
        $menuGroups = \App\Helpers\MenuHelper::getMenuGroups();
        foreach ($menuGroups as $group) {
            foreach ($group['items'] as $item) {
                if (isset($item['subItems']) && count($item['subItems']) > 0) {
                    return redirect('/' . $item['subItems'][0]['path']);
                } else {
                    return redirect('/' . $item['path']);
                }
            }
        }
        // If no menu is accessible, logout and show error
        auth('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        
        return redirect('/login')->with('error', 'Anda tidak memiliki akses ke aplikasi.');
    })->name('redirect');

    Route::get('/dashboard', \App\Livewire\Dashboard::class)->name('dashboard')->middleware('can:view Dashboard');

    // Kategori Surat
    Route::get('/kategori-surat', \App\Livewire\KategoriSuratManager::class)->name('kategori-surat')->middleware('can:view Kategori Surat');
    
    // Unit Kerja
    Route::get('/unit-kerja', \App\Livewire\UnitKerjaManager::class)->name('unit-kerja')->middleware('can:view Unit Kerja');
    
    // Surat Masuk
    Route::get('/surat-masuk', \App\Livewire\SuratMasukManager::class)->name('surat-masuk')->middleware('can:view Surat Masuk');
    
    // Disposisi
    Route::get('/disposisi', \App\Livewire\DisposisiManager::class)->name('disposisi')->middleware('can:view Disposisi');
    
    // Surat Keluar
    Route::get('/surat-keluar', \App\Livewire\SuratKeluarManager::class)->name('surat-keluar')->middleware('can:view Surat Keluar');
    
    // Persetujuan
    Route::get('/persetujuan', \App\Livewire\PersetujuanManager::class)->name('persetujuan')->middleware('can:view Persetujuan');
    
    // Pengguna
    Route::get('/users', \App\Livewire\UserManager::class)->name('users')->middleware('can:view Pengguna');
    
    // Hak Akses
    Route::get('/roles', \App\Livewire\RoleManager::class)->name('roles')->middleware('can:view Hak Akses');

    // Pengaturan
    Route::get('/settings', \App\Livewire\SettingManager::class)->name('settings')->middleware('can:manage Pengaturan');
});
