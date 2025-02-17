<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class CategoryController extends Controller
{
    /**
     * Retrieving all categories from the database
     */
    public function index()
    {
        $categories = Category::paginate10();
        return response()->json($categories, 200);
    }
    public function show($id)
    {
        $category = Category::find($id);
        if ($category) {
            return response()->json($category, 200);
        }
    }
    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'name' => 'required|unique:categories,name',
                'image' => 'required'

            ]);
            $category = new Category();
            if ($request->hasFile('image')) {
                $path = 'assets/uploads/category/'. $category->image;
                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '.' . $ext;
                try {
                    $file->move('assets/uploads/categories'. $filename);
                } catch (FileException $e) {
                    dd($e);
                }
            }
            $category->name = $request->name;
            $category->image = $filename;
            $category->save();
            return response()->json('New category added successfully', 201);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }
    public function update_category($id, Request $request)
    {
        try {
            $validate = $request->validate([
                'name' => 'required|unique$categories,name',
                'image' => 'required'
            ]);
            $category = Category::find($id);
            if ($request->hasFile('image')) {
                $path = 'assets/uploads/category/'. $category->image;
                if (File::exists($path)) {
                    File::delete($path);
                }
                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '.' . $ext;
                try {
                    $file->move('assets/uploads/categories'. $filename);
                } catch (FileException $e) {
                    dd($e);
                }
            }
            $category->name = $request->name;
            $category->update();
            return response()->json('Category updated', 200);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }
    public function delete_category($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->delete();
            return response()->json('Category deleted');
        } else {
            return response()->json('Category not deleted');
        }
    }
}
