<?php

namespace App\Helpers;

class MenuHelper
{
    public static function getMenuGroups()
    {
        $user = auth()->user();

        $groups = [];

        // 1. Menu Utama
        $mainMenu = [];
        if ($user && $user->can('view Dashboard')) {
            $mainMenu[] = [
                'label' => 'Dashboard',
                'icon' => '<svg class="fill-current w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M4 13h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1zm-1 7a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v4zm10 0a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-7a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v7zm1-10h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1z"/></svg>',
                'path' => 'dashboard',
            ];
        }
        if (count($mainMenu) > 0) {
            $groups[] = ['title' => '', 'items' => $mainMenu];
        }

        // 2. Master Data
        $masterData = [];
        if ($user && $user->can('view Unit Kerja')) {
            $masterData[] = [
                'label' => 'Unit Kerja',
                'icon' => '<svg class="fill-current w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M19 4h-3V2h-2v2h-4V2H8v2H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2V6c0-1.103-.897-2-2-2zM5 20V8h14l.002 12H5z"/></svg>',
                'path' => 'unit-kerja',
            ];
        }
        if ($user && $user->can('view Kategori Surat')) {
            $masterData[] = [
                'label' => 'Kategori Surat',
                'icon' => '<svg class="fill-current w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M20 5h-9.586L8.707 2.293A.996.996 0 0 0 8 2H4c-1.103 0-2 .897-2 2v16c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V7c0-1.103-.897-2-2-2zM4 19V7h16l.002 12H4z"/></svg>',
                'path' => 'kategori-surat',
            ];
        }
        if ($user && $user->can('view Pengguna')) {
            $masterData[] = [
                'label' => 'Pengguna',
                'icon' => '<svg class="fill-current w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C9.243 2 7 4.243 7 7s2.243 5 5 5 5-2.243 5-5-2.243-5-5-5zm0 8c-1.654 0-3-1.346-3-3s1.346-3 3-3 3 1.346 3 3-1.346 3-3 3zm9 11v-1c0-3.859-3.141-7-7-7H10c-3.859 0-7 3.141-7 7v1h2v-1c0-2.757 2.243-5 5-5h4c2.757 0 5 2.243 5 5v1h2z"/></svg>',
                'path' => 'users',
            ];
        }
        if ($user && $user->can('view Hak Akses')) {
            $masterData[] = [
                'label' => 'Hak Akses',
                'icon' => '<svg class="fill-current w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M21.226 7.472a1 1 0 0 0-.693-.564l-8.082-1.996a1 1 0 0 0-.616.035l-8.082 3.15A1 1 0 0 0 3 9.034a17.653 17.653 0 0 0 2.215 9.035A11.733 11.733 0 0 0 12 23c2.766 0 5.253-1.472 6.785-4.931A17.653 17.653 0 0 0 21 9.034a1 1 0 0 0-.074-.562zM12 20.892c-2.029 0-3.896-1.189-5.1-3.921A15.655 15.655 0 0 1 5 9.689l6.762-2.635 6.762 1.67a15.655 15.655 0 0 1-1.424 8.247c-1.204 2.732-3.071 3.921-5.1 3.921zM11 9h2v6h-2zM11 16h2v2h-2z"/></svg>',
                'path' => 'roles',
            ];
        }
        if (count($masterData) > 0) {
            $groups[] = ['title' => 'Master Data', 'items' => $masterData];
        }

        // 3. Surat Masuk
        $suratMasuk = [];
        if ($user && $user->can('view Surat Masuk')) {
            $suratMasuk[] = [
                'label' => 'Daftar Surat Masuk',
                'icon' => '<svg class="fill-current w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M20 4H4c-1.103 0-2 .897-2 2v12c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V6c0-1.103-.897-2-2-2zm0 2v.511l-8 6.223-8-6.222V6h16zM4 18V9.044l7.386 5.745a.994.994 0 0 0 1.228 0L20 9.044 20.002 18H4z"/></svg>',
                'path' => 'surat-masuk',
            ];
        }
        if ($user && $user->can('view Disposisi')) {
            $suratMasuk[] = [
                'label' => 'Disposisi Surat',
                'icon' => '<svg class="fill-current w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M19 11h-6V5h-2v6H5v2h6v6h2v-6h6z"/></svg>', // Example icon
                'path' => 'disposisi',
            ];
        }
        if (count($suratMasuk) > 0) {
            $groups[] = ['title' => 'Surat Masuk', 'items' => $suratMasuk];
        }

        // 4. Surat Keluar
        $suratKeluar = [];
        if ($user && $user->can('view Surat Keluar')) {
            $suratKeluar[] = [
                'label' => 'Daftar Surat Keluar',
                'icon' => '<svg class="fill-current w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M20 4H4c-1.103 0-2 .897-2 2v12c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V6c0-1.103-.897-2-2-2zm0 2v.511l-8 6.223-8-6.222V6h16zM4 18V9.044l7.386 5.745a.994.994 0 0 0 1.228 0L20 9.044 20.002 18H4z"/></svg>',
                'path' => 'surat-keluar',
            ];
        }
        if ($user && $user->can('view Persetujuan')) {
            $suratKeluar[] = [
                'label' => 'Persetujuan Surat',
                'icon' => '<svg class="fill-current w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m10 15.586-3.293-3.293-1.414 1.414L10 18.414l9.707-9.707-1.414-1.414z"/></svg>',
                'path' => 'persetujuan',
            ];
        }
        if (count($suratKeluar) > 0) {
            $groups[] = ['title' => 'Surat Keluar', 'items' => $suratKeluar];
        }

        // 5. Sistem
        $sistem = [];
        if ($user && $user->can('manage Pengaturan')) {
            $sistem[] = [
                'label' => 'Pengaturan',
                'icon' => '<svg class="fill-current w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 16c2.206 0 4-1.794 4-4s-1.794-4-4-4-4 1.794-4 4 1.794 4 4 4zm0-6c1.084 0 2 .916 2 2s-.916 2-2 2-2-.916-2-2 .916-2 2-2z"/><path d="m2.845 16.136 1 1.73c.531.917 1.809 1.261 2.73.73l.529-.306A8.1 8.1 0 0 0 9 19.402V20c0 1.103.897 2 2 2h2c1.103 0 2-.897 2-2v-.598a8.132 8.132 0 0 0 1.896-1.111l.529.306c.923.53 2.198.188 2.731-.731l.999-1.729a2.001 2.001 0 0 0-.731-2.732l-.505-.292a7.718 7.718 0 0 0 0-2.224l.505-.292a2.002 2.002 0 0 0 .731-2.732l-.999-1.729c-.531-.92-1.808-1.265-2.731-.732l-.529.306A8.1 8.1 0 0 0 15 4.598V4c0-1.103-.897-2-2-2h-2c-1.103 0-2 .897-2 2v.598a8.132 8.132 0 0 0-1.896 1.111l-.529-.306c-.924-.531-2.2-.187-2.731.732l-.999 1.729a2.001 2.001 0 0 0 .731 2.732l.505.292a7.683 7.683 0 0 0 0 2.223l-.505.292a2.003 2.003 0 0 0-.731 2.733zm3.326-2.758A5.703 5.703 0 0 1 6 12c0-.462.058-.926.17-1.378a.999.999 0 0 0-.47-1.108l-1.123-.65.998-1.729 1.183.684a.999.999 0 0 0 1.185-.116 6.082 6.082 0 0 1 2.056-1.24.999.999 0 0 0 .626-.96V4h2v1.503a1 1 0 0 0 .626.96 6.082 6.082 0 0 1 2.056 1.24.999.999 0 0 0 1.185.116l1.183-.684.998 1.729-1.123.649a1 1 0 0 0-.47 1.108c.112.452.17.916.17 1.378 0 .461-.058.925-.171 1.378a1 1 0 0 0 .471 1.108l1.123.649-.998 1.729-1.183-.684a.999.999 0 0 0-1.185.116 6.082 6.082 0 0 1-2.056 1.24A.999.999 0 0 0 13 18.497V20h-2v-1.503a.999.999 0 0 0-.626-.96 6.082 6.082 0 0 1-2.056-1.24.999.999 0 0 0-1.185-.116l-1.183.684-.998-1.729 1.123-.649a1 1 0 0 0 .47-1.109z"/></svg>',
                'path' => 'settings',
            ];
        }
        if (count($sistem) > 0) {
            $groups[] = ['title' => 'Sistem', 'items' => $sistem];
        }

        return $groups;
    }
}
