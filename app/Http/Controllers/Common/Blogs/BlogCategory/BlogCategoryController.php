<?php

namespace App\Http\Controllers\Common\Blogs\BlogCategory;

use App\Models\Blog\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Common\Blogs\BlogCategoryStoreRequest;

class BlogCategoryController extends Controller
{
     /**
     * Display a listing of the categories.
     */
    public function index()
    {
        $categories = BlogCategory::with('children')->whereNull('parent_id')->get();
        return response()->json($categories, 200);
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(BlogCategoryStoreRequest $request)
    {

        $category = BlogCategory::create($request->only(['name', 'slug', 'parent_id']));

        return response()->json([
            'message' => 'Category created successfully',
            'data' => $category
        ], 201);
    }

    /**
     * Display the specified category with its children.
     */
    public function show($id)
    {
        $category = BlogCategory::with('children')->find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json(['message' => 'Category retrieved successfully', 'data' => $category], 200);
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, $id)
    {

        // Validate status change
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:blog_categories,id|not_in:' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $category = BlogCategory::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->update($request->only(['name', 'parent_id']));

        return response()->json(['message' => 'Category updated successfully', 'category' => $category], 200);
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy($id)
    {
        $category = BlogCategory::with('children')->find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // Recursive delete function
        $this->deleteCategoryWithChildren($category);

        return response()->json(['message' => 'Category and its children deleted successfully'], 200);
    }

    /**
     * Recursive function to delete category and its children
     */
    protected function deleteCategoryWithChildren($category)
    {
        foreach ($category->children as $child) {
            // Recursively delete child's children
            $this->deleteCategoryWithChildren($child);
        }

        $category->delete();
    }

    /**
     * Get all categories for dropdown or other purposes.
     */
    public function list()
    {
        $categories = BlogCategory::all();

        return response()->json(['message' => 'Categories retrieved successfully', 'data' => $categories], 200);
    }


    /**
     * Reassign child categories and update the parent_id of the specified category.
     */
    public function reassignAndUpdateParent(Request $request, $id)
    {
        $category = BlogCategory::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $request->validate([
            'new_parent_id' => 'nullable|exists:categories,id|not_in:' . $id,
        ]);

        $newParentId = $request->input('new_parent_id'); // The new parent ID from the request

        // Reassign child categories
        if ($category->children()->exists()) {
            foreach ($category->children as $child) {
                $child->update(['parent_id' => $category->parent_id]);
            }
        }

        if($newParentId){
            // Update the category's parent_id
            $category->update(['parent_id' => $newParentId]);
        }else{
            $category->update(['parent_id' => null]);

        }

        return response()->json(['message' => 'Category updated successfully, and children reassigned', 'category' => $category], 200);
    }


}
