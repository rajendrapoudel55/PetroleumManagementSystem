<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ ucfirst($report_type) }} Report</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #001f3f;
        }
        .header h1 {
            color: #001f3f;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .info-section {
            margin-bottom: 20px;
            background: #f9fafb;
            padding: 15px;
            border-radius: 5px;
        }
        .info-section p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background: #001f3f;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background: #f9fafb;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Petroleum Management System</h1>
        <h2>{{ ucfirst($report_type) }} Report</h2>
        <p>Generated on {{ date('F j, Y') }}</p>
    </div>

    <div class="info-section">
        <p><strong>Report Type:</strong> {{ ucfirst($report_type) }}</p>
        <p><strong>Date Range:</strong> {{ date('M d, Y', strtotime($from_date)) }} - {{ date('M d, Y', strtotime($to_date)) }}</p>
        <p><strong>Total Records:</strong> {{ count($data) }}</p>
    </div>

    @if($report_type === 'sales')
        @if(!empty($summary))
            <div class="info-section">
                <p><strong>Diesel Sales:</strong> {{ number_format((float) ($summary['diesel_liters'] ?? 0), 2) }}L x NPR {{ number_format((float) ($summary['diesel_rate'] ?? 0), 2) }} = NPR {{ number_format((float) ($summary['diesel_sales'] ?? 0), 2) }}</p>
                <p><strong>Petrol Sales:</strong> {{ number_format((float) ($summary['petrol_liters'] ?? 0), 2) }}L x NPR {{ number_format((float) ($summary['petrol_rate'] ?? 0), 2) }} = NPR {{ number_format((float) ($summary['petrol_sales'] ?? 0), 2) }}</p>
                <p><strong>Total Sales:</strong> NPR {{ number_format((float) ($summary['total_sales'] ?? 0), 2) }}</p>
                <p><strong>Total Cash Sales:</strong> NPR {{ number_format((float) ($summary['total_cash_sales'] ?? 0), 2) }}</p>
                <p><strong>Total Credit Sales:</strong> NPR {{ number_format((float) ($summary['total_credit_sales'] ?? 0), 2) }}</p>
                <p><strong>Expenses:</strong> NPR {{ number_format((float) ($summary['expenses'] ?? 0), 2) }}</p>
                <p><strong>In Bank:</strong> NPR {{ number_format((float) ($summary['in_bank'] ?? 0), 2) }}</p>
            </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Bill #</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Vehicle</th>
                    <th>Payment</th>
                    <th>Txn No.</th>
                    <th class="text-right">Items</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Subtotal</th>
                    <th class="text-right">VAT</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                    <tr>
                        <td>{{ date('Y-m-d', strtotime($item['date'])) }}</td>
                        <td>{{ $item['bill_number'] ?? '-' }}</td>
                        <td>{{ $item['customer_name'] ?? '-' }}</td>
                        <td>{{ $item['phone'] ?? '-' }}</td>
                        <td>{{ $item['vehicle'] ?? '-' }}</td>
                        <td>{{ $item['payment_method'] ?? '-' }}</td>
                        <td>{{ $item['transaction_no'] ?? '-' }}</td>
                        <td class="text-right">{{ (int) ($item['item_count'] ?? 0) }}</td>
                        <td class="text-right">{{ number_format((float) ($item['total_qty'] ?? 0), 2) }}</td>
                        <td class="text-right">NPR {{ number_format((float) ($item['subtotal'] ?? 0), 2) }}</td>
                        <td class="text-right">NPR {{ number_format((float) ($item['vat'] ?? 0), 2) }}</td>
                        <td class="text-right">NPR {{ number_format((float) ($item['total'] ?? 0), 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" style="text-align: center; padding: 20px; color: #999;">No tax invoice sales data available for this period</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @elseif($report_type === 'expenses')
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Vendor</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Payment Method</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                    <tr>
                        <td>{{ date('Y-m-d', strtotime($item['date'])) }}</td>
                        <td>{{ ucfirst($item['type']) }}</td>
                        <td>{{ $item['vendor'] }}</td>
                        <td>{{ $item['category'] }}</td>
                        <td>{{ $item['description'] }}</td>
                        <td>{{ ucfirst($item['payment_method']) }}</td>
                        <td class="text-right">NPR {{ number_format($item['amount'], 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 20px; color: #999;">No expense data available for this period</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @elseif($report_type === 'inventory')
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Product</th>
                    <th>SKU</th>
                    <th class="text-right">Current Stock</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Total Value</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                    <tr>
                        <td>{{ $item['date'] }}</td>
                        <td>{{ $item['product'] }}</td>
                        <td>{{ $item['sku'] }}</td>
                        <td class="text-right">{{ number_format($item['current_stock'], 2) }}</td>
                        <td class="text-right">NPR {{ number_format($item['unit_price'], 2) }}</td>
                        <td class="text-right">NPR {{ number_format($item['total_value'], 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px; color: #999;">No inventory data available for this period</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <div class="footer">
        <p>This is a computer-generated report from Petroleum Management System</p>
        <p>Generated on {{ date('F j, Y \a\t g:i A') }}</p>
    </div>
</body>
</html>
