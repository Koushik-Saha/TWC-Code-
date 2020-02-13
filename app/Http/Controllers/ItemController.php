<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Item;
use App\Models\ItemLog;
use App\Models\Project;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class ItemController extends Controller
{
    public function projectVendors(Request $request) {
        $this->checkPermission();
        $vendors = Project::findOrFail($request->post('project_id'))->vendors();

        return view('admin.inventory.ajax-vendors')
            ->with('vendors', $vendors);
    }

    public function approved() {
        $this->checkPermission();
        if(Auth::user()->isAdmin() || Auth::user()->isAccountant()) {
            $projects = Project::whereProjectStatus('active')
                ->orderBy('project_name')->get();
        }
        else {
            $projects = Auth::user()->projects()
                ->where('project_status', '=', 'active')
                ->get();
        }

        return view('admin.inventory.lists-approved')
            ->with([
                'projects'  => $projects,
            ]);
    }

    public function showItemsApproved(Request $request) {
        $this->checkPermission();

        if(Auth::user()->isAdmin() || Auth::user()->isAccountant()) {
            $project = Project::findOrFail($request->post('pid'));
            $projects = Project::where('project_status', '=', 'active')
                ->whereKeyNot($request->post('pid'))
                ->get();
        }
        else {
            $project = Auth::user()->projects()->findOrFail($request->post('pid'));
            $projects = Auth::user()->projects()
                ->whereKeyNot($request->post('pid'))
                ->where('project_status', '=', 'active')
                ->get();
        }

        return view('admin.inventory.ajax-lists-approved')
            ->with([
                'project'    => $project,
                'projects'   => $projects,
            ]);
    }

    public function purchase() {

        $this->checkPermission();
        if(Auth::user()->isAdmin() || Auth::user()->isAccountant()) {
            $projects = Project::whereProjectStatus('active')
                ->orderBy('project_name')->get();
        }
        else {
            $projects = Auth::user()->projects()
                ->where('project_status', '=', 'active')
                ->get();
        }

        return view('admin.inventory.lists-purchase')
            ->with([
                'projects'  => $projects,
            ]);
    }

    public function showItemsPurchase(Request $request) {
        $this->checkPermission();

        if(Auth::user()->isAdmin() || Auth::user()->isAccountant()) {
            $project = Project::findOrFail($request->post('pid'));
            $projects = Project::where('project_status', '=', 'active')
                ->whereKeyNot($request->post('pid'))
                ->get();
        }
        else {
            $project = Auth::user()->projects()->findOrFail($request->post('pid'));
            $projects = Auth::user()->projects()
                ->whereKeyNot($request->post('pid'))
                ->where('project_status', '=', 'active')
                ->get();
        }

        return view('admin.inventory.ajax-lists-purchase')
            ->with([
                'project'    => $project,
                'projects'   => $projects,
            ]);
    }


    public function index() {
        $this->checkPermission();
        if(Auth::user()->isAdmin() || Auth::user()->isAccountant()) {
            $projects = Project::whereProjectStatus('active')
                ->orderBy('project_name')->get();
        }
        else {
            $projects = Auth::user()->projects()
                ->where('project_status', '=', 'active')
                ->get();
        }

        return view('admin.inventory.lists')
            ->with([
                'projects'  => $projects,
            ]);
    }

    public function indexAllItem() {
        $this->checkPermission();
        if(Auth::user()->isAdmin() || Auth::user()->isAccountant()) {
//            $projects = Project::whereProjectStatus('active')
//                ->orderBy('project_name')->get();

//            $projects = DB::table('bsoft_projects')->where('project_id', '<=', '1' )->first();

            $item = DB::table('bsoft_items')->select('item_name')->get();
        }
        else {
            $projects = Auth::user()->projects()
                ->where('project_status', '=', 'active')
                ->get();
        }

        return view('admin.inventory.lists-all')
            ->with([
//                'projects'  => $projects,
                'item' => $item
            ]);
    }

    public function show($pid, $id) {
        $project = Project::findOrFail($pid);
        $item = Item::findOrFail($id);

        $itemLogs = $project->itemLogs()
            ->where('il_item_id', '=', $item->item_id)
            ->get();

        $itemTransferLogs = $project->transferredItemLogs()
            ->where('il_item_id', '=', $item->item_id)
            ->get();

        return view('admin.inventory.item-details')
            ->with([
                'item'              => $item,
                'itemLogs'          => $itemLogs,
                'itemTransferLogs'  => $itemTransferLogs,
                'project'           => $project
            ]);
    }

    public function showItemsDetails($id) {
//        $project = Project::findOrFail($pid);
        $item = Item::findOrFail($id);

        $itemLogs = ItemLog::where('il_item_id', '=', $item->item_id)
            ->select(DB::raw('il_item_id,
                                    sum(il_cost) AS il_cost,
                                    sum(il_quantity) AS il_quantity'))
            ->groupBy('il_item_id')
            ->get();

        $itemLogInfo = ItemLog::where('il_item_id', '=', $item->item_id)
            ->join('bsoft_items', 'bsoft_items.item_id', '=','bsoft_item_logs.il_item_id')
            ->join('bsoft_projects', 'bsoft_projects.project_id', '=','bsoft_item_logs.il_project_id')
            ->select('bsoft_items.*', 'bsoft_items.item_name',
                'bsoft_projects.*', 'bsoft_projects.project_name',
                'il_cost', 'il_quantity', 'il_project_id')
            ->get();

        $itemTransferLogs = ItemLog::where('il_item_id', '=', $item->item_id)
            ->select(DB::raw('il_quantity, sum(il_cost) AS il_cost'))
            ->groupBy('il_quantity')
            ->get();

        $project = Project::select('project_name')->get();

        return view('admin.inventory.item-details-all')
            ->with([
                'item'              => $item,
                'itemLogs'          => $itemLogs,
                'itemTransferLogs'  => $itemTransferLogs,
                'project'           => $project,
                'itemLogInfo'      => $itemLogInfo
            ]);
    }

    public function showItems(Request $request) {
        $this->checkPermission();

        if(Auth::user()->isAdmin() || Auth::user()->isAccountant()) {
            $project = Project::findOrFail($request->post('pid'));
            $projects = Project::where('project_status', '=', 'active')
                ->whereKeyNot($request->post('pid'))
                ->orderBy('created_at','DESC')
                ->get();
        }
        else {
            $project = Auth::user()->projects()->findOrFail($request->post('pid'));
            $projects = Auth::user()->projects()
                ->whereKeyNot($request->post('pid'))
                ->where('project_status', '=', 'active')
                ->orderBy('created_at','DESC')
                ->get();
        }

        return view('admin.inventory.ajax-lists')
            ->with([
                'project'    => $project,
                'projects'   => $projects,
            ]);
    }

    public function showAllItems(Request $request) {
        $this->checkPermission();

        if(Auth::user()->isAdmin() || Auth::user()->isAccountant()) {
//            $project = Project::findOrFail($request->post('pid'));
//            $projects = Project::where('project_status', '=', 'active')
//                ->whereKeyNot($request->post('pid'))
//                ->get();

//            $items = Item::select('item_name')->get();

            $item = DB::table('bsoft_items')
//                ->join('bsoft_item_logs', 'bsoft_item_logs.il_item_id', '=','bsoft_items.item_id')
//                ->select('bsoft_item_logs.*', 'bsoft_item_logs.il_quantity',
//                     'bsoft_items.*')

                ->get();
//            $itemLog = DB::table('bsoft_item_logs')->select('il_item_id', 'il_quantity')->get();
        }
        else {
            $project = Auth::user()->projects()->findOrFail($request->post('pid'));
            $projects = Auth::user()->projects()
                ->whereKeyNot($request->post('pid'))
                ->where('project_status', '=', 'active')
                ->get();
        }

        return view('admin.inventory.ajax-all-lists')
            ->with([
//                'project'    => $project,
//                'projects'   => $projects,
//                'items'      => $items,
                'item'      => $item
//                'itemLog' => $itemLog

            ]);
    }

    public function showTransferredItems(Request $request) {
        $project = Project::findOrFail($request->post('pid'));
        return view('admin.inventory.ajax-lists-transferred')
            ->with([
                'project'    => $project
            ]);
    }

    public function create() {
        $this->checkPermission();
        $items = Item::orderBy('item_id','desc')
            ->get();

        $itemLogs = DB::table('bsoft_items')
            ->join('bsoft_item_logs', 'bsoft_items.item_id', '=', 'bsoft_item_logs.il_item_id')
            ->select('bsoft_items.*', 'bsoft_item_logs.il_per_cost')
            ->get();

        return view('admin.inventory.item-create')
            ->with([
                'items' => $items,
                'itemLogs' => $itemLogs
            ]);
    }

    public function saveItem(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'      => ['required', 'string'],
            'unit'      => ['required', 'string'],
            'reusable'  => ['required', 'numeric' , 'max:1'],
            'unit_price'=> ['required', 'numeric'],
        ]);

        if($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }

        $item = new Item();

        $item->item_name = $request->post('name');
        $item->item_unit = $request->post('unit');
        $item->item_reusable = $request->post('reusable');
        $item->item_price = $request->post('unit_price');

        if ($item->save()) {
            addActivity('item', $item->item_id, 'Item Created');
            return redirectBackWithNotification('success', 'Item Created Successfully!');
        }

        return redirectBackWithNotification();
    }

    public function edit($id) {
        $this->checkPermission();

        $item = Item::findOrFail($id);

        $items = Item::where('item_id',$id)
                        ->select('bsoft_items.item_id', 'bsoft_item_logs.il_quantity')
                        ->join('bsoft_item_logs', 'bsoft_items.item_id', '=', 'bsoft_item_logs.il_item_id')
                        ->get();

        return view('admin.inventory.item-edit')
            ->with([
                'item' => $item,
                'items' => $items
            ]);
    }

    public function updateItem(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'name'      => ['required', 'string'],
            'unit'      => ['required', 'string'],
            'reusable'  => ['required', 'numeric' , 'max:1'],
            'unit_price'      => ['required', 'numeric'],
//            'final_unit_price'      => ['required', 'numeric'],
        ]);

        if($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }

        $item = Item::findOrFail($id);

        $item->item_name = $request->post('name');
        $item->item_unit = $request->post('unit');
        $item->item_reusable = $request->post('reusable');
        $item->item_price = $request->post('unit_price');
        $item->item_price_final = $request->post('item_price_final');
        $item->final_price_up = $request->post('final_price_up');

        if ($item->save()) {
            addActivity('item', $item->item_id, 'Item Updated');
            return redirectBackWithNotification('success', 'Item Updated Successfully!');
        }

        return redirectBackWithNotification();
    }


    public function add() {
        $this->checkPermission();
        $items = Item::orderBy('item_id')
            ->get();

        $itemLogs = ItemLog::all();

        if(Auth::user()->isAdmin()) {
            $projects = Project::whereProjectStatus('active')
                ->orderBy('project_name')->get();
        }
        else {
            $projects = Auth::user()->projects()->where('project_status', '=', 'active')->get();
        }

        return view('admin.inventory.add-item')
            ->with([
                'projects'  => $projects,
                'items'     => $items,
                'itemLogs'  => $itemLogs
            ]);
    }

    public function storeItem(Request $request) {
        $this->checkPermission();
        if(Auth::user()->isAdmin()) {
            $project = Project::findOrFail($request->post('project_id'));
        }
        else {
            $project = Auth::user()->projects()->findOrFail($request->post('project_id'));
        }

        $validator = Validator::make($request->all(), [
            'project_id'    => ['required', 'numeric'],
            'vendor_id'     => ['required', 'numeric'],
            'item_id'       => ['required', 'numeric'],
            'quantity'      => ['required', 'numeric'],
//            'payable'       => ['required', 'numeric'],
            'unit_payable'       => ['required', 'numeric']
        ]);

        if($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }

        $item = Item::findOrFail($request->post('item_id'));
        $vendor = Role::whereRoleSlug('supplier')
            ->first()
            ->users()
            ->findOrFail($request->post('vendor_id'));

        $log = new ItemLog();

        $log->il_item_id = $item->item_id;
        $log->il_project_id = $project->project_id;
        $log->il_vendor_id = $vendor->id;
        $log->il_quantity = $request->post('quantity');
        $log->il_cost = $request->post('il_cost');
        $log->il_per_cost  = $request->post('unit_payable');
        $log->il_note = $request->post('note');

        if(!$log->save()) {
            return redirectBackWithNotification('error', 'Something Went Wrong!');
        }
        addActivity('item_log', $log->il_id, 'New Item Stored');

        return redirectUrlWithNotification(route('items.index'), 'success', 'Item Successfully Stored!');
    }

    public function transferItem(Request $request) {
        $validator = Validator::make($request->all(), [
            'project_to'    => ['required', 'numeric'],
            'project_from'  => ['required', 'numeric'],
            'quantity'      => ['required', 'numeric']
        ]);

        if($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }

        $project = Project::findOrFail($request->post('project_to'));
        if($project->project_status === 'canceled' || $project->project_status === 'completed') {
            return redirectBackWithNotification('error', 'You Can\'t Transfer To That Project!');
        }

        $oldLogs = Project::findOrFail($request->post('project_from'))
            ->itemLogs()->where('il_item_id', '=', $request->post('item_id'))->get();


        $cost = ($oldLogs->sum('il_cost') / $oldLogs->sum('il_quantity')) * $request->post('quantity');

        $log = new ItemLog();

        $log->il_item_id = $request->post('item_id');
        $log->il_project_id = $request->post('project_to');
        $log->il_project_from = $request->post('project_from');
        $log->il_quantity = $request->post('quantity');
        $log->il_cost = $cost;
        $log->il_paid_amount = 0;

        if(!$log->save()) {
            return redirectBackWithNotification('error', 'Something Went Wrong!');
        }
        addActivity('item_log', $log->il_id, 'Item Transferred!');

        $payment = createNewPayment([
            'type' => 'debit',
            'to_user' => null,
            'from_user' => null,
            'to_bank_account' => null,
            'from_bank_account' => null,
            'amount' => $log->il_cost - ($log->il_cost * 2),
            'project' => $request->post('project_from'),
            'purpose' => 'item_transfer',
            'by' => 'cash',
            'date' => Carbon::now()->toDateString(),
            'image' => null,
            'note'  => $request->post('note')
        ], 'Item Transferred!');
        if(!$payment) {
            return redirectBackWithNotification();
        }

        $payment1 = createNewPayment([
            'type' => 'debit',
            'to_user' => null,
            'from_user' => null,
            'to_bank_account' => null,
            'from_bank_account' => null,
            'amount' => $log->il_cost,
            'project' => $request->post('project_to'),
            'purpose' => 'item_receive',
            'by' => 'cash',
            'date' => Carbon::now()->toDateString(),
            'image' => null,
            'note'  => $request->post('note')
        ], 'Item Received');
        if(!$payment1) {
            return redirectBackWithNotification();
        }
        $invoice = $this->createInvoice($log);

        //return redirectBackWithNotification('success', 'Item Transferred!');
        return redirectUrlWithNotification(route('items.invoice', ['id' => $invoice->invoice_item_log]), 'success', 'Item Transferred!');
    }

    public function showInvoice($id) {
        $invoice = Invoice::where('invoice_item_log', '=', $id)
            ->first();

        if(!$invoice) {
            $log = ItemLog::findOrFail($id);
            $invoice = $this->createInvoice($log);
        }

        return view('admin.inventory.invoice')
            ->with([
                'invoice'   => $invoice
            ]);
    }

    /**
     * @return bool|\Illuminate\Http\RedirectResponse
     */
    protected function checkPermission() {
        $role = Auth::user()->role->role_slug;

        if($role == 'administrator' || $role == 'manager' || $role == 'accountant') {
            return true;
        }
        return redirectBackWithNotification('error', 'Sorry! You Are Not Authorised!.');
    }

    protected function createInvoice(ItemLog $log) {
        $invoice = new Invoice();

        $invoice->invoice_item_log = $log->il_id;
        $invoice->invoice_address_from = $log->projectTransferredFrom->project_location;
        $invoice->invoice_address_to = $log->project->project_location;

        $invoice->save();
        addActivity('invoice', $invoice->invoice_id, 'New Invoice Generated!');

        return $invoice;
    }


    public  function requestItem() {
        $this->checkPermission();

        if(Auth::user()->isAdmin()) {
            $projects = Project::whereProjectStatus('active')
                ->orderBy('project_name')->get();
        }
        else {
            $projects = Auth::user()->projects()->where('project_status', '=', 'active')->get();
        }

        $items = Item::orderBy('item_id')
            ->get();

        $itemLogs = ItemLog::all();

        return view('admin.inventory.requestItem.request-item')->with([
            'items'     => $items,
            'itemLogs'  => $itemLogs,
            'projects'  => $projects,
        ]);
    }
}
