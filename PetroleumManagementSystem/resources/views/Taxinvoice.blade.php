<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tax Invoice - Petroleum Management System</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
    }
    
    .card { background: white; border-radius: 16px; padding: 28px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); border: 1px solid #e5e7eb; transition: all 0.3s ease; }
    .card:hover { box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12); }
    .card + .card { margin-top: 24px; }
    
    .card-title { font-size: 17px; font-weight: 700; color: #111827; margin-bottom: 18px; display: flex; align-items: center; gap: 10px; text-transform: uppercase; letter-spacing: 0.5px; color: #1e40af; }
    
    .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 20px; }
    @media (max-width: 768px) { .form-grid { grid-template-columns: 1fr; } }
    
    .form-group { display: flex; flex-direction: column; }
    .form-group label { font-weight: 600; margin-bottom: 8px; color: #374151; font-size: 13px; text-transform: uppercase; letter-spacing: 0.3px; }
    .form-group label .required { color: #dc2626; }
    
    input, select { padding: 12px 14px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 14px; transition: all 0.3s; font-family: inherit; background: white; }
    input:focus, select:focus { outline: none; border-color: #1e40af; background: #f0f9ff; box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1); }
    input[readonly] { background: #f3f4f6 !important; color: #6b7280; cursor: not-allowed; border-color: #d1d5db; }
    
    .product-section { background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%); padding: 20px; border-radius: 14px; margin-bottom: 20px; border: 1px solid #d1d5db; }
    .product-section .card-title { margin-bottom: 16px; color: #1f2937; }
    
    .product-grid { display: grid; grid-template-columns: 1.8fr 0.7fr 0.7fr 0.75fr 0.85fr; gap: 12px; align-items: flex-end; }
    @media (max-width: 1024px) { .product-grid { grid-template-columns: 1.5fr 0.65fr 0.65fr 0.65fr 0.75fr; gap: 10px; } }
    @media (max-width: 768px) { .product-grid { grid-template-columns: 1fr; gap: 12px; } }
    
    .btn { padding: 12px 18px; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; transition: all 0.3s; font-size: 13px; text-transform: uppercase; letter-spacing: 0.3px; }
    .btn-primary { background: #1e40af; color: white; }
    .btn-primary:hover { background: #1e3a8a; box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3); transform: translateY(-2px); }
    .btn-primary:active { transform: scale(0.98); }
    
    .btn-danger { background: #fee2e2; color: #991b1b; font-size: 12px; padding: 8px 12px; }
    .btn-danger:hover { background: #fecaca; box-shadow: 0 2px 8px rgba(153, 27, 27, 0.2); }
    
    .btn-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; width: 100%; padding: 14px; margin-top: 18px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2); }
    .btn-success:hover { box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3); transform: translateY(-2px); }
    .btn-success:disabled { opacity: 0.5; cursor: not-allowed; }
    
    .btn-secondary { background: #e5e7eb; color: #374151; width: 100%; padding: 12px; margin-top: 10px; }
    .btn-secondary:hover { background: #d1d5db; }
    
    table { width: 100%; border-collapse: collapse; }
    thead { background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%); }
    th { padding: 14px; text-align: left; font-weight: 700; font-size: 12px; color: #374151; border-bottom: 2px solid #d1d5db; text-transform: uppercase; letter-spacing: 0.3px; }
    td { padding: 14px; border-bottom: 1px solid #e5e7eb; font-size: 14px; color: #1f2937; }
    tbody tr:hover { background: #f9fafb; }
    
    .summary-box { background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); padding: 20px; border-radius: 12px; border-left: 4px solid #1e40af; margin: 20px 0; box-shadow: 0 2px 8px rgba(30, 64, 175, 0.1); }
    .summary-row { display: flex; justify-content: space-between; padding: 10px 0; font-size: 14px; font-weight: 500; color: #1f2937; }
    .summary-row.total { border-top: 2px solid #bfdbfe; padding-top: 14px; margin-top: 14px; font-size: 18px; font-weight: 800; color: #1e40af; }
    
    .alert { padding: 14px 16px; border-radius: 10px; margin-bottom: 16px; font-size: 14px; display: none; border-left: 4px solid; }
    .alert.show { display: block; animation: slideIn 0.3s ease; }
    .alert-success { background: #d1fae5; color: #065f46; border-left-color: #10b981; }
    .alert-error { background: #fee2e2; color: #991b1b; border-left-color: #dc2626; }
    
    @keyframes slideIn { from { transform: translateY(-10px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    
    .empty-state { text-align: center; color: #9ca3af; padding: 40px 16px; font-size: 14px; font-style: italic; }
    
    input[type="number"] { text-align: right; }
    
    .divider { height: 1px; background: #e5e7eb; margin: 20px 0; }
  </style>
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
            <h2>Tax Invoice</h2>
            <p>Professional Invoice & Billing System</p>
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
        <div class="container">
          <div id="alert" class="alert"></div>
          <!-- Customer & Bill Info -->
          <div class="card">
     <div class="card-title">
      Bill Details
     </div>
     <div class="form-grid">
      <div class="form-group"><label>Bill Number</label> <input type="text" id="billNum" placeholder="INV-001" readonly>
      </div>
      <div class="form-group"><label>Date</label> <input type="date" id="billDate" readonly>
      </div>
      <div class="form-group"><label>Customer Name</label> <input type="text" id="custName" placeholder="Full name" maxlength="255" pattern="[A-Za-z][A-Za-z .'-]*" title="Use letters and spaces only">
      </div>
      <div class="form-group"><label>Phone</label> <input type="text" id="custPhone" placeholder="10-digit number" inputmode="numeric" maxlength="10" pattern="[0-9]{10}" title="Phone number must be exactly 10 digits" oninput="enforceTenDigitPhoneInput(this)">
      </div>
     </div>
    </div><!-- Add Product -->
    <div class="card">
     <div class="card-title">
      Add Products
     </div>
     <div class="product-section">
      <div class="product-grid">
       <div class="form-group"><label>Product</label> <select id="productSelect" onchange="selectProduct(this.value)"> <option value="">Select Product</option> </select>
       </div>
       <div class="form-group"><label>Rate</label> <input type="text" id="autoRate" readonly>
       </div>
       <div class="form-group"><label>Qty</label> <input type="number" id="addQty" placeholder="0" step="0.01" min="0" oninput="handleQtyOrAmount()">
       </div>
       <div class="form-group"><label>Amount</label> <input type="number" id="addAmount" placeholder="0" step="0.01" min="0" oninput="handleQtyOrAmount()">
       </div><button class="btn btn-primary" onclick="addToBill()">Add</button>
      </div>
     </div>
    </div><!-- Bill Items -->
    <div class="card">
     <div class="card-title">
      Items
     </div>
     <div style="overflow-x: auto;">
      <table>
       <thead>
        <tr>
         <th style="width: 30px;">#</th>
         <th>Product</th>
         <th style="text-align: right; width: 70px;">Rate</th>
         <th style="text-align: center; width: 60px;">Qty</th>
         <th style="text-align: right; width: 70px;">Amount</th>
         <th style="text-align: center; width: 60px;">Action</th>
        </tr>
       </thead>
       <tbody id="billItems">
        <tr id="emptyBill">
         <td colspan="6" class="empty-state">No items yet</td>
        </tr>
       </tbody>
      </table>
     </div>
    </div><!-- Vehicle & Payment Info -->
    <div class="card">
     <div class="card-title">
      Additional Details
     </div>
     <div class="form-grid">
      <div class="form-group"><label>Vehicle Registration</label> <input type="text" id="vehicleReg" placeholder="DL-01-AB-1234">
      </div>
      <div class="form-group"><label>Payment Method <span class="required">*</span></label> <select id="paymentMethod" onchange="toggleTransactionField()"> <option value="">Select Payment</option> <option value="Khalti">Khalti</option> <option value="Cash">Cash</option> <option value="Credit">Credit</option> </select>
      </div>
      <div class="form-group" id="transactionGroup" style="display: none;"><label>Transaction No.</label> <input type="text" id="transactionNo" placeholder="Enter transaction ID">
      </div>
      <div class="form-group" id="khaltiPayGroup" style="display: none;"><label>Khalti Payment</label> <button type="button" class="btn btn-primary" id="khaltiPayBtn" onclick="payWithKhalti()">Pay with Khalti</button>
      </div>
     </div>
    </div><!-- Summary -->
    <div class="card">
     <div class="summary-box">
      <div class="summary-row"><span>Subtotal</span> <span>NPR <span id="subtotal">0.00</span></span>
      </div>
      <div class="summary-row"><span>VAT (13% Included)</span> <span>NPR <span id="vat">0.00</span></span>
      </div>
       <div class="summary-row total"><span>Total</span> <span>NPR <span id="total">0.00</span></span>
      </div>
     </div><button class="btn btn-success" id="saveBill" onclick="saveBill()">Save Bill</button> <button class="btn btn-secondary" onclick="resetForm()">Clear</button>
    </div><!-- Bill History -->
    <div class="card">
     <div class="card-title">
      Recent Bills
     </div>
     <div style="overflow-x: auto;">
      <table>
       <thead>
        <tr>
         <th>Bill #</th>
         <th>Date</th>
         <th>Customer</th>
         <th>Phone</th>
         <th>Vehicle</th>
         <th style="text-align: right;">Amount</th>
         <th style="text-align: center;">Action</th>
        </tr>
       </thead>
       <tbody id="billHistory">
        <tr>
         <td colspan="7" class="empty-state">No bills yet</td>
        </tr>
       </tbody>
      </table>
     </div>
    </div>
   </div>
  </div>
  <script>
    const khaltiConfigured = {{ config('services.khalti.secret_key') ? 'true' : 'false' }};

    let products = [];

    let bills = [];
    let billItemCount = 0;
    let editingId = null;
    let selectedProduct = null;

    function showMessage(message, type = 'success') {
      const alertBox = document.getElementById('alert');
      alertBox.className = `alert alert-${type} show`;
      alertBox.textContent = message;
      setTimeout(() => alertBox.classList.remove('show'), 4500);
    }

    async function parseApiResponse(resp) {
      const isJson = (resp.headers.get('content-type') || '').includes('application/json');
      if (isJson) return await resp.json();
      const text = await resp.text();
      return { isOk: false, error: text || 'Unexpected server response' };
    }

    function getApiErrorMessage(result, fallback) {
      if (result?.error && typeof result.error === 'string') return result.error;
      if (result?.message && typeof result.message === 'string') return result.message;
      const errors = result?.errors;
      if (errors && typeof errors === 'object') {
        const first = Object.values(errors)[0];
        if (Array.isArray(first) && first.length) return String(first[0]);
      }
      return fallback;
    }

    async function fetchWithTimeout(url, options = {}, timeoutMs = 15000) {
      const controller = new AbortController();
      const timer = setTimeout(() => controller.abort(), timeoutMs);
      try {
        return await fetch(url, { ...options, signal: controller.signal });
      } finally {
        clearTimeout(timer);
      }
    }

    function toggleTransactionField() {
      const payMethod = document.getElementById('paymentMethod').value;
      const transactionGroup = document.getElementById('transactionGroup');
      const khaltiGroup = document.getElementById('khaltiPayGroup');
      const khaltiBtn = document.getElementById('khaltiPayBtn');
      
      if (payMethod === 'Khalti') {
        transactionGroup.style.display = 'block';
        khaltiGroup.style.display = 'block';
        
        // Enable/disable Khalti button based on items
        const itemCount = document.querySelectorAll('#billItems tr:not(#emptyBill)').length;
        khaltiBtn.disabled = itemCount === 0;
        khaltiBtn.style.opacity = itemCount === 0 ? '0.5' : '1';
        khaltiBtn.title = itemCount === 0 ? 'Add items before paying with Khalti' : 'Pay with Khalti';
      } else {
        transactionGroup.style.display = 'none';
        khaltiGroup.style.display = 'none';
        document.getElementById('transactionNo').value = '';
      }
    }

    function saveBillDataToSession() {
      const billData = {
        custName: document.getElementById('custName').value.trim(),
        custPhone: document.getElementById('custPhone').value.trim(),
        vehicleReg: document.getElementById('vehicleReg').value.trim(),
        billDate: document.getElementById('billDate').value,
        items: []
      };

      document.querySelectorAll('#billItems tr:not(#emptyBill)').forEach(row => {
        billData.items.push({
          product: row.cells[1].textContent,
          product_id: row.dataset.productId || null,
          product_type: row.dataset.productType || null,
          sku: row.dataset.sku || null,
          rate: parseFloat(row.cells[2].textContent.replace('NPR', '').trim()),
          qty: parseFloat(row.querySelector('.qty').value)
        });
      });

      sessionStorage.setItem('pendingBillData', JSON.stringify(billData));
    }

    function restoreBillDataFromSession() {
      const storedData = sessionStorage.getItem('pendingBillData');
      if (!storedData) return false;

      try {
        const billData = JSON.parse(storedData);
        document.getElementById('custName').value = billData.custName || '';
        document.getElementById('custPhone').value = billData.custPhone || '';
        document.getElementById('vehicleReg').value = billData.vehicleReg || '';
        document.getElementById('billDate').value = billData.billDate || '';

        // Restore bill items
        if (billData.items && billData.items.length > 0) {
          document.getElementById('billItems').innerHTML = '';
          billItemCount = 0;

          billData.items.forEach((item, i) => {
            const row = document.createElement('tr');
            row.id = `bill-${Date.now()}-${i}`;
            row.dataset.rate = String(item.rate);
            row.dataset.productId = String(item.product_id || '');
            row.dataset.productType = String(item.product_type || '');
            row.dataset.sku = String(item.sku || '');
            const amount = item.rate * item.qty;
            row.innerHTML = `
              <td>${i + 1}</td>
              <td>${item.product}</td>
              <td style="text-align: right;">NPR ${item.rate.toFixed(2)}</td>
              <td style="text-align: center;"><input type="number" class="qty" value="${item.qty}" step="0.01" min="0" style="width: 50px; text-align: right; padding: 4px;"></td>
              <td style="text-align: right;">NPR <span>${amount.toFixed(2)}</span></td>
              <td style="text-align: center;"><button class="btn btn-danger" onclick="removeBillItem('${row.id}')">Delete</button></td>
            `;
            document.getElementById('billItems').appendChild(row);
            row.querySelector('.qty').oninput = () => {
              const newQty = parseFloat(row.querySelector('.qty').value) || 0;
              const rowRate = parseFloat(row.dataset.rate) || 0;
              row.querySelector('span').textContent = (rowRate * newQty).toFixed(2);
              calculateBill();
            };
            billItemCount++;
          });

          calculateBill();
          sessionStorage.removeItem('pendingBillData');
          return true;
        }
      } catch (e) {
        console.error('Error restoring bill data:', e);
      }
      return false;
    }

    async function payWithKhalti() {
      if (!khaltiConfigured) {
        showMessage('Khalti is not configured. Please set KHALTI_SECRET_KEY in .env and run php artisan config:clear.', 'error');
        return;
      }

      const total = parseFloat(document.getElementById('total').textContent) || 0;
      if (total <= 0) {
        showMessage('Add bill items before Khalti payment.', 'error');
        return;
      }

      const billNum = document.getElementById('billNum').value.trim();
      const custName = document.getElementById('custName').value.trim();
      const custPhone = document.getElementById('custPhone').value.trim();

      if (custName && !/^[A-Za-z][A-Za-z\s.'-]*$/.test(custName)) {
        showMessage('Customer name can contain letters and spaces only.', 'error');
        return;
      }

      if (custPhone && !/^[0-9]{10}$/.test(custPhone)) {
        showMessage('Phone number must be exactly 10 digits.', 'error');
        return;
      }

      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      const btn = document.getElementById('khaltiPayBtn');

      btn.disabled = true;
      btn.textContent = 'Redirecting...';

      try {
        // Save bill data before redirect
        saveBillDataToSession();

        const resp = await fetch('/api/payments/khalti/initiate', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
          },
          body: JSON.stringify({
            amount: total,
            purchase_order_id: billNum,
            purchase_order_name: `Tax Invoice ${billNum}`,
            customer_name: custName,
            customer_phone: custPhone,
          }),
        });

        const isJson = (resp.headers.get('content-type') || '').includes('application/json');
        const result = isJson ? await resp.json() : { isOk: false, error: await resp.text() };
        if (!result.isOk) {
          showMessage(result.error || 'Khalti initiation failed.', 'error');
          return;
        }

        const paymentUrl = result.data?.payment_url;
        if (!paymentUrl) {
          showMessage('Khalti payment URL missing from response.', 'error');
          return;
        }

        window.location.href = paymentUrl;
      } catch (err) {
        showMessage('Unable to connect to Khalti API.', 'error');
      } finally {
        btn.disabled = false;
        btn.textContent = 'Pay with Khalti';
      }
    }

    async function handleKhaltiReturn() {
      const params = new URLSearchParams(window.location.search);
      const pidx = params.get('pidx');
      const status = (params.get('status') || '').toLowerCase();
      const transactionId = params.get('transaction_id') || '';

      if (!pidx) return;

      // Restore bill data from sessionStorage before verifying payment
      const dataRestored = restoreBillDataFromSession();

      try {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const resp = await fetch('/api/payments/khalti/lookup', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
          },
          body: JSON.stringify({ pidx }),
        });

        const isJson = (resp.headers.get('content-type') || '').includes('application/json');
        const result = isJson ? await resp.json() : { isOk: false, error: await resp.text() };
        if (result.isOk && (status === 'completed' || result.data?.status === 'Completed')) {
          document.getElementById('paymentMethod').value = 'Khalti';
          document.getElementById('transactionNo').value = transactionId || result.data?.transaction_id || pidx;
          toggleTransactionField();
          
          // Auto-save bill if it has items
          const billItems = document.querySelectorAll('#billItems tr:not(#emptyBill)');
          if (billItems.length > 0) {
            showMessage('Khalti payment verified. Auto-saving bill...', 'success');
            setTimeout(() => {
              saveBill();
            }, 1000);
          } else if (dataRestored) {
            showMessage('Khalti payment verified. Bill data restored. Add products and save the bill.', 'success');
          } else {
            showMessage('Khalti payment verified. You can now add products and save the bill.', 'success');
          }
          
          // Clean up URL parameters
          window.history.replaceState({}, document.title, window.location.pathname);
        } else {
          showMessage('Khalti payment verification failed. Please try again.', 'error');
        }
      } catch (e) {
        showMessage('Unable to verify Khalti payment.', 'error');
      }
    }

    async function loadBills() {
      try {
        const resp = await fetch('/api/taxinvoice');
        const result = await resp.json();
        if (result.isOk) { bills = result.data || []; renderHistory(bills); generateNextBillNumber(); }
      } catch (e) { /* silent */ }
    }

    async function init() {
      try {
        document.getElementById('billDate').valueAsDate = new Date();
        
        // Load bills first
        await loadBills();
        
        // Load products with retry logic
        let productLoadAttempts = 0;
        const maxAttempts = 3;
        
        while (productLoadAttempts < maxAttempts && products.length === 0) {
          await loadInventoryProducts();
          if (products.length === 0) {
            productLoadAttempts++;
            if (productLoadAttempts < maxAttempts) {
              await new Promise(resolve => setTimeout(resolve, 1000));
            }
          }
        }
        
        if (products.length === 0) {
          console.warn('No products loaded after 3 attempts');
        }
        
        generateNextBillNumber();
      } catch (e) {
        showMessage('Initialization error: ' + e.message, 'error');
        console.error('Init error:', e);
      }
    }

    async function loadInventoryProducts() {
      try {
        const resp = await fetch('/api/products', {
          headers: { 'Accept': 'application/json' }
        });
        
        if (!resp.ok) {
          showMessage('Failed to load products. Server error: ' + resp.status, 'error');
          return;
        }
        
        const result = await resp.json();

        if (!result.isOk) {
          showMessage('Failed to load inventory products: ' + (result.error || 'Unknown error'), 'error');
          return;
        }

        if (!Array.isArray(result.data)) {
          showMessage('Invalid products data format', 'error');
          return;
        }

        products = result.data.map((item) => ({
          id: item.__backendId,
          type: item.__type || '',
          name: item.product_name || 'Unknown',
          rate: Number(item.selling_price ?? item.cost_price ?? 0),
          stock: Number(item.stock_quantity ?? 0),
          unit: item.unit || '',
          sku: item.sku || 'N/A',
        }));

        if (products.length === 0) {
          showMessage('No products available. Please add products to inventory first.', 'error');
          return;
        }

        populateProducts();
      } catch (e) {
        showMessage('Connection error: Unable to load products. ' + e.message, 'error');
        console.error('Product loading error:', e);
      }
    }

    function generateNextBillNumber() {
      const nextNumber = bills.length + 1;
      const billNum = `INV-${String(nextNumber).padStart(3, '0')}`;
      document.getElementById('billNum').value = billNum;
    }

    function populateProducts() {
      const select = document.getElementById('productSelect');
      select.innerHTML = '<option value="">Select Product</option>';
      products.forEach(p => {
        const opt = document.createElement('option');
        opt.value = p.id;
        opt.textContent = `${p.name} (${p.sku}) - NPR ${p.rate.toFixed(2)}${p.unit ? ` / ${p.unit}` : ''}`;
        select.appendChild(opt);
      });
    }

    function selectProduct(id) {
      selectedProduct = products.find(p => p.id === id);
      if (!selectedProduct) {
        showMessage('Product not found', 'error');
        return;
      }
      document.getElementById('autoRate').value = selectedProduct ? `NPR ${selectedProduct.rate}` : '';
      document.getElementById('addQty').value = '';
      document.getElementById('addAmount').value = '';
    }

    function handleQtyOrAmount() {
      if (!selectedProduct) {
        showMessage('Please select a product first', 'error');
        return;
      }
      const qty = parseFloat(document.getElementById('addQty').value) || 0;
      const amt = parseFloat(document.getElementById('addAmount').value) || 0;
      
      if (document.getElementById('addAmount') === document.activeElement && amt > 0) {
        document.getElementById('addQty').value = (amt / selectedProduct.rate).toFixed(2);
      } else if (document.getElementById('addQty') === document.activeElement && qty > 0) {
        document.getElementById('addAmount').value = (qty * selectedProduct.rate).toFixed(2);
      }
    }

    function addToBill() {
      if (!selectedProduct) {
        showMessage('Please select a product first', 'error');
        return;
      }
      const qty = parseFloat(document.getElementById('addQty').value) || 0;
      if (qty <= 0) {
        showMessage('Please enter a valid quantity', 'error');
        return;
      }

      const row = document.createElement('tr');
      row.id = `bill-${Date.now()}`;
      row.dataset.rate = String(selectedProduct.rate);
      row.dataset.productId = String(selectedProduct.id || '');
      row.dataset.productType = String(selectedProduct.type || '');
      row.dataset.sku = String(selectedProduct.sku || '');
      const amt = selectedProduct.rate * qty;
      row.innerHTML = `
        <td>${billItemCount + 1}</td>
        <td>${selectedProduct.name}</td>
        <td style="text-align: right;">NPR ${selectedProduct.rate.toFixed(2)}</td>
        <td style="text-align: center;"><input type="number" class="qty" value="${qty}" step="0.01" min="0" style="width: 50px; text-align: right; padding: 4px;"></td>
        <td style="text-align: right;">NPR <span>${amt.toFixed(2)}</span></td>
        <td style="text-align: center;"><button class="btn btn-danger" onclick="removeBillItem('${row.id}')">Delete</button></td>
      `;
      
      document.getElementById('billItems').appendChild(row);
      if (document.getElementById('emptyBill')) document.getElementById('emptyBill').remove();
      row.querySelector('.qty').oninput = () => {
        const newQty = parseFloat(row.querySelector('.qty').value) || 0;
        const rowRate = parseFloat(row.dataset.rate) || 0;
        row.querySelector('span').textContent = (rowRate * newQty).toFixed(2);
        calculateBill();
      };

      billItemCount++;
      document.getElementById('productSelect').value = '';
      document.getElementById('autoRate').value = '';
      document.getElementById('addQty').value = '';
      document.getElementById('addAmount').value = '';
      calculateBill();
      toggleTransactionField(); // Update Khalti button state
    }

    function removeBillItem(id) {
      document.getElementById(id).remove();
      billItemCount--;
      if (billItemCount === 0) {
        document.getElementById('billItems').innerHTML = '<tr id="emptyBill"><td colspan="6" class="empty-state">No items yet</td></tr>';
      }
      calculateBill();
      toggleTransactionField(); // Update Khalti button state
    }

    function calculateBill() {
      let sub = 0;
      document.querySelectorAll('#billItems tr').forEach(row => {
        if (row.id === 'emptyBill') return;
        sub += parseFloat(row.querySelector('span').textContent) || 0;
      });
      // Rates are VAT-inclusive, so extract VAT instead of adding it again.
      const gross = sub;
      const vat = gross * (13 / 113);
      const taxable = gross - vat;
      document.getElementById('subtotal').textContent = taxable.toFixed(2);
      document.getElementById('vat').textContent = vat.toFixed(2);
      document.getElementById('total').textContent = gross.toFixed(2);
    }

    function enforceTenDigitPhoneInput(input) {
      const digits = input.value.replace(/\D/g, '').slice(0, 10);
      if (input.value !== digits) {
        input.value = digits;
      }

      input.setCustomValidity(digits.length === 10 || digits.length === 0 ? '' : 'Phone number must be exactly 10 digits.');
    }

    async function saveBill() {
      const btn = document.getElementById('saveBill');
      const billNum = document.getElementById('billNum').value.trim();
      const billDate = document.getElementById('billDate').value;
      const custName = document.getElementById('custName').value.trim();
      const custPhone = document.getElementById('custPhone').value.trim();
      const vehicleReg = document.getElementById('vehicleReg').value.trim();
      const payMethod = document.getElementById('paymentMethod').value;
      const transactionNo = document.getElementById('transactionNo').value.trim();
      const items = [];

      if (custName && !/^[A-Za-z][A-Za-z\s.'-]*$/.test(custName)) {
        showMessage('Customer name can contain letters and spaces only.', 'error');
        return;
      }

      if (custPhone && !/^[0-9]{10}$/.test(custPhone)) {
        showMessage('Phone number must be exactly 10 digits.', 'error');
        return;
      }

      document.querySelectorAll('#billItems tr').forEach(row => {
        if (row.id === 'emptyBill') return;
        items.push({
          product: row.cells[1].textContent,
          product_id: row.dataset.productId || null,
          product_type: row.dataset.productType || null,
          sku: row.dataset.sku || null,
          rate: parseFloat(row.cells[2].textContent.replace('NPR', '').trim()),
          qty: parseFloat(row.querySelector('.qty').value),
          amount: parseFloat(row.querySelector('span').textContent)
        });
      });

      if (!payMethod) {
        showMessage('Required: Select Payment Method', 'error');
        return;
      }

      if (items.length === 0) {
        showMessage('Required: Add at least one product item', 'error');
        return;
      }

      if (payMethod === 'Khalti' && !transactionNo) {
        showMessage('Please complete Khalti payment first to get transaction number.', 'error');
        return;
      }

      const gross = items.reduce((s, i) => s + i.amount, 0);
      const vat = gross * (13 / 113);
      const taxable = gross - vat;
      const data = {
        bill_number: billNum,
        date: billDate,
        customer_name: custName,
        phone: custPhone,
        vehicle: vehicleReg,
        payment_method: payMethod,
        transaction_no: transactionNo,
        items_json: JSON.stringify(items),
        subtotal: taxable,
        gst: vat,
        total: gross
      };

      btn.disabled = true;
      btn.textContent = 'Saving...';
      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      try {
        let result;
        if (editingId) {
          const resp = await fetchWithTimeout(`/api/taxinvoice/${editingId}`, {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': token,
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
          });

          result = await parseApiResponse(resp);

          if (!resp.ok || !result.isOk) {
            showMessage(getApiErrorMessage(result, 'Failed to update bill'), 'error');
            return;
          }

          const idx = bills.findIndex(b => b.__backendId == editingId);
          if (idx >= 0) bills[idx] = result.data;
        } else {
          const resp = await fetchWithTimeout('/api/taxinvoice', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': token,
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
          });

          result = await parseApiResponse(resp);

          if (!resp.ok || !result.isOk) {
            showMessage(getApiErrorMessage(result, 'Failed to save bill'), 'error');
            return;
          }

          bills.push(result.data);
        }

        renderHistory(bills);
        generateNextBillNumber();
        resetForm();
        showMessage('Bill saved successfully', 'success');
        document.getElementById('billItems').innerHTML = '<tr id="emptyBill"><td colspan="6" class="empty-state">No items yet</td></tr>';
        billItemCount = 0;
        editingId = null;
        calculateBill();
        generateNextBillNumber();
      } catch (error) {
        if (error && error.name === 'AbortError') {
          showMessage('Save request timed out. Please try again.', 'error');
        } else {
          showMessage('Save failed. Please check connection and try again.', 'error');
        }
      } finally {
        btn.disabled = false;
        btn.textContent = 'Save Bill';
      }
    }

    function renderHistory(data) {
      const tbody = document.getElementById('billHistory');
      const sorted = [...data].sort((a, b) => new Date(b.date) - new Date(a.date));

      if (sorted.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="empty-state">No bills yet</td></tr>';
        return;
      }

      tbody.innerHTML = sorted.map(b => `
        <tr>
          <td><strong>${b.bill_number}</strong></td>
          <td>${b.date}</td>
          <td>${b.customer_name || '-'}</td>
          <td>${b.phone || '-'}</td>
          <td>${b.vehicle || '-'}</td>
          <td style="text-align: right;">NPR ${Number(b.total || 0).toFixed(2)}</td>
          <td style="text-align: center;">
            <button class="btn btn-danger" style="margin-right: 4px;" onclick="editBill('${b.__backendId}')">Edit</button>
            <button class="btn btn-danger" onclick="deleteBill('${b.__backendId}')">Delete</button>
          </td>
        </tr>
      `).join('');
    }

    function editBill(id) {
      const b = bills.find(x => x.__backendId == id);
      if (!b) return;

      document.getElementById('billNum').value = b.bill_number;
      document.getElementById('billDate').value = b.date;
      document.getElementById('custName').value = b.customer_name || '';
      document.getElementById('custPhone').value = b.phone || '';
      document.getElementById('vehicleReg').value = b.vehicle || '';
      document.getElementById('paymentMethod').value = b.payment_method || '';
      document.getElementById('transactionNo').value = b.transaction_no || '';
      
      if (b.payment_method === 'Khalti') {
        document.getElementById('transactionGroup').style.display = 'block';
        document.getElementById('khaltiPayGroup').style.display = 'block';
      } else {
        document.getElementById('transactionGroup').style.display = 'none';
        document.getElementById('khaltiPayGroup').style.display = 'none';
      }

      const items = JSON.parse(b.items_json || '[]');
      document.getElementById('billItems').innerHTML = '';
      billItemCount = 0;

      items.forEach((item, i) => {
        const safeRate = Number(item.rate || 0);
        const safeQty = Number(item.qty || 0);
        const safeAmount = Number(item.amount || (safeRate * safeQty));
        const row = document.createElement('tr');
        row.id = `bill-${Date.now()}-${i}`;
        row.dataset.rate = String(safeRate);
        row.dataset.productId = String(item.product_id || '');
        row.dataset.productType = String(item.product_type || '');
        row.dataset.sku = String(item.sku || '');
        row.innerHTML = `
          <td>${i + 1}</td>
          <td>${item.product}</td>
          <td style="text-align: right;">NPR ${safeRate.toFixed(2)}</td>
          <td style="text-align: center;"><input type="number" class="qty" value="${safeQty}" step="0.01" min="0" style="width: 50px; text-align: right; padding: 4px;"></td>
          <td style="text-align: right;">NPR <span>${safeAmount.toFixed(2)}</span></td>
          <td style="text-align: center;"><button class="btn btn-danger" onclick="removeBillItem('${row.id}')">Delete</button></td>
        `;
        document.getElementById('billItems').appendChild(row);
        row.querySelector('.qty').oninput = () => {
          const newQty = parseFloat(row.querySelector('.qty').value) || 0;
          const rowRate = parseFloat(row.dataset.rate) || 0;
          row.querySelector('span').textContent = (rowRate * newQty).toFixed(2);
          calculateBill();
        };
        billItemCount++;
      });

      editingId = id;
      calculateBill();
    }

    function resetForm() {
      document.getElementById('billDate').valueAsDate = new Date();
      document.getElementById('custName').value = '';
      document.getElementById('custPhone').value = '';
      document.getElementById('vehicleReg').value = '';
      document.getElementById('paymentMethod').value = '';
      document.getElementById('transactionNo').value = '';
      document.getElementById('transactionGroup').style.display = 'none';
      document.getElementById('khaltiPayGroup').style.display = 'none';

      document.getElementById('productSelect').value = '';
      document.getElementById('autoRate').value = '';
      document.getElementById('addQty').value = '';
      document.getElementById('addAmount').value = '';
      selectedProduct = null;

      document.getElementById('billItems').innerHTML = '<tr id="emptyBill"><td colspan="6" class="empty-state">No items yet</td></tr>';
      billItemCount = 0;
      editingId = null;

      calculateBill();
      generateNextBillNumber();
    }

    async function deleteBill(id) {
      if (!confirm('Delete this bill?')) return;
      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      const resp = await fetch(`/api/taxinvoice/${id}`, {
        method: 'DELETE', headers: { 'X-CSRF-TOKEN': token }
      });
      const result = await resp.json();
      if (result.isOk) { bills = bills.filter(b => b.__backendId != id); renderHistory(bills); generateNextBillNumber(); }
    }

    // Update date and time in header
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

    init();
    handleKhaltiReturn();
  </script>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
