@extends('admin.master')
@section('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #voucher, #voucher * {
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
<div class="min-h-screen p-4 sm:p-6 lg:p-8" style="background: linear-gradient(135deg, #f0f4ff 0%, #e8ecf8 100%);">

    <!-- Back Button (hidden on print) -->
    <div class="mb-6 print:hidden flex justify-end">
        <a href="{{ route('admin.debit-voucher.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-white/60 bg-white/70 px-5 py-2.5 text-sm font-medium text-gray-700 shadow-sm backdrop-blur-sm hover:bg-white hover:border-gray-300 transition-all duration-200 w-full sm:w-auto justify-center">
            <i class="fa fa-arrow-left text-xs"></i> Back to Debit Vouchers
        </a>
    </div>

    <!-- Voucher Container -->
    <div id="voucher" class="max-w-5xl mx-auto bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg shadow-indigo-100/50 border border-white/50 p-6 sm:p-8 lg:p-10">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-gray-200/70 pb-5 mb-6 gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-500 to-rose-600 flex items-center justify-center shadow-md shadow-red-200/60">
                        <i class="fa fa-file-invoice text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Debit Voucher</h2>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Payment Voucher</p>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mt-2">
                    Voucher No: <span class="font-semibold text-gray-700">{{ $debitVoucher->debit_voucher ?? 'N/A' }}</span>
                </p>
                <p class="text-sm text-gray-500">
                    Payment Date: <span class="font-semibold text-gray-700">{{ $debitVoucher->payment_date ? \Carbon\Carbon::parse($debitVoucher->payment_date)->format('d M Y') : 'N/A' }}</span>
                </p>
            </div>
            <div class="text-left sm:text-right w-full sm:w-auto">
                <div class="inline-block bg-gradient-to-br from-red-50 to-rose-50/60 rounded-xl px-5 py-3 border border-red-100/60 shadow-sm">
                    <div class="flex items-center gap-2 mb-2 justify-start sm:justify-end">
                        <div class="w-6 h-6 rounded-md bg-gradient-to-br from-red-500 to-rose-600 flex items-center justify-center shadow-sm">
                            <i class="fa fa-user text-white text-[10px]"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-red-800 uppercase tracking-wider">Party</h3>
                    </div>
                    <p class="text-base font-semibold text-gray-800">{{ $debitVoucher->party->party_name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600 mt-1"><span class="font-medium text-gray-500">Phone:</span> {{ $debitVoucher->party->phone ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600 mt-0.5"><span class="font-medium text-gray-500">Email:</span> {{ $debitVoucher->party->email ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600 mt-0.5"><span class="font-medium text-gray-500">Address:</span> {{ $debitVoucher->party->address ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Account Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-7 text-sm">
            <div class="bg-gradient-to-r from-slate-50 to-gray-100/40 rounded-xl px-5 py-4 border border-gray-200/60 shadow-sm">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-6 h-6 rounded-md bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center shadow-sm">
                        <i class="fa fa-university text-white text-[10px]"></i>
                    </div>
                    <h4 class="font-semibold text-gray-700">Payment Account</h4>
                </div>
                <p class="text-gray-600 ml-8">{{ $debitVoucher->account->account_name ?? 'N/A' }}</p>
                <p class="text-gray-600 ml-8">Account No: {{ $debitVoucher->account->account_no ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Items Table -->
        <div class="overflow-x-auto rounded-xl border border-gray-200/60 shadow-sm">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gradient-to-r from-red-500 to-rose-600 text-white">
                        <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">#</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Chart of Account</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Description</th>
                        <th class="px-4 py-3.5 text-right text-xs font-semibold uppercase tracking-wider">Paid Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white/60">
                    @foreach($debitVoucher->items as $index => $item)
                    <tr class="hover:bg-gradient-to-r hover:from-red-50/40 hover:to-rose-50/40 transition-all duration-200 cursor-default">
                        <td class="px-4 py-3.5 font-medium text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-4 py-3.5 text-gray-800">{{ $item->coa->head_name ?? 'N/A' }}</td>
                        <td class="px-4 py-3.5 text-gray-600">{{ $item->description ?? 'N/A' }}</td>
                        <td class="px-4 py-3.5 text-right font-semibold text-gray-800">৳ {{ number_format($item->paid_amount ?? 0, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="flex justify-end mt-6">
            <div class="w-full sm:w-72 text-sm bg-gradient-to-r from-red-50/60 to-rose-50/60 rounded-xl px-5 py-4 border border-red-100/60 shadow-sm">
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-gray-700">Total Paid</span>
                    <span class="text-lg font-bold bg-gradient-to-r from-red-600 to-rose-600 bg-clip-text text-transparent">৳ {{ number_format($debitVoucher->items->sum('paid_amount') ?? 0, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- In Words -->
        <div class="mt-6 bg-gradient-to-r from-amber-50 to-yellow-50/80 rounded-xl px-5 py-4 border border-amber-200/60 shadow-sm">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-5 h-5 rounded-full bg-gradient-to-br from-amber-400 to-yellow-500 flex items-center justify-center">
                    <i class="fa fa-pen-fancy text-white text-[9px]"></i>
                </div>
                <strong class="text-amber-800 text-sm">In Words (Total Paid) :</strong>
            </div>
            <span class="text-amber-700 text-sm font-medium ml-7">{{ numberToWords($debitVoucher->items->sum('paid_amount') ?? 0, 2) }}</span>
        </div>

        <!-- Footer (hidden on print) -->
        <div class="mt-10 flex flex-col sm:flex-row justify-between items-center gap-4 text-sm text-gray-500 print:hidden">
            <p class="text-xs text-gray-400">
                <i class="fa fa-clock mr-1"></i> Generated on {{ \Carbon\Carbon::now('Asia/Dhaka')->format('d M Y, h:i A') }}
            </p>
            <a href="{{ route('admin.debit-voucher.print', $debitVoucher->id) }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-teal-500 to-emerald-600 hover:from-teal-600 hover:to-emerald-700 text-white font-medium rounded-xl shadow-lg shadow-teal-200/60 hover:shadow-xl hover:shadow-teal-200/80 transition-all duration-200 active:scale-[0.97] w-full sm:w-auto justify-center">
                <i class="fa fa-print"></i> Print Voucher
            </a>
        </div>

    </div>

</div>
@endsection
