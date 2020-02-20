<?php

namespace App\Http\Controllers;

use App\Models\InventoryManagement;
use App\Models\PurchaseItem;
use App\Models\RequestItem;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseItemController extends Controller
{
    public function processPurchaseApprove(Request $request)
    {

//        dd($request->all());

        $request->validate([
            'addmore.*.item_id' => 'required',
            'addmore.*.price' => 'required',
            'addmore.*.quantity' => 'required',
            'addmore.*.amount' => 'required',
        ]);


        foreach ($request->addmore as $key => $value) {
            PurchaseItem::insert($value);
        }

        return redirect()->back()->with('message','Approve Item has been send successfully');
    }


    public function purchaseItem(Request $request,$cartId){

        $cartValue = $cartId;

        $itemList = RequestItem::where('cartId','=',$cartValue)->get();

//        $itemList = PurchaseItem::all();


        return view('admin.item.purchase.purchase-item')->with([
            'itemList' => $itemList
        ]);
    }

    public function purchasePackageList(){

        if(Auth::user()->isAdmin() || Auth::user()->isAccountant()) {
            $itemList = PurchaseItem::select(
                DB::raw('cartId,
                           user_id,
                           status,
                           MAX(created_at) AS created_at,
                           MAX(id) AS id
                           '))
                ->groupBy('cartId')->get();
        }else{
            $itemList = PurchaseItem::select(
                DB::raw('cartId,
                           user_id,
                           status,
                           MAX(created_at) AS created_at,
                           MAX(id) AS id
                           '))
                ->where('user_id','=','303')
                ->groupBy('cartId')->get();
        }


//        dd($itemList);

        return view('admin.item.purchase.purchase-package-list')->with([
            'itemList' => $itemList
        ]);
    }

    public function statusUpdate(Request $request,$id)
    {

        $status = PurchaseItem::findOrFail($id);

        $status->status = $request->post('status');
        $status->save();

        return redirect()->back()->with('message', 'You have purchase all items');
    }
}
