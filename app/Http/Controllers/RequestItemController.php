<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\InventoryManagement;
use App\Models\Item;
use App\Models\Manufacture;
use App\Models\MotherCategory;
use App\Models\Project;
use App\Models\RequestItem;
use App\Models\Settings;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mpdf\Tag\Input;

class RequestItemController extends Controller
{
    public function showRequest()
    {
        $motherCategory = MotherCategory::all();

        $manufacture  = Manufacture::all();

        if(Auth::user()->isAdmin() || Auth::user()->isAccountant()) {

            $project  = Project::where('project_status', '=', 'active')->get();
        }
        else {
            $project = Auth::user()->projects()
                ->where('project_status', '=', 'active')
                ->orderBy('created_at', 'DESC')
                ->get();
        }

        $itemList = RequestItem::all();

        return view('admin.item.request.request-item')->with([
            'motherCategory' => $motherCategory,
            'manufacture'  => $manufacture,
            'project'  => $project,
            'itemList' => $itemList
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return RedirectResponse
     */

    public function processRequest(Request $request): RedirectResponse
    {

//        dd($request->all());

        $add = Cart::add([
            'id' => $request->item_id,
            'name' => 'ID',
            'qty' => $request->quantity,
            'price' => $request->price,
            'weight' => $request->amount,
            'options' => [
                'mother_category_id' => $request->mother_category_id,
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'manufacture_id' => $request->manufacture_id,
                'request_date' => $request->request_date,
                'request_code' => $request->request_code,
                'request_id' => $request->request_id,
                'project_id' => $request->project_id,
                'item_id' => $request->item_id,
                'price' => $request->price,
                'vat' => $request->vat,
                'quantity' => $request->quantity,
                'amount' => $request->amount,
            ]
        ]);

//        dd($add);

        return redirect()->back()->with('message','Item added successfully');

    }

    public function processCartRequest(Request $request) {

//        dd($request->all());


        $request->validate([
            'addmore.*.item_id' => 'required',
            'addmore.*.price' => 'required',
            'addmore.*.quantity' => 'required',
            'addmore.*.amount' => 'required',
            'addmore.*.mother_category_id' => 'required',
        ]);

        foreach ($request->addmore as $key => $value) {
            RequestItem::insert($value);
        }

        Cart::destroy();

        return redirect()->route('request-inventory')
            ->with('message','Request has been send successfully');

    }


    public function showRequestList(){

        $itemList = RequestItem::all();

        return view('admin.item.request.request-item-list')->with([
            'itemList' => $itemList
        ]);
    }

    /* public function delete(Request $request, $id){

        $itemList=RequestItem::find($id);

        $itemList->delete();

        return redirect()->back()->with('message','Item Deleted successfully');
    } */

    public function delete(Request $request){
        Cart::remove($request->rowId);
        return [
            'success' => true,
            'message' => 'Delete Successful !!'
        ];
    }
}
