<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reports - Petroleum Management System</title>
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

    .card {
      background: white;
      border-radius: 16px;
      padding: 2rem;
      margin-bottom: 2rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .card-title {
      font-size: 1.3rem;
      color: #001f3f;
      margin-bottom: 1.5rem;
      font-weight: 700;
    }

    .form-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1.5rem;
      margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) { .form-grid { grid-template-columns: 1fr; } }

    .form-group {
      display: flex;
      flex-direction: column;
    }

    .form-group label {
      font-weight: 600;
      margin-bottom: 8px;
      color: #374151;
      font-size: 13px;
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }

    input, select {
      padding: 12px 14px;
      border: 2px solid #e5e7eb;
      border-radius: 10px;
      font-size: 14px;
      transition: all 0.3s;
      font-family: inherit;
      background: white;
    }

    input:focus, select:focus {
      outline: none;
      border-color: #001f3f;
      box-shadow: 0 0 0 3px rgba(0, 31, 63, 0.1);
    }

    .btn {
      padding: 12px 24px;
      border: none;
      border-radius: 10px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.3s;
      font-size: 13px;
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }

    .btn-primary {
      background: #001f3f;
      color: white;
    }

    .btn-primary:hover {
      background: #003d7a;
      box-shadow: 0 4px 12px rgba(0, 31, 63, 0.3);
      transform: translateY(-2px);
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    thead {
      background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    }

    th {
      padding: 14px;
      text-align: left;
      font-weight: 700;
      font-size: 12px;
      color: #374151;
      border-bottom: 2px solid #d1d5db;
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }

    td {
      padding: 14px;
      border-bottom: 1px solid #e5e7eb;
      font-size: 14px;
      color: #1f2937;
    }

    tbody tr:hover {
      background: #f9fafb;
    }

    .summary-box {
      background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
      padding: 20px;
      border-radius: 12px;
      border-left: 4px solid #001f3f;
      margin: 20px 0;
      box-shadow: 0 2px 8px rgba(0, 31, 63, 0.1);
    }

    .summary-row {
      display: flex;
      justify-content: space-between;
      padding: 10px 0;
      font-size: 14px;
      font-weight: 500;
      color: #1f2937;
    }

    .summary-row.total {
      border-top: 2px solid #bfdbfe;
      padding-top: 14px;
      margin-top: 14px;
      font-size: 16px;
      font-weight: 800;
      color: #001f3f;
    }

    .empty-state {
      text-align: center;
      color: #9ca3af;
      padding: 40px 16px;
      font-size: 14px;
      font-style: italic;
    }
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
            <h2>Reports</h2>
            <p>View and analyze your petroleum business reports</p>
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
          <!-- Report Filters -->
          <div class="card">
            <div class="card-title">Generate Report</div>
            <div class="form-grid">
              <div class="form-group">
                <label>Report Type</label>
                <select id="reportType">
                  <option value="">Select Report Type</option>
                  <option value="sales">Sales Report</option>
                  <option value="expenses">Expenses Report</option>
                  <option value="inventory">Inventory Report</option>
                </select>
              </div>
              <div class="form-group">
                <label>From Date</label>
                <input type="date" id="fromDate">
              </div>
              <div class="form-group">
                <label>To Date</label>
                <input type="date" id="toDate">
              </div>
            </div>
            <div style="display: flex; gap: 10px; margin-top: 10px;">
              <button class="btn btn-primary" onclick="generateReport()">Generate Report</button>
              <button class="btn btn-primary" onclick="previewReport()" style="background: #3b82f6;"> Preview</button>
              <button class="btn btn-primary" onclick="downloadPDF()" style="background: #10b981;"> Download PDF</button>
            </div>
          </div>

          <!-- Report Data Table -->
          <div class="card" id="reportDataCard" style="display: none;">
            <div class="card-title" id="reportTitle">Report Details</div>
            <div style="overflow-x: auto;">
              <table id="reportTableContainer">
                <thead id="reportTableHead">
                </thead>
                <tbody id="reportTable">
                  <tr>
                    <td colspan="10" class="empty-state">No data available. Generate a report to view details.</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Preview Modal -->
  <div id="previewModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; overflow: auto;">
    <div style="background: white; border-radius: 16px; padding: 30px; max-width: 1000px; width: 95%; max-height: 90vh; overflow: auto; margin: 20px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="color: #001f3f; margin: 0;">Report Preview</h3>
        <button onclick="closePreviewModal()" style="background: #f3f4f6; border: none; border-radius: 8px; padding: 8px 16px; cursor: pointer; font-weight: 600;">✖ Close</button>
      </div>
      <div id="previewContent" style="border: 1px solid #e5e7eb; padding: 20px; border-radius: 8px; background: #f9fafb;">
        <p style="text-align: center; color: #9ca3af;">Generate a report first to see the preview</p>
      </div>
    </div>
  </div>

  <script>
    let currentReportData = null;
    let currentReportSummary = null;
    let currentReportType = null;
    let currentFromDate = null;
    let currentToDate = null;

    function generateReport() {
      const reportType = document.getElementById('reportType').value;
      const fromDate = document.getElementById('fromDate').value;
      const toDate = document.getElementById('toDate').value;

      if (!reportType) {
        alert('Please select a report type');
        return;
      }

      if (!fromDate || !toDate) {
        alert('Please select date range');
        return;
      }

      // Store for preview and PDF functions
      currentReportType = reportType;
      currentFromDate = fromDate;
      currentToDate = toDate;

      // Call backend API
      fetch('/api/reports/generate', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          report_type: reportType,
          from_date: fromDate,
          to_date: toDate,
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          currentReportData = data.data;
          currentReportSummary = data.summary || null;
          displayReport(data.report_type, data.data);
          document.getElementById('reportDataCard').style.display = 'block';
        } else {
          alert('Error generating report');
        }
      })
      .catch(error => {
        alert('Failed to generate report');
      });
    }

    function displayReport(reportType, data) {
      const thead = document.getElementById('reportTableHead');
      const tbody = document.getElementById('reportTable');
      const title = document.getElementById('reportTitle');

      const existingSummary = document.getElementById('sales-summary-box');
      if (existingSummary) {
        existingSummary.remove();
      }
      
      title.textContent = reportType.charAt(0).toUpperCase() + reportType.slice(1) + ' Report';

      if (reportType === 'sales' && currentReportSummary) {
        const card = document.getElementById('reportDataCard');
        const summary = currentReportSummary;
        const summaryBox = document.createElement('div');
        summaryBox.id = 'sales-summary-box';
        summaryBox.className = 'summary-box';
        summaryBox.innerHTML = `
          <div class="summary-row"><span>Diesel Sales (${Number(summary.diesel_liters || 0).toFixed(2)}L x NPR ${Number(summary.diesel_rate || 0).toFixed(2)})</span><span>NPR ${Number(summary.diesel_sales || 0).toFixed(2)}</span></div>
          <div class="summary-row"><span>Petrol Sales (${Number(summary.petrol_liters || 0).toFixed(2)}L x NPR ${Number(summary.petrol_rate || 0).toFixed(2)})</span><span>NPR ${Number(summary.petrol_sales || 0).toFixed(2)}</span></div>
          <div class="summary-row total"><span>Total Sales</span><span>NPR ${Number(summary.total_sales || 0).toFixed(2)}</span></div>
          <div class="summary-row"><span>Total Cash Sales</span><span>NPR ${Number(summary.total_cash_sales || 0).toFixed(2)}</span></div>
          <div class="summary-row"><span>Total Credit Sales</span><span>NPR ${Number(summary.total_credit_sales || 0).toFixed(2)}</span></div>
          <div class="summary-row"><span>Expenses</span><span>NPR ${Number(summary.expenses || 0).toFixed(2)}</span></div>
          <div class="summary-row"><span>In Bank</span><span>NPR ${Number(summary.in_bank || 0).toFixed(2)}</span></div>
        `;

        card.insertBefore(summaryBox, card.querySelector('div[style*="overflow-x: auto;"]'));
      }

      if (reportType === 'sales') {
        thead.innerHTML = `
          <tr>
            <th>Date</th>
            <th>Bill #</th>
            <th>Customer</th>
            <th>Phone</th>
            <th>Vehicle</th>
            <th>Payment</th>
            <th>Txn No.</th>
            <th style="text-align: right;">Items</th>
            <th style="text-align: right;">Qty</th>
            <th style="text-align: right;">Subtotal</th>
            <th style="text-align: right;">VAT</th>
            <th style="text-align: right;">Total</th>
          </tr>
        `;
        
        if (data.length === 0) {
          tbody.innerHTML = '<tr><td colspan="12" class="empty-state">No tax invoice sales data found for the selected period</td></tr>';
        } else {
          tbody.innerHTML = data.map(item => `
            <tr>
              <td>${item.date}</td>
              <td>${item.bill_number || '-'}</td>
              <td>${item.customer_name || '-'}</td>
              <td>${item.phone || '-'}</td>
              <td>${item.vehicle || '-'}</td>
              <td>${item.payment_method || '-'}</td>
              <td>${item.transaction_no || '-'}</td>
              <td style="text-align: right;">${parseInt(item.item_count || 0, 10)}</td>
              <td style="text-align: right;">${parseFloat(item.total_qty || 0).toFixed(2)}</td>
              <td style="text-align: right;">NPR ${parseFloat(item.subtotal || 0).toFixed(2)}</td>
              <td style="text-align: right;">NPR ${parseFloat(item.vat || 0).toFixed(2)}</td>
              <td style="text-align: right;">NPR ${parseFloat(item.total || 0).toFixed(2)}</td>
            </tr>
          `).join('');
        }
      } else if (reportType === 'expenses') {
        thead.innerHTML = `
          <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Vendor</th>
            <th>Category</th>
            <th>Description</th>
            <th style="text-align: right;">Amount</th>
            <th>Payment</th>
          </tr>
        `;
        
        if (data.length === 0) {
          tbody.innerHTML = '<tr><td colspan="7" class="empty-state">No expense data found for the selected period</td></tr>';
        } else {
          tbody.innerHTML = data.map(item => `
            <tr>
              <td>${item.date}</td>
              <td>${item.type}</td>
              <td>${item.vendor}</td>
              <td>${item.category}</td>
              <td>${item.description}</td>
              <td style="text-align: right;">NPR ${parseFloat(item.amount).toFixed(2)}</td>
              <td>${item.payment_method}</td>
            </tr>
          `).join('');
        }
      } else if (reportType === 'inventory') {
        thead.innerHTML = `
          <tr>
            <th>Date</th>
            <th>Product</th>
            <th>SKU</th>
            <th style="text-align: right;">Current Stock</th>
            <th style="text-align: right;">Unit Price</th>
            <th style="text-align: right;">Total Value</th>
          </tr>
        `;
        
        if (data.length === 0) {
          tbody.innerHTML = '<tr><td colspan="6" class="empty-state">No inventory data found for the selected period</td></tr>';
        } else {
          tbody.innerHTML = data.map(item => `
            <tr>
              <td>${item.date}</td>
              <td>${item.product}</td>
              <td>${item.sku}</td>
              <td style="text-align: right;">${parseFloat(item.current_stock).toFixed(2)}</td>
              <td style="text-align: right;">NPR ${parseFloat(item.unit_price).toFixed(2)}</td>
              <td style="text-align: right;">NPR ${parseFloat(item.total_value).toFixed(2)}</td>
            </tr>
          `).join('');
        }
      }
    }

    function previewReport() {
      if (!currentReportData || !currentReportType) {
        alert('Please generate a report first');
        return;
      }

      const modal = document.getElementById('previewModal');
      const content = document.getElementById('previewContent');
      
      let html = `
        <div style="max-width: 800px; margin: 0 auto;">
          <div style="text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #001f3f;">
            <h1 style="color: #001f3f; margin: 0; font-size: 28px;">Petroleum Management System</h1>
            <h2 style="margin: 10px 0; font-size: 22px;">${currentReportType.charAt(0).toUpperCase() + currentReportType.slice(1)} Report</h2>
            <p style="color: #666;">Date Range: ${new Date(currentFromDate).toLocaleDateString()} - ${new Date(currentToDate).toLocaleDateString()}</p>
          </div>
      `;

      if (currentReportType === 'sales') {
        const summary = currentReportSummary || {};
        html += '<table style="width: 100%; border-collapse: collapse;">';
        html += '<thead><tr style="background: #001f3f; color: white;"><th style="padding: 10px; text-align: left;">Date</th><th style="padding: 10px; text-align: left;">Bill #</th><th style="padding: 10px; text-align: left;">Customer</th><th style="padding: 10px; text-align: left;">Payment</th><th style="padding: 10px; text-align: right;">Items</th><th style="padding: 10px; text-align: right;">Qty</th><th style="padding: 10px; text-align: right;">Subtotal</th><th style="padding: 10px; text-align: right;">VAT</th><th style="padding: 10px; text-align: right;">Total</th></tr></thead>';
        html += '<tbody>';
        currentReportData.forEach(item => {
          html += `<tr style="border-bottom: 1px solid #ddd;"><td style="padding: 8px;">${item.date}</td><td style="padding: 8px;">${item.bill_number || '-'}</td><td style="padding: 8px;">${item.customer_name || '-'}</td><td style="padding: 8px;">${item.payment_method || '-'}</td><td style="padding: 8px; text-align: right;">${parseInt(item.item_count || 0, 10)}</td><td style="padding: 8px; text-align: right;">${parseFloat(item.total_qty || 0).toFixed(2)}</td><td style="padding: 8px; text-align: right;">NPR ${parseFloat(item.subtotal || 0).toFixed(2)}</td><td style="padding: 8px; text-align: right;">NPR ${parseFloat(item.vat || 0).toFixed(2)}</td><td style="padding: 8px; text-align: right;">NPR ${parseFloat(item.total || 0).toFixed(2)}</td></tr>`;
        });
        html += '</tbody></table>';
        html += `
          <div style="margin-top: 18px; border-top: 2px solid #001f3f; padding-top: 12px;">
            <p><strong>Total Sales:</strong> NPR ${Number(summary.total_sales || 0).toFixed(2)}</p>
            <p><strong>Total Cash Sales:</strong> NPR ${Number(summary.total_cash_sales || 0).toFixed(2)}</p>
            <p><strong>Total Credit Sales:</strong> NPR ${Number(summary.total_credit_sales || 0).toFixed(2)}</p>
            <p><strong>Expenses:</strong> NPR ${Number(summary.expenses || 0).toFixed(2)}</p>
            <p><strong>In Bank:</strong> NPR ${Number(summary.in_bank || 0).toFixed(2)}</p>
          </div>
        `;
      } else if (currentReportType === 'expenses') {
        html += '<table style="width: 100%; border-collapse: collapse;">';
        html += '<thead><tr style="background: #001f3f; color: white;"><th style="padding: 10px; text-align: left;">Date</th><th style="padding: 10px; text-align: left;">Type</th><th style="padding: 10px; text-align: left;">Vendor</th><th style="padding: 10px; text-align: left;">Category</th><th style="padding: 10px; text-align: right;">Amount</th></tr></thead>';
        html += '<tbody>';
        currentReportData.forEach(item => {
          html += `<tr style="border-bottom: 1px solid #ddd;"><td style="padding: 8px;">${item.date}</td><td style="padding: 8px;">${item.type}</td><td style="padding: 8px;">${item.vendor}</td><td style="padding: 8px;">${item.category}</td><td style="padding: 8px; text-align: right;">NPR ${parseFloat(item.amount).toFixed(2)}</td></tr>`;
        });
        html += '</tbody></table>';
      } else if (currentReportType === 'inventory') {
        html += '<table style="width: 100%; border-collapse: collapse;">';
        html += '<thead><tr style="background: #001f3f; color: white;"><th style="padding: 10px; text-align: left;">Date</th><th style="padding: 10px; text-align: left;">Product</th><th style="padding: 10px; text-align: left;">SKU</th><th style="padding: 10px; text-align: right;">Current</th><th style="padding: 10px; text-align: right;">Unit Price</th><th style="padding: 10px; text-align: right;">Total Value</th></tr></thead>';
        html += '<tbody>';
        currentReportData.forEach(item => {
          html += `<tr style="border-bottom: 1px solid #ddd;"><td style="padding: 8px;">${item.date}</td><td style="padding: 8px;">${item.product}</td><td style="padding: 8px;">${item.sku}</td><td style="padding: 8px; text-align: right;">${parseFloat(item.current_stock).toFixed(2)}</td><td style="padding: 8px; text-align: right;">NPR ${parseFloat(item.unit_price).toFixed(2)}</td><td style="padding: 8px; text-align: right;">NPR ${parseFloat(item.total_value).toFixed(2)}</td></tr>`;
        });
        html += '</tbody></table>';
      }

      html += '</div>';
      content.innerHTML = html;
      modal.style.display = 'flex';
    }

    function closePreviewModal() {
      document.getElementById('previewModal').style.display = 'none';
    }

    function downloadPDF() {
      if (!currentReportType || !currentFromDate || !currentToDate) {
        alert('Please generate a report first');
        return;
      }

      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '/api/reports/pdf';
      form.style.display = 'none';

      const csrfInput = document.createElement('input');
      csrfInput.name = '_token';
      csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
      form.appendChild(csrfInput);

      const typeInput = document.createElement('input');
      typeInput.name = 'report_type';
      typeInput.value = currentReportType;
      form.appendChild(typeInput);

      const fromInput = document.createElement('input');
      fromInput.name = 'from_date';
      fromInput.value = currentFromDate;
      form.appendChild(fromInput);

      const toInput = document.createElement('input');
      toInput.name = 'to_date';
      toInput.value = currentToDate;
      form.appendChild(toInput);

      document.body.appendChild(form);
      form.submit();
      document.body.removeChild(form);
    }

    // Initialize date fields with today's date
    window.addEventListener('load', function() {
      const today = new Date().toISOString().split('T')[0];
      document.getElementById('toDate').value = today;

      const thirtyDaysAgo = new Date();
      thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
      document.getElementById('fromDate').value = thirtyDaysAgo.toISOString().split('T')[0];
    });

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
  </script>
</body>
</html>
