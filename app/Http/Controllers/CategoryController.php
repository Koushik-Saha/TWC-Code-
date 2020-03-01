<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MotherCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.category.add-category');
    }

    public function create()
    {
        $motherCategories = MotherCategory::all();

        $category =  Category::join('mother_categories', 'categories.mother_category_id', '=', 'mother_categories.id')
        ->select('categories.*', 'mother_categories.mother_name')
        ->get();

        return view('admin.category.add-category')->with([
            'category' => $category,
            'motherCategories' => $motherCategories
        ]);
    }

    public function store(Request $request)
    {
        /*$validator =Validator::make($request->all(), [
            'motherCategory' => 'required',
            'categoryName' => 'required'

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }*/

        $validator = Validator::make($request->all(), [
            'motherCategory'            => ['required'],
            'categoryName'              => ['required']
        ]);

        if ($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }

        Category::create([
            'mother_category_id' =>$request->motherCategory,
            'category_name'=>$request->categoryName
        ]);

        return redirectBackWithNotification('success', 'Category Create successfully!');

//        return redirect()->back()->with('message','Category Create successfully');
    }

    public function update(Request $request, Category $category)
    {
        $category->update([
            'category_name' => $request->category_name ,
        ]);

        return redirect()->back()->with('message','Category Updated successfully');
    }

    public function del($id){
        $mc=Category::find($id);

        try {
            $mc->delete();
            return redirectBackWithNotification('success', 'Category Delete successfully!');
        }
        catch (\Exception $exception) {
            return redirectBackWithException($exception);
        }

//        return redirect()->back()->with('message','Category Delete successfully');
    }


    public function edit($id) {

        $category = Category::findOrFail($id);

        $motherCategories = MotherCategory::all();

        return view('admin.category.edit-category')
            ->with([
                'category' => $category,
                'motherCategories' => $motherCategories
            ]);
    }

    public function updateCategory(Request $request, $id) {

        $validator = Validator::make($request->all(), [
            'motherCategory'            => ['required'],
            'categoryName'              => ['required']
        ]);

        if ($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }

        $category = Category::findOrFail($id);

        $category->mother_category_id = $request->post('motherCategory');
        $category->category_name = $request->post('categoryName');

        $category->save();

        return redirectBackWithNotification('success', 'Category Updated Successfully!');

//        return redirect()->back()->with('message','Category Updated Successfully');

    }
}
