
<!doctype html>
<html lang="en" class="h-full">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nozzle Management</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
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

    .nozzle-card {
      background: white;
      border-radius: 16px;
      border: 2px solid transparent;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      transition: all 0.3s ease;
    }

    .nozzle-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 10px 30px rgba(0, 31, 63, 0.12);
    }

    .input-wrapper input {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid #d1d5db;
      border-radius: 6px;
      font-size: 16px;
      transition: all 0.2s ease;
      background: white;
    }

    .input-wrapper input:focus {
      outline: none;
      border-color: #001f3f;
      box-shadow: 0 0 0 2px rgba(0, 31, 63, 0.1);
    }

    .btn-primary {
      background: #001f3f;
      color: white;
      padding: 12px 28px;
      border-radius: 6px;
      font-weight: 600;
      border: none;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .btn-primary:hover:not(:disabled) {
      background: #003d7a;
    }

    .btn-primary:disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }

    .btn-delete {
      background: #fee2e2;
      color: #dc2626;
      padding: 6px 10px;
      border-radius: 4px;
      border: none;
      cursor: pointer;
      font-size: 12px;
      font-weight: 500;
    }

    .btn-delete:hover {
      background: #fecaca;
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
    <h2>Nozzle Management</h2>
    <p>Monitor and manage your nozzle readings</p>
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
    <div class="stock-container max-w-6xl mx-auto p-4 pb-12"><!-- Entry Form -->
    <div class="nozzle-card rounded-2xl p-8 mb-8">
     <h2 class="text-2xl font-bold text-gray-800 mb-6">Daily Nozzle Entry</h2><!-- Messages -->
     <div id="message-container"></div><!-- Date Selection -->
     <div class="mb-8"><label class="block text-sm font-600 text-gray-700 mb-2"> Date</label>
      <div class="input-wrapper" style="max-width: 300px;"><input type="date" id="input-date">
      </div>
     </div><!-- Diesel Section -->
     <div class="mb-8 p-6 bg-white rounded-lg border-l-4 border-blue-900">
      <h3 class="text-lg font-bold text-gray-900 mb-6">Diesel</h3>
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8"><!-- Diesel N1 -->
       <div>
        <div class="bg-gray-50 p-4 rounded-lg mb-4 border border-gray-200">
         <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><span class="bg-blue-900 text-white w-6 h-6 rounded-full flex items-center justify-center text-sm">N1</span> Diesel Nozzle 1</h4>
         <div class="space-y-3">
          <div><label class="text-xs font-600 text-gray-700 block mb-1">Opening Reading (L)</label>
           <div class="input-wrapper"><input type="number" id="diesel-n1-opening" placeholder="0.00" step="0.01" min="0" oninput="updateAllTotals()">
           </div>
          </div>
          <div><label class="text-xs font-600 text-gray-700 block mb-1">Closing Reading (L)</label>
           <div class="input-wrapper"><input type="number" id="diesel-n1-closing" placeholder="0.00" step="0.01" min="0" oninput="updateAllTotals()">
           </div>
          </div>
         </div>
        </div>
        <div class="bg-blue-900 text-white p-3 rounded-lg text-center font-bold text-sm"><span id="diesel-n1-total">0.00 L</span>
        </div>
       </div><!-- Diesel N2 -->
       <div>
        <div class="bg-gray-50 p-4 rounded-lg mb-4 border border-gray-200">
         <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><span class="bg-blue-900 text-white w-6 h-6 rounded-full flex items-center justify-center text-sm">N2</span> Diesel Nozzle 2</h4>
         <div class="space-y-3">
          <div><label class="text-xs font-600 text-gray-700 block mb-1">Opening Reading (L)</label>
           <div class="input-wrapper"><input type="number" id="diesel-n2-opening" placeholder="0.00" step="0.01" min="0" oninput="updateAllTotals()">
           </div>
          </div>
          <div><label class="text-xs font-600 text-gray-700 block mb-1">Closing Reading (L)</label>
           <div class="input-wrapper"><input type="number" id="diesel-n2-closing" placeholder="0.00" step="0.01" min="0" oninput="updateAllTotals()">
           </div>
          </div>
         </div>
        </div>
        <div class="bg-blue-900 text-white p-3 rounded-lg text-center font-bold text-sm"><span id="diesel-n2-total">0.00 L</span>
        </div>
       </div>
        <div>
         <div class="bg-gray-50 p-4 rounded-lg mb-4 border border-gray-200">
          <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><span class="bg-blue-900 text-white w-6 h-6 rounded-full flex items-center justify-center text-sm">N3</span> Diesel Nozzle 3</h4>
          <div class="space-y-3">
           <div><label class="text-xs font-600 text-gray-700 block mb-1">Opening Reading (L)</label>
            <div class="input-wrapper"><input type="number" id="diesel-n3-opening" placeholder="0.00" step="0.01" min="0" oninput="updateAllTotals()">
            </div>
           </div>
           <div><label class="text-xs font-600 text-gray-700 block mb-1">Closing Reading (L)</label>
            <div class="input-wrapper"><input type="number" id="diesel-n3-closing" placeholder="0.00" step="0.01" min="0" oninput="updateAllTotals()">
            </div>
           </div>
          </div>
         </div>
         <div class="bg-blue-900 text-white p-3 rounded-lg text-center font-bold text-sm"><span id="diesel-n3-total">0.00 L</span>
         </div>
        </div>
        <div>
         <div class="bg-gray-50 p-4 rounded-lg mb-4 border border-gray-200">
          <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><span class="bg-blue-900 text-white w-6 h-6 rounded-full flex items-center justify-center text-sm">N4</span> Diesel Nozzle 4</h4>
          <div class="space-y-3">
           <div><label class="text-xs font-600 text-gray-700 block mb-1">Opening Reading (L)</label>
            <div class="input-wrapper"><input type="number" id="diesel-n4-opening" placeholder="0.00" step="0.01" min="0" oninput="updateAllTotals()">
            </div>
           </div>
           <div><label class="text-xs font-600 text-gray-700 block mb-1">Closing Reading (L)</label>
            <div class="input-wrapper"><input type="number" id="diesel-n4-closing" placeholder="0.00" step="0.01" min="0" oninput="updateAllTotals()">
            </div>
           </div>
          </div>
         </div>
         <div class="bg-blue-900 text-white p-3 rounded-lg text-center font-bold text-sm"><span id="diesel-n4-total">0.00 L</span>
         </div>
        </div>
      </div><!-- Diesel Total -->
      <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg text-center">
       <p class="text-xs text-gray-600 font-600">Total Diesel Consumption</p>
       <p class="text-2xl font-bold text-blue-900" id="diesel-total">0.00 L</p>
      </div>
     </div><!-- Petrol Section -->
     <div class="mb-8 p-6 bg-white rounded-lg border-l-4 border-blue-800">
      <h3 class="text-lg font-bold text-gray-900 mb-6">Petrol</h3>
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8"><!-- Petrol N1 -->
       <div>
        <div class="bg-gray-50 p-4 rounded-lg mb-4 border border-gray-200">
         <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><span class="bg-blue-800 text-white w-6 h-6 rounded-full flex items-center justify-center text-sm">N1</span> Petrol Nozzle 1</h4>
         <div class="space-y-3">
          <div><label class="text-xs font-600 text-gray-700 block mb-1">Opening Reading (L)</label>
           <div class="input-wrapper"><input type="number" id="petrol-n1-opening" placeholder="0.00" step="0.01" min="0" oninput="updateAllTotals()">
           </div>
          </div>
          <div><label class="text-xs font-600 text-gray-700 block mb-1">Closing Reading (L)</label>
           <div class="input-wrapper"><input type="number" id="petrol-n1-closing" placeholder="0.00" step="0.01" min="0" oninput="updateAllTotals()">
           </div>
          </div>
         </div>
        </div>
        <div class="bg-blue-800 text-white p-3 rounded-lg text-center font-bold text-sm"><span id="petrol-n1-total">0.00 L</span>
        </div>
       </div><!-- Petrol N2 -->
       <div>
        <div class="bg-gray-50 p-4 rounded-lg mb-4 border border-gray-200">
         <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><span class="bg-blue-800 text-white w-6 h-6 rounded-full flex items-center justify-center text-sm">N2</span> Petrol Nozzle 2</h4>
         <div class="space-y-3">
          <div><label class="text-xs font-600 text-gray-700 block mb-1">Opening Reading (L)</label>
           <div class="input-wrapper"><input type="number" id="petrol-n2-opening" placeholder="0.00" step="0.01" min="0" oninput="updateAllTotals()">
           </div>
          </div>
          <div><label class="text-xs font-600 text-gray-700 block mb-1">Closing Reading (L)</label>
           <div class="input-wrapper"><input type="number" id="petrol-n2-closing" placeholder="0.00" step="0.01" min="0" oninput="updateAllTotals()">
           </div>
          </div>
         </div>
        </div>
        <div class="bg-blue-800 text-white p-3 rounded-lg text-center font-bold text-sm"><span id="petrol-n2-total">0.00 L</span>
        </div>
       </div>
        <div>
         <div class="bg-gray-50 p-4 rounded-lg mb-4 border border-gray-200">
          <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><span class="bg-blue-800 text-white w-6 h-6 rounded-full flex items-center justify-center text-sm">N3</span> Petrol Nozzle 3</h4>
          <div class="space-y-3">
           <div><label class="text-xs font-600 text-gray-700 block mb-1">Opening Reading (L)</label>
            <div class="input-wrapper"><input type="number" id="petrol-n3-opening" placeholder="0.00" step="0.01" min="0" oninput="updateAllTotals()">
            </div>
           </div>
           <div><label class="text-xs font-600 text-gray-700 block mb-1">Closing Reading (L)</label>
            <div class="input-wrapper"><input type="number" id="petrol-n3-closing" placeholder="0.00" step="0.01" min="0" oninput="updateAllTotals()">
            </div>
           </div>
          </div>
         </div>
         <div class="bg-blue-800 text-white p-3 rounded-lg text-center font-bold text-sm"><span id="petrol-n3-total">0.00 L</span>
         </div>
        </div>
        <div>
         <div class="bg-gray-50 p-4 rounded-lg mb-4 border border-gray-200">
          <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><span class="bg-blue-800 text-white w-6 h-6 rounded-full flex items-center justify-center text-sm">N4</span> Petrol Nozzle 4</h4>
          <div class="space-y-3">
           <div><label class="text-xs font-600 text-gray-700 block mb-1">Opening Reading (L)</label>
            <div class="input-wrapper"><input type="number" id="petrol-n4-opening" placeholder="0.00" step="0.01" min="0" oninput="updateAllTotals()">
            </div>
           </div>
           <div><label class="text-xs font-600 text-gray-700 block mb-1">Closing Reading (L)</label>
            <div class="input-wrapper"><input type="number" id="petrol-n4-closing" placeholder="0.00" step="0.01" min="0" oninput="updateAllTotals()">
            </div>
           </div>
          </div>
         </div>
         <div class="bg-blue-800 text-white p-3 rounded-lg text-center font-bold text-sm"><span id="petrol-n4-total">0.00 L</span>
         </div>
        </div>
      </div><!-- Petrol Total -->
      <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg text-center">
       <p class="text-xs text-gray-600 font-600">Total Petrol Consumption</p>
       <p class="text-2xl font-bold text-blue-900" id="petrol-total">0.00 L</p>
      </div>
     </div><!-- Grand Totals -->
     <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
      <div class="p-6 bg-blue-900 rounded-lg text-white text-center">
       <p class="text-sm font-600 mb-2">Total Diesel</p>
       <p class="text-3xl font-bold" id="grand-total-diesel">0.00 L</p>
      </div>
      <div class="p-6 bg-blue-800 rounded-lg text-white text-center">
       <p class="text-sm font-600 mb-2">Total Petrol</p>
       <p class="text-3xl font-bold" id="grand-total-petrol">0.00 L</p>
      </div>
     </div><!-- Buttons -->
     <!-- Buttons -->
<div class="flex gap-4">
    <button
        type="button"
        id="save-btn"
        class="btn-primary flex-1"
    >
        Save Entry
    </button>

    <button
        type="button"
        onclick="clearForm()"
        class="px-8 py-3 border-2 border-gray-300 rounded-lg"
    >
        Clear Form
    </button>
</div>

    </div><!-- History Table -->
    <div class="nozzle-card rounded-2xl p-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Entry History</h2>
     <div class="overflow-x-auto">
      <table class="w-full">
       <thead>
        <tr style="border-bottom: 2px solid #d1d5db; background-color: #f3f4f6;">
         <th style="text-align: left; padding: 12px; font-weight: 600; color: #1e3a5f; font-size: 14px;">Date</th>
         <th style="text-align: right; padding: 12px; font-weight: 600; color: #1e3a5f; font-size: 14px;">Diesel N1</th>
         <th style="text-align: right; padding: 12px; font-weight: 600; color: #1e3a5f; font-size: 14px;">Diesel N2</th>
         <th style="text-align: right; padding: 12px; font-weight: 600; color: #1e3a5f; font-size: 14px;">Diesel N3</th>
         <th style="text-align: right; padding: 12px; font-weight: 600; color: #1e3a5f; font-size: 14px;">Diesel N4</th>
         <th style="text-align: right; padding: 12px; font-weight: 600; color: #1e3a5f; font-size: 14px;">Total Diesel</th>
         <th style="text-align: right; padding: 12px; font-weight: 600; color: #1e3a5f; font-size: 14px;">Petrol N1</th>
         <th style="text-align: right; padding: 12px; font-weight: 600; color: #1e3a5f; font-size: 14px;">Petrol N2</th>
         <th style="text-align: right; padding: 12px; font-weight: 600; color: #1e3a5f; font-size: 14px;">Petrol N3</th>
         <th style="text-align: right; padding: 12px; font-weight: 600; color: #1e3a5f; font-size: 14px;">Petrol N4</th>
         <th style="text-align: right; padding: 12px; font-weight: 600; color: #1e3a5f; font-size: 14px;">Total Petrol</th>
         <th style="text-align: right; padding: 12px; font-weight: 600; color: #1e3a5f; font-size: 14px;">Grand Total</th>
         <th style="text-align: center; padding: 12px; font-weight: 600; color: #1e3a5f; font-size: 14px;">Action</th>
        </tr>
       </thead>
       <tbody id="history-tbody">
        @forelse($entries as $entry)
        <tr style="border-bottom: 1px solid #e5e7eb;">
         <td style="padding: 12px; color: #1e3a5f; font-weight: 500;">{{ \Carbon\Carbon::parse($entry->date)->format('M d, Y') }}</td>
         <td style="text-align: right; padding: 12px;">{{ number_format($entry->diesel_n1_closing - $entry->diesel_n1_opening, 2) }} L</td>
         <td style="text-align: right; padding: 12px;">{{ number_format($entry->diesel_n2_closing - $entry->diesel_n2_opening, 2) }} L</td>
         <td style="text-align: right; padding: 12px;">{{ number_format($entry->diesel_n3_closing - $entry->diesel_n3_opening, 2) }} L</td>
         <td style="text-align: right; padding: 12px;">{{ number_format($entry->diesel_n4_closing - $entry->diesel_n4_opening, 2) }} L</td>
         <td style="text-align: right; padding: 12px; font-weight: 600; color: #1e3a5f;">{{ number_format(($entry->diesel_n1_closing - $entry->diesel_n1_opening) + ($entry->diesel_n2_closing - $entry->diesel_n2_opening) + ($entry->diesel_n3_closing - $entry->diesel_n3_opening) + ($entry->diesel_n4_closing - $entry->diesel_n4_opening), 2) }} L</td>
         <td style="text-align: right; padding: 12px;">{{ number_format($entry->petrol_n1_closing - $entry->petrol_n1_opening, 2) }} L</td>
         <td style="text-align: right; padding: 12px;">{{ number_format($entry->petrol_n2_closing - $entry->petrol_n2_opening, 2) }} L</td>
         <td style="text-align: right; padding: 12px;">{{ number_format($entry->petrol_n3_closing - $entry->petrol_n3_opening, 2) }} L</td>
         <td style="text-align: right; padding: 12px;">{{ number_format($entry->petrol_n4_closing - $entry->petrol_n4_opening, 2) }} L</td>
         <td style="text-align: right; padding: 12px; font-weight: 600; color: #1e3a5f;">{{ number_format(($entry->petrol_n1_closing - $entry->petrol_n1_opening) + ($entry->petrol_n2_closing - $entry->petrol_n2_opening) + ($entry->petrol_n3_closing - $entry->petrol_n3_opening) + ($entry->petrol_n4_closing - $entry->petrol_n4_opening), 2) }} L</td>
         <td style="text-align: right; padding: 12px; font-weight: 700; color: #1e3a5f; font-size: 16px;">{{ number_format(($entry->diesel_n1_closing - $entry->diesel_n1_opening) + ($entry->diesel_n2_closing - $entry->diesel_n2_opening) + ($entry->diesel_n3_closing - $entry->diesel_n3_opening) + ($entry->diesel_n4_closing - $entry->diesel_n4_opening) + ($entry->petrol_n1_closing - $entry->petrol_n1_opening) + ($entry->petrol_n2_closing - $entry->petrol_n2_opening) + ($entry->petrol_n3_closing - $entry->petrol_n3_opening) + ($entry->petrol_n4_closing - $entry->petrol_n4_opening), 2) }} L</td>
         <td style="text-align: center; padding: 12px; display: flex; gap: 8px; justify-content: center;">
  <button class="btn-primary edit-btn" style="padding:6px 10px;font-size:12px;" 
    data-id="{{ $entry->id }}"
    data-date="{{ $entry->date }}"
    data-d1o="{{ $entry->diesel_n1_opening }}"
    data-d1c="{{ $entry->diesel_n1_closing }}"
    data-d2o="{{ $entry->diesel_n2_opening }}"
    data-d2c="{{ $entry->diesel_n2_closing }}"
    data-d3o="{{ $entry->diesel_n3_opening }}"
    data-d3c="{{ $entry->diesel_n3_closing }}"
    data-d4o="{{ $entry->diesel_n4_opening }}"
    data-d4c="{{ $entry->diesel_n4_closing }}"
    data-p1o="{{ $entry->petrol_n1_opening }}"
    data-p1c="{{ $entry->petrol_n1_closing }}"
    data-p2o="{{ $entry->petrol_n2_opening }}"
    data-p2c="{{ $entry->petrol_n2_closing }}"
    data-p3o="{{ $entry->petrol_n3_opening }}"
    data-p3c="{{ $entry->petrol_n3_closing }}"
    data-p4o="{{ $entry->petrol_n4_opening }}"
    data-p4c="{{ $entry->petrol_n4_closing }}">
    Edit
  </button>
  <button class="btn-delete" onclick="deleteEntry({{ $entry->id }})">Delete</button>
</td>
        </tr>
        @empty
        <tr>
         <td colspan="13" style="text-align: center; padding: 32px; color: #9ca3af;">No entries yet. Add your first nozzle entry above!</td>
        </tr>
        @endforelse
       </tbody>
      </table>
     </div>
    </div>
   </div>
   </div>
  </div>
 </div>
  <script>
  // Nozzle calculation script
  function updateAllTotals() {
    // Get values
    var d1Open = parseFloat(document.getElementById('diesel-n1-opening').value) || 0;
    var d1Close = parseFloat(document.getElementById('diesel-n1-closing').value) || 0;
    var d1Diff = Math.max(0, d1Close - d1Open);
    
    // Set result
    document.getElementById('diesel-n1-total').innerText = d1Diff.toFixed(2) + ' L';
    
    // Do all others
    var d2Open = parseFloat(document.getElementById('diesel-n2-opening').value) || 0;
    var d2Close = parseFloat(document.getElementById('diesel-n2-closing').value) || 0;
    var d2Diff = Math.max(0, d2Close - d2Open);
    document.getElementById('diesel-n2-total').innerText = d2Diff.toFixed(2) + ' L';

    var d3Open = parseFloat(document.getElementById('diesel-n3-opening').value) || 0;
    var d3Close = parseFloat(document.getElementById('diesel-n3-closing').value) || 0;
    var d3Diff = Math.max(0, d3Close - d3Open);
    document.getElementById('diesel-n3-total').innerText = d3Diff.toFixed(2) + ' L';

    var d4Open = parseFloat(document.getElementById('diesel-n4-opening').value) || 0;
    var d4Close = parseFloat(document.getElementById('diesel-n4-closing').value) || 0;
    var d4Diff = Math.max(0, d4Close - d4Open);
    document.getElementById('diesel-n4-total').innerText = d4Diff.toFixed(2) + ' L';
    
    var p1Open = parseFloat(document.getElementById('petrol-n1-opening').value) || 0;
    var p1Close = parseFloat(document.getElementById('petrol-n1-closing').value) || 0;
    var p1Diff = Math.max(0, p1Close - p1Open);
    document.getElementById('petrol-n1-total').innerText = p1Diff.toFixed(2) + ' L';
    
    var p2Open = parseFloat(document.getElementById('petrol-n2-opening').value) || 0;
    var p2Close = parseFloat(document.getElementById('petrol-n2-closing').value) || 0;
    var p2Diff = Math.max(0, p2Close - p2Open);
    document.getElementById('petrol-n2-total').innerText = p2Diff.toFixed(2) + ' L';

    var p3Open = parseFloat(document.getElementById('petrol-n3-opening').value) || 0;
    var p3Close = parseFloat(document.getElementById('petrol-n3-closing').value) || 0;
    var p3Diff = Math.max(0, p3Close - p3Open);
    document.getElementById('petrol-n3-total').innerText = p3Diff.toFixed(2) + ' L';

    var p4Open = parseFloat(document.getElementById('petrol-n4-opening').value) || 0;
    var p4Close = parseFloat(document.getElementById('petrol-n4-closing').value) || 0;
    var p4Diff = Math.max(0, p4Close - p4Open);
    document.getElementById('petrol-n4-total').innerText = p4Diff.toFixed(2) + ' L';
    
    // Totals
    var dieselTotal = d1Diff + d2Diff + d3Diff + d4Diff;
    var petrolTotal = p1Diff + p2Diff + p3Diff + p4Diff;
    
    document.getElementById('diesel-total').innerText = dieselTotal.toFixed(2) + ' L';
    document.getElementById('petrol-total').innerText = petrolTotal.toFixed(2) + ' L';
    document.getElementById('grand-total-diesel').innerText = dieselTotal.toFixed(2) + ' L';
    document.getElementById('grand-total-petrol').innerText = petrolTotal.toFixed(2) + ' L';
  }

  function clearForm() {
    document.getElementById('input-date').value = '';
    document.getElementById('diesel-n1-opening').value = '';
    document.getElementById('diesel-n1-closing').value = '';
    document.getElementById('diesel-n2-opening').value = '';
    document.getElementById('diesel-n2-closing').value = '';
    document.getElementById('diesel-n3-opening').value = '';
    document.getElementById('diesel-n3-closing').value = '';
    document.getElementById('diesel-n4-opening').value = '';
    document.getElementById('diesel-n4-closing').value = '';
    document.getElementById('petrol-n1-opening').value = '';
    document.getElementById('petrol-n1-closing').value = '';
    document.getElementById('petrol-n2-opening').value = '';
    document.getElementById('petrol-n2-closing').value = '';
    document.getElementById('petrol-n3-opening').value = '';
    document.getElementById('petrol-n3-closing').value = '';
    document.getElementById('petrol-n4-opening').value = '';
    document.getElementById('petrol-n4-closing').value = '';
    updateAllTotals();
  }

  function deleteEntry(id) {
    if (!confirm('Are you sure you want to delete this entry?')) return;
    
    fetch('/nozzle/' + id, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json'
      }
    })
    .then(res => res.json())
    .then(() => {
      alert('Entry deleted successfully ✅');
      location.reload();
    })
    .catch(err => {
      alert('Delete failed ❌');
    });
  }

  // Global variable for editing mode
  let editingEntryId = null;

  // Clear form function (global scope)
  window.clearForm = function() {
    document.getElementById('input-date').value = '';
    document.getElementById('diesel-n1-opening').value = '';
    document.getElementById('diesel-n1-closing').value = '';
    document.getElementById('diesel-n2-opening').value = '';
    document.getElementById('diesel-n2-closing').value = '';
    document.getElementById('diesel-n3-opening').value = '';
    document.getElementById('diesel-n3-closing').value = '';
    document.getElementById('diesel-n4-opening').value = '';
    document.getElementById('diesel-n4-closing').value = '';
    document.getElementById('petrol-n1-opening').value = '';
    document.getElementById('petrol-n1-closing').value = '';
    document.getElementById('petrol-n2-opening').value = '';
    document.getElementById('petrol-n2-closing').value = '';
    document.getElementById('petrol-n3-opening').value = '';
    document.getElementById('petrol-n3-closing').value = '';
    document.getElementById('petrol-n4-opening').value = '';
    document.getElementById('petrol-n4-closing').value = '';
    updateAllTotals();
    editingEntryId = null;
    document.getElementById('save-btn').textContent = 'Save Entry';
  }

