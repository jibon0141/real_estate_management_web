<?php

namespace App\Http\Controllers\Backend\SetCommission;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommissionController extends Controller
{
    public function index(Request $request)
    {
        $commissions = Commission::orderBy('id')->get();
        return view('admin.extends.set_commission.index', compact('commissions'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'commissions' => 'required|array',
            'commissions.*.commission_percentage' => 'required|numeric|min:0|max:100',
        ]);

        try {
            $submittedIds = [];

            foreach ($request->commissions as $key => $item) {
                if (!empty($item['id'])) {
                    $submittedIds[] = $item['id'];

                    $commission = Commission::find($item['id']);
                    if ($commission) {
                        $commission->update([
                            'commission_percentage' => $item['commission_percentage'],
                        ]);
                    }
                } else {
                    $request->validate([
                        "commissions.$key.name" => 'required|string|max:255|unique:commissions,name',
                    ]);

                    $newCommission = Commission::create([
                        'name' => $item['name'],
                        'commission_percentage' => $item['commission_percentage'],
                    ]);
                    $submittedIds[] = $newCommission->id;
                }
            }

            Commission::whereNotIn('id', $submittedIds)->delete();

            Log::info('Commissions saved successfully!');
            return redirect()->route('admin.set-commission.index')->with('success', 'Commissions saved successfully!');
        } catch (\Exception $e) {
            Log::error('Commission save failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to save commissions!');
        }
    }
}
