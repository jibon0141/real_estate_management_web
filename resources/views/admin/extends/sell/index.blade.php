@extends('admin.master')
@section('content')
<div class="min-h-screen p-4 sm:p-6 lg:p-8" style="background: linear-gradient(135deg, #f0f4ff 0%, #e8ecf8 100%);">
    <div class="max-w-7xl mx-auto">
        @include('admin.include.message')
        <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                        <i class="fa fa-shopping-cart text-sm"></i>
                    </span>
                    Sell Package
                </h3>
                <p class="text-sm text-gray-500 mt-1 ml-13">Select a package to sell</p>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg shadow-indigo-100/50 border border-white/50 overflow-hidden mb-6">
            <div class="p-5 border-b border-gray-100 flex items-center gap-2">
                <i class="fa fa-filter text-emerald-500"></i>
                <span class="font-semibold text-gray-700">Filter by Project</span>
            </div>
            <div class="p-5">
                <form method="GET" action="{{ route('admin.sell.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
                    <div class="flex-1 w-full">
                        <select name="project_id" id="project_id"
                                class="form-input !rounded-xl !border-gray-200 !bg-gray-50 focus:!bg-white focus:!border-indigo-400 focus:!ring focus:!ring-indigo-200/50 !transition-all"
                                onchange="this.form.submit()">
                            <option value="">All Projects</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if(request('project_id'))
                        <a href="{{ route('admin.sell.index') }}"
                           class="px-4 py-2.5 text-sm font-medium text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-xl border border-gray-200 transition-all whitespace-nowrap">
                            <i class="fa fa-times mr-1"></i>
                            Clear
                        </a>
                    @endif
                </form>
            </div>
        </div>

        @if($packages->isEmpty())
            <div class="bg-white/80 rounded-2xl shadow-lg shadow-indigo-100/50 border border-white/50 overflow-hidden p-12 text-center">
                <div class="text-gray-400 text-5xl mb-4">
                    <i class="fa fa-cube"></i>
                </div>
                <h4 class="text-lg font-semibold text-gray-600 mb-1">No Packages Found</h4>
                <p class="text-sm text-gray-400">No packages available for the selected project.</p>
            </div>
        @else
            <div class="bg-white/80 rounded-2xl shadow-lg shadow-indigo-100/50 border border-white/50 overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa fa-cube text-emerald-500"></i>
                    <span class="font-semibold text-gray-700">Available Packages</span>
                    <span class="ml-auto text-xs text-gray-400 bg-gray-50 px-3 py-1 rounded-full">{{ $packages->total() }} packages</span>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($packages as $package)
                            @php $project = $package->project; @endphp
                            <div class="group bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg hover:shadow-emerald-200/40 hover:-translate-y-1 transition-all duration-300">
                                <div class="h-32 bg-gradient-to-br from-emerald-500 via-teal-500 to-cyan-600 relative overflow-hidden">
                                    <div class="absolute inset-0 opacity-20">
                                        <div class="absolute -top-6 -right-6 w-24 h-24 bg-white rounded-full"></div>
                                        <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-white rounded-full"></div>
                                    </div>
                                    <div class="absolute top-4 left-5">
                                        <span class="text-white text-xs font-semibold bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full">{{ $project->name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="absolute bottom-4 left-5 right-5">
                                        <h3 class="text-white font-bold text-lg truncate">{{ $package->package_name }}</h3>
                                        <span class="text-white/80 text-xs">{{ $package->package_no }}</span>
                                    </div>
                                </div>
                                <div class="p-5 space-y-3">
                                    <div class="grid grid-cols-2 gap-3 text-sm">
                                        <div>
                                            <span class="text-gray-400 text-xs">Price</span>
                                            <p class="font-semibold text-gray-800">৳{{ number_format($package->package_price, 2) }}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 text-xs">Return</span>
                                            <p class="font-semibold text-gray-800">৳{{ number_format($package->return_amount, 2) }}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 text-xs">Booking Money</span>
                                            <p class="font-semibold text-gray-800">৳{{ number_format($package->booking_money, 2) }}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 text-xs">Return Time</span>
                                            <p class="font-semibold text-gray-800">{{ $package->return_time }}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 text-xs">Land Share</span>
                                            <p class="font-semibold text-gray-800">{{ $package->share_count }}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 text-xs">Allotted Share</span>
                                            <p class="font-semibold text-gray-800">{{ number_format($package->allotted_share, 2) }}</p>
                                        </div>
                                    </div>
                                    @if($package->extra_benefit)
                                        <div class="pt-2 border-t border-gray-100">
                                            <span class="text-gray-400 text-xs">Extra Benefit</span>
                                            <p class="font-semibold text-emerald-600">{{ $package->extra_benefit }}</p>
                                        </div>
                                    @endif
                                    <div class="pt-3">
                                        <a href="{{ route('admin.sell.options', $package->id) }}" class="block w-full py-2.5 text-sm font-medium text-white bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 rounded-xl shadow-lg shadow-emerald-200 transition-all duration-200 hover:shadow-xl hover:-translate-y-0.5 text-center">
                                            <i class="fa fa-shopping-cart mr-1.5"></i>
                                            Sell This Package
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 pt-5 border-t border-gray-100">
                        {{ $packages->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        $('#project_id').select2({
            placeholder: 'All Projects',
            allowClear: true
        });
    });
</script>
@endsection
