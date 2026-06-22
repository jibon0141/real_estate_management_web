<?php

namespace App\Http\Controllers\Backend\GlAccount;


use App\Http\Controllers\Controller;
use App\Models\GlAccount;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GlAccountController extends controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $accounts = GlAccount::all();

            return DataTables::of($accounts)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.gl-account.edit', $row->id);

                    return '
    <div class="flex gap-1.5">
        <a href="' . $editUrl . '"
           class="w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-sky-400 to-blue-500 hover:from-sky-500 hover:to-blue-600 text-white shadow-sm shadow-sky-200 hover:shadow-md hover:shadow-sky-300 hover:-translate-y-0.5 transition-all duration-200"
           title="Edit">
            <i class="fa fa-edit text-xs"></i>
        </a>
    </div>
    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.extends.gl_account.index');
    }

    public function edit($id)
    {
        $account = GlAccount::findOrFail($id);
        return view('admin.extends.gl_account.edit', compact('account'));
    }

    public function update(Request $request, $id)
    {
        $account = GlAccount::findOrFail($id);

        $data = $request->validate([
            'account_name' => 'required|string|max:255',
        ]);

        $account->update($data);

        return redirect()->back()->with('success', 'GL Account updated successfully.');
    }

}
