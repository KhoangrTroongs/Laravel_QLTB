<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::withCount('equipment')->latest()->get();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string|max:500',
            'specs' => 'nullable|array',
        ]);

        $data = $request->all();
        if (isset($data['specs'])) {
            $data['specs'] = array_filter($data['specs'], fn($value) => !empty($value));
        }

        Category::create($data);

        return redirect()->route('categories.index')->with('success', 'Loại thiết bị đã được thêm!');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id,
            'description' => 'nullable|string|max:500',
            'specs' => 'nullable|array',
        ]);

        $data = $request->all();
        if (isset($data['specs'])) {
            $data['specs'] = array_filter($data['specs'], fn($value) => !empty($value));
        } else {
            $data['specs'] = [];
        }

        $category->update($data);

        return redirect()->route('categories.index')->with('success', 'Loại thiết bị đã được cập nhật!');
    }

    public function destroy(Category $category)
    {
        if ($category->equipment()->count() > 0) {
            return back()->with('error', 'Không thể xóa loại này vì đang có thiết bị thuộc về nó!');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Loại thiết bị đã được xóa!');
    }
}
