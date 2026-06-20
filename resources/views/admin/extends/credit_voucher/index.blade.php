@extends("admin.master")
@section("content")
<div class="page-wrapper p-4 sm:p-6 lg:p-8" style="background: linear-gradient(135deg, #f0f4ff 0%, #e8ecf8 100%);">
    @include('admin.include.message')
    <div class="content container mx-auto px-4 overflow-y-auto py-6">
        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex flex-col md:flex-row items-center justify-between mt-5">
                <!-- Title -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-500 to-emerald-600 flex items-center justify-center shadow-md">
                        <i class="fa fa-credit-card text-white text-sm"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Credit Voucher List</h3>
                </div>
                <!-- Create Button -->
                <a href="{{ route('admin.credit-voucher.create') }}"
                   class="mt-3 md:mt-0 inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white text-sm font-medium px-5 py-2.5 rounded-xl shadow-lg shadow-indigo-200/50 transition-all duration-200 hover:shadow-xl hover:shadow-indigo-300/50 hover:-translate-y-0.5">
                    <i class="fa fa-plus"></i>
                    Create Credit Voucher
                </a>
            </div>
        </div>
        <!-- Filter Section -->
        <div class="bg-white/70 rounded-2xl p-5 mb-6 grid grid-cols-1 md:grid-cols-6 gap-4 border border-white/50 shadow-sm">
            <input type="text" id="global_search" placeholder="Search Credit Vouchers..." class="form-input !rounded-xl !border-gray-200 !bg-gray-50 px-4 py-2.5 text-sm focus:!ring-2 focus:!ring-teal-400 focus:!border-transparent w-full">
            <input type="date" id="start_date" class="form-input !rounded-xl !border-gray-200 !bg-gray-50 px-4 py-2.5 text-sm focus:!ring-2 focus:!ring-teal-400 focus:!border-transparent w-full">
            <input type="date" id="end_date" class="form-input !rounded-xl !border-gray-200 !bg-gray-50 px-4 py-2.5 text-sm focus:!ring-2 focus:!ring-teal-400 focus:!border-transparent w-full">
            <button id="date_filter" class="bg-gradient-to-r from-teal-500 to-emerald-600 hover:from-teal-600 hover:to-emerald-700 text-white px-5 py-2.5 rounded-xl shadow-md shadow-teal-200/50 text-sm font-medium transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5">Filter by Date</button>
            <button id="clear_filter" class="bg-gradient-to-r from-gray-400 to-gray-500 hover:from-gray-500 hover:to-gray-600 text-white px-5 py-2.5 rounded-xl shadow-md text-sm font-medium transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5">Clear</button>
        </div>
        <!-- DataTable -->
        <div class="bg-white/80 rounded-2xl shadow-lg shadow-indigo-100/50 border border-white/50 p-6 overflow-x-auto">
            <table id="voucherTable" class="min-w-full divide-y divide-gray-200 text-sm">
                <thead>
                    <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Voucher No</th>
                        <th class="px-4 py-3">Payment Date</th>
                        <th class="px-4 py-3">Party</th>
                        <th class="px-4 py-3">Account</th>
                        <th class="px-4 py-3">Amount</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200"></tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
$(document).ready(function() {
    let table = $('#voucherTable').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        autoWidth: false,
        ajax: {
            url: '{{ route("admin.credit-voucher.index") }}',
            data: function(d) {
                d.custom_search = $('#global_search').val();
                d.start_date = $('#start_date').val();
                d.end_date = $('#end_date').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'credit_voucher', name: 'credit_voucher' },
            { data: 'payment_date', name: 'payment_date' },
            { data: 'party', name: 'party.party_name', orderable: true, searchable: true },
            { data: 'account', name: 'account.account_name', orderable: true, searchable: true },
            { data: 'total_amount', name: 'total_amount' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[1, 'desc']]
    });

    let searchTimeout;
    $('#global_search').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() { table.draw(); }, 500);
    });

    $('#date_filter').on('click', function() { table.draw(); });

    $('#clear_filter').on('click', function() {
        $('#global_search').val('');
        $('#start_date').val('');
        $('#end_date').val('');
        table.draw();
    });
});
</script>
@endsection
