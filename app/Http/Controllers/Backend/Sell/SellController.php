<?php

namespace App\Http\Controllers\Backend\Sell;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Project;
use Illuminate\Http\Request;

class SellController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::where('status', 1)->get();

        $packages = Package::with('project')->where('status', 1);

        if ($request->filled('project_id')) {
            $packages->where('project_id', $request->project_id);
        }

        $packages = $packages->paginate(1)->appends($request->query());

        return view('admin.extends.sell.index', compact('packages', 'projects'));
    }
}
