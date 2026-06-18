@extends('admin.master')
@section('content')
<div class="min-h-screen p-4 sm:p-6 lg:p-8" style="background: linear-gradient(135deg, #f0f4ff 0%, #e8ecf8 100%);">
    <div class="max-w-7xl mx-auto">
        <!-- Page Header -->
        @include('admin.include.message')
        <div class="mb-6 mt-2 pt-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-md shrink-0">
                    <i class="fa fa-plus-circle text-white text-sm"></i>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-800">Create Credit Voucher</h3>
            </div>
            <a href="{{ route('admin.credit-voucher.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-white border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 hover:shadow-md transition-all duration-200 w-full sm:w-auto justify-center">
                <i class="fa fa-reply"></i> Credit Voucher List
            </a>
        </div>
        {{-- Form Card --}}
        <div class="bg-white/80 rounded-2xl shadow-lg shadow-indigo-100/50 border border-white/50 p-8">
            <form action="{{ route('admin.credit-voucher.create') }}" method="POST" id="credit-voucher-form">
                @csrf
                {{-- MASTER DATA --}}
                <div class="bg-gradient-to-r from-gray-50/50 to-white rounded-xl px-5 py-3 mb-6 border border-gray-100">
                    <h4 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Master Information</h4>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 border-b border-gray-100 pb-6 mb-6">
                    <div class="flex flex-col">
                        <label class="mb-2 font-semibold text-gray-700 text-sm">Receive from (Party) <span class="text-red-500">*</span></label>
                        <select id="party_id" name="party_id" class="form-input !rounded-xl !border-gray-200 !bg-gray-50 px-4 py-2.5 text-sm focus:!ring-2 focus:!ring-teal-400 focus:!border-transparent" required></select>
                    </div>
                    <div class="flex flex-col">
                        <label class="mb-2 font-semibold text-gray-700 text-sm">Payment Date <span class="text-red-500">*</span></label>
                        <input type="date" id="payment_date" name="payment_date" value="{{ date('Y-m-d') }}" class="form-input !rounded-xl !border-gray-200 !bg-gray-50 px-4 py-2.5 text-sm focus:!ring-2 focus:!ring-teal-400 focus:!border-transparent" required>
                    </div>
                    <div class="flex flex-col">
                        <label class="mb-2 font-semibold text-gray-700 text-sm">Select Account <span class="text-red-500">*</span></label>
                        <select id="account_id" name="account_id" class="form-input !rounded-xl !border-gray-200 !bg-gray-50 px-4 py-2.5 text-sm focus:!ring-2 focus:!ring-teal-400 focus:!border-transparent" required></select>
                    </div>
                </div>
                {{-- DETAIL ITEMS --}}
                <div class="bg-gradient-to-r from-gray-50/50 to-white rounded-xl px-5 py-3 mb-6 border border-gray-100">
                    <h4 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Detail Items</h4>
                </div>
                <div class="mb-4">
                    <div class="grid grid-cols-12 gap-4 font-semibold text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200 pb-3 mb-3">
                        <div class="col-span-4">Select Chart of Account <span class="text-red-500">*</span></div>
                        <div class="col-span-4">Description</div>
                        <div class="col-span-3">Received Amount <span class="text-red-500">*</span></div>
                        <div class="col-span-1 text-right">
                            <button type="button" id="add-detail-row" class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-2.5 py-1.5 rounded-xl text-sm shadow-md shadow-emerald-200/50 transition-all duration-200 hover:shadow-lg">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div id="voucher-details"></div>
                </div>
                {{-- TOTAL --}}
                <div class="bg-gradient-to-r from-gray-50/50 to-white rounded-xl p-4 mt-6 border border-gray-100">
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-8 text-right font-bold text-lg text-gray-700 flex items-center justify-end">Total:</div>
                        <div class="col-span-3">
                            <div class="flex items-center border border-gray-200 rounded-xl p-2 bg-white">
                                <input type="text" id="total_amount_display" class="w-full text-right font-bold text-lg border-none focus:ring-0 bg-transparent" value="0.00" readonly>
                                <span class="ml-2 font-bold text-lg text-gray-600">TK</span>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- SUBMIT --}}
                <div class="mt-8 border-t border-gray-100 pt-6 text-right flex flex-col sm:flex-row justify-end gap-3">
                    <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-semibold px-6 py-2.5 rounded-xl shadow-lg shadow-emerald-200/50 transition-all duration-200 hover:shadow-xl hover:-translate-y-0.5 w-full sm:w-auto justify-center">
                        <i class="fas fa-plus-circle"></i> Create New
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
.select2-selection--single { height: 44px !important; border: 1px solid #e5e7eb !important; border-radius: 0.75rem !important; background-color: #f9fafb !important; }
.select2-selection__rendered { line-height: 44px !important; }
.select2-container--default .select2-selection--single .select2-selection__arrow { height: 44px !important; }
</style>
<script>
$(document).ready(function () {
    let rowCount = 0;
    const partyUrl   = "{{ route('admin.credit-voucher.get-parties') }}";
    const accountUrl = "{{ route('admin.credit-voucher.get-bank-accounts') }}";
    const coaUrl     = "{{ route('admin.credit-voucher.get-income-coa') }}";

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
<div class="col-span-4"><select name="chart_of_account_id[]" class="coa-select form-input !rounded-xl !border-gray-200 !bg-gray-50 w-full" required></select></div>
<div class="col-span-4"><input type="text" name="description[]" class="form-input !rounded-xl !border-gray-200 !bg-gray-50 px-4 py-2.5 text-sm w-full"></div>
<div class="col-span-3"><input type="number" step="any" min="0.01" name="paid_amount[]" class="paid-amount form-input !rounded-xl !border-gray-200 !bg-gray-50 px-4 py-2.5 text-sm w-full text-right" required></div>
<div class="col-span-1 text-end flex items-center justify-end">${rowId > 0 ? `<button type="button" class="remove-row bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white px-2.5 py-1.5 rounded-xl text-sm shadow-md shadow-red-200/50 transition-all duration-200 hover:shadow-lg"><i class="fa fa-times"></i></button>` : ''}</div>
</div>`;

        $('#voucher-details').append(row);

        $('.coa-select').last().select2({
            placeholder: 'Select Income Account', minimumInputLength: 0,
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
