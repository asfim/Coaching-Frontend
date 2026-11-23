<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index(Request $request)
    {
        $query = Notice::query()->latest('published_at');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $notices = $query->paginate(12);

        return view('admin.notices', compact('notices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'content' => 'required|string',
            'published_at' => 'required|date',
            'target_audience' => 'required|string|max:50',
            'status' => 'required|in:published,draft',
        ]);

        Notice::create($validated);

        return redirect()->route('admin.notices')->with('success', 'Notice posted successfully!');
    }

    public function update(Request $request, $id)
    {
        $notice = Notice::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'content' => 'required|string',
            'published_at' => 'required|date',
            'target_audience' => 'required|string|max:50',
            'status' => 'required|in:published,draft',
        ]);

        $notice->update($validated);

        return redirect()->route('admin.notices')->with('success', 'Notice updated successfully!');
    }

    public function destroy($id)
    {
        $notice = Notice::findOrFail($id);
        $notice->delete();

        return redirect()->route('admin.notices')->with('success', 'Notice deleted successfully!');
    }
}
