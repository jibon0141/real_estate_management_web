<?php

namespace App\Http\Controllers\Backend\Sell;

use App\Http\Controllers\Controller;
use App\Models\CompanyCashFlow;
use App\Models\CompanySetting;
use App\Models\Package;
use App\Models\Project;
use App\Models\Sell;
use App\Models\SellInfo;
use App\Models\ShareInStock;
use App\Models\User;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class SellController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::where('status', 1)->get();

        $packages = Package::with('project')->where('status', 1);

        if ($request->filled('project_id')) {
            $packages->where('project_id', $request->project_id);
        }

        $packages = $packages->paginate(12)->appends($request->query());

        return view('admin.extends.sell.index', compact('packages', 'projects'));
    }

    public function options($id)
    {
        $package = Package::with('project')->find($id);

        if(empty($package)){
            Log::info('Package Not Found!');
            return redirect()->back()->with('error', 'Package Not Found!');
        }

        return view('admin.extends.sell.options', compact('package'));
    }

    public function cash($id)
    {
        $package = Package::with('project')->find($id);

        if(empty($package)){
            Log::info('Package Not Found!');
            return redirect()->back()->with('error', 'Package Not Found!');
        }

        $totalSold = Sell::whereHas('sellInfo', function ($q) use ($id) {
            $q->where('package_id', $id);
        })->sum('total_share_sell');

        $totalBenefitTaken = SellInfo::where('package_id', $id)
            ->whereHas('sell', function ($q) {
                $q->where('take_return', 0);
            })
            ->get()
            ->sum(function ($info) {
                return $info->extra_benefit * $info->sell->sell_quantity;
            });

        $users = User::where('user_type', 'user')->get();
        $accounts = Account::where('status', 1)->get();
        $defaultAccount = Account::where('status', 1)->where('is_default', 1)->first();

        return view('admin.extends.sell.cash', compact('package', 'totalSold', 'totalBenefitTaken', 'users', 'accounts', 'defaultAccount'));
    }

    public function storeCash(Request $request)
    {

        $request->validate([
            'package_id'   => 'required|exists:packages,id',
            'share_qty'    => 'required|numeric|min:1',
            'take_return'  => 'required|in:0,1',
            'user_id'      => 'required|exists:users,id',
            'account_id'   => 'required|exists:accounts,id',
            'admin_password' => 'required',
        ]);

        if (!Hash::check($request->admin_password, auth()->user()->password)) {
            return redirect()->back()->with('error', 'Invalid admin password!');
        }

        try{
            DB::beginTransaction();

            $package = Package::with('project')->find($request->package_id);

            if(empty($package)){
                Log::info('Package Not Found!');
                return redirect()->back()->with('error','Package Not Found!');
            }

            if ($package->status != 1) {
                return redirect()->back()->with('error', 'Package is not active!');
            }

            $totalSold = Sell::whereHas('sellInfo', function ($q) use ($package) {
                $q->where('package_id', $package->id);
            })->sum('total_share_sell');

            $totalBenefitTaken = SellInfo::where('package_id', $package->id)
                ->whereHas('sell', function ($q) {
                    $q->where('take_return', 0);
                })
                ->get()
                ->sum(function ($info) {
                    return $info->extra_benefit * $info->sell->sell_quantity;
                });

            $availableShare = $package->allotted_share - $totalSold - $totalBenefitTaken;

            $qty = $request->share_qty;
            $totalShareRequested = $package->share_count * $qty;

            if ($request->take_return == 0) {
                $totalShareRequested += ($package->extra_benefit ?? 0) * $qty;
            }

            if ($totalShareRequested > $availableShare) {
                return redirect()->back()->with('error', 'You cannot sell more than ' . number_format($availableShare, 2) . ' shares. Requested: ' . number_format($totalShareRequested, 2));
            }

            $qty = $request->share_qty;
            $totalAmount = $package->package_price * $qty;
            $totalShareSell = $package->share_count * $qty;

            $sell = Sell::create([
                'sell_date'           => today(),
                'sell_type'           => 'on_cash',
                'sell_quantity'       => $qty,
                'total_share_sell'    => $totalShareSell,
                'total_amount'        => $totalAmount,
                'paid_amount'         => $totalAmount,
                'due_amount'          => 0,
                'current_due_amount'  => 0,
                'take_return'         => $request->take_return ?? 0,
                'user_id'             => $request->user_id,
                'account_id'          => $request->account_id,
                'installment_number'  => 0,
                'installment_amount'  => 0,
            ]);

            SellInfo::create([
                'sell_id'        => $sell->id,
                'project_id'     => $package->project_id,
                'package_id'     => $package->id,
                'package_price'  => $package->package_price,
                'return_amount'  => $package->return_amount,
                'return_time'    => $package->return_time,
                'extra_benefit'  => $package->extra_benefit,
                'share_count'    => $package->share_count,
                'booking_money'  => $package->booking_money,
            ]);

            User::where('id',$request->user_id)->update(['status' => 1]);

            Account::where('id',$request->account_id)->increment('balance',$totalAmount);
            $account = Account::find($request->account_id);

            CompanyCashFlow::create([
                'date'           => $sell->sell_date,
                'description'    => 'Cash Sale - '.$package->package_name . ' ('.$package->package_no.')',
                'invoice_id'     => $sell->sell_voucher,
                'dr_amount'      => 0,
                'cr_amount'      => $totalAmount,
                'balance'        => $account->balance,
                'account_id'     => $request->account_id,
                'voucher_route'  => 'admin.sell.show',
                'voucher_id'     => $sell->id,
            ]);

            // Stock Share Management

           $stock = ShareInStock::where('package_id',$request->package_id)->where('project_id',$package->project_id)->first();
           $oldStock = $stock->stock;
           $currentStock = $oldStock - $totalShareRequested;

           $stock->update([
               'stock' => $currentStock,
           ]);

            DB::commit();
            Log::info('Cash Sell Completed. Voucher: ' . $sell->sell_voucher);
            return redirect()->route('admin.sell.index')->with('success', 'Cash sale completed successfully. Voucher: ' . $sell->sell_voucher);

        }catch(\Exception $e){
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back()->with('error','Cash Sale Denied!');
        }

    }

    public function installment($id)
    {
        $package = Package::with('project')->find($id);

        if(empty($package)){
            Log::info('Package Not Found!');
            return redirect()->back()->with('error', 'Package Not Found!');
        }

        $totalSold = Sell::whereHas('sellInfo', function ($q) use ($id) {
            $q->where('package_id', $id);
        })->sum('total_share_sell');

        $totalBenefitTaken = SellInfo::where('package_id', $id)
            ->whereHas('sell', function ($q) {
                $q->where('take_return', 0);
            })
            ->get()
            ->sum(function ($info) {
                return $info->extra_benefit * $info->sell->sell_quantity;
            });

        $users = User::where('user_type', 'user')->get();
        $accounts = Account::where('status', 1)->get();
        $defaultAccount = Account::where('status', 1)->where('is_default', 1)->first();

        return view('admin.extends.sell.installment', compact('package', 'totalSold', 'totalBenefitTaken', 'users', 'accounts', 'defaultAccount'));
    }

    public function storeInstallment(Request $request)
    {
        $request->validate([
            'package_id'          => 'required|exists:packages,id',
            'share_qty'           => 'required|numeric|min:1',
            'user_id'             => 'required|exists:users,id',
            'account_id'          => 'required|exists:accounts,id',
            'admin_password'      => 'required',
            'booking_money'       => 'required|numeric|min:0',
            'installment_number'  => 'required|integer|min:1',
        ]);

        if (!Hash::check($request->admin_password, auth()->user()->password)) {
            return redirect()->back()->with('error', 'Invalid admin password!');
        }

        try{

            DB::beginTransaction();

            $package = Package::with('project')->find($request->package_id);

            if(empty($package)){
                Log::info('Package Not Found!');
                return redirect()->back()->with('error','Package Not Found!');
            }

            if ($package->status != 1) {
                return redirect()->back()->with('error', 'Package is not active!');
            }

            $totalSold = Sell::whereHas('sellInfo', function ($q) use ($package) {
                $q->where('package_id', $package->id);
            })->sum('total_share_sell');

            $totalBenefitTaken = SellInfo::where('package_id', $package->id)
                ->whereHas('sell', function ($q) {
                    $q->where('take_return', 0);
                })
                ->get()
                ->sum(function ($info) {
                    return $info->extra_benefit * $info->sell->sell_quantity;
                });

            $availableShare = $package->allotted_share - $totalSold - $totalBenefitTaken;

            $qty = $request->share_qty;
            $totalShareRequested = $package->share_count * $qty;

            if ($totalShareRequested > $availableShare) {
                return redirect()->back()->with('error', 'You cannot sell more than ' . number_format($availableShare, 2) . ' shares. Requested: ' . number_format($totalShareRequested, 2));
            }

            $qty = $request->share_qty;
            $totalAmount = $package->package_price * $qty;
            $totalShareSell = $package->share_count * $qty;
            $bookingMoney = $request->booking_money;
            $installmentNumber = $request->installment_number;
            $installmentAmount = $installmentNumber > 0 ? ($totalAmount - $bookingMoney) / $installmentNumber : 0;

            $sell = Sell::create([
                'sell_date'           => today(),
                'sell_type'           => 'on_installment',
                'sell_quantity'       => $qty,
                'total_share_sell'    => $totalShareSell,
                'total_amount'        => $totalAmount,
                'paid_amount'         => $bookingMoney,
                'due_amount'          => $totalAmount - $bookingMoney,
                'current_due_amount'          => $totalAmount - $bookingMoney,
                'user_id'             => $request->user_id,
                'account_id'          => $request->account_id,
                'installment_number'  => $installmentNumber,
                'installment_amount'  => $installmentAmount,
            ]);

            SellInfo::create([
                'sell_id'        => $sell->id,
                'project_id'     => $package->project_id,
                'package_id'     => $package->id,
                'package_price'  => $package->package_price,
                'return_amount'  => 0,
                'return_time'    => 0,
                'extra_benefit'  => 0,
                'share_count'    => $package->share_count,
                'booking_money'  => $package->booking_money,
            ]);

            User::where('id',$request->user_id)->update(['status' => 1]);

            Account::where('id',$request->account_id)->increment('balance', $bookingMoney);
            $account = Account::find($request->account_id);

            CompanyCashFlow::create([
                'date'           => $sell->sell_date,
                'description'    => 'Installment Sale (Booking) - '.$package->package_name . ' ('.$package->package_no.')',
                'invoice_id'     => $sell->sell_voucher,
                'dr_amount'      => 0,
                'cr_amount'      => $bookingMoney,
                'balance'        => $account->balance,
                'account_id'     => $request->account_id,
                'voucher_route'  => 'admin.sell.show',
                'voucher_id'     => $sell->id,
            ]);

            // Stock Share Management

            $stock = ShareInStock::where('package_id',$request->package_id)->where('project_id',$package->project_id)->first();
            $oldStock = $stock->stock;
            $currentStock = $oldStock - $totalShareRequested;

            $stock->update([
                'stock' => $currentStock,
            ]);


            DB::commit();
            Log::info('Installment Sell Completed. Voucher: ' . $sell->sell_voucher);
            return redirect()->route('admin.sell.all')->with('success', 'Installment sale completed successfully. Voucher: ' . $sell->sell_voucher);

        }catch(\Exception $e){
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back()->with('error','Installment Sale Denied!');
        }
    }

    public function show($id)
    {
        $sell = Sell::with(['user', 'account', 'sellInfo.project', 'sellInfo.package'])->find($id);

        if (!$sell) {
            return redirect()->back()->with('error', 'Sell record not found!');
        }

        $company = CompanySetting::first();

        return view('admin.extends.sell.show', compact('sell', 'company'));
    }

    public function all(Request $request)
    {
        if ($request->ajax()) {
            $sells = Sell::with(['user', 'account', 'sellInfo.project', 'sellInfo.package']);

            if ($request->filled('sell_type')) {
                $sells->where('sell_type', $request->sell_type);
            }

            if ($request->filled('start_date')) {
                $sells->whereDate('sell_date', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $sells->whereDate('sell_date', '<=', $request->end_date);
            }

            return Datatables::of($sells)
                ->addIndexColumn()
                ->addColumn('sell_voucher', function ($row) {
                    return $row->sell_voucher ?? '-';
                })
                ->addColumn('sell_date', function ($row) {
                    return $row->sell_date ? \Carbon\Carbon::parse($row->sell_date)->format('d M Y') : '-';
                })
                ->addColumn('sell_type', function ($row) {
                    if ($row->sell_type == 'on_installment') {
                        return '<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-cyan-100 text-cyan-700">Installment</span>';
                    }
                    return '<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Cash</span>';
                })
                ->addColumn('customer', function ($row) {
                    return $row->user->name ?? 'N/A';
                })
                ->addColumn('package', function ($row) {
                    return $row->sellInfo->package->package_name ?? 'N/A';
                })
                ->addColumn('project', function ($row) {
                    return $row->sellInfo->project->name ?? 'N/A';
                })
                ->addColumn('total_amount', function ($row) {
                    return '৳ ' . number_format($row->total_amount, 2);
                })
                ->addColumn('paid_amount', function ($row) {
                    return '৳ ' . number_format($row->paid_amount, 2);
                })
                ->addColumn('action', function ($row) {
                    $showUrl = route('admin.sell.show', $row->id);
                    return '
                    <div class="flex gap-1.5">
                        <a href="' . $showUrl . '"
                           class="w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-emerald-400 to-teal-500 hover:from-emerald-500 hover:to-teal-600 text-white shadow-sm shadow-emerald-200 hover:shadow-md hover:shadow-emerald-300 hover:-translate-y-0.5 transition-all duration-200"
                           title="Show Voucher">
                            <i class="fa fa-eye text-xs"></i>
                        </a>
                    </div>
                    ';
                })
                ->rawColumns(['sell_type', 'action'])
                ->make(true);
        }

        return view('admin.extends.sell.all');
    }
}
