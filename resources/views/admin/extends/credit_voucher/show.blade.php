@extends('admin.master')
@section('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #voucher,
        #voucher * {
            visibility: visible;
        }
        #voucher {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
        }
        .print\:hidden {
            display: none !important;
        }
    }
</style>
@endsection
@section('content')
<div class="min-h-screen p-3 sm:p-6" style="background: linear-gradient(135deg, #f0f4ff 0%, #e8ecf8 100%);">
    <!-- Back Button -->
    <div class="mb-4 print:hidden flex justify-end">
        <a href="{{ route('admin.credit-voucher.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/80 backdrop-blur-sm border border-white/60 text-gray-700 text-sm font-medium rounded-xl shadow-sm hover:bg-white hover:shadow-md hover:border-gray-200 transition-all duration-200 w-full sm:w-auto justify-center">
            <i class="fa fa-arrow-left text-gray-400"></i> Back to Credit Vouchers
        </a>
    </div>

    <!-- Voucher Container -->
    <div id="voucher" class="max-w-5xl mx-auto bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg shadow-indigo-100/50 border border-white/50 p-4 sm:p-6 lg:p-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-gray-200 pb-6 mb-6 gap-4">
            <div class="w-full sm:w-auto">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-md shadow-emerald-200/50 shrink-0">
                        <i class="fa fa-credit-card text-white text-sm"></i>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Credit Voucher</h2>
                </div>
                <p class="text-sm text-gray-500 ml-[52px]">
                    Voucher No:
                    <span class="font-medium text-gray-700">{{ $creditVoucher->credit_voucher ?? 'N/A' }}</span>
                </p>
                <p class="text-sm text-gray-500 ml-[52px]">
                    Payment Date:
                    <span class="font-medium text-gray-700">{{ $creditVoucher->payment_date ? \Carbon\Carbon::parse($creditVoucher->payment_date)->format('d M Y') : 'N/A' }}</span>
                </p>
            </div>

            <div class="w-full sm:w-auto text-left sm:text-right bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl p-4 sm:p-5 border border-indigo-100/60 shadow-sm">
                <div class="flex items-center gap-2 mb-2 justify-start sm:justify-end">
                    <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center shadow-sm shrink-0">
                        <i class="fa fa-user text-white text-[10px]"></i>
                    </div>
                    <h3 class="text-base font-semibold text-indigo-800">Party Information</h3>
                </div>
                <p class="text-base font-semibold text-gray-800">{{ $creditVoucher->party->party_name ?? 'N/A' }}</p>
                <p class="text-sm text-gray-600 mt-1.5"><span class="font-medium text-gray-500">Phone:</span> {{ $creditVoucher->party->phone ?? 'N/A' }}</p>
                <p class="text-sm text-gray-600 mt-0.5"><span class="font-medium text-gray-500">Email:</span> {{ $creditVoucher->party->email ?? 'N/A' }}</p>
                <p class="text-sm text-gray-600 mt-0.5"><span class="font-medium text-gray-500">Address:</span> {{ $creditVoucher->party->address ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Account Info -->
        <div class="bg-gradient-to-r from-teal-50/60 via-emerald-50/40 to-white rounded-xl p-5 mb-6 border border-teal-100/50 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-teal-500 to-emerald-600 flex items-center justify-center shadow-sm shadow-teal-200/50">
                    <i class="fa fa-university text-white text-xs"></i>
                </div>
                <h4 class="font-semibold text-teal-800 text-sm">Receive Account</h4>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm ml-11">
                <div>
                    <p class="text-gray-600 font-medium">{{ $creditVoucher->account->account_name ?? 'N/A' }}</p>
                    <p class="text-gray-500 text-xs mt-0.5">Account No: {{ $creditVoucher->account->account_no ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gradient-to-r from-emerald-600 to-teal-600 text-left text-xs font-semibold text-white uppercase tracking-wider">
                        <th class="px-4 py-3.5">#</th>
                        <th class="px-4 py-3.5">Chart of Account</th>
                        <th class="px-4 py-3.5">Description</th>
                        <th class="px-4 py-3.5 text-right">Received Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($creditVoucher->items as $index => $item)
                    <tr class="hover:bg-gradient-to-r hover:from-emerald-50/40 hover:to-teal-50/40 transition-all duration-200 cursor-default">
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 text-gray-700 font-medium">{{ $item->coa->head_name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $item->description ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-gray-700">
                            ৳ {{ number_format($item->paid_amount ?? 0, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="flex justify-end mt-6">
            <div class="w-full sm:w-72 text-sm bg-gradient-to-br from-teal-50 to-emerald-50 rounded-xl p-4 sm:p-5 border border-teal-100 shadow-sm shadow-teal-100/30">
                <div class="flex items-center justify-between">
                    <span class="font-semibold text-teal-800 flex items-center gap-2">
                        <i class="fa fa-calculator text-teal-500 text-xs"></i> Total Received
                    </span>
                    <span class="font-bold text-lg sm:text-xl text-teal-700">
                        ৳ {{ number_format($creditVoucher->items->sum('paid_amount') ?? 0, 2) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- In Words -->
        <div class="text-sm mt-6 text-center bg-gradient-to-r from-amber-50 via-yellow-50 to-amber-50 rounded-xl p-4 border border-amber-200/60 shadow-sm shadow-amber-100/30">
            <div class="flex items-center justify-center gap-2 mb-1">
                <div class="w-6 h-6 rounded-md bg-gradient-to-br from-amber-500 to-yellow-600 flex items-center justify-center shadow-sm">
                    <i class="fa fa-pen-fancy text-white text-[9px]"></i>
                </div>
                <strong class="text-amber-800 text-sm">In Words (Total Paid) :</strong>
            </div>
            <span class="text-amber-700 font-medium">{{ numberToWords($creditVoucher->items->sum('paid_amount') ?? 0, 2) }}</span>
        </div>

        <!-- Footer -->
        <div class="mt-10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 text-sm text-gray-500 print:hidden border-t border-gray-100 pt-6">
            <p class="text-gray-400 flex items-center gap-1.5">
                <i class="fa fa-clock text-gray-300 text-xs"></i>
                Generated on
                {{ \Carbon\Carbon::now('Asia/Dhaka')->format('d M Y, h:i A') }}
            </p>
            <a href="{{ route('admin.credit-voucher.print', $creditVoucher->id) }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-teal-500 to-emerald-600 text-white font-medium rounded-xl shadow-lg shadow-teal-200/50 hover:from-teal-600 hover:to-emerald-700 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 w-full sm:w-auto justify-center">
                <i class="fa fa-print"></i> Print Voucher
            </a>
        </div>

    </div>
</div>
@endsection
