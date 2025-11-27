<?php

namespace Modules\News\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\News\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::latest()->paginate(10);
        return view('news::index', compact('news'));
    }

    public function create()
    {
        return view('news::create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);
        News::create($data);
        return redirect()->route('cp.news.index')->with('success', 'Новость добавлена');
    }

    public function edit(News $news)
    {
        return view('news::edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);
        $news->update($data);
        return redirect()->route('cp.news.index')->with('success', 'Новость обновлена');
    }

    public function destroy(News $news)
    {
        $news->delete();
        return redirect()->route('cp.news.index')->with('success', 'Новость удалена');
    }
}
