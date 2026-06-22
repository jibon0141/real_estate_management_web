@extends('admin.master')
@section('content')
<div class="min-h-screen p-4 sm:p-6 lg:p-8" style="background: linear-gradient(135deg, #f0f4ff 0%, #e8ecf8 100%);">
    <div class="max-w-4xl mx-auto">
        @include('admin.include.message')
        <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                        <i class="fa fa-shopping-cart text-sm"></i>
                    </span>
                    Sell Package
                </h3>
                <p class="text-sm text-gray-500 mt-1 ml-13">Choose a payment method</p>
            </div>
            <a href="{{ route('admin.sell.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 rounded-xl border border-gray-200 transition-all">
                <i class="fa fa-arrow-left mr-1"></i> Back
            </a>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg shadow-indigo-100/50 border border-white/50 overflow-hidden mb-6">
            <div class="p-5 border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                        <i class="fa fa-cube text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-800">{{ $package->package_name }}</h4>
                        <p class="text-sm text-gray-500">{{ $package->package_no }} — {{ $package->project->name ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="{{ route('admin.sell.cash', $package->id) }}" class="group bg-white rounded-2xl shadow-lg shadow-indigo-100/50 border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="h-40 bg-gradient-to-br from-emerald-500 via-teal-500 to-cyan-600 relative flex items-center justify-center">
                    <div class="absolute inset-0 opacity-20">
                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-white rounded-full"></div>
                        <div class="absolute -bottom-10 -left-10 w-48 h-48 bg-white rounded-full"></div>
                    </div>
                    <div class="relative text-center text-white">
                        <div class="w-16 h-16 mx-auto mb-3 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            <i class="fa-solid fa-money-bill-wave text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold">On Cash</h3>
                        <p class="text-sm text-white/80 mt-1">Full payment at time of sale</p>
                    </div>
                </div>
                <div class="p-5 text-center">
                    <span class="inline-flex items-center gap-2 text-emerald-600 font-semibold group-hover:gap-3 transition-all">
                        Proceed with Cash Payment
                        <i class="fa fa-arrow-right text-sm"></i>
                    </span>
                </div>
            </a>

            <a href="{{ route('admin.sell.installment', $package->id) }}" class="group bg-white rounded-2xl shadow-lg shadow-indigo-100/50 border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="h-40 bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 relative flex items-center justify-center">
                    <div class="absolute inset-0 opacity-20">
                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-white rounded-full"></div>
                        <div class="absolute -bottom-10 -left-10 w-48 h-48 bg-white rounded-full"></div>
                    </div>
                    <div class="relative text-center text-white">
                        <div class="w-16 h-16 mx-auto mb-3 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            <i class="fa fa-calendar-alt text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold">On Installment</h3>
                        <p class="text-sm text-white/80 mt-1">Pay in monthly installments</p>
                    </div>
                </div>
                <div class="p-5 text-center">
                    <span class="inline-flex items-center gap-2 text-indigo-600 font-semibold group-hover:gap-3 transition-all">
                        Setup Installment Plan
                        <i class="fa fa-arrow-right text-sm"></i>
                    </span>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
