<!doctype html>
<html lang="en" class="h-full">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cash Denomination Record</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
  <link href="/assets/css/stock.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
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

    .input-field {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid #d1d5db;
      border-radius: 6px;
      font-size: 16px;
      transition: all 0.2s ease;
      background: white;
      text-align: center;
      font-weight: 600;
    }
    .input-field:focus {
      outline: none;
      border-color: #1e3a5f;
      box-shadow: 0 0 0 2px rgba(30, 58, 95, 0.1);
    }
    .denomination-row {
      display: grid;
      grid-template-columns: 80px 1fr 100px 1fr;
      gap: 12px;
      align-items: center;
      padding: 12px;
      background-color: rgba(30, 58, 138, 0.05);
      border-bottom: 1px solid rgba(30, 58, 138, 0.15);
    }
    .denomination-value {
      font-weight: 600;
      color: #1e40af;
    }
    .section-header {
      background: #1e3a5f;
      color: white;
      padding: 16px;
      margin-top: 0;
      margin-bottom: 0;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-size: 14px;
    }
    .summary-row {
      display: grid;
      grid-template-columns: 1fr 150px;
      gap: 16px;
      align-items: center;
      padding: 12px 16px;
      border-bottom: 1px solid #e5e7eb;
      background: white;
    }
    .summary-label {
      color: #2c3e50;
      font-size: 14px;
      font-weight: 500;
    }
    .summary-value {
      text-align: right;
      font-weight: 700;
      color: #1e3a5f;
      font-size: 16px;
    }
    .diff-positive {
      color: #34d399;
    }
    .diff-negative {
      color: #f87171;
    }
    .diff-neutral {
      color: #fbbf24;
    }
    .button-group {
      display: flex;
      gap: 12px;
      margin-top: 24px;
    }
    .btn {
      padding: 12px 28px;
      border-radius: 6px;
      border: none;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
      font-family: 'Poppins', sans-serif;
      font-size: 14px;
    }
    .btn-primary {
      background: #1e3a5f;
      color: white;
      flex: 1;
    }
    .btn-primary:hover:not(:disabled) {
      background: #152947;
    }
    .btn-secondary {
      background: #f3f4f6;
      color: #2c3e50;
      padding: 12px 24px;
    }
    .btn-secondary:hover:not(:disabled) {
      background: #e5e7eb;
    }
    .btn:disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }
    .cash-card {
      background: white;
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
      overflow: hidden;
      margin-bottom: 24px;
    }
    .denomination-card {
      background: #f9fafb;
      padding: 16px;
      rounded: 8px;
      border: 1px solid #e5e7eb;
    }
    @keyframes slideIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .fade-in {
      animation: slideIn 0.3s ease-out forwards;
    }
    .message {
      padding: 12px 16px;
      border-radius: 6px;
      margin: 12px 0;
      font-weight: 500;
      text-align: center;
    }
    .message.success {
      background: #dcfce7;
      color: #166534;
    }
    .message.error {
      background: #fee2e2;
      color: #991b1b;
    }
  </style>
  <style>@view-transition { navigation: auto; }</style>
 </head>
 <body class="h-full bg-white text-blue-900 overflow-auto">
  <div class="dashboard-wrapper" style="display: flex; height: 100%;">
   {{-- SIDEBAR --}}
   @include('layouts.sidebar')
   
   {{-- PAGE CONTENT --}}
   <div class="main-content" style="flex: 1; overflow-y: auto;">
    
    <!-- Header -->
    <div class="header">
     <div class="header-content">
      <div class="header-info">
       <h2>Cash Denomination Record</h2>
       <p>Daily Cash & Cheque Reconciliation System</p>
      </div>
      <div class="header-right">
       <div class="datetime-display">
        <p class="current-date" id="current-date">{{ date('l, F j, Y') }}</p>
        <p class="current-time" id="current-time">{{ date('H:i:s') }}</p>
       </div>
      </div>
     </div>
    </div>

    <!-- Main Content -->
    <div class="content-area" style="max-width: 1400px; margin: 0 auto; padding: 24px;">
     <div class="max-w-5xl mx-auto"><!-- Main Form -->
    <div class="cash-card"><!-- Denominations Section -->
     <div class="section-header">
       CASH DENOMINATION
     </div>
     <div class="p-6" style="padding: 24px;">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;"><!-- 1000 -->
       <div class="denomination-card">
        <div class="text-xs font-semibold mb-2" style="color: #1e3a5f; font-size: 12px; font-weight: 600;">
          NOTE 1000
        </div>
        <div class="grid grid-cols-2 gap-3 mb-3">
         <div><label class="text-xs block mb-1" style="color: #6b7280; font-size: 11px; font-weight: 500;">QTY</label> <input type="number" id="qty-1000" min="0" value="0" class="input-field">
         </div>
         <div><label class="text-xs block mb-1" style="color: #6b7280; font-size: 11px; font-weight: 500;">TOTAL</label>
          <div id="total-1000" class="denomination-value text-center py-2" style="background: #1e3a5f; color: white; border-radius: 6px; font-weight: 700; font-size: 16px;">
           0
          </div>
         </div>
        </div>
       </div><!-- 500 -->
       <div class="denomination-card">
        <div class="text-xs font-semibold mb-2" style="color: #1e3a5f; font-size: 12px; font-weight: 600;">
          NOTE 500
        </div>
        <div class="grid grid-cols-2 gap-3 mb-3">
         <div><label class="text-xs block mb-1" style="color: #6b7280; font-size: 11px; font-weight: 500;">QTY</label> <input type="number" id="qty-500" min="0" value="0" class="input-field">
         </div>
         <div><label class="text-xs block mb-1" style="color: #6b7280; font-size: 11px; font-weight: 500;">TOTAL</label>
          <div id="total-500" class="denomination-value text-center py-2" style="background: #1e3a5f; color: white; border-radius: 6px; font-weight: 700; font-size: 16px;">
           0
          </div>
         </div>
        </div>
       </div><!-- 100 -->
       <div class="denomination-card">
        <div class="text-xs font-semibold mb-2" style="color: #1e3a5f; font-size: 12px; font-weight: 600;">
          NOTE 100
        </div>
        <div class="grid grid-cols-2 gap-3 mb-3">
         <div><label class="text-xs block mb-1" style="color: #6b7280; font-size: 11px; font-weight: 500;">QTY</label> <input type="number" id="qty-100" min="0" value="0" class="input-field">
         </div>
         <div><label class="text-xs block mb-1" style="color: #6b7280; font-size: 11px; font-weight: 500;">TOTAL</label>
          <div id="total-100" class="denomination-value text-center py-2" style="background: #1e3a5f; color: white; border-radius: 6px; font-weight: 700; font-size: 16px;">
           0
          </div>
         </div>
        </div>
       </div><!-- 50 -->
       <div class="denomination-card">
        <div class="text-xs font-semibold mb-2" style="color: #1e3a5f; font-size: 12px; font-weight: 600;">
         NOTE 50
        </div>
        <div class="grid grid-cols-2 gap-3 mb-3">
         <div><label class="text-xs block mb-1" style="color: #6b7280; font-size: 11px; font-weight: 500;">QTY</label> <input type="number" id="qty-50" min="0" value="0" class="input-field">
         </div>
         <div><label class="text-xs block mb-1" style="color: #6b7280; font-size: 11px; font-weight: 500;">TOTAL</label>
          <div id="total-50" class="denomination-value text-center py-2" style="background: #1e3a5f; color: white; border-radius: 6px; font-weight: 700; font-size: 16px;">
           0
          </div>
         </div>
        </div>
       </div><!-- 20 -->
       <div class="denomination-card">
        <div class="text-xs font-semibold mb-2" style="color: #1e3a5f; font-size: 12px; font-weight: 600;">
          NOTE 20
        </div>
        <div class="grid grid-cols-2 gap-3 mb-3">
         <div><label class="text-xs block mb-1" style="color: #6b7280; font-size: 11px; font-weight: 500;">QTY</label> <input type="number" id="qty-20" min="0" value="0" class="input-field">
         </div>
         <div><label class="text-xs block mb-1" style="color: #6b7280; font-size: 11px; font-weight: 500;">TOTAL</label>
          <div id="total-20" class="denomination-value text-center py-2" style="background: #1e3a5f; color: white; border-radius: 6px; font-weight: 700; font-size: 16px;">
           0
          </div>
         </div>
        </div>
       </div><!-- 10 -->
       <div class="denomination-card">
        <div class="text-xs font-semibold mb-2" style="color: #1e3a5f; font-size: 12px; font-weight: 600;">
          NOTE 10
        </div>
        <div class="grid grid-cols-2 gap-3 mb-3">
         <div><label class="text-xs block mb-1" style="color: #6b7280; font-size: 11px; font-weight: 500;">QTY</label> <input type="number" id="qty-10" min="0" value="0" class="input-field">
         </div>
         <div><label class="text-xs block mb-1" style="color: #6b7280; font-size: 11px; font-weight: 500;">TOTAL</label>
          <div id="total-10" class="denomination-value text-center py-2" style="background: #1e3a5f; color: white; border-radius: 6px; font-weight: 700; font-size: 16px;">
           0
          </div>
         </div>
        </div>
       </div><!-- 5 -->
       <div class="denomination-card">
        <div class="text-xs font-semibold mb-2" style="color: #1e3a5f; font-size: 12px; font-weight: 600;">
          NOTE 5
        </div>
        <div class="grid grid-cols-2 gap-3 mb-3">
         <div><label class="text-xs block mb-1" style="color: #6b7280; font-size: 11px; font-weight: 500;">QTY</label> <input type="number" id="qty-5" min="0" value="0" class="input-field">
         </div>
         <div><label class="text-xs block mb-1" style="color: #6b7280; font-size: 11px; font-weight: 500;">TOTAL</label>
          <div id="total-5" class="denomination-value text-center py-2" style="background: #1e3a5f; color: white; border-radius: 6px; font-weight: 700; font-size: 16px;">
           0
          </div>
         </div>
        </div>
       </div>
      </div>
     </div><!-- Summary Section -->
    <div class="cash-card">
     <div class="section-header">
      SUMMARY TOTALS
     </div>
     <div style="padding: 16px;">
      <div class="summary-row"><span class="summary-label"> Total Amount</span> <span class="summary-value" id="total-amount">0</span>
      </div>
      <div class="summary-row" style="border-top: 2px solid #e5e7eb; background: #f9fafb;"><span class="summary-label" style="font-weight: 700;">CASH</span> <span class="summary-value" id="cash-total">0</span>
      </div>
      <div class="summary-row"><span class="summary-label"> Cheque</span>
       <div class="flex items-center gap-2"><span class="text-xs" style="color: #6b7280; font-weight: 500;">NPR</span> <input type="number" id="cheque-amount" min="0" step="0.01" value="0" class="input-field" style="width: 120px; text-align: right;">
       </div>
      </div>
      <div class="summary-row"><span class="summary-label">➕ PLUS I.C</span>
       <div class="flex items-center gap-2"><span class="text-xs" style="color: #6b7280; font-weight: 500;">NPR</span> <input type="number" id="ic-amount" min="0" step="0.01" value="0" class="input-field" style="width: 120px; text-align: right;">
       </div>
      </div>
      <div class="summary-row" style="border-top: 2px solid #e5e7eb; border-bottom: none; background: #f9fafb;"><span class="summary-label" style="font-weight: 700;">NET CASH</span> <span class="summary-value" style="font-size: 18px; font-weight: 800;" id="net-cash">0</span>
      </div>
     </div><!-- Cash Record Section -->
    <div class="cash-card">
     <div class="section-header">
       CASH RECORD
     </div>
     <div style="padding: 16px;">
      <div class="summary-row"><span class="summary-label" style="font-weight: 700;"> NET CASH</span> <span class="summary-value" id="record-net-cash">0</span>
      </div>
      <div class="summary-row"><span class="summary-label" style="font-weight: 700;"> TOTAL SALES</span>
       <div class="flex items-center gap-2"><span class="text-xs" style="color: #6b7280; font-weight: 500;">NPR</span> <input type="number" id="total-sales" min="0" step="0.01" value="{{ number_format($defaultTotalSales ?? 0, 2, '.', '') }}" readonly class="input-field" style="width: 140px; text-align: right; background: #f3f4f6; cursor: not-allowed;">
       </div>
      </div>
      <div class="summary-row" style="border-bottom: none; background: #f9fafb;"><span class="summary-label" style="font-weight: 700;">⚖️ DIFFERENCE</span> <span class="summary-value" style="font-size: 18px; font-weight: 800;" id="difference">0</span>
      </div>
     </div><!-- Buttons -->
     <div style="padding: 16px; border-top: 1px solid #e5e7eb;">
      <div class="button-group">
       <button id="save-btn" class="btn btn-primary"> SAVE RECORD</button> 
       <button id="clear-btn" class="btn btn-secondary"> CLEAR</button>
      </div>
     </div>
    </div><!-- History Section -->
    <div class="mt-8 fade-in" style="animation-delay: 0.2s">
     <div class="cash-card">
      <div class="section-header">
        RECORD HISTORY
      </div>
      <div id="history-container" style="padding: 24px;">
       <div id="empty-state" class="text-center py-12" style="color: #6b7280;">
        <div class="text-3xl mb-2">
         
        </div>
        <p style="font-weight: 500;">No records saved yet. Start by creating your first cash record.</p>
       </div>
      </div>
     </div>
    </div><!-- Limit Warning -->
    <div id="limit-warning" class="hidden mt-4" style="background: #fee2e2; border: 1px solid #dc2626; border-radius: 8px; padding: 16px; color: #991b1b; text-align: center; font-weight: 600;">
     ⚠️ Maximum limit of 999 records reached. Please delete some records to continue.
    </div>
   </div>
  </div>
  </div>
  <script>
    const defaultConfig = {
      page_title: 'CASH DENOMINATION RECORD',
      currency_label: 'NPR'
    };

    let config = { ...defaultConfig };
    let records = [];
    let isLoading = false;
    let editingRecordId = null;
    let editingRecordDate = null;
    const DRAFT_STORAGE_KEY = 'cash_denomination_draft_v1';

    // DOM Elements
    const denominations = [1000, 500, 100, 50, 20, 10, 5];
    const qtyInputs = {};
    const totalDisplays = {};

    denominations.forEach(denom => {
      qtyInputs[denom] = document.getElementById(`qty-${denom}`);
      totalDisplays[denom] = document.getElementById(`total-${denom}`);
    });

    const chequeAmount = document.getElementById('cheque-amount');
    const icAmount = document.getElementById('ic-amount');
    const totalSales = document.getElementById('total-sales');
    const saveBtn = document.getElementById('save-btn');
    const clearBtn = document.getElementById('clear-btn');
    const currencyDisplay = null;
    const defaultTotalSales = toNumber(document.getElementById('total-sales').value);

    function setEditMode(recordId, recordDate = null) {
      editingRecordId = recordId;
      editingRecordDate = recordDate;
      saveBtn.innerHTML = '✏️ UPDATE RECORD';
    }

    function resetEditMode() {
      editingRecordId = null;
      editingRecordDate = null;
      saveBtn.innerHTML = '💾 SAVE RECORD';
    }

    function toNumber(value) {
      const n = Number(value);
      return Number.isFinite(n) ? n : 0;
    }

    function formatMoney(value) {
      return toNumber(value).toLocaleString('en-IN', { maximumFractionDigits: 2 });
    }

    function saveDraft() {
      const draft = {
        qty_1000: parseInt(qtyInputs[1000].value) || 0,
        qty_500: parseInt(qtyInputs[500].value) || 0,
        qty_100: parseInt(qtyInputs[100].value) || 0,
        qty_50: parseInt(qtyInputs[50].value) || 0,
        qty_20: parseInt(qtyInputs[20].value) || 0,
        qty_10: parseInt(qtyInputs[10].value) || 0,
        qty_5: parseInt(qtyInputs[5].value) || 0,
        cheque_amount: toNumber(chequeAmount.value),
        ic_amount: toNumber(icAmount.value),
        editing_id: editingRecordId,
        editing_date: editingRecordDate,
      };

      localStorage.setItem(DRAFT_STORAGE_KEY, JSON.stringify(draft));
    }

    function loadDraft() {
      const raw = localStorage.getItem(DRAFT_STORAGE_KEY);
      if (!raw) return;

      try {
        const draft = JSON.parse(raw);
        qtyInputs[1000].value = toNumber(draft.qty_1000);
        qtyInputs[500].value = toNumber(draft.qty_500);
        qtyInputs[100].value = toNumber(draft.qty_100);
        qtyInputs[50].value = toNumber(draft.qty_50);
        qtyInputs[20].value = toNumber(draft.qty_20);
        qtyInputs[10].value = toNumber(draft.qty_10);
        qtyInputs[5].value = toNumber(draft.qty_5);
        chequeAmount.value = toNumber(draft.cheque_amount);
        icAmount.value = toNumber(draft.ic_amount);

        if (draft.editing_id) {
          setEditMode(draft.editing_id, draft.editing_date || null);
        }
      } catch (e) {
        localStorage.removeItem(DRAFT_STORAGE_KEY);
      }
    }

    function clearDraft() {
      localStorage.removeItem(DRAFT_STORAGE_KEY);
    }

    // Calculate all totals
    function calculateTotals() {
      let totalCash = 0;

      // Calculate each denomination
      denominations.forEach(denom => {
        const qty = parseInt(qtyInputs[denom].value) || 0;
        const total = qty * denom;
        totalDisplays[denom].textContent = total.toLocaleString('en-IN');
        totalCash += total;
      });

      const cheque = toNumber(chequeAmount.value);
      const ic = toNumber(icAmount.value);
      const netCash = totalCash + cheque + ic;
      const sales = toNumber(totalSales.value);
      const difference = netCash - sales;

      // Update displays
      document.getElementById('total-amount').textContent = formatMoney(totalCash);
      document.getElementById('cash-total').textContent = formatMoney(totalCash);
      document.getElementById('net-cash').textContent = formatMoney(netCash);
      document.getElementById('record-net-cash').textContent = formatMoney(netCash);

      // Update difference with color
      const diffEl = document.getElementById('difference');
      diffEl.textContent = formatMoney(difference);
      diffEl.className = 'summary-value';
      if (difference > 0) {
        diffEl.classList.add('diff-positive');
      } else if (difference < 0) {
        diffEl.classList.add('diff-negative');
      } else {
        diffEl.classList.add('diff-neutral');
      }

      return { totalCash, netCash, difference };
    }

    // Event listeners
    Object.values(qtyInputs).forEach(input => {
      input.addEventListener('input', () => {
        calculateTotals();
        saveDraft();
      });
    });
    chequeAmount.addEventListener('input', () => {
      calculateTotals();
      saveDraft();
    });
    icAmount.addEventListener('input', () => {
      calculateTotals();
      saveDraft();
    });
    totalSales.addEventListener('input', () => {
      calculateTotals();
      saveDraft();
    });

    // Clear form
    clearBtn.addEventListener('click', () => {
      denominations.forEach(denom => qtyInputs[denom].value = 0);
      chequeAmount.value = 0;
      icAmount.value = 0;
      totalSales.value = defaultTotalSales;
      resetEditMode();
      clearDraft();
      calculateTotals();
    });

    // Save record
    async function saveRecord() {
      if (isLoading) return;
      if (!editingRecordId && records.length >= 999) {
        document.getElementById('limit-warning').classList.remove('hidden');
        return;
      }

      isLoading = true;
      saveBtn.disabled = true;
      saveBtn.innerHTML = editingRecordId ? '⏳ UPDATING...' : '⏳ SAVING...';
      const wasEditing = !!editingRecordId;

      const { totalCash, netCash, difference } = calculateTotals();

      const record = {
        date: wasEditing && editingRecordDate ? editingRecordDate : new Date().toISOString().slice(0, 10),
        qty_1000: parseInt(qtyInputs[1000].value) || 0,
        qty_500: parseInt(qtyInputs[500].value) || 0,
        qty_100: parseInt(qtyInputs[100].value) || 0,
        qty_50: parseInt(qtyInputs[50].value) || 0,
        qty_20: parseInt(qtyInputs[20].value) || 0,
        qty_10: parseInt(qtyInputs[10].value) || 0,
        qty_5: parseInt(qtyInputs[5].value) || 0,
        cheque_amount: toNumber(chequeAmount.value),
        ic_amount: toNumber(icAmount.value),
        total_sales: toNumber(totalSales.value)
      };

      try {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const endpoint = editingRecordId ? `/api/cash/${editingRecordId}` : '/api/cash';
        const method = editingRecordId ? 'PUT' : 'POST';

        const response = await fetch(endpoint, {
          method,
          credentials: 'same-origin',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
          body: JSON.stringify(record)
        });

        if (response.status === 419) {
          showToast('Session expired. Please login again.', 'error');
          setTimeout(() => { window.location.href = '/login'; }, 1200);
          return;
        }

        if (response.status === 401) {
          showToast('You are logged out. Please login again.', 'error');
          setTimeout(() => { window.location.href = '/login'; }, 1200);
          return;
        }

        if (!response.ok) {
          throw new Error('Request failed');
        }

        const result = await response.json();
        if (result.isOk) {
          clearDraft();
          clearBtn.click();
          await loadRecords();
          showToast(wasEditing ? 'Record updated successfully' : 'Record saved successfully', 'success');
        } else {
          showToast(wasEditing ? 'Failed to update record' : 'Failed to save record', 'error');
        }
      } catch (e) {
        showToast(wasEditing ? 'Failed to update record' : 'Failed to save record', 'error');
      } finally {
        isLoading = false;
        saveBtn.disabled = false;
        saveBtn.innerHTML = editingRecordId ? '✏️ UPDATE RECORD' : '💾 SAVE RECORD';
      }
    }

    saveBtn.addEventListener('click', saveRecord);

    // Show toast
    function showToast(message, type = 'error') {
      const toast = document.createElement('div');
      const toneClass = type === 'success' ? 'bg-green-600' : 'bg-red-600';
      toast.className = `fixed bottom-4 right-4 ${toneClass} text-white px-6 py-3 rounded-lg shadow-lg z-50`;
      toast.textContent = message;
      document.body.appendChild(toast);
      setTimeout(() => toast.remove(), 3000);
    }

    // Render history
    function renderHistory(data) {
      const container = document.getElementById('history-container');
      const emptyState = document.getElementById('empty-state');

      if (data.length === 0) {
        emptyState.classList.remove('hidden');
        return;
      }

      emptyState.classList.add('hidden');

      const sorted = [...data].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
      const existingItems = new Map();
      container.querySelectorAll('.history-record').forEach(el => {
        existingItems.set(String(el.dataset.id), el);
      });

      sorted.forEach((record, index) => {
        const id = String(record.__backendId);
        let item = existingItems.get(id);

        if (item) {
          updateHistoryItem(item, record);
          existingItems.delete(id);
        } else {
          item = createHistoryItem(record);
          item.style.animationDelay = `${index * 0.05}s`;
          container.appendChild(item);
        }
      });

      existingItems.forEach(el => el.remove());
    }

    function createHistoryItem(record) {
      const item = document.createElement('div');
      item.className = 'history-record fade-in';
      item.dataset.id = record.__backendId;
      updateHistoryItem(item, record);
      return item;
    }

    function updateHistoryItem(item, record) {
      const netCash = toNumber(record.net_cash);
      const totalSalesAmount = toNumber(record.total_sales);
      const diffValue = toNumber(record.difference);
      const diffClass = diffValue === 0 ? 'diff-neutral' :
                       diffValue > 0 ? 'diff-positive' : 'diff-negative';
      const diffSign = diffValue >= 0 ? '+' : '';

      item.innerHTML = `
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-3 pb-3 border-b border-blue-200 text-sm">
          <div><span class="text-blue-700">Date:</span> <span class="text-blue-900 font-mono">${record.date}</span></div>
          <div><span class="text-blue-700">1000×${record.qty_1000}</span> <span class="text-blue-900 font-mono">${(record.qty_1000 * 1000).toLocaleString('en-IN')}</span></div>
          <div><span class="text-blue-700">500×${record.qty_500}</span> <span class="text-blue-900 font-mono">${(record.qty_500 * 500).toLocaleString('en-IN')}</span></div>
          <div><span class="text-blue-700">100×${record.qty_100}</span> <span class="text-blue-900 font-mono">${(record.qty_100 * 100).toLocaleString('en-IN')}</span></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
          <div><span class="text-blue-700 text-xs">NET CASH</span> <span class="text-blue-900 font-bold">${formatMoney(netCash)}</span></div>
          <div><span class="text-blue-700 text-xs">SALES</span> <span class="text-blue-900 font-bold">${formatMoney(totalSalesAmount)}</span></div>
          <div><span class="text-blue-700 text-xs">DIFF</span> <span class="summary-value ${diffClass}">${diffSign}${formatMoney(diffValue)}</span></div>
          <div class="flex items-center gap-3 justify-start md:justify-end">
            <button class="edit-btn text-indigo-600 hover:text-indigo-800 text-sm font-semibold transition-colors" data-id="${record.__backendId}">EDIT</button>
            <button class="delete-btn text-red-600 hover:text-red-800 text-sm font-semibold transition-colors" data-id="${record.__backendId}">DELETE</button>
          </div>
        </div>
      `;

      const editBtn = item.querySelector('.edit-btn');
      editBtn.onclick = () => handleEdit(record);

      const deleteBtn = item.querySelector('.delete-btn');
      deleteBtn.onclick = () => handleDelete(record);
    }

    function handleEdit(record) {
      qtyInputs[1000].value = toNumber(record.qty_1000);
      qtyInputs[500].value = toNumber(record.qty_500);
      qtyInputs[100].value = toNumber(record.qty_100);
      qtyInputs[50].value = toNumber(record.qty_50);
      qtyInputs[20].value = toNumber(record.qty_20);
      qtyInputs[10].value = toNumber(record.qty_10);
      qtyInputs[5].value = toNumber(record.qty_5);
      chequeAmount.value = toNumber(record.cheque_amount);
      icAmount.value = toNumber(record.ic_amount);
      totalSales.value = toNumber(record.total_sales);

      setEditMode(record.__backendId, record.date);
      calculateTotals();
      saveDraft();
      window.scrollTo({ top: 0, behavior: 'smooth' });
      showToast('Edit mode enabled', 'success');
    }

    async function handleDelete(record) {
      const recordId = record.__backendId;
      if (!recordId) {
        showToast('Unable to delete record', 'error');
        return;
      }

      const confirmed = window.confirm(`Delete cash record dated ${record.date}?`);
      if (!confirmed) return;

      try {
        const response = await fetch(`/api/cash/${recordId}`, {
          method: 'DELETE',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
          },
        });

        if (!response.ok) {
          throw new Error('Delete failed');
        }

        if (editingRecordId && String(editingRecordId) === String(recordId)) {
          clearBtn.click();
        }

        await loadRecords();
        showToast('Record deleted successfully', 'success');
      } catch (error) {
        showToast('Failed to delete record', 'error');
      }
    }

    async function loadRecords() {
      try {
        const response = await fetch('/api/cash');
        if (response.status === 419 || response.status === 401) {
          showToast('Session expired. Please login again.', 'error');
          setTimeout(() => { window.location.href = '/login'; }, 1200);
          return;
        }
        if (!response.ok) {
          throw new Error('Load failed');
        }
        const result = await response.json();
        if (result.isOk) {
          records = result.data || [];
          renderHistory(records);
          const limitWarn = document.getElementById('limit-warning');
          if (records.length >= 999) limitWarn.classList.remove('hidden');
          else limitWarn.classList.add('hidden');
        }
      } catch (e) {
        showToast('Failed to load records');
      }
    }

    async function init() {
      await loadRecords();
      loadDraft();
      calculateTotals();
    }

    init();

    // Update date and time
    function updateDateTime() {
      const now = new Date();
      const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
      const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
      
      const dateEl = document.getElementById('current-date');
      const timeEl = document.getElementById('current-time');
      if (dateEl && timeEl) {
        dateEl.textContent = now.toLocaleDateString('en-US', dateOptions);
        timeEl.textContent = now.toLocaleTimeString('en-US', timeOptions);
      }
    }
    
    updateDateTime();
    setInterval(updateDateTime, 1000);
  </script>

    </div>
   </div>
  </div>
 </body>
</html>