<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        if (!auth()->user()?->canEdit('settings')) {
            return redirect()->back()->with('error', 'You do not have permission to edit settings.');
        }

        $request->validate([
            'site_logo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:3072',
            'site_favicon' => 'nullable|file|mimes:ico,png,svg,jpg,jpeg,webp|max:1024',
        ]);

        // Handle Site Logo Upload
        if ($request->hasFile('site_logo')) {
            $oldLogo = Setting::get('site_logo');
            if ($oldLogo && str_starts_with($oldLogo, 'storage/settings/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $oldLogo));
            }
            $logoPath = $request->file('site_logo')->store('settings', 'public');
            Setting::set('site_logo', 'storage/' . $logoPath);
        } elseif ($request->input('remove_logo') === '1') {
            $oldLogo = Setting::get('site_logo');
            if ($oldLogo && str_starts_with($oldLogo, 'storage/settings/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $oldLogo));
            }
            Setting::set('site_logo', null);
        }

        // Handle Site Favicon Upload
        if ($request->hasFile('site_favicon')) {
            $oldFavicon = Setting::get('site_favicon');
            if ($oldFavicon && str_starts_with($oldFavicon, 'storage/settings/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $oldFavicon));
            }
            $favPath = $request->file('site_favicon')->store('settings', 'public');
            Setting::set('site_favicon', 'storage/' . $favPath);
        } elseif ($request->input('remove_favicon') === '1') {
            $oldFavicon = Setting::get('site_favicon');
            if ($oldFavicon && str_starts_with($oldFavicon, 'storage/settings/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $oldFavicon));
            }
            Setting::set('site_favicon', null);
        }

        // Save other textual settings
        $data = $request->except([
            '_token', '_method', 'site_logo', 'site_favicon', 'remove_logo', 'remove_favicon'
        ]);

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->route('admin.settings')->with('success', 'System settings updated successfully!');
    }
}
