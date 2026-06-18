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
                ->addColumn('user_name', function ($row) {
                    return $row->user_name ?? 'N/A';
                })
                ->addColumn('name', function ($row) {
                    return $row->name ?? 'N/A';
                })
                ->addColumn('email', function ($row) {
                    return $row->email ?? 'N/A';
                })
                ->addColumn('phone', function ($row) {
                    return $row->phone ?? 'N/A';
                })
                ->addColumn('status', function ($row) {
                    return $row->status
                        ? '<span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full">Active</span>'
                        : '<span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-200 rounded-full">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.user.edit', $row->id);

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
        return view('admin.extends.user.index');
    }

    public function create(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                'user_name' => 'required|string|max:50|unique:users,user_name',
                'name'      => 'required|string|max:100',
                'phone'     => 'required|unique:users,phone|digits_between:1,11',
                'email'     => 'nullable|email|unique:users,email',
                'status'    => 'required',
                'password'  => 'required|min:6|confirmed',
            ]);

            try {
                User::create([
                    'user_name' => $request->user_name,
                    'name'      => $request->name,
                    'phone'     => $request->phone,
                    'email'     => $request->email,
                    'user_type' => "user",
                    'status'    => $request->status,
                    'password'  => Hash::make($request->password),
                    'created_at' => now(),
                ]);
                Log::info('User Created Successfully');
                return redirect()->back()->with('success', 'User Created Successfully.');
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return redirect()->back()->with('error', 'User Create Failed.');
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
            'user_name' => 'required|string|max:50|unique:users,user_name,' . $id,
            'name'      => 'required|string|max:100',
            'phone'     => 'required|unique:users,phone,' . $id . '|digits_between:1,11',
            'email'     => 'nullable|email|unique:users,email,' . $id,
            'status'    => 'required',
            'password'  => 'nullable|min:6|confirmed',
        ]);

        try {
            $data = [
                'user_name' => $request->user_name,
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

            return redirect()->back()->with('error', 'User Update Failed.');
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
                'message' => 'User not found.'
            ], 404);
        }

        try {
            $user->delete();

            Log::info('User Deleted Successfully', [
                'user_id' => $id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('User Delete Failed', [
                'user_id' => $id,
                'error'   => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'User delete failed.'
            ], 500);
        }
    }
}
