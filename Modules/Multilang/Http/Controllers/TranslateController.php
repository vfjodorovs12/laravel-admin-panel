<?php
namespace Modules\Multilang\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;

class TranslateController extends Controller
{
    public function index()
    {
        $languages = Language::all();
        return view('multilang.translate.index', compact('languages'));
    }
}
