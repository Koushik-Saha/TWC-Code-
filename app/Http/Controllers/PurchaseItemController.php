<?php

namespace App\Http\Controllers;

use App\Models\InventoryManagement;
use App\Models\Project;
use App\Models\PurchaseItem;
use App\Models\RequestItem;
use App\Models\Role;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use mysql_xdevapi\Table;

class PurchaseItemController extends Controller
{
    public function processPurchaseApprove(Request $request)
    {

        $request->validate([
            'addmore.*.item_id' => 'required',
            'addmore.*.price' => 'required',
            'addmore.*.quantity' => 'required',
            'addmore.*.amount' => 'required',
        ]);


        foreach ($request->addmore as $key => $value) {
            PurchaseItem::create($value);
        }


        return redirect()->route('request-list')->with('message','Approve Item has been send successfully');
    }


    public function purchaseItem(Request $request,$cartId){

        $cartValue = $cartId;

        $vendor = User::where('role_id','16')->get();

        $itemList = PurchaseItem::where('cartId','=',$cartValue)
            ->orderBy('created_at', 'desc')
            ->get();


        return view('admin.item.purchase.purchase-item')->with([
            'itemList' => $itemList,
            'vendor'  => $vendor
        ]);
    }

    public function purchasePackageList(){

        //Get Current User
        $currentUser = Auth::user()->id;

        //Get CartID using groupBy
        if(Auth::user()->isAdmin() || Auth::user()->isAccountant()) {
            $itemList = PurchaseItem::select(
                DB::raw('cartId,
                           user_id,
                           status,
                           MAX(created_at) AS created_at,
                           MAX(id) AS id
                           '))
                ->groupBy('cartId')
                ->orderBy('created_at', 'desc')
                ->get();
        }else if(Auth::user()->isManager()){
            $itemList = PurchaseItem::select(
                DB::raw('cartId,
                           user_id,
                           status,
                           MAX(created_at) AS created_at,
                           MAX(id) AS id
                           '))
                ->where('user_id','=',$currentUser)
                ->orderBy('created_at', 'desc')
                ->groupBy('cartId')
                ->get();
        }

        return view('admin.item.purchase.purchase-package-list')->with([
            'itemList' => $itemList
        ]);
    }

    public function statusUpdate(Request $request, PurchaseItem $purchase, $cartId)
    {
//        dd($request->all());
        $purchaseItem = $cartId;

        // Set ALL records to a status of 0
        DB::table('purchase_items')->where('cartId','=', $purchaseItem)
            ->where('status',0)
            ->update([
                'status' => 1,
            ]);

        return redirect()
            ->route('purchase-package-list' )
            ->with('message', 'You have purchase your items');
    }


    public function edit($id) {

        $purchase = PurchaseItem::findOrFail($id);

        $vendor = User::where('role_id','16')->get();

        return view('admin.item.purchase.edit-purchase-item')
            ->with([
                'purchase' => $purchase,
                'vendor'   => $vendor,
            ]);
    }

    public function updatePurchaseInventory(Request $request, $id) {

        $validator =Validator::make($request->all(), [
            'item_id' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'amount' => 'required',

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $purchaseInventory = PurchaseItem::findOrFail($id);

        $purchaseInventory->item_id = $request->post('item_id');
        $purchaseInventory->price = $request->post('price');
        $purchaseInventory->vat = $request->post('vat');
        $purchaseInventory->quantity = $request->post('quantity');
        $purchaseInventory->amount = $request->post('amount');
        $purchaseInventory->project_id = $request->post('project_id');
        $purchaseInventory->request_code = $request->post('request_code');
        $purchaseInventory->payment_amount = $request->post('payment_amount');
        $purchaseInventory->vendor_id = $request->post('vendor_id');
        $purchaseInventory->user_id = $request->post('user_id');
        $purchaseInventory->cartId = $request->post('cartId');


        $purchaseInventory->save();

        return redirect()
            ->route('purchase-item', ['id' => $purchaseInventory->cartId] )
            ->with('message','Vendor Updated Successfully');

    }


    public function purchaseHistory()
    {
        $itemList = PurchaseItem::select(
            DB::raw('cartId,
                           user_id,
                           status,
                           project_id,
                           MAX(created_at) AS created_at,
                           MAX(id) AS id
                           '))
            ->groupBy('cartId')
            ->orderBy('created_at', 'desc')
            ->where('status','=','1')
            ->get();

        return view('admin.item.purchase.purchase-history')->with([
            'itemList' => $itemList
        ]);
    }


    public function notPurchaseHistory()
    {
        $itemList = PurchaseItem::select(
            DB::raw('cartId,
                           user_id,
                           status,
                           project_id,
                           MAX(created_at) AS created_at,
                           MAX(id) AS id
                           '))
            ->groupBy('cartId')
            ->orderBy('created_at', 'desc')
            ->where('status','=','0')
            ->get();

        return view('admin.item.purchase.not-purchase-history')->with([
            'itemList' => $itemList
        ]);
    }

    public function purchaseItemDetails(RequestItem $requestItem, $cartId){

        $cartValue = $cartId;

        $vendor = User::where('role_id','16')->get();

        $itemList = PurchaseItem::where('cartId','=',$cartValue)
            ->orderBy('created_at', 'desc')
            ->get();


        return view('admin.item.purchase.purchase-item-details')->with([
            'itemList' => $itemList,
            'vendor'  => $vendor
        ]);
    }


    public function showItemByProject()
    {
        if(Auth::user()->isAdmin() || Auth::user()->isAccountant()) {
            $projects  = Project::where('project_status', '=', 'active')->get();
        }
        else {
            $projects = Auth::user()->projects()
                ->where('project_status', '=', 'active')
                ->orderBy('created_at', 'DESC')
                ->get();
        }

        return view('admin.item.item-lists-by-projects')->with([
            'projects' => $projects
        ]);
    }

}
