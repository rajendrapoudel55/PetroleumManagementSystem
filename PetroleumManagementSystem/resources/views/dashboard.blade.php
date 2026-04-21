<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Petroleum Management System PMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="/assets/css/stock.css" rel="stylesheet">
    <link href="{{ asset('assets/css/dashboard.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        body {
            color: #2c3e50;
            box-sizing: border-box;
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 16px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 99999;
            animation: slideIn 0.3s ease-out;
            min-width: 300px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .notification.success {
            background: #dcfce7;
            color: #166534;
            border-left: 4px solid #10b981;
        }

        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }

        .notification.hiding {
            animation: slideOut 0.3s ease-out forwards;
        }

        .card-link {
            display: block;
            color: inherit;
            text-decoration: none;
        }

        .chart-link {
            color: #1e3a5f;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
        }

        .chart-link:hover {
            text-decoration: underline;
        }

        .clickable-chart-card {
            cursor: pointer;
        }
    </style>
</head>
<body>

<!-- Notification Container -->
<div id="notification-container"></div>

<div class="dashboard-wrapper">
    {{-- SIDEBAR --}}
    @include('layouts.sidebar')
        
    <!-- Main Content -->

    <div class="main-content">
        <div class="header">
            <div class="header-content">
                <div class="header-info">
                    <h2>Dashboard Overview</h2>
                    <p>Welcome back! Here's what's happening today.</p>
                </div>
                <div class="header-right">
                    <div class="datetime-display">
                        <p class="current-date" id="current-date">{{ date('l, F j, Y') }}</p>
                        <p class="current-time" id="current-time">{{ date('H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-area">
            @if (session('success'))
                <div class="success-alert">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Stats Grid -->
            <div class="stats-grid">
                <a href="{{ route('taxinvoice.index') }}" class="card-link">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon blue"></div>
                            <span class="stat-badge positive">{{ $salesTrend }}</span>
                        </div>
                        <p class="stat-label">Total Sales</p>
                        <p class="stat-value">Rs.{{ number_format($totalSales) }}</p>
                    </div>
                </a>

                <a href="{{ route('cash.index') }}" class="card-link">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon green"></div>
                            <span class="stat-badge positive">{{ $cashSalesTrend }}</span>
                        </div>
                        <p class="stat-label">Total Cash Sales</p>
                        <p class="stat-value">Rs.{{ number_format($totalCashSales) }}</p>
                    </div>
                </a>

                <a href="{{ route('taxinvoice.index') }}" class="card-link">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon amber"></div>
                            <span class="stat-badge positive">{{ $creditSalesTrend }}</span>
                        </div>
                        <p class="stat-label">Total Credit Sales</p>
                        <p class="stat-value">Rs.{{ number_format($totalCreditSales) }}</p>
                    </div>
                </a>

                <a href="{{ route('expenses.index') }}" class="card-link">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon red"></div>
                            <span class="stat-badge negative">{{ $expenseTrend }}</span>
                        </div>
                        <p class="stat-label">Expenses</p>
                        <p class="stat-value">Rs.{{ number_format($expenses) }}</p>
                    </div>
                </a>

                <a href="{{ route('reports.index') }}" class="card-link">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon green"></div>
                            <span class="stat-badge positive">{{ $bankTrend }}</span>
                        </div>
                        <p class="stat-label">In Bank</p>
                        <p class="stat-value">Rs.{{ number_format($bankDeposit) }}</p>
                    </div>
                </a>
            </div>

            <!-- Charts Section -->
            <div class="charts-container">
                <div class="chart-card clickable-chart-card" onclick="window.location.href='{{ route('reports.index') }}'">
                    <div class="chart-header">
                        <div class="chart-info">
                            <h3>Sales Overview</h3>
                            <p>Weekly sales and expense trend</p>
                        </div>
                        <a href="{{ route('reports.index') }}" class="chart-link">View Details</a>
                    </div>
                    <div class="chart-wrapper">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <div class="chart-card clickable-chart-card" onclick="window.location.href='{{ route('stock.index') }}'">
                    <div class="chart-header">
                        <div class="chart-info">
                            <h3>Stock Distribution</h3>
                            <p>Current inventory levels</p>
                        </div>
                        <a href="{{ route('stock.index') }}" class="chart-link">View Details</a>
                    </div>
                    <div class="chart-wrapper" style="height: 200px;">
                        <canvas id="stockChart"></canvas>
                    </div>
                    <div class="stock-items">
                        <div class="stock-item">
                            <div class="stock-item-info">
                                <div class="stock-color" style="background: #f59e0b;"></div>
                                <span class="stock-label">Petrol</span>
                            </div>
                            <span class="stock-quantity">{{ number_format($petrolStock) }} L</span>
                        </div>
                        <div class="stock-item">
                            <div class="stock-item-info">
                                <div class="stock-color" style="background: #334155;"></div>
                                <span class="stock-label">Diesel</span>
                            </div>
                            <span class="stock-quantity">{{ number_format($dieselStock) }} L</span>
                        </div>
                        <div class="stock-item">
                            <div class="stock-item-info">
                                <div class="stock-color" style="background: #3b82f6;"></div>
                                <span class="stock-label">Lubricants</span>
                            </div>
                            <span class="stock-quantity">{{ number_format($lubricantStock) }} L</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Tax Invoices -->
            <div class="transactions-card">
                <div class="transactions-header">
                    <div>
                        <h3>Recent Tax Invoices</h3>
                        <p>Latest billing activity</p>
                    </div>
                    <a href="{{ route('taxinvoice.index') }}" class="view-all-link">View All</a>
                </div>
                <div class="table-wrapper">
                    <table class="transactions-table">
                        <thead class="table-header">
                            <tr>
                                <th>Bill No.</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Payment</th>
                                <th>Txn No.</th>
                            </tr>
                        </thead>
                        <tbody class="table-body">
                            @forelse($recentTaxInvoices as $invoice)
                            <tr>
                                <td class="tx-id">{{ $invoice->bill_number }}</td>
                                <td>{{ \Carbon\Carbon::parse($invoice->date)->format('Y-m-d') }}</td>
                                <td>{{ $invoice->customer_name ?: 'Walk-in' }}</td>
                                <td class="tx-amount">Rs.{{ number_format((float)$invoice->total, 2) }}</td>
                                <td>{{ ucfirst($invoice->payment_method) }}</td>
                                <td>{{ $invoice->transaction_no ?: '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 18px; color: #64748b;">No recent tax invoices found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Update date/time
    function updateDateTime() {
        const now = new Date();
        const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
        
        document.getElementById('current-date').textContent = now.toLocaleDateString('en-IN', dateOptions);
        document.getElementById('current-time').textContent = now.toLocaleTimeString('en-IN', timeOptions);
    }
    
    updateDateTime();
    setInterval(updateDateTime, 1000);
    
    // Initialize Charts
    const salesLabels = @json($salesChartLabels ?? []);
    const salesSeries = @json($salesChartSeries ?? []);
    const expenseSeries = @json($expenseChartSeries ?? []);
    const stockSeries = [
        Number(@json($petrolStock ?? 0)),
        Number(@json($dieselStock ?? 0)),
        Number(@json($lubricantStock ?? 0)),
    ];

    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'bar',
        data: {
            labels: salesLabels,
            datasets: [
                {
                    label: 'Sales',
                    data: salesSeries,
                    backgroundColor: '#f59e0b',
                    borderRadius: 6,
                    borderSkipped: false,
                },
                {
                    label: 'Expenses',
                    data: expenseSeries,
                    backgroundColor: '#334155',
                    borderRadius: 6,
                    borderSkipped: false,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                }
            },
            scales: {
                x: { grid: { display: false } },
                y: { ticks: { callback: (value) => 'Rs.' + Number(value).toLocaleString() } }
            }
        }
    });
    
    const stockCtx = document.getElementById('stockChart').getContext('2d');
    new Chart(stockCtx, {
        type: 'doughnut',
        data: {
            labels: ['Petrol', 'Diesel', 'Lubricants'],
            datasets: [{
                data: stockSeries,
                backgroundColor: ['#f59e0b', '#334155', '#3b82f6'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: { legend: { display: false } }
        }
    });

    // Show notification for success messages
    function showNotification(message, type = 'success') {
        const container = document.getElementById('notification-container');
        const icon = type === 'success' ? '✅' : '❌';
        
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `<span style="font-size: 18px;">${icon}</span><span>${message}</span>`;
        
        container.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('hiding');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 4000);
    }

    @if (session('success'))
        showNotification("{{ session('success') }}", 'success');
    @endif

    // Close success alert after 5 seconds
    const alert = document.querySelector('.success-alert');
    if (alert) {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.3s ease';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    }
</script>
</body>
</html>