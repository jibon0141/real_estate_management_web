@extends('admin.master')
@section('title', 'Dashboard')
@section('content')
<div class="mb-6">
    <h1 class="page-title">Dashboard</h1>
    <p class="text-sm text-slate-500 mt-1">Welcome back, {{ Auth::user()->name ?? 'Admin' }}. Here's what's happening today.</p>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-5 mb-6 lg:mb-8">
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <div class="stat-icon bg-blue-100 text-blue-600"><i class="fa-solid fa-dollar-sign"></i></div>
            <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded-full"><i class="fa-solid fa-arrow-up mr-0.5"></i>+12.5%</span>
        </div>
        <div class="stat-value">$48,295</div>
        <div class="stat-label">Total Revenue</div>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <div class="stat-icon bg-amber-100 text-amber-600"><i class="fa-solid fa-cart-shopping"></i></div>
            <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded-full"><i class="fa-solid fa-arrow-up mr-0.5"></i>+8.2%</span>
        </div>
        <div class="stat-value">1,842</div>
        <div class="stat-label">Total Orders</div>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <div class="stat-icon bg-green-100 text-green-600"><i class="fa-solid fa-users"></i></div>
            <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded-full"><i class="fa-solid fa-arrow-up mr-0.5"></i>+5.7%</span>
        </div>
        <div class="stat-value">6,431</div>
        <div class="stat-label">Total Customers</div>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <div class="stat-icon bg-purple-100 text-purple-600"><i class="fa-solid fa-chart-line"></i></div>
            <span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-0.5 rounded-full"><i class="fa-solid fa-arrow-down mr-0.5"></i>-2.4%</span>
        </div>
        <div class="stat-value">84.7%</div>
        <div class="stat-label">Conversion Rate</div>
    </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-5 mb-6 lg:mb-8">
    <div class="chart-card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-slate-800 text-sm">Revenue Overview</h3>
            <select class="text-xs border border-slate-200 rounded-lg px-2 py-1 text-slate-500 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                <option>Last 7 days</option>
                <option>Last 30 days</option>
                <option>Last 90 days</option>
            </select>
        </div>
        <div class="chart-container" style="height: 220px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-slate-800 text-sm">Orders</h3>
            <select class="text-xs border border-slate-200 rounded-lg px-2 py-1 text-slate-500 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                <option>Last 7 days</option>
                <option>Last 30 days</option>
                <option>Last 90 days</option>
            </select>
        </div>
        <div class="chart-container" style="height: 220px;">
            <canvas id="ordersChart"></canvas>
        </div>
    </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-5">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-slate-800 text-sm">Recent Orders</h3>
            <a href="#" class="text-xs font-medium text-blue-600 hover:text-blue-700 transition-colors">View All <i class="fa-solid fa-arrow-right ml-1"></i></a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-slate-400 uppercase tracking-wider">
                        <th class="pb-3 font-medium">Order</th>
                        <th class="pb-3 font-medium">Customer</th>
                        <th class="pb-3 font-medium">Status</th>
                        <th class="pb-3 font-medium text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr>
                        <td class="py-3 font-medium text-slate-700">#ORD-001</td>
                        <td class="py-3 text-slate-500">John Doe</td>
                        <td class="py-3"><span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">Completed</span></td>
                        <td class="py-3 text-right font-medium text-slate-700">$235.00</td>
                    </tr>
                    <tr>
                        <td class="py-3 font-medium text-slate-700">#ORD-002</td>
                        <td class="py-3 text-slate-500">Sarah Smith</td>
                        <td class="py-3"><span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">Pending</span></td>
                        <td class="py-3 text-right font-medium text-slate-700">$1,240.00</td>
                    </tr>
                    <tr>
                        <td class="py-3 font-medium text-slate-700">#ORD-003</td>
                        <td class="py-3 text-slate-500">Mike Johnson</td>
                        <td class="py-3"><span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">Processing</span></td>
                        <td class="py-3 text-right font-medium text-slate-700">$580.00</td>
                    </tr>
                    <tr>
                        <td class="py-3 font-medium text-slate-700">#ORD-004</td>
                        <td class="py-3 text-slate-500">Emily Davis</td>
                        <td class="py-3"><span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">Completed</span></td>
                        <td class="py-3 text-right font-medium text-slate-700">$3,450.00</td>
                    </tr>
                    <tr>
                        <td class="py-3 font-medium text-slate-700">#ORD-005</td>
                        <td class="py-3 text-slate-500">Alex Brown</td>
                        <td class="py-3"><span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-0.5 rounded-full">Cancelled</span></td>
                        <td class="py-3 text-right font-medium text-slate-700">$125.00</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-slate-800 text-sm">Top Locations</h3>
            <a href="#" class="text-xs font-medium text-blue-600 hover:text-blue-700 transition-colors">Details <i class="fa-solid fa-arrow-right ml-1"></i></a>
        </div>
        <div class="space-y-4">
            <div>
                <div class="flex items-center justify-between text-sm mb-1">
                    <span class="text-slate-600">New York</span>
                    <span class="font-medium text-slate-700">24.5%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-1.5">
                    <div class="bg-blue-500 h-1.5 rounded-full" style="width: 24.5%"></div>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between text-sm mb-1">
                    <span class="text-slate-600">Los Angeles</span>
                    <span class="font-medium text-slate-700">18.2%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-1.5">
                    <div class="bg-amber-500 h-1.5 rounded-full" style="width: 18.2%"></div>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between text-sm mb-1">
                    <span class="text-slate-600">Chicago</span>
                    <span class="font-medium text-slate-700">14.7%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-1.5">
                    <div class="bg-green-500 h-1.5 rounded-full" style="width: 14.7%"></div>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between text-sm mb-1">
                    <span class="text-slate-600">Houston</span>
                    <span class="font-medium text-slate-700">11.3%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-1.5">
                    <div class="bg-purple-500 h-1.5 rounded-full" style="width: 11.3%"></div>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between text-sm mb-1">
                    <span class="text-slate-600">Miami</span>
                    <span class="font-medium text-slate-700">8.9%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-1.5">
                    <div class="bg-rose-500 h-1.5 rounded-full" style="width: 8.9%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function() {
    var primary = '#3b82f6';
    var gridColor = 'rgba(0,0,0,0.04)';
    var textColor = '#94a3b8';
    var days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

    function hexToRgb(h) {
        var r = parseInt(h.slice(1, 3), 16),
            g = parseInt(h.slice(3, 5), 16),
            b = parseInt(h.slice(5, 7), 16);
        return r + ',' + g + ',' + b;
    }

    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: days,
            datasets: [{
                data: [12400, 18900, 15200, 22100, 19800, 25600, 23400],
                borderColor: primary,
                backgroundColor: 'rgba(' + hexToRgb(primary) + ',0.08)',
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                pointBackgroundColor: primary,
                pointBorderColor: '#fff',
                pointBorderWidth: 1.5,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleColor: '#fff',
                    bodyColor: '#e2e8f0',
                    cornerRadius: 8,
                    padding: 10
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: textColor,
                        font: {
                            size: 11
                        },
                        callback: function(v) {
                            return '$' + (v / 1000).toFixed(0) + 'k';
                        }
                    },
                    grid: {
                        color: gridColor
                    }
                },
                x: {
                    ticks: {
                        color: textColor,
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('ordersChart'), {
        type: 'bar',
        data: {
            labels: days,
            datasets: [{
                data: [42, 78, 55, 91, 67, 103, 88],
                backgroundColor: 'rgba(' + hexToRgb(primary) + ',0.75)',
                borderColor: primary,
                borderWidth: 1,
                borderRadius: 4,
                barPercentage: 0.6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleColor: '#fff',
                    bodyColor: '#e2e8f0',
                    cornerRadius: 8,
                    padding: 10
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: textColor,
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        color: gridColor
                    }
                },
                x: {
                    ticks: {
                        color: textColor,
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
})();
</script>
@endsection