document.addEventListener('DOMContentLoaded', function () {
  const fields = [
    'diesel-n1-opening','diesel-n1-closing',
    'diesel-n2-opening','diesel-n2-closing',
    'diesel-n3-opening','diesel-n3-closing',
    'diesel-n4-opening','diesel-n4-closing',
    'petrol-n1-opening','petrol-n1-closing',
    'petrol-n2-opening','petrol-n2-closing',
    'petrol-n3-opening','petrol-n3-closing',
    'petrol-n4-opening','petrol-n4-closing'
  ];

  fields.forEach(id => {
    const el = document.getElementById(id);
    if (el) {
      el.addEventListener('input', updateAllTotals);
      el.addEventListener('change', updateAllTotals);
    }
  });

  updateAllTotals();

  // Edit button click handler
  document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      editingEntryId = this.dataset.id;
      document.getElementById('input-date').value = this.dataset.date;
      document.getElementById('diesel-n1-opening').value = this.dataset.d1o;
      document.getElementById('diesel-n1-closing').value = this.dataset.d1c;
      document.getElementById('diesel-n2-opening').value = this.dataset.d2o;
      document.getElementById('diesel-n2-closing').value = this.dataset.d2c;
      document.getElementById('diesel-n3-opening').value = this.dataset.d3o;
      document.getElementById('diesel-n3-closing').value = this.dataset.d3c;
      document.getElementById('diesel-n4-opening').value = this.dataset.d4o;
      document.getElementById('diesel-n4-closing').value = this.dataset.d4c;
      document.getElementById('petrol-n1-opening').value = this.dataset.p1o;
      document.getElementById('petrol-n1-closing').value = this.dataset.p1c;
      document.getElementById('petrol-n2-opening').value = this.dataset.p2o;
      document.getElementById('petrol-n2-closing').value = this.dataset.p2c;
      document.getElementById('petrol-n3-opening').value = this.dataset.p3o;
      document.getElementById('petrol-n3-closing').value = this.dataset.p3c;
      document.getElementById('petrol-n4-opening').value = this.dataset.p4o;
      document.getElementById('petrol-n4-closing').value = this.dataset.p4c;
      updateAllTotals();
      document.getElementById('save-btn').textContent = 'Update Entry';
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  });

  // Save/Update button logic
  document.getElementById('save-btn').addEventListener('click', function () {
    const saveBtn = this;
    const date = document.getElementById('input-date').value;
    if (!date) {
      alert('Date is required');
      return;
    }

    const missingFields = fields.filter(id => {
      const el = document.getElementById(id);
      return !el || String(el.value).trim() === '';
    });

    if (missingFields.length > 0) {
      alert('All nozzle opening and closing readings are required. Use 0.00 if there is no consumption.');
      return;
    }

    const formData = new FormData();
    formData.append('date', date);

    fields.forEach(id => {
      const value = document.getElementById(id).value;
      formData.append(id.replace(/-/g,'_'), value);
    });

    let url = "{{ route('nozzle.store') }}";
    let method = "POST";
    
    if (editingEntryId) {
      url = `/nozzle/${editingEntryId}`;
      method = "POST";
      formData.append('_method', 'PUT');
    }

    saveBtn.disabled = true;
    saveBtn.textContent = editingEntryId ? 'Updating...' : 'Saving...';

    fetch(url, {
      method: method,
      headers: {
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
        "Accept": "application/json"
      },
      body: formData
    })
    .then(async res => {
      const contentType = res.headers.get('content-type') || '';
      const isJson = contentType.includes('application/json');
      const payload = isJson ? await res.json() : { message: await res.text() };

      if (!res.ok) {
        let message = payload.message || 'Save failed';

        if (payload.errors && typeof payload.errors === 'object') {
          const firstError = Object.values(payload.errors).flat()[0];
          if (firstError) {
            message = firstError;
          }
        }

        throw new Error(message);
      }

      return payload;
    })
    .then((payload) => {
      alert(editingEntryId ? 'Updated successfully ' : 'Saved successfully ');
      location.reload();
    })
    .catch(err => {
      alert((err && err.message ? err.message : 'Save failed') + ' ');
    })
    .finally(() => {
      saveBtn.disabled = false;
      saveBtn.textContent = editingEntryId ? 'Update Entry' : 'Save Entry';
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
});
  </script>
</body>
</html>