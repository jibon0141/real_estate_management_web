@extends('admin.master')
@section('content')
<div class="min-h-screen p-4 sm:p-6 lg:p-8" style="background: linear-gradient(135deg, #f0f4ff 0%, #e8ecf8 100%);">
    <div class="max-w-4xl mx-auto">
        @include('admin.include.message')
        <div class="mb-6">
            <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-500 to-pink-600 flex items-center justify-center text-white shadow-lg shadow-rose-200">
                    <i class="fa fa-hand-holding-usd text-sm"></i>
                </span>
                Set Commission
            </h3>
            <p class="text-sm text-gray-500 mt-1 ml-13">Manage commission names and percentages</p>
        </div>
        <div class="bg-white/80 rounded-2xl shadow-lg shadow-indigo-100/50 border border-white/50 overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center gap-2">
                <i class="fa fa-list text-rose-500"></i>
                <span class="font-semibold text-gray-700">Commission List</span>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.set-commission.save') }}" method="POST">
                    @csrf
                    <div id="commission-rows" class="space-y-3">
                        @foreach($commissions as $i => $commission)
                        <div class="row-item flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                            <input type="hidden" name="commissions[{{ $i }}][id]" value="{{ $commission->id }}">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-700">{{ $commission->name }}</span>
                            </div>
                            <div class="relative w-44">
                                <input type="number" step="0.01" min="0" max="100" name="commissions[{{ $i }}][commission_percentage]" value="{{ old("commissions.$i.commission_percentage", $commission->commission_percentage) }}"
                                       class="form-input !rounded-xl !border-gray-200 !bg-white focus:!border-indigo-400 focus:!ring focus:!ring-indigo-200/50 !transition-all !text-sm !py-2 !pr-8 !text-center"
                                       placeholder="0">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium pointer-events-none">%</span>
                            </div>
                            <button type="button" class="remove-row w-8 h-8 flex items-center justify-center rounded-lg text-red-400 hover:text-red-600 hover:bg-red-50 transition-all" title="Remove">
                                <i class="fa fa-times text-sm"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        <button type="button" id="add-more-btn"
                                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-xl border border-indigo-200 transition-all hover:shadow-sm">
                            <i class="fa fa-plus"></i>
                            Add More
                        </button>
                    </div>

                    <div class="mt-6 pt-5 border-t border-gray-100 flex justify-end">
                        <button type="submit"
                                class="px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-rose-500 to-pink-600 hover:from-rose-600 hover:to-pink-700 rounded-xl shadow-lg shadow-rose-200 transition-all duration-200 hover:shadow-xl hover:-translate-y-0.5">
                            <i class="fa fa-save mr-1.5"></i>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    let rowIndex = {{ count($commissions) }};

    $('#add-more-btn').on('click', function () {
        const html = `
            <div class="row-item flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                <input type="hidden" name="commissions[` + rowIndex + `][id]" value="">
                <div class="flex-1">
                    <input type="text" name="commissions[` + rowIndex + `][name]"
                           class="form-input !rounded-xl !border-gray-200 !bg-white focus:!border-indigo-400 focus:!ring focus:!ring-indigo-200/50 !transition-all !text-sm !py-2"
                           placeholder="Commission name">
                </div>
                <div class="relative w-44">
                    <input type="number" step="0.01" min="0" max="100" name="commissions[` + rowIndex + `][commission_percentage]"
                           class="form-input !rounded-xl !border-gray-200 !bg-white focus:!border-indigo-400 focus:!ring focus:!ring-indigo-200/50 !transition-all !text-sm !py-2 !pr-8 !text-center"
                           placeholder="0">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium pointer-events-none">%</span>
                </div>
                <button type="button" class="remove-row w-8 h-8 flex items-center justify-center rounded-lg text-red-400 hover:text-red-600 hover:bg-red-50 transition-all" title="Remove">
                    <i class="fa fa-times text-sm"></i>
                </button>
            </div>
        `;
        $('#commission-rows').append(html);
        rowIndex++;
    });

    $(document).on('click', '.remove-row', function () {
        $(this).closest('.row-item').remove();
    });
</script>
@endsection
