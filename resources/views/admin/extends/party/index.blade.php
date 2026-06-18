@extends('admin.master')
@section('content')
<div class="page-wrapper min-h-screen p-4 sm:p-6 lg:p-8" style="background: linear-gradient(135deg, #f0f4ff 0%, #e8ecf8 100%);">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-500 to-cyan-600 flex items-center justify-center text-white shadow-lg shadow-sky-200">
                        <i class="fa fa-handshake text-sm"></i>
                    </span>
                    Party Management
                </h3>
                <p class="text-sm text-gray-500 mt-1 ml-13">Manage parties and customers</p>
            </div>
            <a href="{{ route('admin.party.create') }}"
               class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-sm font-medium px-5 py-2.5 rounded-xl shadow-lg shadow-indigo-200 transition-all duration-200 hover:shadow-xl hover:-translate-y-0.5">
                <i class="fa fa-plus"></i>
                Create Party
            </a>
        </div>
        <div class="bg-white/80 rounded-2xl shadow-lg shadow-indigo-100/50 border border-white/50 overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fa fa-list text-sky-500"></i>
                    <span class="font-semibold text-gray-700">All Parties</span>
                </div>
                <span class="text-xs text-gray-400 bg-gray-50 px-3 py-1 rounded-full">DataTable</span>
            </div>
            <div class="p-5 overflow-x-auto">
                <table id="partyTable" class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-indigo-50/50">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Party Id</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Party Name</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Phone</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Address</th>
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
        $('#partyTable').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            autoWidth: false,
            ajax: "{{ route('admin.party.index') }}",
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'party_code', name: 'party_code' },
                { data: 'party_name', name: 'party_name' },
                { data: 'email', name: 'email' },
                { data: 'phone', name: 'phone' },
                { data: 'address', name: 'address' },
                { data: 'action', orderable: false, searchable: false },
            ],
            dom: '<"flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4"lf>rt<"flex flex-col sm:flex-row items-center justify-between gap-3 mt-4"ip>',
            language: {
                search: '',
                searchPlaceholder: 'Search parties...',
                processing: '<div class="flex items-center justify-center py-4"><div class="animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-600"></div></div>'
            }
        });
        $('.dataTables_filter input').addClass('form-input !pl-9 !pr-3 !py-2 !text-sm !rounded-xl !border-gray-200 !w-64 !bg-gray-50 focus:!bg-white !transition-all');
        $('.dataTables_filter').addClass('relative');
        $('.dataTables_filter').prepend('<i class="fa fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm z-10 pointer-events-none"></i>');
        $('#partyTable_length select').addClass('form-input !py-1.5 !px-3 !text-sm !rounded-xl !border-gray-200 !bg-gray-50 focus:!bg-white !transition-all !w-20');
        $('#partyTable_length label').addClass('text-sm text-gray-500 flex items-center gap-2');
    });
    function deleteItem(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This party will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/party/delete/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Content-Type': 'application/json'
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: data.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                            $('#partyTable').DataTable().ajax.reload(null, false);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed!',
                                text: data.message
                            });
                        }
                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Something went wrong. Please try again.'
                        });
                    });
            }
        });
    }
</script>
@endsection
