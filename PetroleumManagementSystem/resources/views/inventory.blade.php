<!doctype html>
<html lang="en" class="h-full">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inventory Manager</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="/_sdk/element_sdk.js"></script>
  <script src="/_sdk/data_sdk.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
  <link href="/assets/css/stock.css" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    body {
      box-sizing: border-box;
      font-family: 'DM Sans', sans-serif;
    }
    .custom-scrollbar::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
      background: #f8fafc;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 3px;
    }
    .stock-low {
      animation: pulse-red 2s infinite;
    }
    @keyframes pulse-red {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.6; }
    }
    .table-row {
      transition: all 0.2s ease;
    }
    .table-row:hover {
      transform: translateX(4px);
    }
  </style>
  <style>@view-transition { navigation: auto; }</style>
 </head>
 <body class="h-full bg-white text-slate-900">
  <div class="dashboard-wrapper">
    {{-- SIDEBAR --}}
    @include('layouts.sidebar')

    {{-- PAGE CONTENT --}}
    <div class="main-content">
  <div id="app" class="h-full w-full flex flex-col overflow-auto custom-scrollbar"><!-- Header -->
   <header class="bg-gradient-to-r from-blue-900 to-blue-950 border-b border-blue-950 px-6 py-4 flex-shrink-0" style="background: linear-gradient(to right, #1e3a5f, #152947);">
    <div class="flex items-center justify-between flex-wrap gap-4">
     <div class="flex items-center gap-3">
      <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center">
       <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
       </svg>
      </div>
      <div>
       <h1 id="page-title" class="text-xl font-bold text-white">Inventory Manager</h1>
       <p class="text-sm text-blue-100">Track your stock in real-time</p>
      </div>
     </div><button id="add-product-btn" class="bg-white hover:bg-gray-100 px-4 py-2 rounded-lg font-medium flex items-center gap-2 transition-colors" style="color: #1e3a5f;">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
      </svg> Add Product </button>
    </div>
   </header><!-- Stats Cards -->
   <div class="px-6 py-4 grid grid-cols-2 md:grid-cols-4 gap-4 flex-shrink-0">
    <div class="bg-blue-900 rounded-xl p-4 border border-blue-800" style="background: #1e3a5f; border-color: #152947;">
     <div class="flex items-center gap-3">
      <div class="w-10 h-10 bg-blue-800 rounded-lg flex items-center justify-center" style="background: #152947;">
       <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
       </svg>
      </div>
      <div>
       <p class="text-blue-200 text-sm" style="color: #93c5fd;">Total Products</p>
       <p id="stat-total" class="text-xl font-bold text-white">0</p>
      </div>
     </div>
    </div>
    <div class="bg-blue-900 rounded-xl p-4 border border-blue-800" style="background: #1e3a5f; border-color: #152947;">
     <div class="flex items-center gap-3">
      <div class="w-10 h-10 bg-red-900 rounded-lg flex items-center justify-center">
       <svg class="w-5 h-5 text-red-300" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
       </svg>
      </div>
      <div>
       <p class="text-blue-200 text-sm" style="color: #93c5fd;">Low Stock</p>
       <p id="stat-low" class="text-xl font-bold text-white">0</p>
      </div>
     </div>
    </div>
    <div class="bg-blue-900 rounded-xl p-4 border border-blue-800" style="background: #1e3a5f; border-color: #152947;">
     <div class="flex items-center gap-3">
      <div class="w-10 h-10 bg-green-900 rounded-lg flex items-center justify-center">
       <svg class="w-5 h-5 text-green-300" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
       </svg>
      </div>
      <div>
       <p class="text-blue-200 text-sm" style="color: #93c5fd;">Total Value</p>
       <p id="stat-value" class="text-xl font-bold text-white">0</p>
      </div>
     </div>
    </div>
    <div class="bg-blue-900 rounded-xl p-4 border border-blue-800" style="background: #1e3a5f; border-color: #152947;">
     <div class="flex items-center gap-3">
      <div class="w-10 h-10 bg-blue-800 rounded-lg flex items-center justify-center" style="background: #152947;">
       <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
       </svg>
      </div>
      <div>
       <p class="text-blue-200 text-sm" style="color: #93c5fd;">Potential Profit</p>
       <p id="stat-profit" class="text-xl font-bold text-white">0</p>
      </div>
     </div>
    </div>
   </div><!-- Search and Filter -->
   <div class="px-6 py-2 flex flex-wrap gap-3 flex-shrink-0">
    <div class="relative flex-1 min-w-[200px]">
     <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
     </svg><input type="text" id="search-input" placeholder="Search products..." class="w-full bg-white border border-gray-300 rounded-lg pl-10 pr-4 py-2 text-slate-900 placeholder-slate-500 focus:outline-none focus:border-blue-900 transition-colors" style="--tw-ring-color: #1e3a5f;">
    </div><select id="filter-category" class="bg-white border border-gray-300 rounded-lg px-4 py-2 text-slate-900 focus:outline-none focus:border-blue-900" style="--tw-ring-color: #1e3a5f;"> <option value="">All Categories</option> </select> <select id="filter-stock" class="bg-white border border-gray-300 rounded-lg px-4 py-2 text-slate-900 focus:outline-none focus:border-blue-900" style="--tw-ring-color: #1e3a5f;"> <option value="">All Stock</option> <option value="low">Low Stock</option> <option value="out">Out of Stock</option> <option value="ok">In Stock</option> </select>
   </div><!-- Table Container -->
   <div class="flex-1 px-6 py-4 overflow-auto">
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
     <div class="overflow-x-auto custom-scrollbar">
      <table class="w-full min-w-[900px]">
       <thead class="bg-blue-900 border-b border-blue-800" style="background: #1e3a5f; border-color: #152947;">
        <tr>
         <th class="text-left px-4 py-3 text-sm font-semibold text-white">Product</th>
         <th class="text-left px-4 py-3 text-sm font-semibold text-blue-200" style="color: #93c5fd;">SKU</th>
         <th class="text-left px-4 py-3 text-sm font-semibold text-blue-200" style="color: #93c5fd;">Category</th>
         <th class="text-center px-4 py-3 text-sm font-semibold text-blue-200" style="color: #93c5fd;">Stock</th>
         <th class="text-left px-4 py-3 text-sm font-semibold text-blue-200" style="color: #93c5fd;">Unit</th>
         <th class="text-right px-4 py-3 text-sm font-semibold text-blue-200" style="color: #93c5fd;">Cost Price</th>
         <th class="text-right px-4 py-3 text-sm font-semibold text-blue-200" style="color: #93c5fd;">Selling Price</th>
         <th class="text-right px-4 py-3 text-sm font-semibold text-blue-200" style="color: #93c5fd;">Margin</th>
         <th class="text-left px-4 py-3 text-sm font-semibold text-blue-200" style="color: #93c5fd;">Last Purchase</th>
         <th class="text-center px-4 py-3 text-sm font-semibold text-blue-200" style="color: #93c5fd;">Actions</th>
        </tr>
       </thead>
       <tbody id="product-table-body" class="divide-y divide-slate-200"><!-- Products will be rendered here -->
       </tbody>
      </table>
     </div><!-- Empty State -->
     <div id="empty-state" class="hidden py-16 text-center">
      <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4" style="background: #dbeafe;">
       <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
       </svg>
      </div>
      <h3 class="text-lg font-semibold text-slate-900 mb-2">No products yet</h3>
      <p class="text-slate-600 mb-4">Start by adding your first product to track inventory</p><button id="empty-add-btn" class="bg-blue-900 hover:bg-blue-950 text-white px-4 py-2 rounded-lg font-medium transition-colors" style="background: #1e3a5f;"> Add Your First Product </button>
     </div>
    </div>
   </div><!-- Add/Edit Product Modal -->
   <div id="product-modal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg max-h-[90%] overflow-auto custom-scrollbar border border-slate-200 shadow-lg">
     <div class="p-6 border-b border-slate-200 flex items-center justify-between bg-gradient-to-r from-blue-900 to-blue-950" style="background: linear-gradient(to right, #1e3a5f, #152947);">
      <h2 id="modal-title" class="text-xl font-bold text-white">Add New Product</h2><button id="close-modal" class="text-blue-100 hover:text-white transition-colors" style="color: #93c5fd;">
       <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
       </svg></button>
     </div>
     <form id="product-form" class="p-6 space-y-4"><input type="hidden" id="edit-id">
      <div><label for="product-name" class="block text-sm font-medium text-slate-900 mb-1">Product Name *</label>
       <div class="relative"><input type="text" id="product-name" required class="w-full bg-white border border-slate-300 rounded-lg px-4 py-2 pr-12 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-900" style="--tw-ring-color: #1e3a5f;"> <button type="button" id="search-product-btn" class="absolute right-2 top-1/2 -translate-y-1/2 bg-blue-900 hover:bg-blue-950 text-white p-2 rounded-lg transition-colors" style="background: #1e3a5f;" title="Search existing products">
         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
         </svg></button>
       </div><!-- Search Results Dropdown -->
       <div id="search-results" class="hidden absolute top-full left-0 right-0 mt-1 bg-white border border-slate-300 rounded-lg shadow-lg z-40 max-h-48 overflow-y-auto custom-scrollbar"><!-- Results will appear here -->
       </div>
      </div>
      <div><label for="product-category" class="block text-sm font-medium text-slate-900 mb-1">Category</label> <input type="text" id="product-category" list="category-list" placeholder="e.g., Diesel" class="w-full bg-white border border-slate-300 rounded-lg px-4 py-2 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-900" style="--tw-ring-color: #1e3a5f;"> <datalist id="category-list"></datalist>
      </div>
      <div><label for="product-new-stock" class="block text-sm font-medium text-slate-900 mb-1">New Stock *</label> <input type="number" id="product-new-stock" required min="0" step="0.01" placeholder="Quantity to add" class="w-full bg-white border border-slate-300 rounded-lg px-4 py-2 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-900" style="--tw-ring-color: #1e3a5f;">
      </div>
      <div><label for="product-stock" class="block text-sm font-medium text-slate-900 mb-1">Current Stock *</label> <input type="number" id="product-stock" required min="0" step="0.01" class="w-full bg-white border border-slate-300 rounded-lg px-4 py-2 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-900" style="--tw-ring-color: #1e3a5f;">
      </div>
      <div><label for="product-unit" class="block text-sm font-medium text-slate-900 mb-1">Unit *</label> <select id="product-unit" required class="w-full bg-white border border-slate-300 rounded-lg px-4 py-2 text-slate-900 focus:outline-none focus:border-blue-900" style="--tw-ring-color: #1e3a5f;"> <option value="pc">Piece (pc)</option> <option value="ltr">Liter (ltr)</option> <option value="kg">Kilogram (kg)</option> <option value="g">Gram (g)</option> <option value="ml">Milliliter (ml)</option> <option value="box">Box</option> <option value="pack">Pack</option> <option value="dozen">Dozen</option> </select>
      </div><!-- Add More Stock Section -->
      <div id="add-stock-section" class="hidden pt-4 border-t border-slate-300">
       <h3 class="text-sm font-semibold text-slate-900 mb-3 flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg> Add More Stock</h3>
       <div class="space-y-3">
        <div><label for="add-stock-qty" class="block text-sm font-medium text-slate-900 mb-1">Quantity to Add</label> <input type="number" id="add-stock-qty" min="0" step="0.01" placeholder="0" class="w-full bg-white border border-emerald-300 rounded-lg px-4 py-2 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-emerald-700" style="--tw-ring-color: #059669;">
        </div><button type="button" id="quick-add-stock-btn" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center justify-center gap-2">
         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
         </svg><span id="quick-add-text">Add Stock</span>
         <svg id="quick-add-spinner" class="hidden w-5 h-5 animate-spin" fill="none" viewbox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle> <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
         </svg></button>
       </div>
      </div>
      <div class="grid grid-cols-2 gap-4">
      <div><label for="product-cost" class="block text-sm font-medium text-slate-900 mb-1">Cost Price *</label>
       <div class="relative"><span id="cost-currency" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-600 pointer-events-none">NPR</span> <input type="number" id="product-cost" required min="0" step="0.01" class="w-full bg-white border border-slate-300 rounded-lg pl-14 pr-4 py-2 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-900" style="--tw-ring-color: #1e3a5f;">
        </div>
       </div>
       <div><label for="product-selling" class="block text-sm font-medium text-slate-900 mb-1">Selling Price *</label>
       <div class="relative"><span id="selling-currency" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-600 pointer-events-none">NPR</span> <input type="number" id="product-selling" required min="0" step="0.01" class="w-full bg-white border border-slate-300 rounded-lg pl-14 pr-4 py-2 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-900" style="--tw-ring-color: #1e3a5f;">
        </div>
       </div>
      </div>
      <div><label for="product-purchase-date" class="block text-sm font-medium text-slate-900 mb-1">Last Purchase Date</label> <input type="date" id="product-purchase-date" class="w-full bg-white border border-slate-300 rounded-lg px-4 py-2 text-slate-900 focus:outline-none focus:border-blue-900" style="--tw-ring-color: #1e3a5f;">
      </div>
      <div class="pt-4 flex gap-3"><button type="button" id="cancel-btn" class="flex-1 bg-slate-300 hover:bg-slate-400 text-slate-900 px-4 py-2 rounded-lg font-medium transition-colors"> Cancel </button> <button type="submit" id="save-btn" class="flex-1 bg-blue-900 hover:bg-blue-950 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center justify-center gap-2" style="background: #1e3a5f;"> <span id="save-text">Save Product</span>
        <svg id="save-spinner" class="hidden w-5 h-5 animate-spin" fill="none" viewbox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle> <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
        </svg></button>
      </div>
     </form>
    </div>
   </div><!-- Delete Confirmation Modal -->
   <div id="delete-modal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-sm border border-slate-200 p-6 shadow-lg">
     <div class="text-center">
      <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
       <svg class="w-8 h-8 text-red-700" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
       </svg>
      </div>
      <h3 class="text-lg font-bold text-slate-900 mb-2">Delete Product?</h3>
      <p id="delete-product-name" class="text-slate-600 mb-6">This action cannot be undone.</p>
      <div class="flex gap-3"><button id="cancel-delete" class="flex-1 bg-slate-300 hover:bg-slate-400 text-slate-900 px-4 py-2 rounded-lg font-medium transition-colors"> Cancel </button> <button id="confirm-delete" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center justify-center gap-2"> <span id="delete-text">Delete</span>
        <svg id="delete-spinner" class="hidden w-5 h-5 animate-spin" fill="none" viewbox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle> <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
        </svg></button>
      </div>
     </div>
    </div>
   </div><!-- Toast Notification -->
   <div id="toast" class="fixed bottom-4 right-4 bg-white border border-slate-300 rounded-lg px-4 py-3 shadow-lg transform translate-y-20 opacity-0 transition-all duration-300 z-50">
    <div class="flex items-center gap-3">
     <div id="toast-icon" class="w-8 h-8 rounded-full flex items-center justify-center"></div>
     <p id="toast-message" class="text-slate-900 font-medium"></p>
    </div>
   </div><!-- Limit Warning -->
   <div id="limit-warning" class="hidden fixed top-4 left-1/2 -translate-x-1/2 bg-red-600 text-white px-4 py-2 rounded-lg shadow-lg z-50 flex items-center gap-2">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
    </svg><span>Maximum 999 products reached. Please delete some products first.</span>
   </div>
  </div>
  <script>
    // State
    let products = [];
    let filteredProducts = [];
    let editingProduct = null;
    let deletingProduct = null;

    // Default Config
    const defaultConfig = {
      page_title: 'Inventory Manager',
      currency_symbol: 'Rs.',
      background_color: '#ffffff',
      surface_color: '#f0f4f8',
      text_color: '#1e3a8a',
      primary_action_color: '#1e3a8a',
      secondary_action_color: '#cbd5e1',
      font_family: 'DM Sans',
      font_size: 16
    };

    // Initialize Element SDK
    if (window.elementSdk) {
      window.elementSdk.init({
        defaultConfig,
        onConfigChange: async (config) => {
          const fontFamily = config.font_family || defaultConfig.font_family;
          const fontSize = config.font_size || defaultConfig.font_size;
          const bgColor = config.background_color || defaultConfig.background_color;
          const surfaceColor = config.surface_color || defaultConfig.surface_color;
          const textColor = config.text_color || defaultConfig.text_color;
          const primaryColor = config.primary_action_color || defaultConfig.primary_action_color;
          const secondaryColor = config.secondary_action_color || defaultConfig.secondary_action_color;
          const currency = config.currency_symbol || defaultConfig.currency_symbol;

          // Apply fonts
          document.body.style.fontFamily = `${fontFamily}, sans-serif`;
          document.body.style.fontSize = `${fontSize}px`;

          // Apply colors
          document.body.style.backgroundColor = bgColor;
          document.getElementById('app').style.backgroundColor = bgColor;

          // Update title
          document.getElementById('page-title').textContent = config.page_title || defaultConfig.page_title;

          // Update currency symbols
          document.getElementById('cost-currency').textContent = currency;
          document.getElementById('selling-currency').textContent = currency;

          // Update stats with currency
          updateStats();

          // Re-render table with new currency
          renderProducts();
        },
        mapToCapabilities: (config) => ({
          recolorables: [
            {
              get: () => config.background_color || defaultConfig.background_color,
              set: (value) => window.elementSdk.setConfig({ background_color: value })
            },
            {
              get: () => config.surface_color || defaultConfig.surface_color,
              set: (value) => window.elementSdk.setConfig({ surface_color: value })
            },
            {
              get: () => config.text_color || defaultConfig.text_color,
              set: (value) => window.elementSdk.setConfig({ text_color: value })
            },
            {
              get: () => config.primary_action_color || defaultConfig.primary_action_color,
              set: (value) => window.elementSdk.setConfig({ primary_action_color: value })
            },
            {
              get: () => config.secondary_action_color || defaultConfig.secondary_action_color,
              set: (value) => window.elementSdk.setConfig({ secondary_action_color: value })
            }
          ],
          borderables: [],
          fontEditable: {
            get: () => config.font_family || defaultConfig.font_family,
            set: (value) => window.elementSdk.setConfig({ font_family: value })
          },
          fontSizeable: {
            get: () => config.font_size || defaultConfig.font_size,
            set: (value) => window.elementSdk.setConfig({ font_size: value })
          }
        }),
        mapToEditPanelValues: (config) => new Map([
          ['page_title', config.page_title || defaultConfig.page_title],
          ['currency_symbol', config.currency_symbol || defaultConfig.currency_symbol]
        ])
      });
    }

    // Initialize Data SDK
    const dataHandler = {
      onDataChanged(data) {
        products = data;
        updateCategoryFilter();
        applyFilters();
        updateStats();
      }
    };

    // Helper functions
    function getConfig() {
      return window.elementSdk?.config || defaultConfig;
    }

    function getCurrency() {
      return getConfig().currency_symbol || defaultConfig.currency_symbol;
    }

    function generateSKU() {
      return 'SKU-' + Math.random().toString(36).substr(2, 8).toUpperCase();
    }

    function formatCurrency(amount) {
      return 'NPR ' + parseFloat(amount).toFixed(2);
    }

    function formatDate(dateStr) {
      if (!dateStr) return '-';
      const date = new Date(dateStr);
      return date.toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' });
    }

    function calculateMargin(cost, selling) {
      if (cost <= 0) return 0;
      return ((selling - cost) / cost * 100).toFixed(1);
    }

    function showToast(message, type = 'success') {
      const toast = document.getElementById('toast');
      const icon = document.getElementById('toast-icon');
      const msg = document.getElementById('toast-message');

      msg.textContent = message;

      if (type === 'success') {
        icon.innerHTML = '<svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
        icon.className = 'w-8 h-8 rounded-full flex items-center justify-center bg-emerald-500/20';
      } else {
        icon.innerHTML = '<svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
        icon.className = 'w-8 h-8 rounded-full flex items-center justify-center bg-red-500/20';
      }

      toast.classList.remove('translate-y-20', 'opacity-0');
      toast.classList.add('translate-y-0', 'opacity-100');

      setTimeout(() => {
        toast.classList.add('translate-y-20', 'opacity-0');
        toast.classList.remove('translate-y-0', 'opacity-100');
      }, 3000);
    }

    function updateStats() {
      const total = products.length;
      const lowStock = products.filter(p => p.stock_quantity <= p.min_stock && p.stock_quantity > 0).length;
      const outOfStock = products.filter(p => p.stock_quantity === 0).length;
      const totalValue = products.reduce((sum, p) => sum + (p.stock_quantity * p.cost_price), 0);
      const potentialProfit = products.reduce((sum, p) => sum + (p.stock_quantity * (p.selling_price - p.cost_price)), 0);

      document.getElementById('stat-total').textContent = total;
      document.getElementById('stat-low').textContent = lowStock + outOfStock;
      document.getElementById('stat-value').textContent = parseFloat(totalValue).toFixed(2);
      document.getElementById('stat-profit').textContent = parseFloat(potentialProfit).toFixed(2);
    }

    function updateCategoryFilter() {
      const categories = [...new Set(products.map(p => p.category).filter(Boolean))];
      const select = document.getElementById('filter-category');
      const currentValue = select.value;

      select.innerHTML = '<option value="">All Categories</option>';
      categories.forEach(cat => {
        const option = document.createElement('option');
        option.value = cat;
        option.textContent = cat;
        select.appendChild(option);
      });

      select.value = currentValue;

      // Update datalist for category input
      const datalist = document.getElementById('category-list');
      datalist.innerHTML = '';
      categories.forEach(cat => {
        const option = document.createElement('option');
        option.value = cat;
        datalist.appendChild(option);
      });
    }

    function applyFilters() {
      const searchTerm = document.getElementById('search-input').value.toLowerCase();
      const categoryFilter = document.getElementById('filter-category').value;
      const stockFilter = document.getElementById('filter-stock').value;

      filteredProducts = products.filter(p => {
        const matchesSearch = p.product_name.toLowerCase().includes(searchTerm) ||
                              (p.sku && p.sku.toLowerCase().includes(searchTerm)) ||
                              (p.category && p.category.toLowerCase().includes(searchTerm));

        const matchesCategory = !categoryFilter || p.category === categoryFilter;

        let matchesStock = true;
        if (stockFilter === 'low') matchesStock = p.stock_quantity <= p.min_stock && p.stock_quantity > 0;
        if (stockFilter === 'out') matchesStock = p.stock_quantity === 0;
        if (stockFilter === 'ok') matchesStock = p.stock_quantity > p.min_stock;

        return matchesSearch && matchesCategory && matchesStock;
      });

      renderProducts();
    }

    function renderProducts() {
      const tbody = document.getElementById('product-table-body');
      const emptyState = document.getElementById('empty-state');

      if (filteredProducts.length === 0) {
        tbody.innerHTML = '';
        emptyState.classList.remove('hidden');
        return;
      }

      emptyState.classList.add('hidden');

      // Create a map of existing rows
      const existingRows = new Map();
      tbody.querySelectorAll('tr[data-id]').forEach(row => {
        existingRows.set(row.dataset.id, row);
      });

      // Track which IDs we've seen
      const seenIds = new Set();

      filteredProducts.forEach((product, index) => {
        const id = product.__backendId;
        seenIds.add(id);

        const isLowStock = product.stock_quantity <= product.min_stock;
        const isOutOfStock = product.stock_quantity === 0;
        const margin = calculateMargin(product.cost_price, product.selling_price);

        let stockClass = 'bg-emerald-500/20 text-emerald-400';
        if (isOutOfStock) stockClass = 'bg-red-500/20 text-red-400 stock-low';
        else if (isLowStock) stockClass = 'bg-amber-500/20 text-amber-400 stock-low';

        const rowHtml = `
          <td class="px-4 py-3">
            <div class="font-medium text-slate-900">${escapeHtml(product.product_name)}</div>
          </td>
          <td class="px-4 py-3 text-slate-600 font-mono text-sm">${escapeHtml(product.sku || '-')}</td>
          <td class="px-4 py-3">
            <span class="bg-blue-100 px-2 py-1 rounded text-sm text-blue-900">${escapeHtml(product.category || 'Uncategorized')}</span>
          </td>
          <td class="px-4 py-3 text-center">
            <span class="px-3 py-1 rounded-full text-sm font-medium ${stockClass}">
              ${product.stock_quantity}
            </span>
          </td>
          <td class="px-4 py-3 text-slate-600">${product.unit}</td>
          <td class="px-4 py-3 text-right text-slate-600">${formatCurrency(product.cost_price)}</td>
          <td class="px-4 py-3 text-right text-slate-900 font-medium">${formatCurrency(product.selling_price)}</td>
          <td class="px-4 py-3 text-right">
            <span class="${margin >= 0 ? 'text-green-700' : 'text-red-700'}">${margin}%</span>
          </td>
          <td class="px-4 py-3 text-slate-600 text-sm">${formatDate(product.last_purchase)}</td>
          <td class="px-4 py-3">
            <div class="flex items-center justify-center gap-2">
              <button type="button" class="edit-btn p-2 text-slate-500 hover:text-blue-700 hover:bg-blue-100 rounded-lg transition-colors" onclick="openEditModal('${id}')" title="Edit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
              </button>
              <button type="button" class="delete-btn p-2 text-slate-500 hover:text-red-700 hover:bg-red-100 rounded-lg transition-colors" onclick="openDeleteModal('${id}')" title="Delete">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
              </button>
            </div>
          </td>
        `;

        if (existingRows.has(id)) {
          // Update existing row
          const row = existingRows.get(id);
          row.innerHTML = rowHtml;
          existingRows.delete(id);
        } else {
          // Create new row
          const row = document.createElement('tr');
          row.className = 'table-row border-b border-slate-200 hover:bg-blue-50';
          row.dataset.id = id;
          row.innerHTML = rowHtml;

          // Insert at correct position
          const existingRowsArray = [...tbody.children];
          if (index < existingRowsArray.length) {
            tbody.insertBefore(row, existingRowsArray[index]);
          } else {
            tbody.appendChild(row);
          }
        }
      });

      // Remove rows that no longer exist
      existingRows.forEach(row => row.remove());

    }

    function escapeHtml(text) {
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    function searchProducts(searchTerm) {
      const resultsDiv = document.getElementById('search-results');
      
      if (!searchTerm.trim()) {
        resultsDiv.classList.add('hidden');
        return;
      }

      const matches = products.filter(p => 
        p.product_name.toLowerCase().includes(searchTerm.toLowerCase())
      );

      if (matches.length === 0) {
        resultsDiv.innerHTML = '<div class="px-4 py-3 text-slate-600 text-sm">No products found</div>';
        resultsDiv.classList.remove('hidden');
        return;
      }

      resultsDiv.innerHTML = matches.map(product => `
        <div class="px-4 py-3 border-b border-slate-200 hover:bg-blue-50 cursor-pointer transition-colors search-result" data-id="${product.__backendId}">
          <div class="font-medium text-slate-900">${escapeHtml(product.product_name)}</div>
          <div class="text-xs text-slate-600 mt-1">
            <span class="inline-block">Cost: ${formatCurrency(product.cost_price)}</span>
            <span class="inline-block ml-3">Selling: ${formatCurrency(product.selling_price)}</span>
            <span class="inline-block ml-3">Stock: ${product.stock_quantity} ${product.unit}</span>
          </div>
        </div>
      `).join('');

      resultsDiv.classList.remove('hidden');

      // Attach click listeners to results
      document.querySelectorAll('.search-result').forEach(result => {
        result.addEventListener('click', () => {
          const product = products.find(p => p.__backendId === result.dataset.id);
          if (product) {
            document.getElementById('product-name').value = product.product_name;
            document.getElementById('product-category').value = product.category || '';
            document.getElementById('product-unit').value = product.unit;
            document.getElementById('product-cost').value = product.cost_price;
            document.getElementById('product-selling').value = product.selling_price;
            document.getElementById('product-stock').value = product.stock_quantity;
            resultsDiv.classList.add('hidden');
          }
        });
      });
    }

    function openModal() {
      document.getElementById('product-modal').classList.remove('hidden');
      document.getElementById('product-modal').classList.add('flex');
    }

    function closeModal() {
      document.getElementById('product-modal').classList.add('hidden');
      document.getElementById('product-modal').classList.remove('flex');
      document.getElementById('product-form').reset();
      document.getElementById('edit-id').value = '';

      // Reset field states so add/edit mode validations work correctly
      const newStockInput = document.getElementById('product-new-stock');
      const stockInput = document.getElementById('product-stock');
      newStockInput.required = true;
      newStockInput.disabled = false;
      stockInput.required = true;
      stockInput.disabled = false;

      editingProduct = null;
    }

    function openAddModal() {
      if (products.length >= 999) {
        document.getElementById('limit-warning').classList.remove('hidden');
        setTimeout(() => {
          document.getElementById('limit-warning').classList.add('hidden');
        }, 5000);
        return;
      }

      document.getElementById('modal-title').textContent = 'Add New Product';
      document.getElementById('save-text').textContent = 'Save Product';
      document.getElementById('product-purchase-date').value = new Date().toISOString().split('T')[0];
      
      // Show new stock field and hide add stock section for new products
      const newStockInput = document.getElementById('product-new-stock');
      const stockInput = document.getElementById('product-stock');
      newStockInput.style.display = 'block';
      newStockInput.parentElement.style.display = 'block';
      newStockInput.required = true;
      newStockInput.disabled = false;

      stockInput.required = false;
      stockInput.disabled = true;
      stockInput.value = '';

      document.getElementById('add-stock-section').classList.add('hidden');
      
      openModal();
    }

    function openEditModal(id) {
      const product = products.find(p => p.__backendId === id);
      if (!product) return;

      editingProduct = product;
      document.getElementById('modal-title').textContent = 'Edit Product';
      document.getElementById('save-text').textContent = 'Update Product';
      document.getElementById('edit-id').value = id;

      document.getElementById('product-name').value = product.product_name;
      document.getElementById('product-category').value = product.category || '';
      document.getElementById('product-stock').value = product.stock_quantity;
      document.getElementById('product-unit').value = product.unit;
      document.getElementById('product-cost').value = product.cost_price;
      document.getElementById('product-selling').value = product.selling_price;
      document.getElementById('product-purchase-date').value = product.last_purchase ? product.last_purchase.split('T')[0] : '';

      // Hide new stock field and show add stock section for editing
      const newStockInput = document.getElementById('product-new-stock');
      const stockInput = document.getElementById('product-stock');
      newStockInput.style.display = 'none';
      newStockInput.parentElement.style.display = 'none';
      newStockInput.required = false;
      newStockInput.disabled = true;

      stockInput.required = true;
      stockInput.disabled = false;

      document.getElementById('add-stock-section').classList.remove('hidden');
      document.getElementById('add-stock-qty').value = '';

      openModal();
    }

    function openDeleteModal(id) {
      const product = products.find(p => p.__backendId === id);
      if (!product) return;

      deletingProduct = product;
      document.getElementById('delete-product-name').textContent = `"${product.product_name}" will be permanently deleted.`;
      document.getElementById('delete-modal').classList.remove('hidden');
      document.getElementById('delete-modal').classList.add('flex');
    }

    function closeDeleteModal() {
      document.getElementById('delete-modal').classList.add('hidden');
      document.getElementById('delete-modal').classList.remove('flex');
      deletingProduct = null;
    }

    async function saveProduct(e) {
      e.preventDefault();

      const saveBtn = document.getElementById('save-btn');
      const saveText = document.getElementById('save-text');
      const saveSpinner = document.getElementById('save-spinner');

      saveBtn.disabled = true;
      saveText.classList.add('opacity-50');
      saveSpinner.classList.remove('hidden');

      try {
        // Get values from form
        const productName = document.getElementById('product-name').value.trim();
        const category = document.getElementById('product-category').value.trim();
        const unit = document.getElementById('product-unit').value;
        const costPrice = parseFloat(document.getElementById('product-cost').value);
        const sellingPrice = parseFloat(document.getElementById('product-selling').value);
        const purchaseDate = document.getElementById('product-purchase-date').value;

        // For new products, use "New Stock" field; for editing, use "Current Stock"
        const stockQuantity = editingProduct 
          ? parseFloat(document.getElementById('product-stock').value)
          : parseFloat(document.getElementById('product-new-stock').value);

        if (!editingProduct) {
          document.getElementById('product-stock').value = stockQuantity;
        }

        // Validation
        if (!productName) {
          showToast('Please enter product name', 'error');
          saveBtn.disabled = false;
          saveText.classList.remove('opacity-50');
          saveSpinner.classList.add('hidden');
          return;
        }

        if (isNaN(stockQuantity) || stockQuantity < 0) {
          showToast('Please enter valid stock quantity', 'error');
          saveBtn.disabled = false;
          saveText.classList.remove('opacity-50');
          saveSpinner.classList.add('hidden');
          return;
        }

        if (isNaN(costPrice) || costPrice < 0) {
          showToast('Please enter valid cost price', 'error');
          saveBtn.disabled = false;
          saveText.classList.remove('opacity-50');
          saveSpinner.classList.add('hidden');
          return;
        }

        if (isNaN(sellingPrice) || sellingPrice < 0) {
          showToast('Please enter valid selling price', 'error');
          saveBtn.disabled = false;
          saveText.classList.remove('opacity-50');
          saveSpinner.classList.add('hidden');
          return;
        }

        const productData = {
          product_name: productName,
          sku: editingProduct ? editingProduct.sku : generateSKU(),
          category: category,
          stock_quantity: stockQuantity,
          unit: unit,
          cost_price: costPrice,
          selling_price: sellingPrice,
          min_stock: 0,
          last_purchase: purchaseDate || new Date().toISOString(),
        };

        let result;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        if (editingProduct) {
          const formData = new FormData();
          formData.append('_method', 'PUT');
          Object.entries(productData).forEach(([key, value]) => {
            formData.append(key, value ?? '');
          });

          const response = await fetch(`/api/products/${editingProduct.__backendId}`, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': token,
              'Accept': 'application/json',
            },
            body: formData
          });

          const isJson = (response.headers.get('content-type') || '').includes('application/json');
          result = isJson ? await response.json() : { isOk: false, error: { message: await response.text() } };
          console.log('Update response:', result, 'Status:', response.status);

          if (!response.ok) {
            throw new Error(result?.error?.message ? JSON.stringify(result.error.message) : 'Update failed');
          }
        } else {
          const response = await fetch('/api/products', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': token,
              'Accept': 'application/json',
            },
            body: JSON.stringify(productData)
          });
          const isJson = (response.headers.get('content-type') || '').includes('application/json');
          result = isJson ? await response.json() : { isOk: false, error: { message: await response.text() } };
          console.log('Create response:', result, 'Status:', response.status);

          if (!response.ok) {
            throw new Error(result?.error?.message ? JSON.stringify(result.error.message) : 'Create failed');
          }
        }

        if (result.isOk) {
          showToast(editingProduct ? 'Product updated successfully!' : 'Product added successfully!');
          closeModal();
          await loadProducts();
        } else {
          const errorMsg = result?.error?.message || 'Unknown error';
          console.error('Save error:', errorMsg, result.error);
          showToast(`Error: ${typeof errorMsg === 'string' ? errorMsg : JSON.stringify(errorMsg)}`, 'error');
        }
      } catch (error) {
        console.error('Error saving product:', error);
        showToast(error?.message || 'An error occurred. Please try again.', 'error');
      } finally {
        saveBtn.disabled = false;
        saveText.classList.remove('opacity-50');
        saveSpinner.classList.add('hidden');
      }
    }

    async function deleteProduct() {
      if (!deletingProduct) return;

      const deleteBtn = document.getElementById('confirm-delete');
      const deleteText = document.getElementById('delete-text');
      const deleteSpinner = document.getElementById('delete-spinner');

      deleteBtn.disabled = true;
      deleteText.classList.add('opacity-50');
      deleteSpinner.classList.remove('hidden');

      try {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const response = await fetch(`/api/products/${deletingProduct.__backendId}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
          }
        });
        const result = await response.json();
        console.log('Delete response:', result, 'Status:', response.status);

        if (result.isOk) {
          showToast('Product deleted successfully!');
          closeDeleteModal();
          await loadProducts();
        } else {
          showToast('Failed to delete product: ' + (result.error?.message || 'Unknown error'), 'error');
        }
      } catch (error) {
        console.error('Error deleting product:', error);
        showToast('Failed to delete product: ' + error.message, 'error');
      } finally {
        deleteBtn.disabled = false;
        deleteText.classList.remove('opacity-50');
        deleteSpinner.classList.add('hidden');
      }
    }

    async function loadProducts() {
      try {
        const response = await fetch('/api/products');
        const contentType = response.headers.get('content-type') || '';
        if (!contentType.includes('application/json')) {
          throw new Error('Inventory API returned non-JSON response. Please sign in again.');
        }
        const result = await response.json();
        
        console.log('Load products response:', result, 'Status:', response.status);
        
        if (result.isOk || response.ok) {
          products = result.data || [];
          console.log('Loaded products:', products);
          updateCategoryFilter();
          applyFilters();
          updateStats();
        } else {
          console.error('Failed to load products:', result);
          showToast('Failed to load products', 'error');
        }
      } catch (error) {
        console.error('Error loading products:', error);
        showToast('Error loading products: ' + error.message, 'error');
      }
    }

    // Event Listeners
    document.getElementById('add-product-btn').addEventListener('click', openAddModal);
    document.getElementById('empty-add-btn').addEventListener('click', openAddModal);
    document.getElementById('close-modal').addEventListener('click', closeModal);
    document.getElementById('cancel-btn').addEventListener('click', closeModal);
    document.getElementById('product-form').addEventListener('submit', saveProduct);
    document.getElementById('cancel-delete').addEventListener('click', closeDeleteModal);
    document.getElementById('confirm-delete').addEventListener('click', deleteProduct);

    document.getElementById('search-input').addEventListener('input', applyFilters);
    document.getElementById('filter-category').addEventListener('change', applyFilters);
    document.getElementById('filter-stock').addEventListener('change', applyFilters);

    // Product search in modal
    document.getElementById('product-name').addEventListener('input', (e) => {
      searchProducts(e.target.value);
    });

    document.getElementById('search-product-btn').addEventListener('click', (e) => {
      e.preventDefault();
      searchProducts(document.getElementById('product-name').value);
    });

    console.log('Inventory page initialized. Event listeners attached.');

    // Add stock functionality
    document.getElementById('quick-add-stock-btn').addEventListener('click', async () => {
      const quantity = parseFloat(document.getElementById('add-stock-qty').value) || 0;

      if (quantity <= 0) {
        showToast('Please enter a quantity greater than 0', 'error');
        return;
      }

      if (!editingProduct) {
        showToast('Please select a product first', 'error');
        return;
      }

      const button = document.getElementById('quick-add-stock-btn');
      const text = document.getElementById('quick-add-text');
      const spinner = document.getElementById('quick-add-spinner');
      button.disabled = true;
      text.classList.add('opacity-50');
      spinner.classList.remove('hidden');

      try {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const updatedProduct = {
          product_name: editingProduct.product_name,
          sku: editingProduct.sku,
          category: editingProduct.category,
          stock_quantity: editingProduct.stock_quantity + quantity,
          unit: editingProduct.unit,
          cost_price: editingProduct.cost_price,
          selling_price: editingProduct.selling_price,
          min_stock: editingProduct.min_stock,
          last_purchase: new Date().toISOString(),
        };

        const formData = new FormData();
        formData.append('_method', 'PUT');
        Object.entries(updatedProduct).forEach(([key, value]) => {
          formData.append(key, value ?? '');
        });

        const response = await fetch(`/api/products/${editingProduct.__backendId}`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
          },
          body: formData
        });

        const isJson = (response.headers.get('content-type') || '').includes('application/json');
        const result = isJson ? await response.json() : { isOk: false, error: { message: await response.text() } };

        if (result.isOk) {
          showToast(`Added ${quantity} ${editingProduct.unit}!`);
          document.getElementById('add-stock-qty').value = '';
          document.getElementById('product-stock').value = (editingProduct.stock_quantity + quantity);
          editingProduct.stock_quantity = editingProduct.stock_quantity + quantity;
          await loadProducts();
        } else {
          const errorMsg = result?.error?.message || 'Failed to add stock. Please try again.';
          showToast(typeof errorMsg === 'string' ? errorMsg : JSON.stringify(errorMsg), 'error');
        }
      } catch (error) {
        console.error('Error adding stock:', error);
        showToast('Failed to add stock. Please try again.', 'error');
      } finally {
        button.disabled = false;
        text.classList.remove('opacity-50');
        spinner.classList.add('hidden');
      }
    });

    // Close search results when clicking outside
    document.addEventListener('click', (e) => {
      const searchResults = document.getElementById('search-results');
      const productName = document.getElementById('product-name');
      const searchBtn = document.getElementById('search-product-btn');
      
      if (!searchResults.contains(e.target) && e.target !== productName && e.target !== searchBtn) {
        searchResults.classList.add('hidden');
      }
    });

    // Close modals on backdrop click
    document.getElementById('product-modal').addEventListener('click', (e) => {
      if (e.target === document.getElementById('product-modal')) closeModal();
    });

    document.getElementById('delete-modal').addEventListener('click', (e) => {
      if (e.target === document.getElementById('delete-modal')) closeDeleteModal();
    });

    // Initialize Data SDK
    (async () => {
      console.log('Starting loadProducts...');
      await loadProducts();
      console.log('Products loaded. Total:', products.length);
    })();
  </script>
    </div><!-- Close main-content -->
  </div><!-- Close dashboard-wrapper -->
 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9c6b822e466c9e80',t:'MTc2OTg4NjYyMC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>