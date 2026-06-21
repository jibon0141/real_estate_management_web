@extends('admin.master')
@section('content')
<div class="min-h-screen p-4 sm:p-6 lg:p-8" style="background: linear-gradient(135deg, #f0f4ff 0%, #e8ecf8 100%);">
    <div class="max-w-5xl mx-auto">
        @include('admin.include.message')
        <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white shadow-lg shadow-amber-200">
                        <i class="fa fa-eye text-sm"></i>
                    </span>
                    Package Details
                </h3>
                <p class="text-sm text-gray-500 mt-1 ml-13">{{ $package->package_no }} &mdash; {{ $package->package_name }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.package.edit', $package->id) }}"
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl shadow-lg shadow-blue-200 transition-all duration-200 hover:shadow-xl hover:-translate-y-0.5">
                    <i class="fa fa-edit"></i>
                    Edit
                </a>
                <a href="{{ route('admin.package.index') }}"
                   class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2.5 rounded-xl border border-gray-200 shadow-sm transition-all duration-200 hover:shadow-md">
                    <i class="fa fa-reply"></i>
                    Back
                </a>
            </div>
        </div>

        @php $project = $package->project; @endphp

        <div class="bg-white/80 rounded-2xl shadow-lg shadow-indigo-100/50 border border-white/50 overflow-hidden mb-6">
            <div class="relative h-72 sm:h-96 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 overflow-hidden">
                @if ($project && $project->image)
                    <img src="{{ asset('image/project/' . $project->image) }}" alt="{{ $project->name }}"
                         class="w-full h-full object-cover opacity-40">
                @endif
                <div class="absolute inset-0 flex items-center justify-center text-white p-6">
                    <div class="text-4xl sm:text-5xl font-bold drop-shadow-lg">{{ $project->name ?? 'N/A' }}</div>
                </div>
                <div class="absolute top-4 right-4">
                    @if ($package->status == 1)
                        <span class="px-4 py-1.5 text-xs font-semibold text-green-900 bg-green-300/90 backdrop-blur-sm rounded-full shadow">Active</span>
                    @else
                        <span class="px-4 py-1.5 text-xs font-semibold text-red-900 bg-red-300/90 backdrop-blur-sm rounded-full shadow">Inactive</span>
                    @endif
                </div>
            </div>

            @if ($project && $project->description)
                <div class="px-6 sm:px-8 -mt-8 relative z-10">
                    <div class="bg-white/90 backdrop-blur-sm rounded-xl p-5 border border-white/60 shadow-lg shadow-indigo-100/30">
                        <div class="flex items-start gap-3">
                            <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow shrink-0 mt-0.5">
                                <i class="fa fa-info text-xs"></i>
                            </span>
                            <p class="text-gray-600 text-sm leading-relaxed">{{ $project->description }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="p-6 sm:p-8">
                <div class="flex flex-wrap items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                    <span class="px-4 py-1.5 bg-indigo-100 text-indigo-700 text-sm font-semibold rounded-lg">{{ $package->package_no }}</span>
                    <span class="text-gray-400 text-sm">{{ $package->package_name }}</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="relative group bg-gradient-to-br from-emerald-50 to-emerald-100/50 rounded-xl p-5 border border-emerald-200/50 hover:shadow-lg hover:shadow-emerald-200/40 hover:-translate-y-0.5 transition-all duration-300">
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-emerald-400/10 rounded-full blur-md"></div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="w-9 h-9 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white shadow-md shadow-emerald-200">
                                <i class="fa fa-tag text-xs"></i>
                            </span>
                            <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Package Price</span>
                        </div>
                        <div class="relative text-3xl font-extrabold text-gray-800 tracking-tight">৳{{ number_format($package->package_price, 2) }}

                            <div class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-emerald-400/30 blur-sm"></div>
                        </div>
                    </div>

                    <div class="relative group bg-gradient-to-br from-blue-50 to-blue-100/50 rounded-xl p-5 border border-blue-200/50 hover:shadow-lg hover:shadow-blue-200/40 hover:-translate-y-0.5 transition-all duration-300">
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-blue-400/10 rounded-full blur-md"></div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white shadow-md shadow-blue-200">
                                <i class="fa fa-undo text-xs"></i>
                            </span>
                            <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Return Amount</span>
                        </div>
                        <div class="relative text-3xl font-extrabold text-gray-800 tracking-tight">৳{{ number_format($package->return_amount, 2) }}

                            <div class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-blue-400/30 blur-sm"></div>
                        </div>
                    </div>

                    <div class="relative group bg-gradient-to-br from-amber-50 to-amber-100/50 rounded-xl p-5 border border-amber-200/50 hover:shadow-lg hover:shadow-amber-200/40 hover:-translate-y-0.5 transition-all duration-300">
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-amber-400/10 rounded-full blur-md"></div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="w-9 h-9 rounded-lg bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white shadow-md shadow-amber-200">
                                <i class="fa fa-clock text-xs"></i>
                            </span>
                            <span class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Return Time</span>
                        </div>
                        <div class="relative text-3xl font-extrabold text-gray-800 tracking-tight">{{ $package->return_time }}

                            <div class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-amber-400/30 blur-sm"></div>
                        </div>
                    </div>

                    <div class="relative group bg-gradient-to-br from-purple-50 to-purple-100/50 rounded-xl p-5 border border-purple-200/50 hover:shadow-lg hover:shadow-purple-200/40 hover:-translate-y-0.5 transition-all duration-300">
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-purple-400/10 rounded-full blur-md"></div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="w-9 h-9 rounded-lg bg-gradient-to-br from-purple-500 to-violet-600 flex items-center justify-center text-white shadow-md shadow-purple-200">
                                <i class="fa fa-gift text-xs"></i>
                            </span>
                            <span class="text-xs font-semibold text-purple-600 uppercase tracking-wider">Extra Benefit</span>
                        </div>
                        <div class="relative text-3xl font-extrabold text-gray-800 tracking-tight">{{ $package->extra_benefit ? '৳' . number_format($package->extra_benefit, 2) : 'N/A' }}

                            <div class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-purple-400/30 blur-sm"></div>
                        </div>
                    </div>

                    <div class="relative group bg-gradient-to-br from-rose-50 to-rose-100/50 rounded-xl p-5 border border-rose-200/50 hover:shadow-lg hover:shadow-rose-200/40 hover:-translate-y-0.5 transition-all duration-300">
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-rose-400/10 rounded-full blur-md"></div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="w-9 h-9 rounded-lg bg-gradient-to-br from-rose-500 to-pink-600 flex items-center justify-center text-white shadow-md shadow-rose-200">
                                <i class="fa fa-cubes text-xs"></i>
                            </span>
                            <span class="text-xs font-semibold text-rose-600 uppercase tracking-wider">Share Count</span>
                        </div>
                        <div class="relative text-3xl font-extrabold text-gray-800 tracking-tight">{{ $package->share_count }}

                            <div class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-rose-400/30 blur-sm"></div>
                        </div>
                    </div>

                    <div class="relative group bg-gradient-to-br from-orange-50 to-orange-100/50 rounded-xl p-5 border border-orange-200/50 hover:shadow-lg hover:shadow-orange-200/40 hover:-translate-y-0.5 transition-all duration-300">
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-orange-400/10 rounded-full blur-md"></div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="w-9 h-9 rounded-lg bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center text-white shadow-md shadow-orange-200">
                                <i class="fa fa-pie-chart text-xs"></i>
                            </span>
                            <span class="text-xs font-semibold text-orange-600 uppercase tracking-wider">Allotted Share</span>
                        </div>
                        <div class="relative text-3xl font-extrabold text-gray-800 tracking-tight">{{ $package->allotted_share ? number_format($package->allotted_share, 2) : '0.00' }}

                            <div class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-orange-400/30 blur-sm"></div>
                        </div>
                    </div>

                    <div class="relative group bg-gradient-to-br from-teal-50 to-teal-100/50 rounded-xl p-5 border border-teal-200/50 hover:shadow-lg hover:shadow-teal-200/40 hover:-translate-y-0.5 transition-all duration-300">
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-teal-400/10 rounded-full blur-md"></div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="w-9 h-9 rounded-lg bg-gradient-to-br from-teal-500 to-emerald-600 flex items-center justify-center text-white shadow-md shadow-teal-200">
                                <i class="fa fa-money text-xs"></i>
                            </span>
                            <span class="text-xs font-semibold text-teal-600 uppercase tracking-wider">Booking Money</span>
                        </div>
                        <div class="relative text-3xl font-extrabold text-gray-800 tracking-tight">৳{{ number_format($package->booking_money, 2) }}
                        </div>
                    </div>

                    <div class="relative group bg-gradient-to-br from-cyan-50 to-cyan-100/50 rounded-xl p-5 border border-cyan-200/50 hover:shadow-lg hover:shadow-cyan-200/40 hover:-translate-y-0.5 transition-all duration-300">
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-cyan-400/10 rounded-full blur-md"></div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="w-9 h-9 rounded-lg bg-gradient-to-br from-cyan-500 to-teal-600 flex items-center justify-center text-white shadow-md shadow-cyan-200">
                                <i class="fa fa-folder text-xs"></i>
                            </span>
                            <span class="text-xs font-semibold text-cyan-600 uppercase tracking-wider">Project</span>
                        </div>
                        <div class="relative text-2xl font-extrabold text-gray-800 tracking-tight">{{ $project->name ?? 'N/A' }}</div>
                    </div>
                        <div class="relative text-2xl font-extrabold text-gray-800 tracking-tight">{{ $project->name ?? 'N/A' }}

                            <div class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-cyan-400/30 blur-sm"></div>
                        </div>
                    </div>
                </div>

                @if ($package->description)
                    <div class="mt-6 pt-5 border-t border-gray-100">
                        <h4 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <i class="fa fa-align-left text-gray-400"></i>
                            Description
                        </h4>
                        <div class="bg-gray-50/80 rounded-xl p-5 border border-gray-100">
                            <p class="text-gray-600 text-sm leading-relaxed">{{ $package->description }}</p>
                        </div>
                    </div>
                @endif

                <div class="mt-6 pt-5 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400">
                    <span>Created: {{ $package->created_at ? $package->created_at->format('M d, Y h:i A') : 'N/A' }}</span>
                    <span>Last Updated: {{ $package->updated_at ? $package->updated_at->format('M d, Y h:i A') : 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
