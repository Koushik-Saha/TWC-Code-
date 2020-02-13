<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\MotherCategory;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MotherCategoryController extends Controller
{
    public function index()
    {
        return view('admin.category.add-motherCategory');
    }

    public function create()
    {
        return view('admin.category.add-motherCategory');
    }


    public function store(Request $request)
    {
        $validator =Validator::make($request->all(), [
            'mother_name' => 'required',

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        MotherCategory::insert([
            'mother_name'=>$request->mother_name
        ]);

        return redirect()->back()->with('message','Mother Category Created Successfully');
    }

    public function edit($id) {

        $motherCategory = MotherCategory::findOrFail($id);

        return view('admin.category.edit-motherCategory')
            ->with([
                'motherCategory' => $motherCategory
            ]);
    }

    public function updateMotherCategory(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'mother_name'      => ['required', 'string'],
        ]);

        if($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }

        $motherCategory = MotherCategory::findOrFail($id);

        $motherCategory->mother_name = $request->post('mother_name');

        $motherCategory->save();

        return redirect()->back()->with('message','Mother Category Updated Successfully');

//        return redirectBackWithNotification();
    }



    public function update(Request $request, MotherCategory $motherCategory)
    {
        $motherCategory->update([
            'mother_name' => $request->mother_name
        ]);

        return redirect()->back()->with('message','Mother Category Updated successfully');
    }



    public function del($id){

        $mc=MotherCategory::find($id);
        $mc->delete();

        return redirect()->back()->with('message','Mother Category Deleted successfully');
    }
}
