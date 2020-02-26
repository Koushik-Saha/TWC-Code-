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

        dd($request->all());

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
                ->groupBy('cartId')->get();
        }

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
            ->update(['status' => 1, 'vendor_id' => $request->post('vendor_id')]);

        return redirect()
            ->route('purchase-package-list' )
            ->with('message', 'You have purchase your items');
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
            ->get();

        return view('admin.item.purchase.purchase-history')->with([
            'itemList' => $itemList
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
