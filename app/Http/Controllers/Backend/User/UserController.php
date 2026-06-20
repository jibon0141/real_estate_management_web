<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::where('user_type','!=','admin')->get();

            return Datatables::of($users)
                ->addIndexColumn()
                ->addColumn('user_code', function ($row) {
                    return $row->user_id ?? '-';
                })
                ->addColumn('ref_id', function ($row) {
                    return $row->ref_id ?? '-';
                })
                ->addColumn('name', function ($row) {
                    return $row->name ?? '-';
                })
                ->addColumn('email', function ($row) {
                    return $row->email ?? '-';
                })
                ->addColumn('phone', function ($row) {
                    return $row->phone ?? '-';
                })
                ->addColumn('status', function ($row) {
                    return $row->status
                        ? '<span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full">Active</span>'
                        : '<span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-200 rounded-full">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.user.edit', $row->id);

                    return '
    <div class="flex gap-1.5">
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
        return view('admin.extends.user.index');
    }

    public function create(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                'ref_id'    => 'nullable|string|max:50',
                'name'      => 'required|string|max:100',
                'phone'     => 'required|unique:users,phone|digits_between:1,11',
                'email'     => 'nullable|email|unique:users,email',
                'password'  => 'required|min:6|confirmed',
            ]);

            try {

                if($request->ref_id){
                    $check = User::where('user_id',$request->ref_id)->first();
                    if(empty($check)){
                        Log::info('Invalid Reference Id!');
                        return redirect()->back()->with('success','Invalid Reference Id!');
                    }
                }

                $user = User::create([
                    'ref_id'          => $request->ref_id,
                    'name'            => $request->name,
                    'phone'           => $request->phone,
                    'email'           => $request->email,
                    'user_type'       => "user",
                    'status'          => 0,
                    'designation_id'  => 1,
                    'password'        => Hash::make($request->password),
                    'created_at'      => now(),
                ]);

                Log::info('User Created Successfully!');
                return redirect()->back()->with('success', 'User Created Successfully!');
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return redirect()->back()->with('error', 'User Create Failed!');
            }
        }
        return view('admin.extends.user.create');
    }

    public function edit($id)
    {
        $user = User::where('id', $id)->first();

        if (empty($user)) {
            Log::info('User Not Found', ['user_id' => $id]);
            return redirect()->back()->with('error', 'User Not Found.');
        }

        return view('admin.extends.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::where('id', $id)->first();

        if (empty($user)) {
            Log::info('User Not Found', ['user_id' => $id]);
            return redirect()->back()->with('error', 'User Not Found.');
        }

        $request->validate([
            'ref_id'    => 'nullable|string|max:50',
            'name'      => 'required|string|max:100',
            'phone'     => 'required|unique:users,phone,' . $id . '|digits_between:1,11',
            'email'     => 'nullable|email|unique:users,email,' . $id,
            'status'    => 'required',
            'password'  => 'nullable|min:6|confirmed',
        ]);

        try {

            if($request->ref_id){
                $check = User::where('user_id',$request->ref_id)->first();
                if(empty($check)){
                    Log::info('Invalid Reference Id!');
                    return redirect()->back()->with('success','Invalid Reference Id!');
                }
            }

            $data = [
                'ref_id'    => $request->ref_id,
                'name'      => $request->name,
                'phone'     => $request->phone,
                'email'     => $request->email,
                'status'    => $request->status,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            Log::info('User Updated Successfully', ['user_id' => $user->id]);

            return redirect()->back()->with('success', 'User Updated Successfully.');
        } catch (\Exception $e) {
            Log::error('User Update Failed', [
                'user_id' => $id,
                'error'   => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'User Update Failed!');
        }
    }

    public function destroy($id)
    {
        $user = User::where('id', $id)->first();

        if (empty($user)) {
            Log::info('User Not Found', [
                'user_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'User not found!'
            ], 404);
        }

        try {
            $user->delete();

            Log::info('User Deleted Successfully', [
                'user_id' => $id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('User Delete Failed', [
                'user_id' => $id,
                'error'   => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'User delete failed!'
            ], 500);
        }
    }
}
