<?php

namespace App\Http\Controllers\Backend\DistributeCommission;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\CommissionSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class CommissionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $commissions = Commission::with('commissionSetting')->get();

            return Datatables::of($commissions)
                ->addIndexColumn()
                ->addColumn('commission_setting_id', function ($row) {
                    return $row->commissionSetting->name ?? '-';
                })
                ->addColumn('commission_percentage', function ($row) {
                    return $row->commission_percentage ?? '-';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.distribute-commission.edit', $row->id);

                    return '
    <div class="flex gap-2">
        <a href="' . $editUrl . '" class="px-2 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded">
            <i class="fa fa-edit"></i>
        </a>
        <button onclick="deleteItem(' . $row->id . ')" class="px-2 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded">
            <i class="fa fa-trash"></i>
        </button>
    </div>
    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.extends.distribute_commission.index');
    }

    public function create(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                'commission_setting_id' => 'required|integer|exists:commission_settings,id|unique:commissions,commission_setting_id',
                'commission_percentage' => 'required|numeric|min:0|max:100',
            ]);

            try {
                Commission::create([
                    'commission_setting_id' => $request->commission_setting_id,
                    'commission_percentage' => $request->commission_percentage,
                    'created_at'            => now(),
                ]);

                Log::info('Commission Created Successfully!');
                return redirect()->back()->with('success', 'Commission Created Successfully!');
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return redirect()->back()->with('error', 'Commission Create Failed!');
            }
        }

        $commissionTypes = CommissionSetting::all();
        return view('admin.extends.distribute_commission.create', compact('commissionTypes'));
    }

    public function edit($id)
    {
        $commission = Commission::where('id', $id)->first();

        if (empty($commission)) {
            Log::info('Commission Not Found', ['commission_id' => $id]);
            return redirect()->back()->with('error', 'Commission Not Found.');
        }

        $commissionTypes = CommissionSetting::all();
        return view('admin.extends.distribute_commission.edit', compact('commission', 'commissionTypes'));
    }

    public function update(Request $request, $id)
    {
        $commission = Commission::where('id', $id)->first();

        if (empty($commission)) {
            Log::info('Commission Not Found', ['commission_id' => $id]);
            return redirect()->back()->with('error', 'Commission Not Found.');
        }

        $request->validate([
            'commission_setting_id' => 'required|integer|exists:commission_settings,id|unique:commissions,commission_setting_id,' . $id,
            'commission_percentage' => 'required|numeric|min:0|max:100',
        ]);

        try {
            $commission->update([
                'commission_setting_id' => $request->commission_setting_id,
                'commission_percentage' => $request->commission_percentage,
            ]);

            Log::info('Commission Updated Successfully', ['commission_id' => $commission->id]);

            return redirect()->back()->with('success', 'Commission Updated Successfully.');
        } catch (\Exception $e) {
            Log::error('Commission Update Failed', [
                'commission_id' => $id,
                'error'         => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Commission Update Failed!');
        }
    }

    public function destroy($id)
    {
        $commission = Commission::where('id', $id)->first();

        if (empty($commission)) {
            return response()->json([
                'success' => false,
                'message' => 'Commission not found!'
            ], 404);
        }

        try {

            $commission->delete();

            Log::info('Commission Deleted Successfully', [
                'commission_id' => $id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Commission deleted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Commission Delete Failed', [
                'commission_id' => $id,
                'error'         => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Commission delete failed!'
            ], 500);
        }
    }
}
