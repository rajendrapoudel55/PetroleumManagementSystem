<!doctype html>
<html lang="en" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Employee Portal</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
  <link href="/assets/css/stock.css" rel="stylesheet">
  <style>
    * {
      font-family: 'Outfit', sans-serif;
      box-sizing: border-box;
    }

    .portal-bg {
      background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 55%, #334155 100%);
      min-height: 100vh;
    }

    .glass-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(16px);
    }
  </style>
</head>
<body class="h-full">
  <div class="dashboard-wrapper">
    @include('layouts.sidebar')

    <div class="main-content">
      <div class="portal-bg p-4 md:p-8">
        <div class="max-w-5xl mx-auto space-y-6">
          <div class="flex items-center justify-between gap-4 flex-wrap">
            <div>
              <h1 class="text-white text-2xl md:text-3xl font-bold">Employee Portal</h1>
              <p class="text-slate-200">Manage your profile information</p>
            </div>
          </div>

          @if(session('success'))
          <div class="glass-card rounded-xl p-4 border border-emerald-200 text-emerald-800 font-medium">
            {{ session('success') }}
          </div>
          @endif

          <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="glass-card rounded-2xl p-6 shadow-xl lg:col-span-1">
              <div class="text-center">
                <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-indigo-100 to-indigo-200 flex items-center justify-center text-indigo-700 font-bold text-3xl mb-4">
                  {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <h2 class="text-xl font-bold text-slate-800">{{ auth()->user()->name }}</h2>
                <p class="text-indigo-700 font-medium">{{ ucfirst(auth()->user()->role ?? 'employee') }}</p>
                <div class="mt-4 text-sm text-slate-600">
                  <p class="font-semibold text-slate-700">Email</p>
                  <p>{{ auth()->user()->email }}</p>
                </div>
              </div>
            </div>

            <div class="glass-card rounded-2xl p-6 shadow-xl lg:col-span-2">
              <h3 class="text-xl font-bold text-slate-800 mb-5">Edit Employee Details</h3>

              <form method="POST" action="{{ route('employee.update.profile') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                  <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Employee Name</label>
                  <input id="name" name="name" type="text" required value="{{ old('name', auth()->user()->name) }}"
                         pattern="[A-Za-z][A-Za-z .'-]*" title="Use letters and spaces only"
                         class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                  @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <label for="address" class="block text-sm font-semibold text-slate-700 mb-2">Address</label>
                  <textarea id="address" name="address" rows="3"
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('address', auth()->user()->address) }}</textarea>
                  @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <label for="phoneNumber" class="block text-sm font-semibold text-slate-700 mb-2">Phone Number</label>
                      <input id="phoneNumber" name="phoneNumber" type="text" value="{{ old('phoneNumber', auth()->user()->phoneNumber) }}" oninput="enforceTenDigitPhoneInput(this)"
                        inputmode="numeric" maxlength="10" pattern="[0-9]{10}" title="Phone number must be exactly 10 digits"
                         class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                  @error('phoneNumber')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                  @enderror
                </div>

                <input type="hidden" id="role" name="role" value="employee">

                <div class="pt-3">
                  <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-semibold">
                    Update Details
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
<script>
  function enforceTenDigitPhoneInput(input) {
    const digits = input.value.replace(/\D/g, '').slice(0, 10);
    if (input.value !== digits) {
      input.value = digits;
    }

    input.setCustomValidity(digits.length === 10 || digits.length === 0 ? '' : 'Phone number must be exactly 10 digits.');
  }
</script>
</html>
