@extends('admin.master')
@section('app_styles')
<style>
    #sellTable tbody tr:nth-child(even) { background-color: #f8fafc; }
    #sellTable tbody tr:hover { background-color: #ecfdf5; }
</style>
@endsection
@section('content')
<div class="page-wrapper min-h-screen p-4 sm:p-6 lg:p-8" style="background: linear-gradient(135deg, #f0f4ff 0%, #e8ecf8 100%);">
    <div class="max-w-7xl mx-auto">
        @include('admin.include.message')
        <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                        <i class="fa fa-shopping-bag text-sm"></i>
                    </span>
                    All Sales
                </h3>
                <p class="text-sm text-gray-500 mt-1 ml-13">View all completed sales</p>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg shadow-indigo-100/50 border border-white/50 overflow-hidden mb-6">
            <div class="p-5 border-b border-gray-100 flex items-center gap-2">
                <i class="fa fa-filter text-emerald-500"></i>
                <span class="font-semibold text-gray-700">Filters</span>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Sale Type</label>
                        <select id="sell_type" class="form-input !rounded-xl !border-gray-200 !bg-gray-50 w-full">
                            <option value="">All Types</option>
                            <option value="on_cash">Cash</option>
                            <option value="on_installment">Installment</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Start Date</label>
                        <input type="date" id="start_date" class="form-input !rounded-xl !border-gray-200 !bg-gray-50 w-full">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">End Date</label>
                        <input type="date" id="end_date" class="form-input !rounded-xl !border-gray-200 !bg-gray-50 w-full">
                    </div>
                    <div class="flex items-end gap-2">
                        <button id="filterBtn" class="px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white text-sm font-medium rounded-xl shadow-md shadow-emerald-200 transition-all duration-200 flex-1">
                            <i class="fa fa-search mr-1"></i> Filter
                        </button>
                        <button id="clearBtn" class="px-5 py-2.5 bg-gradient-to-r from-gray-400 to-gray-500 hover:from-gray-500 hover:to-gray-600 text-white text-sm font-medium rounded-xl shadow-md shadow-gray-200 transition-all duration-200">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg shadow-indigo-100/50 border border-white/50 overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fa fa-list text-emerald-500"></i>
                    <span class="font-semibold text-gray-700">Sales List</span>
                </div>
                <span class="text-xs text-gray-400 bg-gray-50 px-3 py-1 rounded-full">DataTable</span>
            </div>
            <div class="p-5 overflow-x-auto">
                <table id="sellTable" class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-emerald-50/50">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Voucher No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Package</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Project</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Paid</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        var table = $('#sellTable').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            autoWidth: false,
            ajax: {
                url: "{{ route('admin.sell.all') }}",
                data: function (d) {
                    d.sell_type = $('#sell_type').val();
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'sell_voucher', name: 'sell_voucher' },
                { data: 'sell_date', name: 'sell_date' },
                { data: 'sell_type', name: 'sell_type', orderable: true, searchable: true },
                { data: 'customer', name: 'user.name' },
                { data: 'package', name: 'sellInfo.package.package_name' },
                { data: 'project', name: 'sellInfo.project.name' },
                { data: 'total_amount', name: 'total_amount' },
                { data: 'paid_amount', name: 'paid_amount' },
                { data: 'action', orderable: false, searchable: false },
            ],
            order: [[1, 'desc']],
            dom: '<"flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4"lf>rt<"flex flex-col sm:flex-row items-center justify-between gap-3 mt-4"ip>',
            language: {
                search: '',
                searchPlaceholder: 'Search sales...',
                processing: '<div class="flex items-center justify-center py-4"><div class="animate-spin rounded-full h-6 w-6 border-b-2 border-emerald-600"></div></div>'
            }
        });

        $('#filterBtn').on('click', function () { table.draw(); });
        $('#clearBtn').on('click', function () {
            $('#sell_type').val('');
            $('#start_date').val('');
            $('#end_date').val('');
            table.draw();
        });

        $('.dataTables_filter input').addClass('form-input !pl-9 !pr-3 !py-2 !text-sm !rounded-xl !border-gray-200 !w-64 !bg-gray-50 focus:!bg-white !transition-all');
        $('.dataTables_filter').addClass('relative');
        $('.dataTables_filter').prepend('<i class="fa fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm z-10 pointer-events-none"></i>');
        $('#sellTable_length select').addClass('form-input !py-1.5 !px-3 !text-sm !rounded-xl !border-gray-200 !bg-gray-50 focus:!bg-white !transition-all !w-20');
        $('#sellTable_length label').addClass('text-sm text-gray-500 flex items-center gap-2');
    });
</script>
@endsection
