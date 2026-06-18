<?php

namespace App\Http\Controllers\Backend\CommissionSetting;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\CommissionSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class CommissionSettingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $settings = CommissionSetting::all();

            return Datatables::of($settings)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->name ?? '-';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.commission-setting.edit', $row->id);

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

        return view('admin.extends.commission_setting.index');
    }

    public function create(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                'name' => 'required|string|max:255|unique:commission_settings,name',
            ]);

            try {
                CommissionSetting::create([
                    'name'       => $request->name,
                    'created_at' => now(),
                ]);

                Log::info('Commission Setting Created Successfully!');
                return redirect()->back()->with('success', 'Commission Setting Created Successfully!');
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return redirect()->back()->with('error', 'Commission Setting Create Failed!');
            }
        }

        return view('admin.extends.commission_setting.create');
    }

    public function edit($id)
    {
        $setting = CommissionSetting::where('id', $id)->first();

        if (empty($setting)) {
            Log::info('Commission Setting Not Found', ['commission_setting_id' => $id]);
            return redirect()->back()->with('error', 'Commission Setting Not Found.');
        }

        return view('admin.extends.commission_setting.edit', compact('setting'));
    }

    public function update(Request $request, $id)
    {
        $setting = CommissionSetting::where('id', $id)->first();

        if (empty($setting)) {
            Log::info('Commission Setting Not Found', ['commission_setting_id' => $id]);
            return redirect()->back()->with('error', 'Commission Setting Not Found.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:commission_settings,name,' . $id,
        ]);

        try {
            $setting->update([
                'name' => $request->name,
            ]);

            Log::info('Commission Setting Updated Successfully', ['commission_setting_id' => $setting->id]);

            return redirect()->back()->with('success', 'Commission Setting Updated Successfully.');
        } catch (\Exception $e) {
            Log::error('Commission Setting Update Failed', [
                'commission_setting_id' => $id,
                'error'                 => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Commission Setting Update Failed!');
        }
    }

    public function destroy($id)
    {
        $setting = CommissionSetting::where('id', $id)->first();

        if (empty($setting)) {
            return response()->json([
                'success' => false,
                'message' => 'Commission Setting not found!'
            ], 404);
        }

        try {

           $commission=Commission::where('commission_setting_id',$id)->first();

           if(!empty($commission)){
               Log::info('Commission Setting Can Not be deleted. Has Children Under It!');
               return response()->json([
                   'success' => 'error',
                   'message' => 'Commission Setting Can Not be deleted. Has Children Under It!'
               ]);
           }

            $setting->delete();

            Log::info('Commission Setting Deleted Successfully', [
                'commission_setting_id' => $id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Commission Setting deleted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Commission Setting Delete Failed', [
                'commission_setting_id' => $id,
                'error'                 => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Commission Setting delete failed!'
            ], 500);
        }
    }
}
