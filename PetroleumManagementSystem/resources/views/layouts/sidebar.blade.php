<style>

/* ============================================
   SIDEBAR STYLES
   ============================================ */

   .sidebar {
  width: 256px;
  min-width: 256px;
  max-width: 256px;
  flex: 0 0 256px;
  background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
  color: white;
  display: flex;
  flex-direction: column;
  box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
  overflow-y: auto;
  flex-shrink: 0;
}
.sidebar-header {
  padding: 1.25rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.logo {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  text-decoration: none;
  color: inherit;
}

.logo-icon {
  width: 40px;
  height: 40px;
  background: linear-gradient(135deg, #f97316, #f59e0b);
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  color: white;
  font-size: 20px;
}

.logo-image {
  width: 44px;
  height: 44px;
  display: block;
  border-radius: 50%;
  object-fit: cover;
}

.logo-text h1 {
  font-size: 1rem;
  font-weight: 600;
  margin: 0;
}

.logo-text p {
  font-size: 0.75rem;
  color: #94a3b8;
  margin: 0;
}

.sidebar-nav {
  flex: 1;
  padding: 1rem 0;
  overflow-y: auto;
}

.nav-section {
  padding: 0.75rem 1rem;
  margin-top: 1.5rem;
}

.nav-section-title {
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: #94a3b8;
  margin-bottom: 0.5rem;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 1.25rem;
  color: #cbd5e1;
  text-decoration: none;
  transition: all 0.2s ease;
  font-size: 0.9rem;
  font-weight: 500;
  cursor: pointer;
  border: none;
  background: transparent;
  width: 100%;
  text-align: left;
}

.nav-item:hover {
  color: white;
  transform: translateX(4px);
  background: rgba(255, 255, 255, 0.05);
}

.nav-item.active {
  background: linear-gradient(90deg, rgba(249, 115, 22, 0.2) 0%, transparent 100%);
  color: white;
  border-left: 3px solid var(--primary-color);
  padding-left: calc(1.25rem - 3px);
}

.nav-icon {
  width: 20px;
  height: 20px;
  flex-shrink: 0;
  font-size: 18px;
}

.sidebar-footer {
  padding: 1rem;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.user-info {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.user-avatar {
  width: 36px;
  height: 36px;
  background: linear-gradient(135deg, #10b981, #14b8a6);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 0.75rem;
  color: white;
  flex-shrink: 0;
}

.user-details {
  min-width: 0;
  flex: 1;
}

.user-name {
  font-size: 0.9rem;
  font-weight: 500;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  margin: 0;
}

.user-role {
  font-size: 0.75rem;
  color: #94a3b8;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  margin: 0;
}
</style>

<aside class="sidebar">
    <div class="sidebar-header">
      <div class="logo">
        <img src="{{ asset('assets/images/pms-logo.png') }}" alt="PMS Logo" class="logo-image">
        <div class="logo-text">
          <h1>Petroleum</h1>
          <p>Management System</p>
        </div>
      </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-section-title">Main Menu</div>

            @if(auth()->user()?->role !== 'employee')
            <a href="{{ route('dashboard.index') }}" class="nav-item {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
              <span class="nav-icon"></span> Dashboard
            </a>
            @endif

            <a href="{{ route('stock.index') }}" class="nav-item {{ request()->routeIs('stock.*') ? 'active' : '' }}">
                <span class="nav-icon"></span> Stocks
            </a>

            @if(auth()->user()?->role !== 'employee')
            <a href="{{ route('inventory.index') }}" class="nav-item {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                <span class="nav-icon"></span> Inventory
            </a>
            @endif

            <a href="{{ route('nozzle.index') }}" class="nav-item {{ request()->routeIs('nozzle.*') ? 'active' : '' }}">
                <span class="nav-icon"></span> Nozzle Entry
            </a>

            <a href="{{ route('expenses.index') }}" class="nav-item {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                <span class="nav-icon"></span> Expenses
            </a>

            <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <span class="nav-icon"></span> Reports
            </a>

            <a href="{{ route('taxinvoice.index') }}" class="nav-item {{ request()->routeIs('taxinvoice.*') ? 'active' : '' }}">
                <span class="nav-icon"></span> Tax Invoice
            </a>

            <a href="{{ route('cash.index') }}" class="nav-item {{ request()->routeIs('cash.*') ? 'active' : '' }}">
                <span class="nav-icon"></span> Cash Denomination
            </a>
        </div>

        @if(auth()->user()?->role === 'employee')
        <div class="nav-section">
          <div class="nav-section-title">Account</div>

          <a href="{{ route('employee.index') }}" class="nav-item {{ request()->routeIs('employee.*') ? 'active' : '' }}">
            <span class="nav-icon">👤</span> Employee Portal
          </a>
        </div>
        @endif

        @if(auth()->user()?->role !== 'employee')
        <div class="nav-section">
            <div class="nav-section-title">Administration</div>
            
            <a href="{{ route('admin.index') }}" class="nav-item {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                <span class="nav-icon">👤</span> Admin Portal
            </a>
        </div>
        @endif

        <div class="nav-section">
            <div class="nav-section-title">Support</div>
            <a href="#" class="nav-item">
                <span class="nav-icon">❓</span> Help & Support
            </a>
        </div>
    </nav>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-item" style="background:#7f1d1d;color:white; width: 100%;">
                <span class="nav-icon">🚪</span> Logout
            </button>
        </form>
    </div>
</aside>

<script>
  (function () {
    if (window.location.pathname.includes('/cash')) {
      return;
    }

    const STORAGE_PREFIX = 'pms_global_draft_v1:';
    const fieldSelector = 'input, select, textarea';

    function shouldSkipField(field) {
      if (!field) return true;

      const type = (field.type || '').toLowerCase();
      const name = (field.name || '').toLowerCase();

      return (
        field.disabled ||
        field.readOnly ||
        type === 'hidden' ||
        type === 'password' ||
        type === 'file' ||
        type === 'submit' ||
        type === 'button' ||
        type === 'reset' ||
        name === '_token'
      );
    }

    function getFieldKey(field, index) {
      if (field.id) return 'id:' + field.id;
      if (field.name) return 'name:' + field.name + ':' + index;
      return 'idx:' + index;
    }

    function getPageKey() {
      return STORAGE_PREFIX + window.location.pathname;
    }

    function collectDraft() {
      const fields = Array.from(document.querySelectorAll(fieldSelector));
      const data = {};

      fields.forEach((field, index) => {
        if (shouldSkipField(field)) return;

        const key = getFieldKey(field, index);
        if (field.type === 'checkbox' || field.type === 'radio') {
          data[key] = field.checked;
        } else {
          data[key] = field.value;
        }
      });

      localStorage.setItem(getPageKey(), JSON.stringify(data));
    }

    function restoreDraft() {
      const raw = localStorage.getItem(getPageKey());
      if (!raw) return;

      let saved;
      try {
        saved = JSON.parse(raw);
      } catch (e) {
        localStorage.removeItem(getPageKey());
        return;
      }

      const fields = Array.from(document.querySelectorAll(fieldSelector));
      fields.forEach((field, index) => {
        if (shouldSkipField(field)) return;

        const key = getFieldKey(field, index);
        if (!(key in saved)) return;

        if (field.type === 'checkbox' || field.type === 'radio') {
          field.checked = !!saved[key];
        } else {
          field.value = saved[key] ?? '';
        }

        // Re-trigger page-specific listeners (totals, conditional UI, calculations).
        field.dispatchEvent(new Event('input', { bubbles: true }));
        field.dispatchEvent(new Event('change', { bubbles: true }));
      });
    }

    let saveTimer = null;
    function scheduleSave() {
      clearTimeout(saveTimer);
      saveTimer = setTimeout(collectDraft, 250);
    }

    document.addEventListener('input', scheduleSave);
    document.addEventListener('change', scheduleSave);
    window.addEventListener('beforeunload', collectDraft);

    // Run restore multiple times because some pages initialize inputs after script load.
    restoreDraft();
    document.addEventListener('DOMContentLoaded', restoreDraft);
    window.addEventListener('load', () => {
      restoreDraft();
      setTimeout(restoreDraft, 300);
    });
  })();
</script>
