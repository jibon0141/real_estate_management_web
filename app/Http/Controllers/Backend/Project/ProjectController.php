<?php

namespace App\Http\Controllers\Backend\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Traits\ManageImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ProjectController extends Controller
{
    use ManageImage;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $projects = Project::get();

            return Datatables::of($projects)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->name ?? 'N/A';
                })
                ->addColumn('image', function ($row) {
                    if ($row->image) {
                        return '<img src="' . asset('uploads/project/' . $row->image) . '" width="60" height="60" style="object-fit: cover;">';
                    }
                    return 'N/A';
                })
                ->addColumn('description', function ($row) {
                    return $row->description ?? 'N/A';
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 1
                        ? '<span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full">Active</span>'
                        : '<span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-200 rounded-full">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.project.edit', $row->id);

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
                ->rawColumns(['image', 'status', 'action'])
                ->make(true);
        }

        return view('admin.extends.project.index');
    }

    public function create(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                'name'         => 'required|string|max:255',
                'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'description'  => 'nullable|string',
                'status'       => 'required|in:0,1',
            ]);

            try {
                $data = [
                    'name'        => $request->name,
                    'description' => $request->description,
                    'status'      => $request->status,
                    'created_at'  => now(),
                ];

                if ($request->hasFile('image')) {
                    $data['image'] = $this->storeImage($request->file('image'), 'uploads/project');
                }

                Project::create($data);

                Log::info('Project Created Successfully');
                return redirect()->back()->with('success', 'Project Created Successfully.');
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return redirect()->back()->with('error', 'Project Create Failed.');
            }
        }

        return view('admin.extends.project.create');
    }

    public function edit($id)
    {
        $project = Project::where('id', $id)->first();

        if (empty($project)) {
            Log::info('Project Not Found', ['project_id' => $id]);
            return redirect()->back()->with('error', 'Project Not Found.');
        }

        return view('admin.extends.project.edit', compact('project'));
    }

    public function update(Request $request, $id)
    {
        $project = Project::where('id', $id)->first();

        if (empty($project)) {
            Log::info('Project Not Found', ['project_id' => $id]);
            return redirect()->back()->with('error', 'Project Not Found.');
        }

        $request->validate([
            'name'         => 'required|string|max:255',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'description'  => 'nullable|string',
            'status'       => 'required|in:0,1',
        ]);

        try {
            $data = [
                'name'        => $request->name,
                'description' => $request->description,
                'status'      => $request->status,
            ];

            if ($request->hasFile('image')) {
                if ($project->image) {
                    $this->destroyImage($project->image, 'uploads/project');
                }
                $data['image'] = $this->storeImage($request->file('image'), 'uploads/project');
            }

            $project->update($data);

            Log::info('Project Updated Successfully', ['project_id' => $project->id]);

            return redirect()->back()->with('success', 'Project Updated Successfully.');
        } catch (\Exception $e) {
            Log::error('Project Update Failed', [
                'project_id' => $id,
                'error'      => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Project Update Failed.');
        }
    }

    public function destroy($id)
    {
        $project = Project::where('id', $id)->first();

        if (empty($project)) {
            Log::info('Project Not Found', ['project_id' => $id]);

            return response()->json([
                'success' => false,
                'message' => 'Project not found.'
            ], 404);
        }

        try {
            if ($project->image) {
                $this->destroyImage($project->image, 'uploads/project');
            }

            $project->delete();

            Log::info('Project Deleted Successfully', ['project_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Project deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Project Delete Failed', [
                'project_id' => $id,
                'error'      => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Project delete failed.'
            ], 500);
        }
    }
}
