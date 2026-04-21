<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fuel Stock Management</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
  <link href="/assets/css/stock.css" rel="stylesheet">
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

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            height: 100%;
            overflow-y: auto;
        }

        .stock-hero {
            background: linear-gradient(135deg, #001f3f 0%, #003d7a 100%);
            color: white;
            padding: 3rem 2rem;
            border-radius: 16px;
            margin-bottom: 3rem;
            box-shadow: 0 10px 40px rgba(0, 31, 63, 0.3);
            text-align: center;
        }

        .stock-hero h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .stock-hero p {
            font-size: 1.1rem;
            opacity: 0.95;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border-left: 5px solid #ffc107;
        }

        .stat-label {
            font-size: 0.95rem;
            color: #7f8c8d;
            margin-bottom: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #001f3f;
        }

        .stock-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .stock-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 2px solid transparent;
            transition: all 0.3s ease;
            position: relative;
        }

        .stock-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #001f3f 0%, #ffc107 100%);
            border-radius: 16px 16px 0 0;
        }

        .stock-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 35px rgba(0, 31, 63, 0.15);
        }

        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .card-title {
            font-size: 1.3rem;
            color: #001f3f;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
            font-size: 0.9rem;
        }

        .info-label {
            color: #7f8c8d;
            font-weight: 500;
        }

        .info-value {
            color: #001f3f;
            font-weight: 600;
        }

        .quantity-display {
            font-size: 1.8rem;
            font-weight: 700;
            color: #667eea;
            margin: 1rem 0;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .btn-primary {
            background: #001f3f;
            color: white;
        }

        .btn-primary:hover {
            background: #003d7a;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 31, 63, 0.4);
        }

        .card-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #001f3f;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .btn-success:hover {
            background: #059669;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        /* Modal Styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            padding: 1rem;
            overflow-y: auto;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 16px;
            width: 100%;
            max-width: 1100px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            background: linear-gradient(135deg, #001f3f 0%, #003d7a 100%);
            color: white;
            padding: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 16px 16px 0 0;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 1.8rem;
        }

        .close-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 2rem;
            cursor: pointer;
            width: 50px;
            height: 50px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .form-section {
            padding: 2rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .section-title {
            font-size: 1.3rem;
            color: #001f3f;
            margin-bottom: 1.5rem;
            font-weight: 700;
            padding-bottom: 0.75rem;
            border-bottom: 3px solid #ffc107;
            display: inline-block;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
            font-size: 0.95rem;
        }

        .form-group input,
        .form-group select {
            padding: 0.75rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #001f3f;
            box-shadow: 0 0 0 3px rgba(0, 31, 63, 0.1);
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.5rem;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }

        .items-table thead {
            background: #001f3f;
            color: white;
        }

        .items-table th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }

        .items-table td {
            padding: 1rem;
            border-bottom: 1px solid #e0e0e0;
            color: #333;
        }

        .items-table tbody tr:hover {
            background: #f8f9fa;
        }

        .chamber-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .chamber-btn {
            padding: 1rem;
            border: 3px solid #001f3f;
            background: white;
            color: #001f3f;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .chamber-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 31, 63, 0.2);
        }

        .chamber-btn.active {
            background: #ffc107;
            color: #001f3f;
            border-color: #ffc107;
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.4);
        }

        .summary-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #f0f0f0 100%);
            border: 2px solid #001f3f;
            border-radius: 12px;
            padding: 2rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            font-size: 1rem;
            color: #333;
            border-bottom: 1px solid #e0e0e0;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            padding: 1.5rem 0;
            font-size: 1.3rem;
            font-weight: 700;
            color: #001f3f;
            border-top: 2px solid #ffc107;
            margin-top: 1rem;
        }

        .summary-total span:last-child {
            color: #ffc107;
            font-size: 1.5rem;
        }

        .modal-actions {
            padding: 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            background: linear-gradient(135deg, #f8f9fa 0%, #f0f0f0 100%);
            border-radius: 0 0 16px 16px;
        }

        .modal-actions .btn {
            padding: 0.75rem 1.5rem;
            font-size: 0.95rem;
        }

        .add-stock-btn {
            display: inline-block;
            margin-bottom: 2rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .header h1 {
                font-size: 1.8rem;
            }

            .chamber-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .card-actions {
                grid-template-columns: 1fr;
            }

            .modal-actions {
                grid-template-columns: 1fr;
            }
        }
  </style>
      
  <style>@view-transition { navigation: auto; }</style>
  <script src="https://cdn.tailwindcss.com" type="text/javascript"></script>
 </head>
 <meta name="csrf-token" content="{{ csrf_token() }}">

 <body>
    <div class="dashboard-wrapper">
    
      {{-- SIDEBAR --}}
    @include('layouts.sidebar')

     <div class="main-content">
    <div class="header">
     <div class="header-content">
      <div class="header-info">
             <h2>Stock Management</h2>
             <p>Monitor and manage your inventory</p>
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
     <div class="stock-container"><!-- Header -->
    <div class="stock-hero" style="display: none;">
       <h1> Stock Management</h1>
       <p>Monitor and manage your inventory with ease</p>
      </div><!-- Stats -->
    <div style="display:flex; justify-content:flex-end; margin-bottom:1rem;">
     <button type="button" class="btn btn-primary add-stock-btn" onclick="openAddStock()">+ Add Stock</button>
     
    </div>
    <div class="stats-grid">
    <div class="stat-card">
     <div class="stat-label">
      Current Petrol Stock
     </div>
     <div class="stat-value">
      {{ number_format($petrol?->current_quantity ?? 0, 0) }} L
     </div>
    </div>
    <div class="stat-card">
     <div class="stat-label">
      Current Diesel Stock
     </div>
     <div class="stat-value">
      {{ number_format($diesel?->current_quantity ?? 0, 0) }} L
     </div>
    </div>
    <div class="stat-card">
     <div class="stat-label">
      Total Stock Value
     </div>
     <div class="stat-value">
      NPR{{ number_format($totalValue ?? 0, 0) }}
     </div>
    </div>
   </div><!-- Stock Cards -->
   <div class="stock-grid">
   @forelse ($stocks as $stock)
    @php
        $fuelType = strtolower((string) $stock->fuel_type);
        if (str_contains($fuelType, 'diesel')) {
            $resolvedCode = 'HSD';
        } elseif (str_contains($fuelType, 'petrol')) {
            $resolvedCode = 'MS';
        } elseif (str_contains($fuelType, 'lub')) {
            $resolvedCode = 'LUB';
        } else {
            $resolvedCode = strtoupper((string) $stock->fuel_code);
        }
        $meta = $cardMeta[$resolvedCode] ?? ['icon' => '⛽', 'title' => $stock->fuel_type, 'color' => '#667eea'];
    @endphp
    <div class="stock-card">
     <div class="card-icon">
      {{ $meta['icon'] }}
     </div>
     <div class="card-title">
      {{ $meta['title'] }}
     </div>
     <div class="quantity-display" style="color: {{ $meta['color'] }};">
      {{ number_format($stock->current_quantity ?? 0, 0) }} L
     </div>
     <div class="info-row"><span class="info-label">Rate:</span> <span class="info-value">NPR{{ number_format($stock->unit_price ?? 0, 2) }}/L</span>
     </div>
     <div class="info-row"><span class="info-label">Total Value:</span> <span class="info-value">NPR{{ number_format($stock->total_value ?? 0, 0) }}</span>
     </div>
     <div class="info-row"><span class="info-label">Last Updated:</span> <span class="info-value">{{ $stock->updated_at?->format('M d, Y h:i A') ?? 'N/A' }}</span>
     </div>
     <div class="card-actions"><button type="button" class="btn btn-primary" onclick="openAddStock('{{ $stock->fuel_code }}')">+ Add Stock</button>

         <button class="btn btn-secondary" onclick="openStockDetails('{{ $resolvedCode }}', '{{ $meta['title'] }}')">Details</button>
     </div>
    </div>
   @empty
    <div class="stock-card">
     <div class="card-title">No stock records found</div>
    </div>
   @endforelse
    </div>
  </div>

  <div id="stockDetailsModal" class="modal">
    <div class="modal-content" style="max-width: 900px;">
     <div class="modal-header">
      <h2 id="details-title">Stock Reduction Details</h2><button class="close-btn" onclick="closeStockDetails()">×</button>
     </div>
     <div class="form-section">
      <div class="form-grid" style="grid-template-columns: 1fr 1fr 1fr; align-items: end;">
        <div class="form-group"><label>Filter</label>
         <select id="details-period"> <option value="day">Per Day</option> <option value="week">1 Week</option> <option value="month">Per Month</option> <option value="year">Per Year</option> </select>
        </div>
        <div class="form-group"><label>Reference Date</label> <input type="date" id="details-date">
        </div>
        <div class="form-group"><button class="btn btn-primary" type="button" onclick="loadStockDetails()">Apply Filter</button>
        </div>
      </div>

      <div class="stats-grid" style="margin-top: 1rem;">
        <div class="stat-card">
         <div class="stat-label">Total Reduced</div>
         <div class="stat-value" id="details-total-reduced">0.00 L</div>
        </div>
        <div class="stat-card">
         <div class="stat-label">Current Stock</div>
         <div class="stat-value" id="details-current-stock">0.00 L</div>
        </div>
        <div class="stat-card">
         <div class="stat-label">Range</div>
         <div class="stat-value" id="details-range" style="font-size: 1.1rem;">-</div>
        </div>
      </div>

      <table class="items-table">
        <thead>
         <tr>
          <th>Date</th>
          <th style="text-align:right;">Reduced (L)</th>
         </tr>
        </thead>
        <tbody id="details-table-body">
         <tr><td colspan="2" style="text-align:center; color:#7f8c8d;">Select filter to view details</td></tr>
        </tbody>
      </table>
     </div>
    </div>
  </div>

  <!-- Add Stock Modal -->
  <div id="addStockModal" class="modal">
   <div class="modal-content"><!-- Header -->
    <div class="modal-header">
     <h2> Add New Stock</h2><button class="close-btn" onclick="closeAddStock()">×</button>
    </div><!-- Purchase Details -->
    <div class="form-section">
     <h3 class="section-title">Purchase Details</h3>
     <div class="form-grid">
      <div class="form-group"><label>Voucher No *</label> <input type="text" id="voucherNo" placeholder="Auto-generated" readonly>
      </div>
      <div class="form-group"><label>Invoice No *</label> <input type="text" id="invoiceNo" placeholder="Enter invoice number">
      </div>
      <div class="form-group"><label>Invoice Date *</label> <input type="date" id="invoiceDate">
      </div>
      <div class="form-group"><label>Payment Mode *</label> <select id="paymentMode"> <option>Select</option> <option>Cash</option> <option>Cheque</option> <option>Online Transfer</option> <option>Card</option> </select>
      </div>
     </div>
     <div class="form-grid">
    <div class="form-group"><label>Party/Vendor Name *</label> <input type="text" id="partyName" placeholder="Enter vendor name" maxlength="255" pattern="[A-Za-z0-9][A-Za-z0-9 .,&'-]*" title="Use letters, numbers, spaces and . , & ' -">
      </div>
      <div class="form-group"><label>Address *</label> <input type="text" id="partyAddress" placeholder="Enter address">
      </div>
        <div class="form-group"><label>Phone Number *</label> <input type="tel" id="partyPhone" placeholder="Enter 10-digit phone number" inputmode="numeric" maxlength="10" pattern="[0-9]{10}" title="Phone number must be exactly 10 digits" oninput="enforceTenDigitPhoneInput(this)">
      </div>
      <div class="form-group"><label>Tax *</label> <input type="text" id="taxNo" placeholder="Enter VAT number">
      </div>
     </div>
    </div><!-- Item Entry -->
    <div class="form-section">
     <h3 class="section-title">Add Items</h3>
     <div class="form-grid">
      <div class="form-group"><label>Product Type *</label> <select id="productType" onchange="handleProductChange()"> <option>Select</option> <option value="MS">MS - Petrol</option> <option value="HSD">HSD - Diesel</option> <option value="LUB">Lubricants</option> </select>
      </div>
      <div class="form-group"><label>Quantity (L) *</label> <input type="number" id="itemQty" placeholder="0.00" step="0.01">
      </div>
      <div class="form-group"><label>Unit Rate (NPR) *</label> <input type="number" id="itemRate" placeholder="0.00" step="0.01">
      </div>
      <div class="form-group"><label>Discount %</label> <input type="number" id="itemDiscount" placeholder="0" step="0.01" value="0">
      </div>
     </div>
     <div style="display: flex; gap: 1rem; margin-top: 1rem;">
        <button type="button" class="btn btn-primary" onclick="addItem()">+ Add Item</button>

        <button type="button" class="btn btn-secondary" onclick="clearItemForm()">Clear</button>
 
     </div><!-- Items Table -->
     <table class="items-table">
      <thead>
       <tr>
        <th>Product</th>
        <th>Qty (L)</th>
        <th>Rate (NPR)</th>
        <th>Discount %</th>
        <th>Amount</th>
        <th>Action</th>
       </tr>
      </thead>
      <tbody id="itemsTableBody">
      </tbody>
     </table>
    </div><!-- Vehicle Information -->
    <div class="form-section">
     <h3 class="section-title">Vehicle Information</h3>
     <div class="form-grid">
      <div class="form-group"><label>Vehicle No *</label> <input type="text" id="vehicleNo" placeholder="e.g., HR26AB1234">
      </div>
      <div class="form-group"><label>Vehicle Type *</label> <select id="vehicleType"> <option>Select</option> <option>Truck</option> <option>Bus</option> <option>Car</option> <option>Bike</option> <option>Auto</option> </select>
      </div>
     </div>
    </div><!-- Chamber Selection -->
    <div class="form-section" id="chamberSection" style="display: none;">
     <h3 class="section-title">Chamber Selection - Storage Location</h3>
     <div class="chamber-grid">
<button type="button" class="chamber-btn" onclick="selectChamber(1, this)">Chamber 1</button>
<button type="button" class="chamber-btn" onclick="selectChamber(2, this)">Chamber 2</button>
<button type="button" class="chamber-btn" onclick="selectChamber(3, this)">Chamber 3</button>
<button type="button" class="chamber-btn" onclick="selectChamber(4, this)">Chamber 4</button>
<button type="button" class="chamber-btn" onclick="selectChamber(5, this)">Chamber 5</button>

     </div>
     <div style="margin-top: 1rem; padding: 1rem; background: #f0f0f0; border-radius: 8px;">
      <p><strong>Selected Chamber:</strong> <span id="selectedChamber">None</span></p>
     </div>
    </div><!-- Fuel Properties -->
    <div class="form-section" id="fuelPropertiesSection" style="display: none;">
     <h3 class="section-title">Fuel Properties</h3>
     <div class="form-grid">
      <div class="form-group"><label>Density (kg/m³)</label> <input type="number" id="fuelDensity" placeholder="e.g., 750" step="0.01">
      </div>
      <div class="form-group"><label>Temperature (°C)</label> <input type="number" id="fuelTemperature" placeholder="e.g., 25" step="0.01">
      </div>
      <div class="form-group"><label>FBP (Flash Boiling Point)</label> <input type="number" id="fuelFBP" placeholder="e.g., 200" step="0.01">
      </div>
     </div>
    </div><!-- Summary -->
    <div class="form-section">
     <h3 class="section-title">Amount Summary</h3>
     <div class="summary-box">
    <div class="summary-row"><span>Subtotal (Before Tax):</span> <span id="subtotal">NPR0.00</span>
      </div>
    <div class="summary-row"><span>Tax (13% VAT):</span> <span id="taxAmount">NPR0.00</span>
      </div>
      <div class="summary-row"><span>Extra Charges:</span> <input type="number" id="extraCharges" placeholder="0.00" step="0.01" value="0" style="width: 150px; padding: 0.5rem; border: 1px solid #ccc;" onchange="calculateTotals()">
      </div>
      <div class="summary-row"><span>Rounding:</span> <select id="rounding" style="width: 150px; padding: 0.5rem; border: 1px solid #ccc;" onchange="calculateTotals()"> <option value="0">No Rounding</option> <option value="0.5">Round to 0.50</option> <option value="1">Round to 1.00</option> </select>
      </div>
    <div class="summary-total"><span>TOTAL AMOUNT:</span> <span id="totalAmount">NPR0.00</span>
      </div>
     </div>
    </div><!-- Action Buttons -->
    <div class="modal-actions"><button class="btn btn-success" onclick="prepareVoucherForSubmit()">✓ Save Voucher</button> <button class="btn btn-primary" onclick="printVoucher()"> Print Voucher</button> <button class="btn btn-secondary" onclick="resetVoucher()">↻ Reset Voucher</button> <button class="btn btn-danger" onclick="closeAddStock()">✕ Exit Voucher</button>
    </div>
     </div>
    </div>
        </div>

<!-- Hidden voucher submission form (outside JS) -->
<form id="voucherSubmitForm" method="POST" action="{{ route('stock.voucher.save') }}" style="display:none;">
    @csrf
    <input type="hidden" name="voucher_number" id="voucher_number">
    <input type="hidden" name="invoice_number" id="invoice_number">
    <input type="hidden" name="invoice_date" id="invoice_date">
    <input type="hidden" name="payment_mode" id="payment_mode">
    <input type="hidden" name="party_name" id="party_name_input">
    <input type="hidden" name="address" id="address_input">
    <input type="hidden" name="phone_number" id="phone_input">
    <input type="hidden" name="tax_number" id="tax_input_field">
    <input type="hidden" name="vehicle_number" id="vehicle_number_input">
    <input type="hidden" name="extra_charge" id="extra_charge_input">
    <input type="hidden" name="rounding" id="rounding_input">
    <input type="hidden" name="before_tax_total" id="before_tax_total_input">
    <input type="hidden" name="subtotal" id="subtotal_input">
    <input type="hidden" name="tax_amount" id="tax_input">
    <input type="hidden" name="total_amount" id="total_input">
    <input type="hidden" name="chambers" id="chambers_input">
    <input type="hidden" name="items" id="items_input">
</form>

<script>
                let items = [];
        let nextVoucherNumber = @json($nextStockVoucherNumber ?? 'V1001');
        let selectedChamber = null;
    let detailsFuelCode = null;

        function openAddStock(productCode = null) {
            document.getElementById('addStockModal').classList.add('active');
            document.getElementById('voucherNo').value = nextVoucherNumber;
            document.getElementById('invoiceDate').valueAsDate = new Date();

            if (productCode) {
                const productSelect = document.getElementById('productType');
                productSelect.value = productCode;
                handleProductChange();
            }
        }

        function closeAddStock() {
            document.getElementById('addStockModal').classList.remove('active');
        }

        function openStockDetails(fuelCode, fuelTitle) {
            detailsFuelCode = fuelCode;
            document.getElementById('details-title').textContent = `${fuelTitle} - Stock Reduction`;
            document.getElementById('details-date').valueAsDate = new Date();
            document.getElementById('details-period').value = 'day';
            document.getElementById('stockDetailsModal').classList.add('active');
            loadStockDetails();
        }

        function closeStockDetails() {
            document.getElementById('stockDetailsModal').classList.remove('active');
            detailsFuelCode = null;
        }

        async function loadStockDetails() {
            if (!detailsFuelCode) return;

            const period = document.getElementById('details-period').value;
            const date = document.getElementById('details-date').value;
            const tbody = document.getElementById('details-table-body');

            tbody.innerHTML = '<tr><td colspan="2" style="text-align:center;">Loading...</td></tr>';

            try {
                const response = await fetch(`/api/stock/reduction/${detailsFuelCode}?period=${encodeURIComponent(period)}&date=${encodeURIComponent(date)}`);
                const result = await response.json();

                if (!result.isOk) {
                    tbody.innerHTML = `<tr><td colspan="2" style="text-align:center; color:#ef4444;">${result.error || 'Failed to load details'}</td></tr>`;
                    return;
                }

                const data = result.data;
                document.getElementById('details-total-reduced').textContent = `${Number(data.total_reduced_liters).toFixed(2)} L`;
                document.getElementById('details-current-stock').textContent = `${Number(data.current_stock_liters).toFixed(2)} L`;
                document.getElementById('details-range').textContent = `${data.start_date} to ${data.end_date}`;

                if (!data.rows || data.rows.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="2" style="text-align:center; color:#7f8c8d;">No reduction data for selected range</td></tr>';
                    return;
                }

                tbody.innerHTML = data.rows.map(row => `
                    <tr>
                        <td>${row.date}</td>
                        <td style="text-align:right; font-weight:600;">${Number(row.reduced_liters).toFixed(2)} L</td>
                    </tr>
                `).join('');
            } catch (error) {
                tbody.innerHTML = '<tr><td colspan="2" style="text-align:center; color:#ef4444;">Error loading reduction details</td></tr>';
            }
        }

        function handleProductChange() {
            const product = document.getElementById('productType').value;
            const isFuel = product === 'MS' || product === 'HSD';
            document.getElementById('chamberSection').style.display = isFuel ? 'block' : 'none';
            document.getElementById('fuelPropertiesSection').style.display = isFuel ? 'block' : 'none';
        }

       function selectChamber(num, el) {
    selectedChamber = num;
    document.querySelectorAll('.chamber-btn').forEach(btn => btn.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('selectedChamber').textContent = 'Chamber ' + num;
}
function addItem() {
    const product = document.getElementById('productType').value;
    const qty = parseFloat(document.getElementById('itemQty').value);
    const rate = parseFloat(document.getElementById('itemRate').value);
    const discount = parseFloat(document.getElementById('itemDiscount').value) || 0;

    if (!product || product === 'Select') {
        alert('Select product');
        return;
    }
    if (!qty || qty <= 0) {
        alert('Enter quantity');
        return;
    }
    if (!rate || rate <= 0) {
        alert('Enter rate');
        return;
    }

    const amount = qty * rate - (qty * rate * discount / 100);

    items.push({
        id: Date.now(),
        product,
        qty,
        rate,
        discount,
        amount,
        chamber: selectedChamber ?? 'N/A'
    });

    renderItems();
    calculateTotals();
    clearItemForm();
}




        function renderItems() {
            const tbody = document.getElementById('itemsTableBody');
            tbody.innerHTML = items.map(item => `
                <tr>
                    <td>${item.product}</td>
                    <td>${item.qty.toFixed(2)}</td>
                    <td>NPR${item.rate.toFixed(2)}</td>
                    <td>${item.discount.toFixed(2)}%</td>
                    <td>NPR${item.amount.toFixed(2)}</td>
                    <td><button class="btn btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;" onclick="deleteItem(${item.id})">Delete</button></td>
                </tr>
            `).join('');
        }

        function deleteItem(id) {
            items = items.filter(item => item.id !== id);
            renderItems();
            calculateTotals();
        }

        function clearItemForm() {
            document.getElementById('productType').value = 'Select';
            document.getElementById('itemQty').value = '';
            document.getElementById('itemRate').value = '';
            document.getElementById('itemDiscount').value = '0';
            selectedChamber = null;
            document.getElementById('selectedChamber').textContent = 'None';
            document.querySelectorAll('.chamber-btn').forEach(btn => btn.classList.remove('active'));
        }

        function calculateTotals() {
            const subtotal = items.reduce((sum, item) => sum + item.amount, 0);
            const tax = subtotal * 0.13;
            const extraCharges = parseFloat(document.getElementById('extraCharges').value) || 0;
            let total = subtotal + tax + extraCharges;
            
            const rounding = parseFloat(document.getElementById('rounding').value) || 0;
            if (rounding > 0) {
                total = Math.round(total / rounding) * rounding;
            }

            document.getElementById('subtotal').textContent = 'NPR' + subtotal.toFixed(2);
            document.getElementById('taxAmount').textContent = 'NPR' + tax.toFixed(2);
            document.getElementById('totalAmount').textContent = 'NPR' + total.toFixed(2);
        }

        function enforceTenDigitPhoneInput(input) {
            const digits = input.value.replace(/\D/g, '').slice(0, 10);
            if (input.value !== digits) {
                input.value = digits;
            }

            input.setCustomValidity(digits.length === 10 || digits.length === 0 ? '' : 'Phone number must be exactly 10 digits.');
        }

        function saveVoucher() {

    const data = {
        invoice_number: document.getElementById('invoiceNo').value,
        invoice_date: document.getElementById('invoiceDate').value,
        payment_mode: document.getElementById('paymentMode').value,
        party_name: document.getElementById('partyName').value,
        address: document.getElementById('partyAddress').value,
        phone_number: document.getElementById('partyPhone').value,
        tax_number: document.getElementById('taxNo').value,
        vehicle_number: document.getElementById('vehicleNo').value,
        density: document.getElementById('fuelDensity')?.value,
        temperature: document.getElementById('fuelTemperature')?.value,
        fbp_chamber: document.getElementById('fuelFBP')?.value,
        chambers: items.map(i => i.chamber),
        subtotal: parseFloat(document.getElementById('subtotal').innerText.replace('NPR','')),
        tax_amount: parseFloat(document.getElementById('taxAmount').innerText.replace('NPR','')),
        total_amount: parseFloat(document.getElementById('totalAmount').innerText.replace('NPR','')),
        extra_charge: document.getElementById('extraCharges').value,
        rounding: document.getElementById('rounding').value,
    };

    // For now, saveVoucher can log or be extended to do AJAX saving.

}

function prepareVoucherForSubmit() {
    if (items.length === 0) {
        alert('Please add items before saving the voucher');
        return false;
    }

    const partyPhone = (document.getElementById('partyPhone').value || '').trim();
    if (!/^[0-9]{10}$/.test(partyPhone)) {
        alert('Phone number must be exactly 10 digits.');
        return false;
    }

    const voucherValue = document.getElementById('voucherNo').value || '';
    const invoiceValue = document.getElementById('invoiceNo').value || voucherValue;
    const invoiceDateValue = document.getElementById('invoiceDate').value || new Date().toISOString().slice(0, 10);

    document.getElementById('voucher_number').value = voucherValue;
    document.getElementById('invoice_number').value = invoiceValue;
    document.getElementById('invoice_date').value = invoiceDateValue;
    document.getElementById('payment_mode').value = document.getElementById('paymentMode').value || '';
    document.getElementById('party_name_input').value = document.getElementById('partyName').value || '';
    document.getElementById('address_input').value = document.getElementById('partyAddress').value || '';
    document.getElementById('phone_input').value = document.getElementById('partyPhone').value || '';
    document.getElementById('tax_input_field').value = document.getElementById('taxNo').value || '';
    document.getElementById('vehicle_number_input').value = document.getElementById('vehicleNo').value || '';
    document.getElementById('extra_charge_input').value = document.getElementById('extraCharges').value || '0';
    document.getElementById('rounding_input').value = document.getElementById('rounding').value || '0';

    // before_tax_total — use subtotal (before tax) if available
    const beforeTax = parseFloat(document.getElementById('subtotal').textContent.replace('NPR','')) || 0;
    document.getElementById('before_tax_total_input').value = beforeTax.toFixed(2);

    document.getElementById('subtotal_input').value = beforeTax.toFixed(2) || '0';
    document.getElementById('tax_input').value = document.getElementById('taxAmount').textContent.replace('NPR','') || '0';
    document.getElementById('total_input').value = document.getElementById('totalAmount').textContent.replace('NPR','') || '0';
    document.getElementById('chambers_input').value = JSON.stringify(items.map(i => i.chamber));
    document.getElementById('items_input').value = JSON.stringify(items);
    // build debug object and log it before submit
    const payload = {
        voucher_number: document.getElementById('voucher_number').value,
        invoice_number: document.getElementById('invoice_number').value,
        invoice_date: document.getElementById('invoice_date').value,
        payment_mode: document.getElementById('payment_mode').value,
        party_name: document.getElementById('party_name_input').value,
        address: document.getElementById('address_input').value,
        phone_number: document.getElementById('phone_input').value,
        tax_number: document.getElementById('tax_input_field').value,
        vehicle_number: document.getElementById('vehicle_number_input').value,
        subtotal: document.getElementById('subtotal_input').value,
        tax_amount: document.getElementById('tax_input').value,
        total_amount: document.getElementById('total_input').value,
        density: (document.getElementById('fuelDensity')?.value || '').trim(),
        temperature: (document.getElementById('fuelTemperature')?.value || '').trim(),
        fbp_chamber: (document.getElementById('fuelFBP')?.value || '').trim(),
        chambers: (document.getElementById('chambers_input').value || '[]'),
        items: (document.getElementById('items_input').value || '[]'),
    };
    // If browser supports fetch, submit via AJAX to get immediate feedback
    const url = '{{ route('stock.voucher.save') }}';
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const formData = new FormData();
    Object.entries(payload).forEach(([k, v]) => {
        if (typeof v === 'object') formData.append(k, JSON.stringify(v));
        else formData.append(k, v);
    });
    formData.append('_token', token);

    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'Accept': 'application/json'
        }
    }).then(async res => {
        if (res.ok) {
            const data = await res.json().catch(() => ({}));
            if (data.data?.next_voucher_number) {
                nextVoucherNumber = data.data.next_voucher_number;
            }
            alert(data.message || 'Voucher saved successfully');
            // close modal and reset
            closeAddStock();
            resetVoucher();
        } else if (res.status === 422) {
            const json = await res.json();
            const errs = json.errors || {};
            alert('Validation failed: ' + Object.values(errs).flat().join('\n'));
        } else {
            const txt = await res.text();
            alert('Server error: ' + (txt || res.statusText));
        }
    }).catch(err => {
        alert('Network error: ' + err.message);
    });

    return false;
}

