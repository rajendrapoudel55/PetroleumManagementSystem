<!doctype html>
<html lang="en" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Expenses Management</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="/assets/css/stock.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
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
    
    html, body, #app-wrapper {
      height: 100%;
      width: 100%;
    }

    .gradient-bg {
      background: #1e3a5f;
      box-shadow: 0 2px 8px rgba(30, 58, 95, 0.15);
    }

    .expense-card {
      background: white;
      border: 1px solid #e5e7eb;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
      border-radius: 12px;
    }

    .input-wrapper input,
    .input-wrapper select,
    .input-wrapper textarea {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid #d1d5db;
      border-radius: 6px;
      font-size: 14px;
      transition: all 0.2s ease;
      background: white;
      font-family: 'Poppins', sans-serif;
    }

    .input-wrapper textarea {
      resize: vertical;
      min-height: 80px;
    }

    .input-wrapper input:focus,
    .input-wrapper select:focus,
    .input-wrapper textarea:focus {
      outline: none;
      border-color: #1e3a5f;
      box-shadow: 0 0 0 2px rgba(30, 58, 95, 0.1);
    }

    .btn-primary {
      background: #1e3a5f;
      color: white;
      padding: 12px 28px;
      border-radius: 6px;
      font-weight: 600;
      border: none;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .btn-primary:hover:not(:disabled) {
      background: #152947;
    }

    .btn-primary:disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }

    .btn-secondary {
      background: #f3f4f6;
      color: #374151;
      padding: 12px 28px;
      border-radius: 6px;
      font-weight: 600;
      border: 1px solid #d1d5db;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .btn-secondary:hover {
      background: #e5e7eb;
    }

    .btn-edit {
      background: #3b82f6;
      color: white;
      padding: 6px 12px;
      border-radius: 4px;
      border: none;
      cursor: pointer;
      font-size: 12px;
      font-weight: 500;
    }

    .btn-edit:hover {
      background: #2563eb;
    }

    .btn-delete {
      background: #fee2e2;
      color: #dc2626;
      padding: 6px 12px;
      border-radius: 4px;
      border: none;
      cursor: pointer;
      font-size: 12px;
      font-weight: 500;
    }

    .btn-delete:hover {
      background: #fecaca;
    }

    .stat-card {
      background: #1e3a5f;
      padding: 20px;
      border-radius: 12px;
      color: white;
    }

    .stat-card.payment {
      background: #1e3a5f;
    }

    .stat-card.receive {
      background: #1e3a5f;
    }

    .stat-card.expense {
      background: #1e3a5f;
    }

    .stat-card.deposit {
      background: #1e3a5f;
    }

    .stat-label {
      font-size: 12px;
      opacity: 0.9;
      margin-bottom: 4px;
    }

    .stat-value {
      font-size: 24px;
      font-weight: 700;
    }

    .badge {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 12px;
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
    }

    .badge.payment {
      background: #fee2e2;
      color: #dc2626;
    }

    .badge.receive {
      background: #dcfce7;
      color: #166534;
    }

    .badge.expense {
      background: #fef3c7;
      color: #92400e;
    }

    .badge.deposit {
      background: #dbeafe;
      color: #1e40af;
    }
  </style>
  <style>@view-transition { navigation: auto; }</style>
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
  <div class="dashboard-wrapper">

    {{-- SIDEBAR --}}
    @include('layouts.sidebar')

    {{-- PAGE CONTENT --}}
    <div class="main-content">
          
      <!-- Header -->
      <div class="header">
        <div class="header-content">
          <div class="header-info">
            <h2>Expenses Management</h2>
            <p>Track and manage your payments, receipts, and expenses</p>
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
      <div class="content-area">
        <div class="max-w-7xl mx-auto p-6">

          <!-- Statistics Cards -->
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="stat-card payment">
              <div class="stat-label">Total Payments</div>
              <div class="stat-value" id="total-payment">NPR 0.00</div>
            </div>
            <div class="stat-card receive">
              <div class="stat-label">Total Received</div>
              <div class="stat-value" id="total-receive">NPR 0.00</div>
            </div>
            <div class="stat-card expense">
              <div class="stat-label">Total Expenses</div>
              <div class="stat-value" id="total-expense">NPR 0.00</div>
            </div>
            <div class="stat-card deposit">
              <div class="stat-label">Bank Deposits</div>
              <div class="stat-value" id="total-deposit">NPR 0.00</div>
            </div>
          </div>

          <!-- Entry Form -->
          <div class="expense-card p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6"> Add New Transaction</h2>
            
            <!-- Messages -->
            <div id="message-container"></div>

            <form id="expense-form" class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Transaction Type -->
              <div>
                <label class="block text-sm font-600 text-gray-700 mb-2">Transaction Type</label>
                <div class="input-wrapper">
                  <select id="transaction-type" required>
                    <option value="">Select Type</option>
                    <option value="payment">Payment</option>
                    <option value="payment">Insurance</option>
                    <option value="receive">Receive</option>
                    <option value="expense">Expense</option>
                    <option value="expense">Transportation</option>
                    <option value="deposit">Bank Deposit</option>
                  </select>
                </div>
              </div>

              <!-- Date -->
              <div>
                <label class="block text-sm font-600 text-gray-700 mb-2">Date</label>
                <div class="input-wrapper">
                  <input type="date" id="transaction-date" required>
                </div>
              </div>

              <!-- Name/Vendor -->
              <div>
                <label class="block text-sm font-600 text-gray-700 mb-2">Name / Vendor</label>
                <div class="input-wrapper">
                  <input type="text" id="vendor-name" placeholder="e.g., ABC Company" required>
                </div>
              </div>

              <!-- Amount -->
              <div>
                <label class="block text-sm font-600 text-gray-700 mb-2">Amount (NPR)</label>
                <div class="input-wrapper">
                  <input type="number" id="amount" placeholder="0.00" step="0.01" min="0" required>
                </div>
              </div>
              

              <!-- Category -->
              <div>
                <label class="block text-sm font-600 text-gray-700 mb-2">Category</label>
                <div class="input-wrapper">
                  <select id="category" required>
                    <option value="">Select Category</option>
                    <option value="Salary">Salary</option>
                    <option value="Fuel">Fuel Purchase</option>
                    <option value="Utilities">Utilities</option>
                    <option value="Maintenance">Maintenance</option>
                    <option value="Supplies">Supplies</option>
                    <option value="Transportation">Transportation</option>
                    <option value="Marketing">Marketing</option>
                    <option value="Tax">Tax</option>
                    <option value="Other">Other</option>
                  </select>
                </div>
              </div>

              <!-- Payment Method -->
              <div>
                <label class="block text-sm font-600 text-gray-700 mb-2">Payment Method</label>
                <div class="input-wrapper">
                  <select id="payment-method" required>
                    <option value="cash">Cash</option>
                    <option value="bank">Bank Transfer</option>
                    <option value="cheque">Cheque</option>
                    <option value="card">Card</option>
                  </select>
                </div>
              </div>

              <!-- Description (Full Width) -->
              <div class="md:col-span-2">
                <label class="block text-sm font-600 text-gray-700 mb-2">Description</label>
                <div class="input-wrapper">
                  <textarea id="description" placeholder="Enter transaction details..." required></textarea>
                </div>
              </div>

              <!-- Buttons -->
              <div class="md:col-span-2 flex gap-4 mt-4">
                <button type="submit" id="save-btn" class="btn-primary flex-1">
                  Save Transaction
                </button>
                <button type="button" onclick="clearForm()" class="btn-secondary">
                  Clear Form
                </button>
              </div>
            </form>
          </div>

          <!-- History Table -->
          <div class="expense-card p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">📊 Transaction History</h2>
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead>
                  <tr style="border-bottom: 2px solid #d1d5db; background-color: #f3f4f6;">
                    <th style="text-align: left; padding: 12px; font-weight: 600; color: #1e3a5f; font-size: 14px;">Date</th>
                    <th style="text-align: left; padding: 12px; font-weight: 600; color: #1e3a5f; font-size: 14px;">Type</th>
                    <th style="text-align: left; padding: 12px; font-weight: 600; color: #1e3a5f; font-size: 14px;">Vendor/Name</th>
                    <th style="text-align: left; padding: 12px; font-weight: 600; color: #1e3a5f; font-size: 14px;">Category</th>
                    <th style="text-align: left; padding: 12px; font-weight: 600; color: #1e3a5f; font-size: 14px;">Description</th>
                    <th style="text-align: right; padding: 12px; font-weight: 600; color: #1e3a5f; font-size: 14px;">Amount</th>
                    <th style="text-align: left; padding: 12px; font-weight: 600; color: #1e3a5f; font-size: 14px;">Payment</th>
                    <th style="text-align: center; padding: 12px; font-weight: 600; color: #1e3a5f; font-size: 14px;">Action</th>
                  </tr>
                </thead>
                <tbody id="history-tbody">
                  <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: #9ca3af;">
                      No transactions yet. Add your first expense above!
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <script>
    // Global variables
    let editingTransactionId = null;
    let transactions = [];

    // Initialize
    window.clearForm = function() {
      document.getElementById('expense-form').reset();
      document.getElementById('transaction-date').value = new Date().toISOString().split('T')[0];
      editingTransactionId = null;
      document.getElementById('save-btn').innerHTML = '💾 Save Transaction';
    }

    // Set today's date as default
    document.getElementById('transaction-date').value = new Date().toISOString().split('T')[0];

    // Edit transaction
    window.editTransaction = function(id) {
      const transaction = transactions.find(t => t.id === id);
      if (!transaction) return;

      editingTransactionId = id;
      document.getElementById('transaction-type').value = transaction.transaction_type;
      document.getElementById('transaction-date').value = transaction.date;
      document.getElementById('vendor-name').value = transaction.vendor_name;
      document.getElementById('amount').value = transaction.amount;
      document.getElementById('category').value = transaction.category;
      document.getElementById('payment-method').value = transaction.payment_method;
      document.getElementById('description').value = transaction.description;
      
      document.getElementById('save-btn').innerHTML = '✏️ Update Transaction';
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Delete transaction (Backend)
    window.deleteTransaction = function(id) {
      if (!confirm('Are you sure you want to delete this transaction?')) return;
      
      fetch(`/api/expenses/${id}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Content-Type': 'application/json',
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          showMessage('Transaction deleted successfully ✅', 'success');
          loadTransactions();
        }
      })
      .catch(error => {
        showMessage('Error deleting transaction ❌', 'error');
      });
    }

    // Render transactions
    function renderTransactions() {
      const tbody = document.getElementById('history-tbody');
      
      if (transactions.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="8" style="text-align: center; padding: 40px; color: #9ca3af;">
              No transactions yet. Add your first expense above!
            </td>
          </tr>
        `;
        return;
      }

      tbody.innerHTML = transactions.map(t => `
        <tr style="border-bottom: 1px solid #e5e7eb;">
          <td style="padding: 12px; color: #1e3a5f; font-weight: 500;">
            ${new Date(t.date).toLocaleDateString('en-IN', { year: 'numeric', month: 'short', day: 'numeric' })}
          </td>
          <td style="padding: 12px;">
            <span class="badge ${t.transaction_type}">${t.transaction_type}</span>
          </td>
          <td style="padding: 12px; color: #374151;">${t.vendor_name}</td>
          <td style="padding: 12px; color: #6b7280; font-size: 13px;">${t.category}</td>
          <td style="padding: 12px; color: #6b7280; font-size: 13px; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${t.description}">
            ${t.description}
          </td>
          <td style="text-align: right; padding: 12px; font-weight: 700; color: #1e3a5f; font-size: 15px;">
            NPR ${parseFloat(t.amount).toFixed(2)}
          </td>
          <td style="padding: 12px; color: #6b7280; font-size: 13px; text-transform: capitalize;">
            ${t.payment_method}
          </td>
          <td style="text-align: center; padding: 12px;">
            <div style="display: flex; gap: 8px; justify-content: center;">
              <button class="btn-edit" onclick="editTransaction(${t.id})">Edit</button>
              <button class="btn-delete" onclick="deleteTransaction(${t.id})">Delete</button>
            </div>
          </td>
        </tr>
      `).join('');
    }

    // Update statistics
    function updateStatistics() {
      const payment = transactions.filter(t => t.transaction_type === 'payment').reduce((sum, t) => sum + parseFloat(t.amount), 0);
      const receive = transactions.filter(t => t.transaction_type === 'receive').reduce((sum, t) => sum + parseFloat(t.amount), 0);
      const expense = transactions.filter(t => t.transaction_type === 'expense').reduce((sum, t) => sum + parseFloat(t.amount), 0);
      const deposit = transactions.filter(t => t.transaction_type === 'deposit').reduce((sum, t) => sum + parseFloat(t.amount), 0);

      document.getElementById('total-payment').textContent = `NPR ${payment.toFixed(2)}`;
      document.getElementById('total-receive').textContent = `NPR ${receive.toFixed(2)}`;
      document.getElementById('total-expense').textContent = `NPR ${expense.toFixed(2)}`;
      document.getElementById('total-deposit').textContent = `NPR ${deposit.toFixed(2)}`;
    }

    // Load transactions from backend
    function loadTransactions() {
      fetch('/api/expenses', {
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
      })
      .then(response => response.json())
      .then(data => {
        transactions = data.expenses;
        renderTransactions();
        updateStatistics();
      })
      .catch(() => {});
    }

    // Show message
    function showMessage(message, type = 'success') {
      const container = document.getElementById('message-container');
      const bgColor = type === 'success' ? '#dcfce7' : '#fee2e2';
      const textColor = type === 'success' ? '#166534' : '#991b1b';
      
      container.innerHTML = `
        <div style="padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; font-weight: 500; text-align: center; background: ${bgColor}; color: ${textColor};">
          ${message}
        </div>
      `;
      
      setTimeout(() => {
        container.innerHTML = '';
      }, 3000);
    }

    // Form submit (Backend)
    document.getElementById('expense-form').addEventListener('submit', function(e) {
      e.preventDefault();

      const formData = {
        transaction_type: document.getElementById('transaction-type').value,
        date: document.getElementById('transaction-date').value,
        vendor_name: document.getElementById('vendor-name').value,
        amount: document.getElementById('amount').value,
        category: document.getElementById('category').value,
        payment_method: document.getElementById('payment-method').value,
        description: document.getElementById('description').value,
      };

      const url = editingTransactionId ? `/api/expenses/${editingTransactionId}` : '/api/expenses';
      const method = editingTransactionId ? 'PUT' : 'POST';

      fetch(url, {
        method: method,
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData)
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          showMessage(data.message, 'success');
          loadTransactions();
          clearForm();
        } else {
          showMessage('Error: ' + (data.message || 'Unknown error'), 'error');
        }
      })
      .catch(error => {
        showMessage('Error saving transaction ❌', 'error');
      });
    });

    // Update date/time
    function updateDateTime() {
      const now = new Date();
      const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
      const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
      
      const dateEl = document.getElementById('current-date');
      const timeEl = document.getElementById('current-time');
      if (dateEl && timeEl) {
        dateEl.textContent = now.toLocaleDateString('en-IN', dateOptions);
        timeEl.textContent = now.toLocaleTimeString('en-IN', timeOptions);
      }
    }
    
    updateDateTime();
    setInterval(updateDateTime, 1000);

    // Load data on page load
    loadTransactions();
  </script>
</body>
</html>
