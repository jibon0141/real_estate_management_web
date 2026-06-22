<?php

use App\Http\Controllers\Backend\Account\MainAccountController;
use App\Http\Controllers\Backend\ChartOfAccount\ChartOfAccountController;
use App\Http\Controllers\Backend\CommissionSetting\CommissionSettingController;
use App\Http\Controllers\Backend\Designation\DesignationController;
use App\Http\Controllers\Backend\SetCommission\CommissionController;
use App\Http\Controllers\Backend\CompanySetting\CompanySettingController;
use App\Http\Controllers\Backend\CreditVoucher\CreditVoucherController;
use App\Http\Controllers\Backend\DebitVoucher\DebitVoucherController;
use App\Http\Controllers\Backend\GlAccount\GlAccountController;
use App\Http\Controllers\Backend\Party\PartyController;
use App\Http\Controllers\Backend\Project\ProjectController;
use App\Http\Controllers\Backend\Package\PackageController;
use App\Http\Controllers\Backend\Sell\SellController;
use App\Http\Controllers\Backend\User\UserController;
use Illuminate\Support\Facades\Route;


Route::middleware(['super_admin:admin'])->group(function () {

Route::group(['namespace' => 'Admin'], function () {

});

});



Route::middleware(['super_admin:admin','block_purchase_admin'])->group(function () {

    // Company Route
    Route::group(['namespace' => 'CompanySetting'], function () {
        Route::get('/main-company', [CompanySettingController::class, 'index'])->name('main.company.index');
        Route::put('/main-company/update/{id}', [CompanySettingController::class, 'update'])->name('main.company.update');
    });

  // Account Route
    Route::group(['namespace'=>'Account'],function(){
        Route::get('/account',[MainAccountController::class,'index'])->name('admin.account.index');
        Route::match(['get','post'],'/account/create',[MainAccountController::class,'create'])->name('admin.account.create');
        Route::get('/account/edit/{id}',[MainAccountController::class,'edit'])->name('admin.account.edit');
        Route::put('/account/update/{id}',[MainAccountController::class,'update'])->name('admin.account.update');
        Route::delete('/account/delete/{id}',[MainAccountController::class,'destroy'])->name('admin.account.delete');
    });


    // Gl account
    Route::group(['namespace'=>'GlAccount'],function(){
        Route::get('/gl-account',[GlAccountController::class,'index'])->name('admin.gl-account.index');
        Route::get('/gl-account/edit/{id}',[GlAccountController::class,'edit'])->name('admin.gl-account.edit');
        Route::put('/gl-account/update/{id}',[GlAccountController::class,'update'])->name('admin.gl-account.update');
    });

    // Chart of Account
    Route::group(['namespace'=>'ChartOfAccount'],function(){
        Route::get('/chart-of-account',[ChartOfAccountController::class,'index'])->name('admin.chart-of-account.index');
        Route::match(['get','post'],'/chart-of-account/create',[ChartOfAccountController::class,'create'])->name('admin.chart-of-account.create');
        Route::get('/chart-of-account/edit/{id}',[ChartOfAccountController::class,'edit'])->name('admin.chart-of-account.edit');
        Route::put('/chart-of-account/update/{id}',[ChartOfAccountController::class,'update'])->name('admin.chart-of-account.update');
        Route::delete('/chart-of-account/delete/{id}',[ChartOfAccountController::class,'destroy'])->name('admin.chart-of-account.delete');
    });


    // Party Route
    Route::group(['namespace'=>'Party'],function(){
        Route::get('/party',[PartyController::class,'index'])->name('admin.party.index');
        Route::match(['get','post'],'/party/create',[PartyController::class,'create'])->name('admin.party.create');
        Route::get('/party/edit/{id}',[PartyController::class,'edit'])->name('admin.party.edit');
        Route::put('/party/update/{id}',[PartyController::class,'update'])->name('admin.party.update');
        Route::delete('/party/delete/{id}',[PartyController::class,'destroy'])->name('admin.party.delete');
    });

    // Project Route
    Route::group(['namespace'=>'Project'],function(){
        Route::get('/project',[ProjectController::class,'index'])->name('admin.project.index');
        Route::match(['get','post'],'/project/create',[ProjectController::class,'create'])->name('admin.project.create');
        Route::get('/project/edit/{id}',[ProjectController::class,'edit'])->name('admin.project.edit');
        Route::put('/project/update/{id}',[ProjectController::class,'update'])->name('admin.project.update');
        Route::delete('/project/delete/{id}',[ProjectController::class,'destroy'])->name('admin.project.delete');
    });

    // Package Route
    Route::group(['namespace'=>'Package'],function(){
        Route::get('/package',[PackageController::class,'index'])->name('admin.package.index');
        Route::get('/package/show/{id}',[PackageController::class,'show'])->name('admin.package.show');
        Route::match(['get','post'],'/package/create',[PackageController::class,'create'])->name('admin.package.create');
        Route::get('/package/edit/{id}',[PackageController::class,'edit'])->name('admin.package.edit');
        Route::put('/package/update/{id}',[PackageController::class,'update'])->name('admin.package.update');
        Route::delete('/package/delete/{id}',[PackageController::class,'destroy'])->name('admin.package.delete');
    });

    // User Route
    Route::group(['namespace'=>'User'],function(){
        Route::get('/user',[UserController::class,'index'])->name('admin.user.index');
        Route::match(['get','post'],'/user/create',[UserController::class,'create'])->name('admin.user.create');
        Route::get('/user/edit/{id}',[UserController::class,'edit'])->name('admin.user.edit');
        Route::put('/user/update/{id}',[UserController::class,'update'])->name('admin.user.update');
        Route::delete('/user/delete/{id}',[UserController::class,'destroy'])->name('admin.user.delete');
    });


    // Set Commission Route
    Route::group(['namespace'=>'SetCommission'],function(){
        Route::get('/set-commission',[CommissionController::class,'index'])->name('admin.set-commission.index');
        Route::post('/set-commission/save',[CommissionController::class,'save'])->name('admin.set-commission.save');
        Route::delete('/set-commission/delete/{id}',[CommissionController::class,'destroy'])->name('admin.set-commission.delete');
    });

    // Sell Route
    Route::group(['namespace' => 'Sell'], function () {
        Route::get('/sell', [SellController::class, 'index'])->name('admin.sell.index');
        Route::get('/sell/options/{id}', [SellController::class, 'options'])->name('admin.sell.options');
        Route::get('/sell/cash/{id}', [SellController::class, 'cash'])->name('admin.sell.cash');
        Route::post('/sell/cash/store', [SellController::class, 'storeCash'])->name('admin.sell.cash.store');
        Route::get('/sell/installment/{id}', [SellController::class, 'installment'])->name('admin.sell.installment');
        Route::post('/sell/installment/store', [SellController::class, 'storeInstallment'])->name('admin.sell.installment.store');
        Route::get('/sell/show/{id}', [SellController::class, 'show'])->name('admin.sell.show');
        Route::get('/sell/all', [SellController::class, 'all'])->name('admin.sell.all');
    });

    // Debit Voucher Route
    Route::group(['namespace'=>'DebitVoucher'],function(){
        Route::get('/debit-voucher',[DebitVoucherController::class,'index'])->name('admin.debit-voucher.index');
        Route::match(['get','post'],'/debit-voucher/create',[DebitVoucherController::class,'create'])->name('admin.debit-voucher.create');
        Route::get('/debit-voucher/show/{id}',[DebitVoucherController::class,'show'])->name('admin.debit-voucher.show');
        Route::put('/debit-voucher/update/{id}',[DebitVoucherController::class,'update'])->name('admin.debit-voucher.update');
        Route::delete('/debit-voucher/delete/{id}',[DebitVoucherController::class,'destroy'])->name('admin.debit-voucher.delete');
        Route::get('/debit-voucher/print/{id}', [DebitVoucherController::class, 'print'])->name('admin.debit-voucher.print');

        // AJAX routes
        Route::get('/debit-voucher/get-parties', [DebitVoucherController::class, 'getParties'])->name('admin.debit-voucher.get-parties');
        Route::get('/debit-voucher/get-bank-accounts', [DebitVoucherController::class, 'getBankAccounts'])->name('admin.debit-voucher.get-bank-accounts');
        Route::get('/debit-voucher/get-expense-coa', [DebitVoucherController::class, 'getExpenseCoa'])->name('admin.debit-voucher.get-expense-coa');
    });

    // Credit Voucher
    Route::group(['namespace' => 'CreditVoucher'], function () {
        Route::get('/credit-voucher', [CreditVoucherController::class, 'index'])->name('admin.credit-voucher.index');
        Route::match(['get', 'post'], '/credit-voucher/create', [CreditVoucherController::class, 'create'])->name('admin.credit-voucher.create');
        Route::get('/credit-voucher/show/{id}', [CreditVoucherController::class, 'show'])->name('admin.credit-voucher.show');
        Route::put('/credit-voucher/update/{id}', [CreditVoucherController::class, 'update'])->name('admin.credit-voucher.update');
        Route::get('/credit-voucher/print/{id}', [CreditVoucherController::class, 'print'])->name('admin.credit-voucher.print');

        // AJAX routes
        Route::get('/credit-voucher/get-parties', [CreditVoucherController::class, 'getParties'])->name('admin.credit-voucher.get-parties');
        Route::get('/credit-voucher/get-bank-accounts', [CreditVoucherController::class, 'getBankAccounts'])->name('admin.credit-voucher.get-bank-accounts');
        Route::get('/credit-voucher/get-income-coa', [CreditVoucherController::class, 'getIncomeCoa'])->name('admin.credit-voucher.get-income-coa');
    });

    // Designation Route
    Route::group(['namespace' => 'Designation'], function () {
        Route::get('/designation',[DesignationController::class,'index'])->name('admin.designation.index');
    });


});






