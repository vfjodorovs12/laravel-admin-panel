<?php
namespace Modules\Multilang\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Language;

class SettingsController extends Controller
{
    public function index()
    {
        $multilang = Setting::get('multilang_enabled', 0);
        $lang_style = Setting::get('lang_style', 'flag');
        $languages = Language::all();
        return view('multilang.settings.index', compact('multilang', 'lang_style', 'languages'));
    }

    public function save(Request $request)
    {
        Setting::set('multilang_enabled', $request->has('multilang_enabled'));
        Setting::set('lang_style', $request->input('lang_style', 'flag'));
        return redirect()->route('multilang.settings.index')->with('success', 'Настройки сохранены');
    }

    public function addLanguage(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:10|unique:languages,code',
            'name' => 'required|string|max:50',
            'flag' => 'nullable|string|max:10',
        ]);
        Language::create($data);
        return redirect()->route('multilang.settings.index')->with('success', 'Язык добавлен');
    }

    public function deleteLanguage(Language $language)
    {
        $language->delete();
        return redirect()->route('multilang.settings.index')->with('success', 'Язык удалён');
    }
}
