<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::firstOrCreate([]);
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'library_name' => 'required',
            'contact_email' => 'required|email',
            'borrow_limit' => 'required|integer',
            'return_days' => 'required|integer',
            'late_fine' => 'required|integer',
        ]);

        $settings = Setting::first();
        $settings->update($request->all());

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated!');
    }
}
