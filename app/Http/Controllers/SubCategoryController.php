<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MotherCategory;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    public function index()
    {
        $motherCategories=MotherCategory::all();

        return view('admin.category.add-subCategory')->with('motherCategories',$motherCategories);
    }

    public function create()
    {
        $motherCategories=MotherCategory::all();

        $subCategory = SubCategory::join('mother_categories', 'sub_categories.mother_category_id', '=', 'mother_categories.id')
            ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->select('sub_categories.*', 'mother_categories.mother_name', 'categories.category_name')
            ->get();

        return view('admin.category.add-subCategory')->with([
            'subCategory' => $subCategory,
            'motherCategories' => $motherCategories
        ]);
    }

    public function store(Request $request)
    {
        /*$validator =Validator::make($request->all(), [
            'motherCategory' => 'required',
            'category' => 'required',
            'subCategoryName' => 'required'

        ]);

        if ($validator->fails()) {
            Session::flash('error', 'Opps! some field are not properly inserted!!');
            return redirect()->back()->withErrors($validator);
        }*/

        $validator = Validator::make($request->all(), [
            'motherCategory'        => ['required'],
            'category'              => ['required',],
            'subCategoryName'       => ['required']
        ]);

        if ($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }

        SubCategory::create([
            'mother_category_id' =>$request->motherCategory,
            'category_id' =>$request->category,
            'sub_category_name'=>$request->subCategoryName
        ]);

        return redirectBackWithNotification('success', 'Sub Category Created Successfully!');
//        return redirect()->back()->with('message','Sub Category Created Successfully');
    }

    public function update(Request $request, SubCategory $subCategory)
    {
        $subCategory->update([
            'sub_category_name' => $request->sub_category_name,
        ]);


        return redirect()->back()->with('message','Sub Category Updated Successfully');
    }


    public function selectCategoryAjax(Request $request, $id) {
        if ($request->ajax()) {
            return response()->json([
                'categories' => Category::where('mother_category_id', $id)->get()
            ]);
        }
    }

    public function del($id){
        $mc=SubCategory::find($id);

        try {
            $mc->delete();
            return redirectBackWithNotification('success', 'Sub Category Deleted Successfully!');
        }
        catch (\Exception $exception) {
            return redirectBackWithException($exception);
        }

//        return redirect()->back()->with('message','Sub Category Deleted Successfully');
    }


    public function edit($id) {

        $subCategory = SubCategory::findOrFail($id);

        $motherCategories = MotherCategory::all();

        $category = Category::all();

        return view('admin.category.edit-subCategory')
            ->with([
                'subCategory' => $subCategory,
                'motherCategories' => $motherCategories,
                'category'   => $category,
            ]);
    }

    public function updateSubCategory(Request $request, $id) {

        $validator = Validator::make($request->all(), [
            'motherCategory'        => ['required'],
            'category'              => ['required',],
            'subCategoryName'       => ['required']
        ]);

        if ($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }

        $subCategory = SubCategory::findOrFail($id);

        $subCategory->mother_category_id = $request->post('motherCategory');
        $subCategory->category_id = $request->post('category');
        $subCategory->sub_category_name = $request->post('subCategoryName');

        $subCategory->save();

        return redirectBackWithNotification('success', 'Sub Category Updated Successfully!');

//        return redirect()->back()->with('message','Sub Category Updated Successfully');

    }
}
