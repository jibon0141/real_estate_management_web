@extends('admin.master')
@section('content')
<div class="min-h-screen p-4 sm:p-6 lg:p-8" style="background: linear-gradient(135deg, #f0f4ff 0%, #e8ecf8 100%);">
    <div class="max-w-7xl mx-auto">
        <!-- Page Header -->
        @include('admin.include.message')
        <div class="mb-6 mt-2 pt-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-md shadow-emerald-200 shrink-0">
                    <i class="fa fa-plus-circle text-white text-lg"></i>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-800">Create Debit Voucher</h3>
            </div>
            <a href="{{ route('admin.debit-voucher.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 w-full sm:w-auto justify-center">
                <i class="fa fa-reply"></i> Debit Voucher List
            </a>
        </div>
        {{-- Form Card --}}
        <div class="bg-white/80 rounded-2xl shadow-lg shadow-indigo-100/50 border border-white/50 p-8">
            <form action="{{ route('admin.debit-voucher.create') }}" method="POST" id="debit-voucher-form">
                @csrf
                {{-- Form Header Bar --}}
                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl px-5 py-3 mb-6 border border-emerald-100">
                    <h4 class="text-sm font-semibold text-emerald-700"><i class="fa fa-info-circle mr-2"></i>Master Information</h4>
                </div>
                {{-- MASTER DATA --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 border-b pb-6 mb-6">
                    <div class="flex flex-col">
                        <label class="mb-2 font-semibold text-gray-700">Pay to (Party) <span class="text-red-500">*</span></label>
                        <select id="party_id" name="party_id" class="form-input !rounded-xl !border-gray-200 !bg-gray-50 px-4 py-3 focus:!ring-2 focus:!ring-emerald-300" required></select>
                    </div>
                    <div class="flex flex-col">
                        <label class="mb-2 font-semibold text-gray-700">Payment Date <span class="text-red-500">*</span></label>
                        <input type="date" id="payment_date" name="payment_date" value="{{ date('Y-m-d') }}" class="form-input !rounded-xl !border-gray-200 !bg-gray-50 px-4 py-3 focus:!ring-2 focus:!ring-emerald-300" required>
                    </div>
                    <div class="flex flex-col">
                        <label class="mb-2 font-semibold text-gray-700">Select Account <span class="text-red-500">*</span></label>
                        <select id="account_id" name="account_id" class="form-input !rounded-xl !border-gray-200 !bg-gray-50 px-4 py-3 focus:!ring-2 focus:!ring-emerald-300" required></select>
                    </div>
                </div>
                {{-- Detail Header Bar --}}
                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl px-5 py-3 mb-6 border border-emerald-100">
                    <h4 class="text-sm font-semibold text-emerald-700"><i class="fa fa-list mr-2"></i>Voucher Details</h4>
                </div>
                {{-- DETAIL ITEMS --}}
                <div class="mb-4">
                    <div class="grid grid-cols-12 gap-4 font-semibold text-gray-600 border-b pb-2 mb-2">
                        <div class="col-span-4">Select Chart of Account <span class="text-red-500">*</span></div>
                        <div class="col-span-4">Description</div>
                        <div class="col-span-3">Paid Amount <span class="text-red-500">*</span></div>
                        <div class="col-span-1 text-right">
                            <button type="button" id="add-detail-row" class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-3 py-1.5 rounded-lg text-sm shadow-md shadow-emerald-200 transition-all duration-200">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div id="voucher-details"></div>
                </div>
                {{-- TOTAL --}}
                <div class="grid grid-cols-12 gap-4 mt-4">
                    <div class="col-span-8 text-right font-bold text-lg">Total:</div>
                    <div class="col-span-3">
                        <div class="flex items-center rounded-xl border border-gray-200 p-2 bg-gray-50/50">
                            <input type="text" id="total_amount_display" class="w-full text-right font-bold text-lg border-none focus:ring-0 bg-transparent" value="0.00" readonly>
                            <span class="ml-2 font-bold text-lg text-gray-600">TK</span>
                        </div>
                    </div>
                </div>
                {{-- SUBMIT --}}
                <div class="mt-6 border-t pt-4 text-right flex flex-col sm:flex-row justify-end gap-3">
                    <button type="submit" class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-semibold px-8 py-3 rounded-xl shadow-lg shadow-emerald-200 transition-all duration-200 w-full sm:w-auto">
                        <i class="fas fa-plus-circle me-2"></i> Create New
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
<style>
.select2-container { width: 100% !important; }
.select2-selection--single { height: 44px !important; border: 1px solid #d1d5db !important; border-radius: 0.375rem !important; }
.select2-selection__rendered { line-height: 44px !important; }
</style>
<script>
$(document).ready(function () {
    let rowCount = 0;
    const partyUrl   = "{{ route('admin.debit-voucher.get-parties') }}";
    const accountUrl = "{{ route('admin.debit-voucher.get-bank-accounts') }}";
    const coaUrl     = "{{ route('admin.debit-voucher.get-expense-coa') }}";

    $('#party_id').select2({
        placeholder: 'Select Party', allowClear: true, minimumInputLength: 0,
        ajax: {
            url: partyUrl, dataType: 'json', delay: 250,
            data: params => ({ q: params.term || '' }),
            processResults: data => ({ results: data.results })
        }
    });

    $('#account_id').select2({
        placeholder: 'Select Account', allowClear: true, minimumInputLength: 0,
        ajax: {
            url: accountUrl, dataType: 'json', delay: 250,
            data: params => ({ q: params.term || '' }),
            processResults: data => ({
                results: data.results.map(item => ({
                    id: item.id,
                    text: `${item.text} (Balance: ${item.balance})`
                }))
            })
        }
    });

    function addDetailRow() {
        const rowId = rowCount++;
        const row = `<div class="grid grid-cols-12 gap-4 mb-3 detail-row">
<div class="col-span-4"><select name="chart_of_account_id[]" class="coa-select border rounded p-2 w-full" required></select></div>
<div class="col-span-4"><input type="text" name="description[]" class="form-input !rounded-xl !border-gray-200 !bg-gray-50 px-4 py-2.5 w-full"></div>
<div class="col-span-3"><input type="number" step="any" min="0.01" name="paid_amount[]" class="paid-amount form-input !rounded-xl !border-gray-200 !bg-gray-50 px-4 py-2.5 w-full text-right" required></div>
<div class="col-span-1 text-end">${rowId > 0 ? `<button type="button" class="remove-row bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white px-3 py-1.5 rounded-lg text-sm shadow-md shadow-red-200 transition-all duration-200"><i class="fa fa-times"></i></button>` : ''}</div>
</div>`;

        $('#voucher-details').append(row);

        $('.coa-select').last().select2({
            placeholder: 'Select Expense', minimumInputLength: 0,
            ajax: {
                url: coaUrl, dataType: 'json', delay: 250,
                data: params => ({ q: params.term || '' }),
                processResults: data => ({ results: data.results })
            }
        });
    }

    addDetailRow();
    $('#add-detail-row').on('click', addDetailRow);
    $('#voucher-details').on('click', '.remove-row', function () {
        $(this).closest('.detail-row').remove();
        calculateTotal();
    });
    $('#voucher-details').on('input', '.paid-amount', calculateTotal);

    function calculateTotal() {
        let total = 0;
        $('.paid-amount').each(function () {
            total += parseFloat($(this).val()) || 0;
        });
        $('#total_amount_display').val(total.toFixed(2));
    }
});
</script>
@endsection
