<?php

namespace App\Http\Controllers;

use App\Models\InventoryManagement;
use App\Models\PurchaseItem;
use App\Models\RequestItem;
use App\Models\Role;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Table;

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
            PurchaseItem::create($value);
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

        $role = DB::table('bsoft_users')->select('id')->where('role_id','=', '12')->get();

//        dd($role);

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
                ->whereIn('user_id','=', '303')
                ->groupBy('cartId')->get();
        }


//        dd($itemList);

        return view('admin.item.purchase.purchase-package-list')->with([
            'itemList' => $itemList
        ]);
    }

    public function statusUpdate(Request $request, PurchaseItem $purchase, $cartId)
    {

        $purchaseItem = $cartId;

        // Set ALL records to a status of 0
        DB::table('purchase_items')->where('cartId','=', $purchaseItem)
            ->where('status',0)
            ->update(['status' => 1]);

        // Set the passed record to a status of what ever is passed in the Request
        $purchase->status = $request->post('status');
        $purchase->save();

        return redirect()->back()->with('message', 'You have purchase your items');
    }
}
