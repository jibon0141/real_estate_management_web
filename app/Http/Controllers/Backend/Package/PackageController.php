<?php

namespace App\Http\Controllers\Backend\Package;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Project;
use App\Models\ShareInStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                ->addColumn('extra_benefit', function ($row) {
                    return number_format($row->extra_benefit, 2) ?? 'N/A';
                })
                ->addColumn('allotted_share', function ($row) {
                    return number_format($row->allotted_share, 2) ?? 'N/A';
                })
                ->addColumn('booking_money', function ($row) {
                    return number_format($row->booking_money, 2) ?? 'N/A';
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 1
                        ? '<span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full">Active</span>'
                        : '<span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-200 rounded-full">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    $showUrl = route('admin.package.show', $row->id);
                    $editUrl = route('admin.package.edit', $row->id);

                    return '
    <div class="flex gap-1.5">
        <a href="' . $showUrl . '"
           class="w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-emerald-400 to-green-500 hover:from-emerald-500 hover:to-green-600 text-white shadow-sm shadow-emerald-200 hover:shadow-md hover:shadow-emerald-300 hover:-translate-y-0.5 transition-all duration-200"
           title="View">
            <i class="fa fa-eye text-xs"></i>
        </a>
        <a href="' . $editUrl . '"
           class="w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-sky-400 to-blue-500 hover:from-sky-500 hover:to-blue-600 text-white shadow-sm shadow-sky-200 hover:shadow-md hover:shadow-sky-300 hover:-translate-y-0.5 transition-all duration-200"
           title="Edit">
            <i class="fa fa-edit text-xs"></i>
        </a>
        <button onclick="deleteItem(' . $row->id . ')"
                class="w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-rose-400 to-red-500 hover:from-rose-500 hover:to-red-600 text-white shadow-sm shadow-rose-200 hover:shadow-md hover:shadow-rose-300 hover:-translate-y-0.5 transition-all duration-200"
                title="Delete">
            <i class="fa fa-trash text-xs"></i>
        </button>
    </div>
    ';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.extends.package.index');
    }

    public function show($id)
    {
        $package = Package::with('project')->where('id', $id)->first();

        if (empty($package)) {
            Log::info('Package Not Found', ['package_id' => $id]);
            return redirect()->back()->with('error', 'Package Not Found.');
        }

        $stock = ShareInStock::where('package_id', $id)->first();
        $availableShare = $stock ? $stock->stock : 0;

        return view('admin.extends.package.show', compact('package', 'availableShare'));
    }

    public function create(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                'project_id'     => 'required|exists:projects,id',
                'package_name'   => 'required|string|max:255|unique:packages,package_name,NULL,id,project_id,' . $request->project_id,
                'package_price'  => 'required|numeric|min:0',
                'return_amount'  => 'required|numeric|min:0',
                'return_time'    => 'required|string|max:255',
                'extra_benefit'  => 'nullable|numeric|min:0',
                'booking_money'  => 'required|numeric|min:0',
                'share_count'    => 'required|numeric|min:0',
                'allotted_share' => [
                    'required', 'numeric', 'min:0',
                    function ($attribute, $value, $fail) use ($request) {
                        $project = Project::find($request->project_id);
                        if ($project) {
                            $alreadyAllotted = Package::where('project_id', $request->project_id)->sum('allotted_share');
                            if (($alreadyAllotted + $value) > $project->total_share) {
                                $remaining = $project->total_share - $alreadyAllotted;
                                $fail('Only ' . number_format(max($remaining, 0), 2) . ' shares left to allot for this project.');
                            }
                        }
                    },
                ],
                'description'    => 'nullable|string',
                'status'         => 'required|in:0,1',
            ]);

            try {

                DB::beginTransaction();

                $data = [
                    'project_id'     => $request->project_id,
                    'package_name'   => $request->package_name,
                    'package_price'  => $request->package_price,
                    'return_amount'  => $request->return_amount,
                    'return_time'    => $request->return_time,
                    'extra_benefit'  => $request->extra_benefit,
                    'booking_money'  => $request->booking_money,
                    'share_count'    => $request->share_count,
                    'allotted_share' => $request->allotted_share,
                    'description'    => $request->description,
                    'status'         => $request->status,
                    'created_at'     => now(),
                ];

                $package= Package::create($data);

                // Package Share In Stock
                ShareInStock::create([
                    'project_id'  => $request->project_id,
                    'package_id'  => $package->id,
                    'stock'       => $request->allotted_share,
                ]);

                DB::commit();
                Log::info('Package Created Successfully');
                return redirect()->back()->with('success', 'Package Created Successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error($e->getMessage());
                return redirect()->back()->with('error', 'Package Create Failed.');
            }
        }
        $projects = Project::where('status', 1)->get();
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
            'package_name'   => 'required|string|max:255|unique:packages,package_name,' . $id . ',id,project_id,' . $request->project_id,
            'package_price'  => 'required|numeric|min:0',
            'return_amount'  => 'required|numeric|min:0',
            'return_time'    => 'required|string|max:255',
            'extra_benefit'  => 'nullable|numeric|min:0',
            'booking_money'  => 'required|numeric|min:0',
            'share_count'    => 'required|numeric|min:0',
            'allotted_share' => [
                'required', 'numeric', 'min:0',
                function ($attribute, $value, $fail) use ($request, $id) {
                    $project = Project::find($request->project_id);
                    if ($project) {
                        $alreadyAllotted = Package::where('project_id', $request->project_id)
                            ->where('id', '!=', $id)
                            ->sum('allotted_share');
                        if (($alreadyAllotted + $value) > $project->total_share) {
                            $remaining = $project->total_share - $alreadyAllotted;
                            $fail('Only ' . number_format(max($remaining, 0), 2) . ' shares left to allot for this project.');
                        }
                    }
                },
            ],
            'description'    => 'nullable|string',
            'status'         => 'required|in:0,1',
        ]);

        try {
            DB::beginTransaction();

            $oldAllotted = $package->allotted_share;

            $data = [
                'project_id'     => $request->project_id,
                'package_name'   => $request->package_name,
                'package_price'  => $request->package_price,
                'return_amount'  => $request->return_amount,
                'return_time'    => $request->return_time,
                'extra_benefit'  => $request->extra_benefit,
                'booking_money'  => $request->booking_money,
                'share_count'    => $request->share_count,
                'allotted_share' => $request->allotted_share,
                'description'    => $request->description,
                'status'         => $request->status,
            ];

            $package->update($data);

            $stock = ShareInStock::where('package_id', $package->id)->first();

            if ($stock) {
                if($oldAllotted > $request->allotted_share){
                    $diff = $oldAllotted - $request->allotted_share;
                    $stock->decrement('stock', $diff);
                }elseif($oldAllotted < $request->allotted_share){
                    $diff = $request->allotted_share - $oldAllotted;
                    $stock->increment('stock', $diff);
                }
            }

            DB::commit();
            Log::info('Package Updated Successfully', ['package_id' => $package->id]);
            return redirect()->back()->with('success', 'Package Updated Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
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