function printVoucher() {
            if (items.length === 0) {
                alert('Please add items before printing');
                return;
            }

            const printWin = window.open('', '', 'width=800,height=600');
            printWin.document.write(`
                <html>
                <head>
                    <style>
                        body { font-family: Arial; margin: 20px; color: #001f3f; }
                        .header { background: #667eea; color: white; padding: 20px; }
                        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                        th { background: #667eea; color: white; padding: 10px; text-align: left; }
                        td { border-bottom: 1px solid #ddd; padding: 10px; }
                        .summary { margin-top: 30px; }
                        .total { font-size: 18px; font-weight: bold; color: #667eea; }
                    </style>
                </head>
                <body>
                    <div class="header"><h1>📋 FUEL STOCK VOUCHER</h1></div>
                    <p><strong>Voucher No:</strong> ${document.getElementById('voucherNo').value}</p>
                    <p><strong>Invoice:</strong> ${document.getElementById('invoiceNo').value} | <strong>Date:</strong> ${document.getElementById('invoiceDate').value}</p>
                    <p><strong>Party:</strong> ${document.getElementById('partyName').value}</p>
                    <p><strong>Vehicle:</strong> ${document.getElementById('vehicleNo').value}</p>
                    
                    <table>
                        <tr><th>Product</th><th>Qty</th><th>Rate (NPR)</th><th>Amount</th></tr>
                        ${items.map(i => `<tr><td>${i.product}</td><td>${i.qty}</td><td>NPR${i.rate}</td><td>NPR${i.amount.toFixed(2)}</td></tr>`).join('')}
                    </table>
                    
                    <div class="summary">
                        <p class="total">Total: ${document.getElementById('totalAmount').textContent}</p>
                    </div>
                </body>
                </html>
            `);
            printWin.document.close();
            printWin.print();
        }

        function resetVoucher() {
            if (confirm('Are you sure you want to reset everything?')) {
                items = [];
                renderItems();
                document.getElementById('voucherNo').value = nextVoucherNumber;
                document.getElementById('invoiceNo').value = '';
                document.getElementById('invoiceDate').valueAsDate = new Date();
                document.getElementById('paymentMode').value = 'Select';
                document.getElementById('partyName').value = '';
                document.getElementById('partyAddress').value = '';
                document.getElementById('partyPhone').value = '';
                document.getElementById('taxNo').value = '';
                document.getElementById('vehicleNo').value = '';
                document.getElementById('vehicleType').value = 'Select';
                document.getElementById('extraCharges').value = '0';
                document.getElementById('rounding').value = '0';
                calculateTotals();
            }
        }
        function updateDateTime() {
            const now = new Date();
            const dateEl = document.getElementById('current-date');
            const timeEl = document.getElementById('current-time');
            if (dateEl) {
                dateEl.textContent = now.toLocaleDateString(undefined, {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            }
            if (timeEl) {
                timeEl.textContent = now.toLocaleTimeString(undefined, { hour12: false });
            }
        }

        updateDateTime();
        setInterval(updateDateTime, 1000);
    </script>
</div></div></div></body>
</html>