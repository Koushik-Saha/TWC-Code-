<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


use Carbon\Carbon;

Route::get('test', 'AttendanceController@showReport');

Route::get('/', function () {
    return view('welcome');
})->middleware('auth')->name('index');

Auth::routes(['register' => false]);

Route::get('install', 'InstallationController@install')->name('install');
Route::post('install', 'InstallationController@saveInstallation');

// Authenticated Routes
Route::middleware('auth')->group(function() {
    // Project Routes
    Route::prefix('project')->group(function() {
        Route::get('add', 'ProjectController@add')->name('project.add');
        Route::post('add', 'ProjectController@store');
        //Route::get('assign', 'ProjectController@assign')->name('project.assign');
        Route::post('assign/{id}', 'ProjectController@assignToProject')->name('project.assign');
        Route::get('show/{id}', 'ProjectController@show')->name('project.show');
        Route::get('edit/{id}', 'ProjectController@edit')->name('project.edit');
        Route::patch('edit/{id}', 'ProjectController@update');
        Route::patch('change-status', 'ProjectController@changeStatus')->name('project.change_status');
        //Route::delete('delete/{id}', 'ProjectController@delete')->name('project.delete');

        Route::get('{status?}', 'ProjectController@showProjects')->name('project.all');
    });

    //Client Routes
    Route::prefix('client')->group(function() {
        Route::get('/', 'ClientController@index')->name('client.all');
        Route::get('show/{id}', 'ClientController@show')->name('client.show');
        Route::get('edit/{id}', 'ClientController@edit')->name('client.edit');
        Route::patch('edit/{id}', 'ClientController@update');
        //Route::delete('delete/{id}', 'ClientController@delete')->name('client.delete');
    });

    //vendor Routes
    Route::prefix('vendors')->group(function() {
        Route::get('/', 'VendorController@index')->name('vendor.all');
        Route::get('add', 'VendorController@add')->name('vendor.add');
        Route::post('add', 'VendorController@store');
        Route::post('pay', 'VendorController@pay')->name('vendor.pay');
        Route::get('show/{id}', 'VendorController@show')->name('vendor.show');
        Route::get('edit/{id}', 'VendorController@edit')->name('vendor.edit');
        Route::patch('edit/{id}', 'VendorController@update');

        Route::post('get-bank-accounts', 'VendorController@bankAccounts')->name('vendor.banks');
        //Route::delete('delete/{id}', 'VendorController@delete')->name('vendor.delete');
    });

    //administrators Routes
    Route::prefix('administrators')->group(function() {
        Route::get('/', 'AdminsController@index')->name('administrators.all');
        Route::get('add', 'AdminsController@add')->name('administrators.add');
        Route::post('add', 'AdminsController@store');
        Route::get('show/{id}', 'AdminsController@show')->name('administrators.show');
        Route::get('edit/{id}', 'AdminsController@edit')->name('administrators.edit');
        Route::patch('edit/{id}', 'AdminsController@update');
        //Route::delete('delete/{id}', 'VendorController@delete')->name('vendor.delete');
    });

    //Man Power Routes
    Route::prefix('working-shift')->group(function() {
        Route::get('/', 'WorkingShiftController@index')->name('shift.all');
        //Route::get('add', 'WorkingShiftController@add');
        Route::post('add', 'WorkingShiftController@store')->name('shift.add');
        Route::post('show', 'WorkingShiftController@show')->name('shift.show');
        //Route::get('edit/{id}', 'WorkingShiftController@edit')->name('shift.edit');
        //Route::patch('edit/{id}', 'WorkingShiftController@update');
        Route::delete('delete', 'WorkingShiftController@delete')->name('shift.delete');
    });

    //Man Power Routes
    Route::prefix('man-power')->group(function() {
        Route::get('/', 'ManPowerController@index')->name('man_power.all');
        Route::get('/monthly-index', 'ManPowerController@monthlyIndex')->name('man_power.monthly.all');
        Route::get('/staff-attendance', 'ManPowerController@staffAttendance')->name('man_power.staff.attendance.all');
        Route::get('add', 'ManPowerController@add')->name('man_power.add');
        Route::post('add', 'ManPowerController@store');
        Route::get('show/{project}/{id}', 'ManPowerController@show')->name('man_power.show');
        Route::get('edit/{id}', 'ManPowerController@edit')->name('man_power.edit');
        Route::patch('edit/{id}', 'ManPowerController@update');
        Route::post('pay', 'ManPowerController@pay')->name('man_power.pay');

        Route::get('designation', 'ManPowerController@designations')->name('man_power.designation');
        Route::post('add-designation', 'ManPowerController@addDesignation')->name('man_power.designation.add');

        Route::delete('delete-designation', 'ManPowerController@deleteDesignation')->name('man_power.designation.delete');

        Route::post('search-staff', 'ManPowerController@searchStaff')->name('man_power.search_staff');

//        Route::get('salary-report', 'ManPowerController@salaryReport')->name('users.update.status');
        Route::post('salary-report', 'ManPowerController@salaryReport')->name('man_power.salary_report');
        Route::get('salary-report', 'ManPowerController@changeLaborStatus')->name('man_power.salary_report-s');


        Route::prefix('attendance')->group(function () {
            Route::post('search', 'AttendanceController@searchAttendance')->name('man_power.search_attendance');

            Route::post('store', 'AttendanceController@storeAttendance')->name('man_power.store_attendance');

            Route::get('report', 'AttendanceController@report')->name('man_power.attendance_report');
            Route::post('report', 'AttendanceController@showReport')->name('man_power.attendance_report');

            Route::get('edit/{id}', 'AttendanceController@edit')->name('man_power.attendance.edit');
            Route::patch('edit/{id}', 'AttendanceController@update');

            Route::delete('delete', 'AttendanceController@delete')->name('man_power.attendance.delete');
        });


        //Route::delete('delete/{id}', 'VendorController@delete')->name('vendor.delete');
    });

    // Items Route
    Route::prefix('items')->group(function () {
        Route::get('/', 'ItemController@index')->name('items.index');
        Route::post('/', 'ItemController@showItems');
        Route::get('/showAllItems', 'ItemController@indexAllItem');
        Route::post('/showAllItems', 'ItemController@showAllItems')->name('items.all-lists');
        Route::post('/transferred', 'ItemController@showTransferredItems')->name('items.transferred');
        Route::get('/create', 'ItemController@create')->name('items.create');
        Route::post('/create', 'ItemController@saveItem');
        Route::get('/edit/{id}', 'ItemController@edit')->name('items.edit');
        Route::patch('/edit/{id}', 'ItemController@updateItem');
        Route::get('/add', 'ItemController@add')->name('items.add');
        Route::post('/add', 'ItemController@storeItem');
        Route::get('show/{pid}/{id}', 'ItemController@show')->name('items.show');
        Route::get('show/{id}', 'ItemController@showItemsDetails')->name('items.showItemsDetails');

        // Purchase Requisition
        Route::get('/purchase', 'ItemController@purchase')->name('items.purchase');
        Route::post('/purchase', 'ItemController@showItemsPurchase');
        Route::get('/approved', 'ItemController@approved')->name('items.approved');
        Route::post('/approved', 'ItemController@showItemsApproved');

        //Request Item
        Route::get('/request-item', 'ItemController@requestItem')->name('items.request-item');



        Route::patch('/transfer', 'ItemController@transferItem')->name('items.transfer');
        Route::post('project-vendors', 'ItemController@projectVendors')->name('items.project_vendors');

        Route::get('invoice/{id}', 'ItemController@showInvoice')->name('items.invoice');
    });

    // Accounting Route
    Route::prefix('accounts')->group(function () {
        Route::get('/', 'BankController@index')->name('bank.index');
        Route::get('/bank-details/{id}', 'BankController@bankDetails')->name('bank.show');
        Route::post('add-account', 'BankController@storeAccount')->name('bank.add');
        Route::post('recharge-from-customer', 'BankController@rechargeFromCustomer')->name('bank.recharge');

        Route::post('transfer-to-employee', 'BankController@transferToEmployee')->name('bank.transfer.to_employee');
        Route::post('transfer-to-office', 'BankController@transferToOffice')->name('bank.transfer.to_office');

        Route::post('withdraw-from-bank', 'BankController@withdrawFromBank')->name('bank.withdraw');
        Route::post('deposit-to-bank', 'BankController@depositToBank')->name('bank.deposit');

        Route::get('income', 'BankController@income')->name('bank.income');
        Route::get('expense', 'BankController@expense')->name('bank.expense');
        Route::get('income-report', 'BankController@incomeReport')->name('bank.income-report');

        Route::post('get-bank-users', 'BankController@getUsers')->name('bank.users.search');
        Route::post('get-bank-projects', 'BankController@getManagers')->name('bank.project_manager.search');
        Route::post('get-bank-client-projects', 'BankController@getClientProjects')->name('bank.client.project.search');

        Route::get('transfer-to-employee', 'BankController@transferToEmployeeTable')->name('bank.transfer_to_employee');
        Route::get('refund-to-employee', 'BankController@refundFromEmployeeTable')->name('bank.refund_to_employee');
    });

    // Loans Route
    Route::prefix('loans')->group(function () {
        Route::get('/', 'LoanController@index')->name('loan.index');
        Route::get('/new', 'LoanController@newLoan')->name('loan.new');
        Route::post('/new', 'LoanController@storeLoan');
        Route::get('/pay', 'LoanController@payLoanView')->name('loan.pay');
        Route::post('/pay', 'LoanController@payLoan');
        Route::get('/show/{id}', 'LoanController@show')->name('loan.show');

        Route::get('/pay/banks', 'LoanController@bankAccounts')->name('loan.banks');
    });

    Route::get('options', 'OptionController@index')->name('options');
    Route::post('save-option', 'OptionController@save')->name('option.save');

    Route::get('profile', 'ProfileController@index')->name('profile');
    Route::patch('profile/update', 'ProfileController@update')->name('profile.update');
    Route::patch('profile/change-password', 'ProfileController@changePassword')->name('profile.password');

    Route::prefix('bsoft-api')->namespace('Axios')->group(function () {
        Route::get('income/{type}/{id?}', 'BankController@income');
        Route::get('expense/{type}/{id?}', 'BankController@expense');
        Route::get('cash-transactions/{type}/{id?}', 'BankController@cashTransactions');

        Route::get('vendors/{project_id}', 'VendorController@index');
    });


    // PDF Routes
    Route::prefix('get-pdf')->namespace('PDF')->group(function () {
        Route::get('income/{type}/{id?}', 'BankController@income');
    });


    //Settings Route
    Route::get('settings','SettingsController@showSettingsForm')->name('settings-layout');
    Route::post('settings','SettingsController@processSettingsForm');


    //mother-category route
    Route::get('/mother-category/delete/{id}', 'MotherCategoryController@del');
    Route::resource('/mother-category', 'MotherCategoryController');
    Route::get('/mother/{id}', 'MotherCategoryController@edit')->name('category');
    Route::post('/mother/{id}', 'MotherCategoryController@updateMotherCategory')->name('category');


    //category route
    Route::get('/category/delete/{id}', 'CategoryController@del');
    Route::resource('/category', 'CategoryController');
    Route::get('/categories/{id}', 'CategoryController@edit')->name('categories');
    Route::post('/categories/{id}', 'CategoryController@updateCategory')->name('categories');

    //sub category route
    Route::get('sub-category/category/{id}', 'SubCategoryController@selectCategoryAjax');
    Route::get('/sub-category/delete/{id}', 'SubCategoryController@del');
    Route::resource('/sub-category', 'SubCategoryController');
    Route::get('/sub-categories/{id}', 'SubCategoryController@edit')->name('sub-category');
    Route::post('/sub-categories/{id}', 'SubCategoryController@updateSubCategory')->name('sub-category');
    Route::get('sub-categories/category/{id}', 'SubCategoryController@selectCategoryAjax');

    //Manufacturer Category
    Route::get('/manufacturer/delete/{id}', 'ManufactureController@del');
    Route::resource('/manufacturer', 'ManufactureController');
    Route::get('/brands/{id}', 'ManufactureController@edit')->name('brands');
    Route::post('/brands/{id}', 'ManufactureController@updateManufacturer')->name('brands');


    //New Inventory
    Route::prefix('inventory')->group(function () {
        //Create Item
        Route::get('/', 'InventoryManagementController@showInventory')->name('create-inventory');
        Route::post('/', 'InventoryManagementController@processInventory');
        Route::get('items/{id}', 'InventoryManagementController@selectCategoryAjax');
        Route::get('items-sub/{id}', 'InventoryManagementController@selectSubCategoryAjax');

        //All List of Item
        Route::get('show-all-item', 'InventoryManagementController@showAllInventory')->name('inventory.all-lists');

        //Edit/Delete/Update Item
        Route::get('edit/{id}', 'InventoryManagementController@edit')->name('edit-inventory');
        Route::post('edit/{id}', 'InventoryManagementController@updateInventory')->name('edit-inventory');
        Route::get('edit/inventory/items/{id}', 'InventoryManagementController@selectCategoryAjax');
        Route::get('edit/inventory/items-sub/{id}', 'InventoryManagementController@selectSubCategoryAjax');
        Route::get('items-del/delete/{id}', 'InventoryManagementController@delete');


        //Request
        Route::get('request', 'RequestItemController@showRequest')->name('request-inventory');
        Route::post('request', 'RequestItemController@processRequest')->name('request-inventory');
        Route::post('request-cart', 'RequestItemController@processCartRequest')->name('request-cart-inventory');

        //Ajax For select item
        Route::get('inventory/items/{id}', 'InventoryManagementController@selectCategoryAjax');
        Route::get('inventory/items-sub/{id}', 'InventoryManagementController@selectSubCategoryAjax');
        Route::get('inventory/item-sub-category/{id}', 'InventoryManagementController@selectItemSubCategoryAjax');
        Route::get('inventory/item-category/{id}', 'InventoryManagementController@selectItemCategoryAjax');
        Route::get('inventory/item-mother-category/{id}', 'InventoryManagementController@selectItemMotherCategoryAjax');


        //Request Item List
        Route::get('request-list', 'RequestItemController@showRequestList')->name('request-list');
        Route::get('request-item-list/{cartId}', 'RequestItemController@showRequestItemList')->name('request-item-list');
        Route::get('item-list-del/delete/{id}', 'RequestItemController@delete');

        //Cart Delete
        Route::post('inventory/cart/qty/delete','RequestItemController@delete');

        //Edit and Update Requested Item
        Route::get('request/edit/{id}', 'RequestItemController@edit')->name('edit-request-inventory');
        Route::post('request/edit/{id}', 'RequestItemController@updateInventory')->name('edit-request-inventory');
        Route::get('request-item-list/request/delete/{id}', 'RequestItemController@deleteRequest')->name('delete-request-inventory');


        //Purchase Item
        Route::post('purchase-approve', 'PurchaseItemController@processPurchaseApprove')->name('approve-item');
        Route::get('purchase-item/{cartId}', 'PurchaseItemController@purchaseItem')->name('purchase-item');
        Route::get('purchase-package-list', 'PurchaseItemController@purchasePackageList')->name('purchase-package-list');
        Route::post('purchase-status', 'PurchaseItemController@statusUpdate')->name('change-status');


        //Item Show
        Route::get('show/{id}', 'ItemController@showItemsDetails')->name('items.showItemsDetails');


    });


});
