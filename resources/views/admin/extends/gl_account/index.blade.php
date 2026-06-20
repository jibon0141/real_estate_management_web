@extends('admin.master')
@section('app_styles')
<style>
    #glAccountTable tbody tr:nth-child(even) { background-color: #f8fafc; }
    #glAccountTable tbody tr:hover { background-color: #eef2ff; }
</style>
@endsection
@section('content')
<div class="page-wrapper min-h-screen p-4 sm:p-6 lg:p-8" style="background: linear-gradient(135deg, #f0f4ff 0%, #e8ecf8 100%);">
    <div class="max-w-7xl mx-auto">
        @include('admin.include.message')
        <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-violet-200">
                        <i class="fa fa-book text-sm"></i>
                    </span>
                    GL Account List
                </h3>
                <p class="text-sm text-gray-500 mt-1 ml-13">Manage general ledger accounts</p>
            </div>
        </div>
        <div class="bg-white/80 rounded-2xl shadow-lg shadow-indigo-100/50 border border-white/50 overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fa fa-list text-violet-500"></i>
                    <span class="font-semibold text-gray-700">All GL Accounts</span>
                </div>
                <span class="text-xs text-gray-400 bg-gray-50 px-3 py-1 rounded-full">DataTable</span>
            </div>
            <div class="p-5 overflow-x-auto">
                <table id="glAccountTable" class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-indigo-50/50">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Account Name</th>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        $('#glAccountTable').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            autoWidth: false,
            ajax: "{{ route('admin.gl-account.index') }}",
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'account_name', name: 'account_name' },
                { data: 'action', orderable: false, searchable: false },
            ],
            dom: '<"flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4"lf>rt<"flex flex-col sm:flex-row items-center justify-between gap-3 mt-4"ip>',
            language: {
                search: '',
                searchPlaceholder: 'Search GL accounts...',
                processing: '<div class="flex items-center justify-center py-4"><div class="animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-600"></div></div>'
            }
        });
        $('.dataTables_filter input').addClass('form-input !pl-9 !pr-3 !py-2 !text-sm !rounded-xl !border-gray-200 !w-64 !bg-gray-50 focus:!bg-white !transition-all');
        $('.dataTables_filter').addClass('relative');
        $('.dataTables_filter').prepend('<i class="fa fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm z-10 pointer-events-none"></i>');
        $('#glAccountTable_length select').addClass('form-input !py-1.5 !px-3 !text-sm !rounded-xl !border-gray-200 !bg-gray-50 focus:!bg-white !transition-all !w-20');
        $('#glAccountTable_length label').addClass('text-sm text-gray-500 flex items-center gap-2');
    });
</script>
@endsection
