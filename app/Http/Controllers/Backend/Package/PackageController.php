<?php

namespace App\Http\Controllers\Backend\Package;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $packages = Package::with('project')->get();

            return Datatables::of($packages)
                ->addIndexColumn()
                ->addColumn('package_no', function ($row) {
                    return $row->package_no ?? 'N/A';
                })
                ->addColumn('package_name', function ($row) {
                    return $row->package_name ?? 'N/A';
                })
                ->addColumn('project', function ($row) {
                    return $row->project->name ?? 'N/A';
                })
                ->addColumn('package_price', function ($row) {
                    return number_format($row->package_price, 2) ?? 'N/A';
                })
                ->addColumn('return_amount', function ($row) {
                    return number_format($row->return_amount, 2) ?? 'N/A';
                })
                ->addColumn('share_count', function ($row) {
                    return $row->share_count ?? 'N/A';
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 1
                        ? '<span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full">Active</span>'
                        : '<span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-200 rounded-full">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.package.edit', $row->id);

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
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.extends.package.index');
    }

    public function create(Request $request)
    {
        $projects = Project::where('status', 1)->get();

        if ($request->isMethod('POST')) {
            $request->validate([
                'project_id'     => 'required|exists:projects,id',
                'package_name'   => 'required|string|max:255',
                'package_price'  => 'required|numeric|min:0',
                'return_amount'  => 'required|numeric|min:0',
                'return_time'    => 'required|string|max:255',
                'extra_benefit'  => 'nullable|numeric|min:0',
                'share_count'    => 'required|numeric|min:0',
                'description'    => 'nullable|string',
                'status'         => 'required|in:0,1',
            ]);

            try {
                $data = [
                    'project_id'    => $request->project_id,
                    'package_name'  => $request->package_name,
                    'package_price' => $request->package_price,
                    'return_amount' => $request->return_amount,
                    'return_time'   => $request->return_time,
                    'extra_benefit' => $request->extra_benefit,
                    'share_count'   => $request->share_count,
                    'description'   => $request->description,
                    'status'        => $request->status,
                    'created_at'    => now(),
                ];

                Package::create($data);

                Log::info('Package Created Successfully');
                return redirect()->back()->with('success', 'Package Created Successfully.');
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return redirect()->back()->with('error', 'Package Create Failed.');
            }
        }

        return view('admin.extends.package.create', compact('projects'));
    }

    public function edit($id)
    {
        $package = Package::with('project')->where('id', $id)->first();
        $projects = Project::where('status', 1)->get();

        if (empty($package)) {
            Log::info('Package Not Found', ['package_id' => $id]);
            return redirect()->back()->with('error', 'Package Not Found.');
        }

        return view('admin.extends.package.edit', compact('package', 'projects'));
    }

    public function update(Request $request, $id)
    {
        $package = Package::where('id', $id)->first();

        if (empty($package)) {
            Log::info('Package Not Found', ['package_id' => $id]);
            return redirect()->back()->with('error', 'Package Not Found.');
        }

        $request->validate([
            'project_id'     => 'required|exists:projects,id',
            'package_name'   => 'required|string|max:255',
            'package_price'  => 'required|numeric|min:0',
            'return_amount'  => 'required|numeric|min:0',
            'return_time'    => 'required|string|max:255',
            'extra_benefit'  => 'nullable|numeric|min:0',
            'share_count'    => 'required|numeric|min:0',
            'description'    => 'nullable|string',
            'status'         => 'required|in:0,1',
        ]);

        try {
            $data = [
                'project_id'    => $request->project_id,
                'package_name'  => $request->package_name,
                'package_price' => $request->package_price,
                'return_amount' => $request->return_amount,
                'return_time'   => $request->return_time,
                'extra_benefit' => $request->extra_benefit,
                'share_count'   => $request->share_count,
                'description'   => $request->description,
                'status'        => $request->status,
            ];

            $package->update($data);

            Log::info('Package Updated Successfully', ['package_id' => $package->id]);

            return redirect()->back()->with('success', 'Package Updated Successfully.');
        } catch (\Exception $e) {
            Log::error('Package Update Failed', [
                'package_id' => $id,
                'error'      => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Package Update Failed.');
        }
    }

    public function destroy($id)
    {
        $package = Package::where('id', $id)->first();

        if (empty($package)) {
            Log::info('Package Not Found', ['package_id' => $id]);

            return response()->json([
                'success' => false,
                'message' => 'Package not found.'
            ], 404);
        }

        try {
            $package->delete();

            Log::info('Package Deleted Successfully', ['package_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Package deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Package Delete Failed', [
                'package_id' => $id,
                'error'      => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Package delete failed.'
            ], 500);
        }
    }
}
