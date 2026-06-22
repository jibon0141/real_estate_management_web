@extends('admin.master')
@section('app_styles')
<style>
    @media print {
        body * { visibility: hidden; }
        #voucher, #voucher * { visibility: visible; }
        #voucher { position: absolute; top: 0; left: 0; width: 100%; }
        .print\:hidden { display: none !important; }
    }
</style>
@endsection
@section('content')
<div class="min-h-screen p-4 sm:p-6 lg:p-8" style="background: linear-gradient(135deg, #f0f4ff 0%, #e8ecf8 100%);">

    <div class="mb-6 print:hidden flex justify-end">
        <a href="{{ route('admin.sell.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-white/60 bg-white/70 px-5 py-2.5 text-sm font-medium text-gray-700 shadow-sm backdrop-blur-sm hover:bg-white hover:border-gray-300 transition-all duration-200 w-full sm:w-auto justify-center">
            <i class="fa fa-arrow-left text-xs"></i> Back to Sales
        </a>
    </div>

    <div id="voucher" class="max-w-5xl mx-auto bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg shadow-indigo-100/50 border border-white/50 p-6 sm:p-8 lg:p-10">

        <div class="text-center border-b-2 border-gray-300 pb-5 mb-5">
            @if($company && $company->logo)
                <img src="{{ asset('image/company_logo/'.$company->logo) }}" alt="Logo" class="h-16 mx-auto mb-2">
            @endif
            <h1 class="text-2xl font-bold text-gray-800 uppercase tracking-wide">{{ $company->company_name ?? 'Company' }}</h1>
            <p class="text-sm text-gray-500">{{ $company->address ?? '' }}</p>
            <p class="text-sm text-gray-500">{{ $company->phone ?? '' }}{{ $company->email ? ' | '.$company->email : '' }}</p>
        </div>

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-md shadow-emerald-200/60">
                    <i class="fa fa-file-invoice text-white text-sm"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $sell->sell_type == 'on_installment' ? 'Installment' : 'Cash' }} Sale Voucher</h2>
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Sale Invoice</p>
                </div>
            </div>
            <div class="text-left sm:text-right mt-3 sm:mt-0 space-y-0.5">
                <p class="text-sm text-gray-600">Voucher No: <span class="font-semibold text-gray-800">{{ $sell->sell_voucher ?? 'N/A' }}</span></p>
                <p class="text-sm text-gray-600">Date: <span class="font-semibold text-gray-800">{{ $sell->sell_date ? \Carbon\Carbon::parse($sell->sell_date)->format('d M Y') : 'N/A' }}</span></p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-6 mb-7">
            <div class="flex-1 bg-gradient-to-r from-indigo-50 to-blue-50/60 rounded-xl px-5 py-4 border border-indigo-100/60 shadow-sm">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-6 h-6 rounded-md bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center shadow-sm">
                        <i class="fa fa-user text-white text-[10px]"></i>
                    </div>
                    <h4 class="font-semibold text-gray-700 text-sm">Buyer Information</h4>
                </div>
                <p class="text-base font-semibold text-gray-800 ml-8">{{ $sell->user->name ?? 'N/A' }}</p>
                <p class="text-sm text-gray-600 ml-8"><span class="font-medium text-gray-500">Phone:</span> {{ $sell->user->phone ?? 'N/A' }}</p>
                <p class="text-sm text-gray-600 ml-8"><span class="font-medium text-gray-500">Email:</span> {{ $sell->user->email ?? 'N/A' }}</p>
            </div>
            <div class="flex-1 bg-gradient-to-r from-teal-50 to-emerald-50/60 rounded-xl px-5 py-4 border border-teal-100/60 shadow-sm">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-6 h-6 rounded-md bg-gradient-to-br from-teal-500 to-emerald-600 flex items-center justify-center shadow-sm">
                        <i class="fa fa-university text-white text-[10px]"></i>
                    </div>
                    <h4 class="font-semibold text-gray-700 text-sm">Payment Account</h4>
                </div>
                <p class="text-sm text-gray-800 ml-8 font-medium">{{ $sell->account->account_name ?? 'N/A' }}</p>
                <p class="text-sm text-gray-600 ml-8">Account No: {{ $sell->account->account_no ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-200/60 shadow-sm mb-6">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white">
                        <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">#</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Project</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Package</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Share</th>
                        <th class="px-4 py-3.5 text-center text-xs font-semibold uppercase tracking-wider">Qty</th>
                        <th class="px-4 py-3.5 text-right text-xs font-semibold uppercase tracking-wider">Total Share</th>
                        <th class="px-4 py-3.5 text-right text-xs font-semibold uppercase tracking-wider">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white/60">
                    <tr class="hover:bg-gradient-to-r hover:from-emerald-50/40 hover:to-teal-50/40 transition-all duration-200 cursor-default">
                        <td class="px-4 py-3.5 font-medium text-gray-500">1</td>
                        <td class="px-4 py-3.5 text-gray-800">{{ $sell->sellInfo->project->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3.5">
                            <span class="font-medium text-gray-800">{{ $sell->sellInfo->package->package_name ?? 'N/A' }}</span>
                            <span class="text-gray-400 text-xs block">{{ $sell->sellInfo->package->package_no ?? '' }}</span>
                        </td>
                        <td class="px-4 py-3.5 text-gray-700">{{ $sell->sellInfo->share_count ?? 0 }}</td>
                        <td class="px-4 py-3.5 text-center text-gray-700">{{ $sell->sell_quantity }}</td>
                        <td class="px-4 py-3.5 text-right font-semibold text-gray-800">{{ number_format($sell->total_share_sell, 2) }}</td>
                        <td class="px-4 py-3.5 text-right font-semibold text-gray-800">৳ {{ number_format($sell->total_amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            @if($sell->sell_type != 'on_installment')
            <div class="bg-gradient-to-r from-purple-50 to-violet-50/60 rounded-xl px-4 py-3 border border-purple-100/60 shadow-sm">
                <span class="text-xs text-gray-500 uppercase tracking-wider font-medium">Extra Benefit</span>
                <p class="text-base font-bold text-purple-700 mt-0.5">{{ $sell->take_return ? 'No' : 'Yes ('.number_format($sell->sellInfo->extra_benefit, 2).' / share)' }}</p>
            </div>
            @endif
            @if($sell->sell_type != 'on_installment')
            <div class="bg-gradient-to-r from-amber-50 to-yellow-50/60 rounded-xl px-4 py-3 border border-amber-100/60 shadow-sm">
                <span class="text-xs text-gray-500 uppercase tracking-wider font-medium">Return Preference</span>
                <p class="text-base font-bold text-amber-700 mt-0.5">{{ $sell->take_return ? 'Yes (Return: ৳'.number_format($sell->sellInfo->return_amount,2).')' : 'No' }}</p>
            </div>
            @endif
            @if($sell->sell_type == 'on_installment')
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50/60 rounded-xl px-4 py-3 border border-blue-100/60 shadow-sm">
                <span class="text-xs text-gray-500 uppercase tracking-wider font-medium">Booking Money</span>
                <p class="text-base font-bold text-blue-700 mt-0.5">৳ {{ number_format($sell->sellInfo->booking_money, 2) }}</p>
            </div>
            <div class="bg-gradient-to-r from-cyan-50 to-sky-50/60 rounded-xl px-4 py-3 border border-cyan-100/60 shadow-sm">
                <span class="text-xs text-gray-500 uppercase tracking-wider font-medium">Installment</span>
                <p class="text-base font-bold text-cyan-700 mt-0.5">{{ $sell->installment_number }} × ৳ {{ number_format($sell->installment_amount, 2) }}</p>
            </div>
            @endif
        </div>

        <div class="flex justify-end">
            <div class="w-full sm:w-80 text-sm bg-gradient-to-r from-emerald-50/60 to-teal-50/60 rounded-xl px-5 py-4 border border-emerald-100/60 shadow-sm space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Amount</span>
                    <span class="font-semibold text-gray-800">৳ {{ number_format($sell->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Paid Amount</span>
                    <span class="font-semibold text-emerald-600">৳ {{ number_format($sell->paid_amount, 2) }}</span>
                </div>
                @if($sell->due_amount > 0)
                <div class="flex justify-between items-center border-t border-emerald-100/60 pt-2">
                    <span class="font-semibold text-rose-700">Due Amount</span>
                    <span class="font-bold text-lg text-rose-600">৳ {{ number_format($sell->due_amount, 2) }}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="mt-6 bg-gradient-to-r from-amber-50 to-yellow-50/80 rounded-xl px-5 py-4 border border-amber-200/60 shadow-sm">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-5 h-5 rounded-full bg-gradient-to-br from-amber-400 to-yellow-500 flex items-center justify-center">
                    <i class="fa fa-pen-fancy text-white text-[9px]"></i>
                </div>
                <strong class="text-amber-800 text-sm">In Words (Total Amount) :</strong>
            </div>
            <span class="text-amber-700 text-sm font-medium ml-7">{{ numberToWords($sell->total_amount ?? 0, 2) }}</span>
        </div>

        <div class="mt-10 flex flex-col sm:flex-row justify-between items-center gap-4 text-sm text-gray-500 print:hidden">
            <p class="text-xs text-gray-400">
                <i class="fa fa-clock mr-1"></i> Generated on {{ \Carbon\Carbon::now('Asia/Dhaka')->format('d M Y, h:i A') }}
            </p>
            <button onclick="window.print()"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-teal-500 to-emerald-600 hover:from-teal-600 hover:to-emerald-700 text-white font-medium rounded-xl shadow-lg shadow-teal-200/60 hover:shadow-xl hover:shadow-teal-200/80 transition-all duration-200 active:scale-[0.97] w-full sm:w-auto justify-center">
                <i class="fa fa-print"></i> Print Voucher
            </button>
        </div>

    </div>

</div>
@endsection
