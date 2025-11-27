<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Routing\Controller;

class ModuleController extends Controller
{
    public function index()
    {
        $modules = [];
        $modulesPath = base_path('Modules');
        if (is_dir($modulesPath)) {
            foreach (scandir($modulesPath) as $dir) {
                if ($dir === '.' || $dir === '..') continue;
                $modulePath = $modulesPath . DIRECTORY_SEPARATOR . $dir;
                if (is_dir($modulePath)) {
                    $modules[] = [
                        'name' => $dir,
                        'path' => $modulePath,
                    ];
                }
            }
        }
        return view('admin.modules.index', compact('modules'));
    }
}
