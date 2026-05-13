<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'profile_photo.image' => 'File foto profil harus berupa gambar.',
            'profile_photo.max' => 'Ukuran foto profil maksimal 2MB.',
        ]);

        $user->nama_lengkap = $validated['nama_lengkap'];

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $dir = public_path('uploads/profile');

            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $filename = 'user-' . $user->user_id . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);

            if (!empty($user->profile_photo)) {
                $oldPath = public_path($user->profile_photo);
                if (is_file($oldPath) && str_contains(str_replace('\\', '/', $oldPath), '/uploads/profile/')) {
                    @unlink($oldPath);
                }
            }

            $user->profile_photo = 'uploads/profile/' . $filename;
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
