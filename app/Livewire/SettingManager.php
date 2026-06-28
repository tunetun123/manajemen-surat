<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Setting;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class SettingManager extends Component
{
    use WithFileUploads;

    public $app_name;
    public $app_subtitle;
    public $agency_name;
    public $logo;
    public $new_logo;

    public function mount()
    {
        $setting = Setting::first();
        if ($setting) {
            $this->app_name = $setting->app_name;
            $this->app_subtitle = $setting->app_subtitle;
            $this->agency_name = $setting->institution_name;
            $this->logo = $setting->logo_path;
        } else {
            $this->app_name = 'Arsip Digital';
            $this->app_subtitle = 'Sistem Manajemen Dokumen';
            $this->agency_name = 'RS Bhayangkara';
        }
    }

    public function render()
    {
        return view('livewire.setting-manager')->layout('layouts.app')->title('Pengaturan');
    }

    public function save()
    {
        $this->validate([
            'app_name' => 'required|string|max:255',
            'app_subtitle' => 'nullable|string|max:255',
            'agency_name' => 'required|string|max:255',
            'new_logo' => 'nullable|image|max:2048', // 2MB max
        ]);

        $setting = Setting::first() ?? new Setting();
        
        $setting->app_name = $this->app_name;
        $setting->app_subtitle = $this->app_subtitle;
        $setting->institution_name = $this->agency_name;

        if ($this->new_logo) {
            if ($setting->logo_path && Storage::disk('public')->exists($setting->logo_path)) {
                Storage::disk('public')->delete($setting->logo_path);
            }
            $setting->logo_path = $this->new_logo->store('logos', 'public');
            $this->logo = $setting->logo_path;
        }

        $setting->save();

        session()->flash('message', 'Pengaturan berhasil disimpan.');
        
        return redirect(request()->header('Referer'));
    }
}
