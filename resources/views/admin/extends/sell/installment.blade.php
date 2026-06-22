@extends('admin.master')
@section('content')
<div class="min-h-screen p-4 sm:p-6 lg:p-8" style="background: linear-gradient(135deg, #f0f4ff 0%, #e8ecf8 100%);">
    <div class="max-w-4xl mx-auto space-y-6">
        @include('admin.include.message')
        <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-blue-200">
                        <i class="fa fa-calendar-alt text-sm"></i>
                    </span>
                    Installment Sale
                </h3>
                <p class="text-sm text-gray-500 mt-1 ml-13">Setup installment plan for this package</p>
            </div>
            <a href="{{ route('admin.sell.options', $package->id) }}" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 rounded-xl border border-gray-200 transition-all">
                <i class="fa fa-arrow-left mr-1"></i> Back
            </a>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg shadow-indigo-100/50 border border-white/50 overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center gap-2">
                <i class="fa fa-cube text-blue-500"></i>
                <span class="font-semibold text-gray-700">Package Information</span>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <span class="text-xs text-gray-400 uppercase tracking-wider">Package No</span>
                        <p class="text-lg font-bold text-gray-800 mt-1">{{ $package->package_no }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <span class="text-xs text-gray-400 uppercase tracking-wider">Package Name</span>
                        <p class="text-lg font-bold text-gray-800 mt-1">{{ $package->package_name }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <span class="text-xs text-gray-400 uppercase tracking-wider">Project</span>
                        <p class="text-lg font-bold text-gray-800 mt-1">{{ $package->project->name ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <span class="text-xs text-gray-400 uppercase tracking-wider">Package Price</span>
                        <p class="text-lg font-bold text-emerald-600 mt-1">৳{{ number_format($package->package_price, 2) }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <span class="text-xs text-gray-400 uppercase tracking-wider">Booking Money</span>
                        <p class="text-lg font-bold text-blue-600 mt-1">৳{{ number_format($package->booking_money, 2) }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <span class="text-xs text-gray-400 uppercase tracking-wider">Return Amount</span>
                        <p class="text-lg font-bold text-gray-800 mt-1">৳{{ number_format($package->return_amount, 2) }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <span class="text-xs text-gray-400 uppercase tracking-wider">Return Time</span>
                        <p class="text-lg font-bold text-gray-800 mt-1">{{ $package->return_time }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <span class="text-xs text-gray-400 uppercase tracking-wider">Land Share</span>
                        <p class="text-lg font-bold text-gray-800 mt-1">{{ $package->share_count }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <span class="text-xs text-gray-400 uppercase tracking-wider">Extra Benefit</span>
                        <p class="text-lg font-bold text-gray-800 mt-1">{{ $package->extra_benefit ?? '—' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <span class="text-xs text-gray-400 uppercase tracking-wider">Allotted Share</span>
                        <p class="text-lg font-bold text-gray-800 mt-1">{{ number_format($package->allotted_share, 2) }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <span class="text-xs text-gray-400 uppercase tracking-wider">Total Share Sold</span>
                        <p class="text-lg font-bold text-rose-600 mt-1">{{ number_format($totalSold, 2) }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <span class="text-xs text-gray-400 uppercase tracking-wider">Total Benefit Taken</span>
                        <p class="text-lg font-bold text-purple-600 mt-1">{{ number_format($totalBenefitTaken, 2) }}</p>
                    </div>
                    <div class="bg-amber-50 rounded-xl p-4 border border-amber-200">
                        <span class="text-xs text-amber-600 uppercase tracking-wider font-semibold">Available Share</span>
                        <p class="text-2xl font-bold text-amber-600 mt-1" id="avail_share_top">{{ number_format(max(0, $package->allotted_share - $totalSold - $totalBenefitTaken), 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg shadow-indigo-100/50 border border-white/50 overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center gap-2">
                <i class="fa fa-shopping-bag text-blue-500"></i>
                <span class="font-semibold text-gray-700">Purchase Details</span>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.sell.installment.store') }}" method="POST" id="installmentForm">
                    @csrf
                    <input type="hidden" name="package_id" value="{{ $package->id }}">
                    <input type="hidden" name="share_qty" id="share_qty_hidden" value="1">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Number of Shares</label>
                            <div class="flex items-center gap-2">
                                <button type="button" id="qty_minus" class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 border border-gray-200 flex items-center justify-center text-gray-600 font-bold transition-all">
                                    <i class="fa fa-minus text-sm"></i>
                                </button>
                                <input type="number" id="share_qty" min="1" step="1" value="1" readonly
                                       class="form-input w-20 text-center rounded-xl border-gray-200 bg-gray-50 text-lg font-bold text-gray-800 cursor-default">
                                <button type="button" id="qty_plus" class="w-10 h-10 rounded-xl bg-blue-100 hover:bg-blue-200 border border-blue-200 flex items-center justify-center text-blue-600 font-bold transition-all">
                                    <i class="fa fa-plus text-sm"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Share Value</label>
                            <div class="text-2xl font-bold text-emerald-600" id="total_price">৳{{ number_format($package->package_price, 2) }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Booking Money <span class="text-red-500">*</span></label>
                            <input type="number" name="booking_money" id="booking_money" step="0.01" min="0"
                                   value="{{ number_format($package->booking_money, 2, '.', '') }}"
                                   class="form-input w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring focus:ring-blue-200/50 transition-all font-semibold text-gray-800">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Number of Installments</label>
                            <input type="number" name="installment_number" id="installment_number" step="1" min="1" value="1"
                                   class="form-input w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring focus:ring-blue-200/50 transition-all font-semibold text-gray-800">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Installment Amount <span class="text-red-500">*</span></label>
                            <input type="number" name="installment_amount" id="installment_amount" step="0.01" min="0" readonly
                                   class="form-input w-full rounded-xl border-gray-200 bg-gray-50 text-lg font-bold text-gray-800 cursor-default">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Number of Share</label>
                            <div class="text-2xl font-bold text-gray-800" id="total_shares">{{ $package->share_count }}</div>
                        </div>
                    </div>

                    <div class="mt-4 pt-5 border-t border-gray-100 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-600">Total Share Value</span>
                            <span class="text-2xl font-bold text-emerald-600" id="total_share_value">৳{{ number_format($package->package_price, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-600">Booking Money (Down Payment)</span>
                            <span class="text-xl font-bold text-blue-600" id="booking_display">৳{{ number_format($package->booking_money, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-600">Due Amount</span>
                            <span class="text-xl font-bold text-rose-600" id="due_display">৳{{ number_format(max(0, $package->package_price - $package->booking_money), 2) }}</span>
                        </div>
                    </div>

                    <button type="button" id="reviewBtn" class="w-full py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 rounded-xl shadow-lg shadow-blue-200 transition-all duration-200 hover:shadow-xl hover:-translate-y-0.5">
                        <i class="fa fa-eye mr-1.5"></i>
                        Review & Confirm Installment
                    </button>

                    <div id="confirmModal" class="fixed inset-0 z-50 hidden overflow-y-auto" style="background: rgba(0,0,0,0.5);">
                        <div class="flex items-center justify-center min-h-screen p-4">
                            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden">
                                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <i class="fa fa-check-circle text-blue-500 text-xl"></i>
                                        <span class="font-semibold text-lg text-gray-800">Confirm Installment Sale</span>
                                    </div>
                                    <button type="button" id="closeModal" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500 transition-all">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                                <div class="p-6 space-y-5">
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <h4 class="text-sm font-semibold text-gray-600 mb-3">Purchase Summary</h4>
                                        <div class="grid grid-cols-2 gap-3 text-sm">
                                            <div><span class="text-gray-400">Package:</span> <span class="font-semibold text-gray-800">{{ $package->package_name }}</span></div>
                                            <div><span class="text-gray-400">Project:</span> <span class="font-semibold text-gray-800">{{ $package->project->name ?? 'N/A' }}</span></div>
                                            <div><span class="text-gray-400">Quantity:</span> <span class="font-semibold text-gray-800" id="modalQty">1</span></div>
                                            <div><span class="text-gray-400">Share Value:</span> <span class="font-semibold text-emerald-600" id="modalShareValue">৳{{ number_format($package->package_price, 2) }}</span></div>
                                            <div><span class="text-gray-400">Total Share Value:</span> <span class="font-semibold text-emerald-600" id="modalTotalValue">৳{{ number_format($package->package_price, 2) }}</span></div>
                                            <div><span class="text-gray-400">Booking Money:</span> <span class="font-semibold text-blue-600" id="modalBooking">৳{{ number_format($package->booking_money, 2) }}</span></div>
                                            <div><span class="text-gray-400">Installments:</span> <span class="font-semibold text-gray-800" id="modalInstallment">1 × ৳0.00</span></div>
                                            <div><span class="text-gray-400">Due:</span> <span class="font-semibold text-rose-600" id="modalDue">৳0.00</span></div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Select User <span class="text-red-500">*</span></label>
                                            <select name="user_id" id="user_id" class="form-input w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring focus:ring-blue-200/50 transition-all" required>
                                                <option value="">Select User</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->phone }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Account <span class="text-red-500">*</span></label>
                                            <select name="account_id" id="account_id" class="form-input w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring focus:ring-blue-200/50 transition-all" required>
                                                <option value="">Select Account</option>
                                                @foreach($accounts as $account)
                                                    <option value="{{ $account->id }}" {{ isset($defaultAccount) && $defaultAccount->id == $account->id ? 'selected' : '' }}>{{ $account->account_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Admin Password <span class="text-red-500">*</span></label>
                                        <input type="password" name="admin_password" id="admin_password" placeholder="Enter your password to confirm"
                                               class="form-input w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring focus:ring-blue-200/50 transition-all" required>
                                    </div>

                                    <div class="flex gap-3 pt-2">
                                        <button type="button" id="cancelSale" class="flex-1 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all">Cancel</button>
                                        <button type="submit" class="flex-1 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 rounded-xl shadow-lg shadow-blue-200 transition-all duration-200 hover:shadow-xl hover:-translate-y-0.5">
                                            <i class="fa fa-check mr-1.5"></i>
                                            Confirm Installment Sale
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        var unitPrice = {{ $package->package_price }};
        var shareCount = {{ $package->share_count }};
        var unitBooking = {{ $package->booking_money }};
        var allottedShare = {{ $package->allotted_share }};
        var totalSold = {{ $totalSold }};
        var totalBenefitTaken = {{ $totalBenefitTaken }};

        function getMaxQty() {
            return Math.max(0, Math.floor((allottedShare - totalSold - totalBenefitTaken) / shareCount));
        }

        function calcInstallment(updateBooking) {
            var qty = parseInt($('#share_qty').val()) || 1;
            var totalPrice = unitPrice * qty;
            var booking;
            if (updateBooking) {
                booking = unitBooking * qty;
                $('#booking_money').val(booking.toFixed(2));
            } else {
                booking = parseFloat($('#booking_money').val()) || 0;
            }
            var numInstallments = parseInt($('#installment_number').val()) || 1;
            var due = Math.max(0, totalPrice - booking);
            var instAmt = numInstallments > 0 ? due / numInstallments : 0;

            $('#total_price').text('৳' + totalPrice.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            $('#total_share_value').text('৳' + totalPrice.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            $('#booking_display').text('৳' + booking.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            $('#due_display').text('৳' + due.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            $('#installment_amount').val(instAmt.toFixed(2));
        }

        function updateDisplay() {
            var qty = parseInt($('#share_qty').val()) || 1;
            $('#share_qty_hidden').val(qty);

            var totalLand = shareCount * qty;
            var availableShare = Math.max(0, allottedShare - totalSold - totalBenefitTaken - totalLand);

            $('#total_shares').text(totalLand.toFixed(2));
            $('#avail_share_top').text(availableShare.toFixed(2));
            calcInstallment(true);
        }

        $('#qty_minus').on('click', function () {
            var qty = parseInt($('#share_qty').val()) || 1;
            if (qty > 1) { $('#share_qty').val(qty - 1); updateDisplay(); }
        });

        $('#qty_plus').on('click', function () {
            var qty = parseInt($('#share_qty').val()) || 0;
            var maxQty = getMaxQty();
            if (qty < maxQty) { $('#share_qty').val(qty + 1); updateDisplay(); }
            else {
                Swal.fire({ icon: 'warning', title: 'Limit Reached', text: 'Available share is ' + (allottedShare - totalSold - totalBenefitTaken).toFixed(2), confirmButtonColor: '#3b82f6' });
            }
        });

        $('#booking_money, #installment_number').on('input', function () { calcInstallment(false); });

        $('#reviewBtn').on('click', function () {
            var qty = parseInt($('#share_qty').val()) || 0;
            if (qty < 1) { Swal.fire({ icon: 'warning', title: 'Invalid Quantity', confirmButtonColor: '#3b82f6' }); return; }
            var booking = parseFloat($('#booking_money').val()) || 0;
            var numInst = parseInt($('#installment_number').val()) || 1;
            var totalPrice = unitPrice * qty;
            var instAmt = numInst > 0 ? Math.max(0, totalPrice - booking) / numInst : 0;

            $('#modalQty').text(qty);
            $('#modalShareValue').text('৳' + unitPrice.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            $('#modalTotalValue').text('৳' + totalPrice.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            $('#modalBooking').text('৳' + booking.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            $('#modalInstallment').text(numInst + ' × ৳' + instAmt.toFixed(2));
            $('#modalDue').text('৳' + Math.max(0, totalPrice - booking).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            $('#confirmModal').removeClass('hidden');
        });

        $('#closeModal, #cancelSale').on('click', function () { $('#confirmModal').addClass('hidden'); });
        $('#confirmModal').on('click', function (e) { if (e.target === this) $(this).addClass('hidden'); });

        $('#user_id').select2({ dropdownParent: $('#confirmModal'), width: '100%' });
        $('#account_id').select2({ dropdownParent: $('#confirmModal'), width: '100%' });

        updateDisplay();
    });
</script>
@endsection
