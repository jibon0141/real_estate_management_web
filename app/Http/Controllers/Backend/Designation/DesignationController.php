<?php

namespace App\Http\Controllers\Backend\Designation;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DesignationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $designations = Designation::all();

            return DataTables::of($designations)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.extends.designation.index');
    }
}
