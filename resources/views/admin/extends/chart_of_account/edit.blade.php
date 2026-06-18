@extends('admin.master')
@section('content')
<div class="min-h-screen p-4 sm:p-6 lg:p-8" style="background: linear-gradient(135deg, #f0f4ff 0%, #e8ecf8 100%);">
    <div class="max-w-4xl mx-auto">
        @include('admin.include.message')
        <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white shadow-lg shadow-amber-200">
                        <i class="fa fa-edit text-sm"></i>
                    </span>
                    Update Chart of Account
                </h3>
                <p class="text-sm text-gray-500 mt-1 ml-13">Edit chart of account head</p>
            </div>
            <a href="{{ route('admin.chart-of-account.index') }}"
               class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2.5 rounded-xl border border-gray-200 shadow-sm transition-all duration-200 hover:shadow-md">
                <i class="fa fa-reply"></i>
                Chart of Account List
            </a>
        </div>
        <div class="bg-white/80 rounded-2xl shadow-lg shadow-indigo-100/50 border border-white/50 overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center gap-2">
                <i class="fa fa-info-circle text-amber-500"></i>
                <span class="font-semibold text-gray-700">Edit Chart of Account Information</span>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.chart-of-account.update', $chartOfAccount->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col">
                            <label class="mb-2 text-sm font-medium text-gray-600">
                                GL Account <span class="text-red-500">*</span>
                            </label>
                            <select name="gl_account_id"
                                    class="form-input !rounded-xl !border-gray-200 !bg-gray-50 focus:!bg-white focus:!border-indigo-400 focus:!ring focus:!ring-indigo-200/50 !transition-all">
                                <option value="">Select GL Account</option>
                                @foreach($glAccounts as $glAccount)
                                    <option value="{{ $glAccount->id }}" {{ old('gl_account_id', $chartOfAccount->gl_account_id) == $glAccount->id ? 'selected' : '' }}>
                                        {{ $glAccount->account_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('gl_account_id')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex flex-col">
                            <label class="mb-2 text-sm font-medium text-gray-600">
                                Head Type <span class="text-red-500">*</span>
                            </label>
                            <select name="head_type"
                                    class="form-input !rounded-xl !border-gray-200 !bg-gray-50 focus:!bg-white focus:!border-indigo-400 focus:!ring focus:!ring-indigo-200/50 !transition-all">
                                <option value="">Select Head Type</option>
                                <option value="Income" {{ old('head_type', $chartOfAccount->head_type) == 'Income' ? 'selected' : '' }}>Income</option>
                                <option value="Expense" {{ old('head_type', $chartOfAccount->head_type) == 'Expense' ? 'selected' : '' }}>Expense</option>
                            </select>
                            @error('head_type')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex flex-col">
                            <label class="mb-2 text-sm font-medium text-gray-600">
                                Head Name <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fa fa-tag absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="text" name="head_name"
                                       value="{{ old('head_name', $chartOfAccount->head_name) }}"
                                       class="form-input !rounded-xl !border-gray-200 !bg-gray-50 focus:!bg-white focus:!border-indigo-400 focus:!ring focus:!ring-indigo-200/50 !transition-all !pl-10"
                                       placeholder="e.g., Cash in Hand"
                                       required>
                            </div>
                            @error('head_name')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex flex-col">
                            <label class="mb-2 text-sm font-medium text-gray-600">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status"
                                    class="form-input !rounded-xl !border-gray-200 !bg-gray-50 focus:!bg-white focus:!border-indigo-400 focus:!ring focus:!ring-indigo-200/50 !transition-all">
                                <option value="1" {{ old('status', $chartOfAccount->status) == '1' || old('status', $chartOfAccount->status) === 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', $chartOfAccount->status) == '0' || old('status', $chartOfAccount->status) === 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-8 pt-5 border-t border-gray-100 flex flex-col sm:flex-row justify-end gap-3">
                        <a href="{{ route('admin.chart-of-account.index') }}"
                           class="px-6 py-2.5 text-sm font-medium text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-xl border border-gray-200 transition-all w-full sm:w-auto text-center">
                            Cancel
                        </a>
                        <button type="submit"
                                class="px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 rounded-xl shadow-lg shadow-amber-200 transition-all duration-200 hover:shadow-xl hover:-translate-y-0.5 w-full sm:w-auto">
                            <i class="fa fa-save mr-1.5"></i>
                            Update Chart of Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
