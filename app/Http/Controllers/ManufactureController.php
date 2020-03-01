<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Manufacture;
use App\Models\MotherCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ManufactureController extends Controller
{
    public function index()
    {
        return view('admin.category.add-manufacturer');
    }

    public function create()
    {
        return view('admin.category.add-manufacturer');
    }


    public function store(Request $request)
    {
        $validator =Validator::make($request->all(), [
            'name' => 'required',

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        Manufacture::create([
            'name'=>$request->name
        ]);

        return redirect()->back()->with('message','Manufacture Add successfully');
    }



    public function update(Request $request, Manufacture $manufacture)
    {
        $manufacture->update([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('message','Manufacture Updated successfully');
    }



    public function del($id){
        $mc=Manufacture::find($id);
        $mc->delete();

        return redirect()->back()->with('message','Manufacture Deleted successfully');
    }

    public function edit($id) {

        $manufacturer = Manufacture::findOrFail($id);

        return view('admin.category.edit-manufacturer')
            ->with([
                'manufacturer' => $manufacturer
            ]);
    }

    public function updateManufacturer(Request $request, $id) {

        $validator =Validator::make($request->all(), [
            'name' => 'required',

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $manufacturer = Manufacture::findOrFail($id);

        $manufacturer->name = $request->post('name');

        $manufacturer->save();

        return redirect()->back()->with('message','Manufacture Updated Successfully');

    }
}
