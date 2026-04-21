<!doctype html>
<html lang="en" class="h-full">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
  <link href="/assets/css/stock.css" rel="stylesheet">
  <style>
    body {
      box-sizing: border-box;
    }
    * {
      font-family: 'Outfit', sans-serif;
    }
    .gradient-bg {
      background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 60%, #2c5282 100%);
    }
    .card-glass {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
    }
    .avatar-ring {
      background: linear-gradient(135deg, #1e3a5f 0%, #1e40af 50%, #334155 100%);
    }
    .status-pulse {
      animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes pulse {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.5; }
    }
    @keyframes slideIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide {
      animation: slideIn 0.5s ease-out forwards;
    }
    .animate-slide-delay-1 { animation-delay: 0.1s; opacity: 0; }
    .animate-slide-delay-2 { animation-delay: 0.2s; opacity: 0; }
    .animate-slide-delay-3 { animation-delay: 0.3s; opacity: 0; }
    .role-badge {
      display: inline-block;
      padding: 0.25rem 0.75rem;
      border-radius: 9999px;
      font-size: 0.75rem;
      font-weight: 600;
    }
    .role-accountant { background-color: #e0f2fe; color: #1e3a5f; }
    .role-station-manager { background-color: #dbeafe; color: #1e40af; }
    .role-admin { background-color: #e2e8f0; color: #0f172a; }
    .role-super-admin { background-color: #cbd5e1; color: #0f172a; }
    .employee-row {
      cursor: pointer;
      transition: background-color 0.2s;
    }
    .employee-row:hover {
      background-color: #f1f5f9;
    }
  </style>
  <style>@view-transition { navigation: auto; }</style>
 </head>
 <body class="h-full">
  <div class="dashboard-wrapper">
   {{-- SIDEBAR --}}
   @include('layouts.sidebar')

   <div class="main-content">
  <div id="app-wrapper" class="h-full w-full gradient-bg overflow-auto">
   <div class="min-h-full p-4 md:p-8"><!-- Header -->
    <header class="mb-8 animate-slide">
     <div class="flex items-center justify-between flex-wrap gap-4">
      <div class="flex items-center gap-3">
       <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
       </div>
       <div>
        <h1 class="text-white text-xl md:text-2xl font-bold">Admin Portal</h1>
        <p id="welcome-text" class="text-indigo-200 text-sm">Welcome back to your dashboard</p>
       </div>
      </div>
     </div>
    </header>
    
    <!-- Success Message -->
    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 animate-slide">
      <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center flex-shrink-0">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
      </div>
      <div>
        <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
      </div>
      <button onclick="this.parentElement.remove()" class="ml-auto text-emerald-600 hover:text-emerald-800">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6"><!-- Admin Profile Card -->
     <div class="lg:col-span-1 animate-slide animate-slide-delay-1">
      <div class="card-glass rounded-3xl p-6 shadow-2xl">
       <div class="text-center"><!-- Avatar -->
        <div class="relative inline-block mb-4">
         <div class="avatar-ring p-1 rounded-full">
          <div class="w-28 h-28 rounded-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center text-4xl font-bold text-indigo-600" id="avatar-initials">
           {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}{{ strtoupper(substr(explode(' ', auth()->user()->name ?? 'U')[1] ?? substr(auth()->user()->name ?? 'U', 1, 1), 0, 1)) }}
          </div>
         </div>
         <div class="absolute bottom-2 right-2 w-5 h-5 bg-emerald-500 rounded-full border-3 border-white status-pulse"></div>
        </div><!-- Name & Role -->
        <h2 id="admin-name" class="text-2xl font-bold text-slate-800 mb-1">{{ auth()->user()->name ?? 'User' }}</h2>
        <p id="admin-role" class="text-indigo-600 font-medium mb-6">{{ ucfirst(auth()->user()->role ?? 'User') }}</p><!-- Contact Details -->
        <div class="space-y-3 text-left">
         <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
          <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center">
           <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
           </svg>
          </div>
          <div class="flex-1 min-w-0">
           <p class="text-xs text-slate-500 uppercase tracking-wide">Email</p>
           <p id="admin-email" class="text-slate-800 font-medium truncate">{{ auth()->user()->email ?? 'N/A' }}</p>
          </div>
         </div>
         <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
          <div class="w-10 h-10 rounded-lg bg-violet-100 flex items-center justify-center">
           <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
           </svg>
          </div>
          <div class="flex-1 min-w-0">
           <p class="text-xs text-slate-500 uppercase tracking-wide">Phone Number</p>
           <p id="admin-phone" class="text-slate-800 font-medium">{{ auth()->user()->phoneNumber ?? 'N/A' }}</p>
          </div>
         </div>
         <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
          <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
           <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
           </svg>
          </div>
          <div class="flex-1 min-w-0">
           <p class="text-xs text-slate-500 uppercase tracking-wide">Address</p>
           <p class="text-slate-800 font-medium">{{ auth()->user()->address ?? 'N/A' }}</p>
          </div>
         </div>
        </div>
        <!-- Edit Profile Button -->
        <div class="mt-4">
         <button id="edit-profile-btn" class="w-full px-4 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium flex items-center justify-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
          Edit Profile
         </button>
        </div>
       </div>
      </div>
     </div><!-- Main Content Area -->
     <div class="lg:col-span-2"><!-- Quick Actions -->
      <div class="card-glass rounded-3xl p-6 shadow-2xl animate-slide animate-slide-delay-2">
       <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
        </svg> Quick Actions</h3>
       <div class="grid grid-cols-2 md:grid-cols-3 gap-3"><button id="add-user-btn" class="flex flex-col items-center gap-2 p-4 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl hover:from-emerald-100 hover:to-emerald-200 transition-all group">
         <div class="w-12 h-12 rounded-xl bg-emerald-500 flex items-center justify-center group-hover:scale-110 transition-transform">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
         </div><span class="text-sm font-medium text-slate-700">Add User</span> </button> <button id="manage-employees-btn" class="flex flex-col items-center gap-2 p-4 bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl hover:from-indigo-100 hover:to-indigo-200 transition-all group">
         <div class="w-12 h-12 rounded-xl bg-indigo-500 flex items-center justify-center group-hover:scale-110 transition-transform">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
          </svg>
         </div><span class="text-sm font-medium text-slate-700">Manage Employees</span> </button> <button id="remove-employee-btn" class="flex flex-col items-center gap-2 p-4 bg-gradient-to-br from-violet-50 to-violet-100 rounded-xl hover:from-violet-100 hover:to-violet-200 transition-all group">
         <div class="w-12 h-12 rounded-xl bg-violet-500 flex items-center justify-center group-hover:scale-110 transition-transform">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
          </svg>
         </div><span class="text-sm font-medium text-slate-700">Remove User</span> </button>
       </div>
      </div>
     </div>
    </div>
   </div>
  </div><!-- Add User Modal -->
  <div id="add-user-modal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
   <div class="card-glass rounded-3xl p-6 shadow-2xl w-full max-w-md">
    <div class="flex items-center justify-between mb-6">
     <h2 class="text-2xl font-bold text-slate-800">Add New User</h2><button id="close-modal-btn" class="text-slate-400 hover:text-slate-600">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg></button>
    </div>
    <form id="add-user-form" class="space-y-4">
     <div><label for="user-name" class="block text-sm font-medium text-slate-700 mb-2">Name</label> <input type="text" id="user-name" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Enter full name">
     </div>
    <div><label for="user-role" class="block text-sm font-medium text-slate-700 mb-2">Role</label> <select id="user-role" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"> <option value="">Select a role</option> <option value="admin">Admin</option> <option value="operator">Operator</option> <option value="employee">Employee</option> </select>
     </div>
     <div><label for="user-email" class="block text-sm font-medium text-slate-700 mb-2">Email</label> <input type="email" id="user-email" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Enter email address">
     </div>
     <div><label for="user-password" class="block text-sm font-medium text-slate-700 mb-2">Password</label> <input type="password" id="user-password" required minlength="6" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Min 6 characters">
     </div>
     <div class="flex gap-3 pt-4"><button type="button" id="cancel-btn" class="flex-1 px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors font-medium">Cancel</button> <button type="submit" id="submit-btn" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">Add User</button>
     </div>
    </form>
   </div>
  </div><!-- Manage Employees Modal -->
  <div id="manage-modal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
   <div class="card-glass rounded-3xl p-6 shadow-2xl w-full max-w-2xl max-h-[80vh] overflow-y-auto">
    <div class="flex items-center justify-between mb-6 sticky top-0 bg-white/95 -m-6 p-6 pb-4 rounded-t-3xl">
     <h2 class="text-2xl font-bold text-slate-800">Manage Employees</h2><button id="close-manage-modal-btn" class="text-slate-400 hover:text-slate-600">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg></button>
    </div>
    <div id="manage-employees-list" class="space-y-3"><!-- Employees will be populated here -->
    </div>
   </div>
  </div><!-- Remove Employee Modal -->
  <div id="remove-modal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
   <div class="card-glass rounded-3xl p-6 shadow-2xl w-full max-w-2xl max-h-[80vh] overflow-y-auto">
    <div class="flex items-center justify-between mb-6 sticky top-0 bg-white/95 -m-6 p-6 pb-4 rounded-t-3xl">
     <h2 class="text-2xl font-bold text-slate-800">Remove Employee</h2><button id="close-remove-modal-btn" class="text-slate-400 hover:text-slate-600">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg></button>
    </div>
    <div id="remove-employees-list" class="space-y-3"><!-- Employees will be populated here -->
    </div>
   </div>
  </div><!-- Edit Profile Modal -->
  <div id="edit-profile-modal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
   <div class="card-glass rounded-3xl p-6 shadow-2xl w-full max-w-md">
    <div class="flex items-center justify-between mb-6">
     <h2 class="text-2xl font-bold text-slate-800">Edit Profile</h2><button id="close-edit-profile-btn" class="text-slate-400 hover:text-slate-600">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg></button>
    </div>
    <form id="edit-profile-form" method="POST" action="{{ route('admin.update.profile') }}" class="space-y-4">
     @csrf
     @method('PUT')
    <div><label for="edit-name" class="block text-sm font-medium text-slate-700 mb-2">Full Name</label> <input type="text" id="edit-name" name="name" required pattern="[A-Za-z][A-Za-z .'-]*" title="Use letters and spaces only" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Enter full name" value="{{ auth()->user()->name }}">
     </div>
    <div><label for="edit-phone" class="block text-sm font-medium text-slate-700 mb-2">Phone Number</label> <input type="text" id="edit-phone" name="phoneNumber" inputmode="numeric" maxlength="10" pattern="[0-9]{10}" title="Phone number must be exactly 10 digits" oninput="enforceTenDigitPhoneInput(this)" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Enter phone number" value="{{ auth()->user()->phoneNumber }}">
     </div>
     <div><label for="edit-address" class="block text-sm font-medium text-slate-700 mb-2">Address</label> <textarea id="edit-address" name="address" rows="3" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Enter address">{{ auth()->user()->address }}</textarea>
     </div>
     <div class="flex gap-3 pt-4"><button type="button" id="cancel-edit-btn" class="flex-1 px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors font-medium">Cancel</button> <button type="submit" id="submit-edit-btn" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">Save Changes</button>
     </div>
    </form>
   </div>
  </div>
  <script>
    function enforceTenDigitPhoneInput(input) {
      const digits = input.value.replace(/\D/g, '').slice(0, 10);
      if (input.value !== digits) {
        input.value = digits;
      }

      input.setCustomValidity(digits.length === 10 || digits.length === 0 ? '' : 'Phone number must be exactly 10 digits.');
    }

    const defaultConfig = {
      admin_name: '{{ auth()->user()->name ?? "User" }}',
      admin_role: '{{ ucfirst(auth()->user()->role ?? "user") }}',
      admin_email: '{{ auth()->user()->email ?? "N/A" }}',
      admin_department: '{{ auth()->user()->address ?? "N/A" }}',
      welcome_message: 'Welcome back, {{ auth()->user()->name ?? "User" }}!',
      background_color: '#1e1b4b',
      card_color: '#ffffff',
      text_color: '#1e293b',
      primary_color: '#4f46e5',
      accent_color: '#8b5cf6',
      font_family: 'Outfit',
      font_size: 16
    };

    let allUsers = [];

    function getInitials(name) {
      return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
    }

    function getRoleBadgeClass(role) {
      const roleMap = {
        admin: 'role-admin',
        operator: 'role-station-manager',
        employee: 'role-accountant'
      };
      return roleMap[role] || 'role-admin';
    }

    function getRoleLabel(role) {
      const roleMap = {
        admin: 'Admin',
        operator: 'Operator',
        employee: 'Employee'
      };
      return roleMap[role] || role || 'N/A';
    }

    function getToken() {
      const tokenEl = document.querySelector('meta[name="csrf-token"]');
      return tokenEl ? tokenEl.getAttribute('content') : '';
    }

    function renderUsersTable() {
      const tableBody = document.getElementById('users-table-body');
      if (!tableBody) return;
      tableBody.innerHTML = '';

      if (allUsers.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="5" class="text-center py-8 text-slate-500">No users found. Add your first employee!</td></tr>';
        return;
      }

      allUsers.forEach(user => {
        const row = document.createElement('tr');
        row.className = 'border-b border-slate-100 hover:bg-slate-50 transition-colors';
        row.innerHTML = `
          <td class="py-3 px-4 font-medium text-slate-800">${user.name || ''}</td>
          <td class="py-3 px-4">
            <span class="role-badge ${getRoleBadgeClass(user.role || '')}">
              ${getRoleLabel(user.role || '')}
            </span>
          </td>
          <td class="py-3 px-4 text-slate-600">${user.email || ''}</td>
          <td class="py-3 px-4 text-slate-600">${user.department || ''}</td>
          <td class="py-3 px-4 text-slate-600">-</td>
        `;
        tableBody.appendChild(row);
      });
    }

    function renderManageEmployees() {
      const list = document.getElementById('manage-employees-list');
      list.innerHTML = '';

      if (allUsers.length === 0) {
        list.innerHTML = '<p class="text-center py-8 text-slate-500">No employees to manage</p>';
        return;
      }

      allUsers.forEach(user => {
        const card = document.createElement('div');
        card.className = 'p-4 border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors';
        const editBtn = document.createElement('button');
        editBtn.type = 'button';
        editBtn.className = 'ml-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium';
        editBtn.textContent = 'Edit';
        editBtn.addEventListener('click', async () => {
          const name = prompt('Name', user.name || '');
          if (name === null) return;
          const email = prompt('Email', user.email || '');
          if (email === null) return;
          const role = prompt('Role (admin, operator, employee)', user.role || 'operator');
          if (role === null) return;

          const normalizedRole = role.trim().toLowerCase();
          if (!['admin', 'operator', 'employee'].includes(normalizedRole)) {
            alert('Role must be admin, operator, or employee.');
            return;
          }

          editBtn.disabled = true;
          editBtn.textContent = 'Saving...';
          try {
            const resp = await fetch(`/api/admin/users/${user.__backendId}`, {
              method: 'PUT',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getToken(),
                'Accept': 'application/json'
              },
              body: JSON.stringify({
                name: name.trim(),
                email: email.trim(),
                role: normalizedRole,
                phoneNumber: user.phoneNumber || '',
                address: user.address || ''
              })
            });
            const result = await resp.json();
            if (result.isOk) {
              const idx = allUsers.findIndex((u) => u.__backendId === user.__backendId);
              if (idx >= 0) {
                allUsers[idx] = result.data;
              }
              renderManageEmployees();
              renderUsersTable();
            } else {
              alert(result.error || 'Unable to update user');
            }
          } catch (error) {
            alert('Update failed. Please try again.');
          } finally {
            editBtn.disabled = false;
            editBtn.textContent = 'Edit';
          }
        });

        card.innerHTML = `
          <div class="flex items-center justify-between">
            <div class="flex-1">
              <h4 class="font-semibold text-slate-800">${user.name || 'Unknown'}</h4>
              <p class="text-sm text-slate-600">
                <span class="role-badge ${getRoleBadgeClass(user.role || '')} inline-block mr-2">${getRoleLabel(user.role || '')}</span>
                <span>${user.email || ''}</span>
              </p>
              <p class="text-xs text-slate-500 mt-2">
                <strong>Department:</strong> ${user.department || 'N/A'}
              </p>
            </div>
          </div>
        `;
        card.querySelector('.flex.items-center.justify-between').appendChild(editBtn);
        list.appendChild(card);
      });
    }

    function renderRemoveEmployees() {
      const list = document.getElementById('remove-employees-list');
      list.innerHTML = '';

      if (allUsers.length === 0) {
        list.innerHTML = '<p class="text-center py-8 text-slate-500">No employees to remove</p>';
        return;
      }

      allUsers.forEach(user => {
        const card = document.createElement('div');
        card.className = 'p-4 border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors flex items-center justify-between';
        const deleteBtn = document.createElement('button');
        deleteBtn.type = 'button';
        deleteBtn.className = 'ml-auto px-4 py-2 bg-rose-500 text-white rounded-lg hover:bg-rose-600 transition-colors text-sm font-medium';
        deleteBtn.textContent = 'Delete';
        deleteBtn.addEventListener('click', async () => {
          deleteBtn.disabled = true;
          deleteBtn.textContent = 'Deleting...';
          const resp = await fetch(`/api/admin/users/${user.__backendId}`, {
            method: 'DELETE', headers: { 'X-CSRF-TOKEN': getToken() }
          });
          const result = await resp.json();
          deleteBtn.disabled = false;
          deleteBtn.textContent = 'Delete';
          if (result.isOk) {
            allUsers = allUsers.filter(u => u.__backendId !== user.__backendId);
            renderRemoveEmployees();
            renderUsersTable();
          }
        });

        card.innerHTML = `
          <div class="flex-1">
            <h4 class="font-semibold text-slate-800">${user.name || 'Unknown'}</h4>
            <p class="text-sm text-slate-600">
              <span class="role-badge ${getRoleBadgeClass(user.role || '')} inline-block mr-2">${getRoleLabel(user.role || '')}</span>
              <span>${user.email || ''}</span>
            </p>
            <p class="text-xs text-slate-500 mt-2">
              <strong>Department:</strong> ${user.department || 'N/A'}
            </p>
          </div>
        `;
        card.appendChild(deleteBtn);
        list.appendChild(card);
      });
    }

    const dataHandler = {
      async load() {
        try {
          const resp = await fetch('/api/admin/users');
          const result = await resp.json();
          if (result.isOk) {
            allUsers = result.data || [];
            renderUsersTable();
          }
        } catch (e) {
          console.error('Failed loading users', e);
        }
      }
    };

    dataHandler.load();

    // Add User Modal
    const addUserModal = document.getElementById('add-user-modal');
    const addUserBtn = document.getElementById('add-user-btn');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const cancelBtn = document.getElementById('cancel-btn');
    const addUserForm = document.getElementById('add-user-form');

    addUserBtn.addEventListener('click', () => {
      addUserModal.classList.remove('hidden');
    });

    closeModalBtn.addEventListener('click', () => {
      addUserModal.classList.add('hidden');
    });

    cancelBtn.addEventListener('click', () => {
      addUserModal.classList.add('hidden');
    });

    addUserModal.addEventListener('click', (e) => {
      if (e.target === addUserModal) {
        addUserModal.classList.add('hidden');
      }
    });

    addUserForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      const name = document.getElementById('user-name').value.trim();
      const role = document.getElementById('user-role').value;
      const email = document.getElementById('user-email').value.trim();
      const password = document.getElementById('user-password').value;

      if (!name || !role || !email || !password) {
        return;
      }

      const submitBtn = document.getElementById('submit-btn');
      submitBtn.disabled = true;
      submitBtn.textContent = 'Adding...';

      const resp = await fetch('/api/admin/users', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': getToken(),
          'Accept': 'application/json'
        },
        body: JSON.stringify({ name, role, email, password })
      });
      const result = await resp.json();

      submitBtn.disabled = false;
      submitBtn.textContent = 'Add User';

      if (result.isOk) {
        allUsers.push(result.data);
        renderUsersTable();
        addUserForm.reset();
        addUserModal.classList.add('hidden');
      }
    });

    // Manage Employees Modal
    const manageModal = document.getElementById('manage-modal');
    const manageEmployeesBtn = document.getElementById('manage-employees-btn');
    const closeManageModalBtn = document.getElementById('close-manage-modal-btn');

    manageEmployeesBtn.addEventListener('click', () => {
      renderManageEmployees();
      manageModal.classList.remove('hidden');
    });

    closeManageModalBtn.addEventListener('click', () => {
      manageModal.classList.add('hidden');
    });

    manageModal.addEventListener('click', (e) => {
      if (e.target === manageModal) {
        manageModal.classList.add('hidden');
      }
    });

    // Remove Employee Modal
    const removeModal = document.getElementById('remove-modal');
    const removeEmployeeBtn = document.getElementById('remove-employee-btn');
    const closeRemoveModalBtn = document.getElementById('close-remove-modal-btn');

    if (removeEmployeeBtn) {
      removeEmployeeBtn.addEventListener('click', () => {
        renderRemoveEmployees();
        removeModal.classList.remove('hidden');
      });
    }

    closeRemoveModalBtn.addEventListener('click', () => {
      removeModal.classList.add('hidden');
    });

    removeModal.addEventListener('click', (e) => {
      if (e.target === removeModal) {
        removeModal.classList.add('hidden');
      }
    });

    // Edit Profile Modal
    const editProfileModal = document.getElementById('edit-profile-modal');
    const editProfileBtn = document.getElementById('edit-profile-btn');
    const closeEditProfileBtn = document.getElementById('close-edit-profile-btn');
    const cancelEditBtn = document.getElementById('cancel-edit-btn');

    editProfileBtn.addEventListener('click', () => {
      editProfileModal.classList.remove('hidden');
    });

    closeEditProfileBtn.addEventListener('click', () => {
      editProfileModal.classList.add('hidden');
    });

    cancelEditBtn.addEventListener('click', () => {
      editProfileModal.classList.add('hidden');
    });

    editProfileModal.addEventListener('click', (e) => {
      if (e.target === editProfileModal) {
        editProfileModal.classList.add('hidden');
      }
    });
  </script>
     </div>
    </div>
   </div>
  </div>
</body>
</html>