<!doctype html>
<html lang="en" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Petroleum Management System</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  @if(auth()->check())
    <script>
      window.location.href = "{{ route('dashboard.index') }}";
    </script>
  @endif

  <style>
    * {
      box-sizing: border-box;
    }

    html,
    body {
      height: 100%;
      margin: 0;
      padding: 0;
      font-family: 'Outfit', sans-serif;
    }

    body {
      background: #0a1428;
      color: #ffffff;
    }

    #app {
      min-height: 100%;
      width: 100%;
      overflow-x: hidden;
    }

    .notification {
      position: fixed;
      top: 20px;
      right: 20px;
      padding: 14px 18px;
      border-radius: 10px;
      font-weight: 600;
      font-size: 14px;
      box-shadow: 0 12px 28px rgba(0, 0, 0, 0.22);
      z-index: 99999;
      animation: slideIn 0.28s ease-out;
      min-width: 280px;
      max-width: 420px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .notification.success {
      background: #dcfce7;
      color: #166534;
      border-left: 4px solid #10b981;
    }

    .notification.error {
      background: #fee2e2;
      color: #991b1b;
      border-left: 4px solid #dc2626;
    }

    .notification.hiding {
      animation: slideOut 0.25s ease-in forwards;
    }

    @keyframes slideIn {
      from {
        transform: translateX(120%);
        opacity: 0;
      }

      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    @keyframes slideOut {
      from {
        transform: translateX(0);
        opacity: 1;
      }

      to {
        transform: translateX(120%);
        opacity: 0;
      }
    }

    nav {
      position: sticky;
      top: 0;
      z-index: 70;
      background: rgba(255, 255, 255, 0.96);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(220, 20, 60, 0.16);
      padding: 14px 0;
    }

    .nav-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 12px;
      min-width: 0;
    }

    .site-logo {
      width: 46px;
      height: 46px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid rgba(220, 20, 60, 0.25);
      box-shadow: 0 8px 16px rgba(220, 20, 60, 0.2);
      display: block;
    }

    .logo-text {
      font-size: 1rem;
      font-weight: 800;
      line-height: 1.1;
      letter-spacing: 0.2px;
      white-space: nowrap;
      background: linear-gradient(to right, #dc143c, #8b0000);
      -webkit-background-clip: text;
      background-clip: text;
      -webkit-text-fill-color: transparent;
      color: transparent;
    }

    .nav-links {
      display: none;
      align-items: center;
      gap: 26px;
    }

    .nav-links a {
      color: #1a3a5c;
      font-weight: 600;
      text-decoration: none;
      transition: color 0.2s ease;
    }

    .nav-links a:hover {
      color: #dc143c;
    }

    .nav-buttons {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    @media (min-width: 860px) {
      .nav-links {
        display: flex;
      }
    }

    .btn-login,
    .btn-primary,
    .btn-secondary {
      cursor: pointer;
      border-radius: 10px;
      font-weight: 700;
      transition: all 0.2s ease;
      border: none;
    }

    .btn-login {
      background: linear-gradient(135deg, #dc143c 0%, #8b0000 100%);
      color: #ffffff;
      padding: 11px 20px;
      box-shadow: 0 10px 20px rgba(220, 20, 60, 0.22);
    }

    .btn-login:hover {
      transform: translateY(-1px);
      box-shadow: 0 14px 24px rgba(220, 20, 60, 0.26);
    }

    .hero-section {
      position: relative;
      min-height: 92vh;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 44px 20px 56px;
      overflow: hidden;
      isolation: isolate;
    }

    .hero-video {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      z-index: -2;
    }

    .hero-overlay {
      position: absolute;
      inset: 0;
      background:
        radial-gradient(circle at 12% 12%, rgba(220, 20, 60, 0.25) 0%, rgba(220, 20, 60, 0) 36%),
        radial-gradient(circle at 88% 78%, rgba(23, 55, 95, 0.45) 0%, rgba(23, 55, 95, 0) 40%),
        linear-gradient(180deg, rgba(10, 20, 40, 0.48), rgba(10, 20, 40, 0.72));
      z-index: -1;
    }

    .hero-content {
      max-width: 920px;
    }

    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 16px;
      background: rgba(139, 0, 0, 0.22);
      border: 1px solid rgba(220, 20, 60, 0.55);
      border-radius: 999px;
      margin-bottom: 22px;
      font-size: 13px;
      font-weight: 600;
      backdrop-filter: blur(5px);
    }

    .hero-badge-dot {
      width: 8px;
      height: 8px;
      border-radius: 999px;
      background: #ff4a62;
      animation: pulse 1.8s infinite;
    }

    @keyframes pulse {
      0%,
      100% {
        opacity: 1;
      }

      50% {
        opacity: 0.45;
      }
    }

    .hero-title {
      margin: 0 0 16px;
      font-size: clamp(2rem, 5vw, 3.6rem);
      font-weight: 800;
      line-height: 1.08;
      letter-spacing: -0.02em;
    }

    .hero-title-red {
      background: linear-gradient(90deg, #ff4867 0%, #dc143c 42%, #ff775e 100%);
      -webkit-background-clip: text;
      background-clip: text;
      -webkit-text-fill-color: transparent;
      color: transparent;
    }

    .hero-subtitle {
      font-size: 1.06rem;
      line-height: 1.7;
      color: rgba(255, 255, 255, 0.9);
      margin: 0 auto 30px;
      max-width: 800px;
    }

    .hero-buttons {
      display: flex;
      flex-direction: column;
      gap: 12px;
      width: min(420px, 100%);
      margin: 0 auto 34px;
    }

    .btn-primary {
      padding: 14px 26px;
      color: #ffffff;
      background: linear-gradient(135deg, #dc143c 0%, #8b0000 100%);
      box-shadow: 0 16px 30px rgba(220, 20, 60, 0.32);
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 22px 34px rgba(220, 20, 60, 0.38);
    }

    .btn-secondary {
      padding: 14px 26px;
      color: #dc143c;
      background: #ffffff;
      border: 1px solid rgba(220, 20, 60, 0.2);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
    }

    .btn-secondary:hover {
      transform: translateY(-2px);
      background: #fef2f2;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 14px;
      max-width: 720px;
      margin: 0 auto;
    }

    .stat-box {
      background: rgba(255, 255, 255, 0.94);
      border-radius: 14px;
      padding: 18px;
      text-align: center;
      border: 1px solid rgba(220, 20, 60, 0.15);
    }

    .stat-number {
      font-size: 1.8rem;
      font-weight: 800;
      color: #dc143c;
      line-height: 1;
    }

    .stat-label {
      color: #1a3a5c;
      font-size: 0.86rem;
      margin-top: 8px;
      font-weight: 600;
    }

    .features-section,
    .contact-section {
      background: rgba(255, 255, 255, 0.98);
      padding: 64px 20px;
    }

    .section-title {
      text-align: center;
      font-size: clamp(1.7rem, 4vw, 2.4rem);
      color: #0a1428;
      font-weight: 800;
      margin: 0 0 12px;
      letter-spacing: -0.02em;
    }

    .section-subtitle {
      text-align: center;
      margin: 0 auto 40px;
      color: #6b7c99;
      font-size: 1.02rem;
      max-width: 720px;
      line-height: 1.65;
    }

    .features-grid,
    .pricing-grid,
    .contact-grid {
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      gap: 22px;
    }

    .features-grid {
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    }

    .feature-card {
      background: #ffffff;
      border: 1px solid rgba(220, 20, 60, 0.12);
      border-radius: 16px;
      padding: 24px;
      transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .feature-card:hover {
      transform: translateY(-4px);
      border-color: rgba(220, 20, 60, 0.36);
      box-shadow: 0 16px 28px rgba(220, 20, 60, 0.12);
    }

    .feature-icon {
      width: 46px;
      height: 46px;
      border-radius: 12px;
      background: rgba(220, 20, 60, 0.1);
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 14px;
    }

    .feature-icon svg {
      width: 24px;
      height: 24px;
      color: #dc143c;
    }

    .feature-title {
      margin: 0 0 8px;
      font-size: 1.1rem;
      font-weight: 700;
      color: #0a1428;
    }

    .feature-description {
      margin: 0;
      color: #6b7c99;
      line-height: 1.62;
      font-size: 0.95rem;
    }

    .pricing-section {
      padding: 64px 20px;
      background:
        linear-gradient(145deg, rgba(10, 20, 40, 0.85), rgba(130, 0, 16, 0.62)),
        radial-gradient(circle at right top, rgba(255, 140, 105, 0.22), transparent 35%);
    }

    .pricing-grid {
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      align-items: stretch;
    }

    .price-card {
      background: rgba(255, 255, 255, 0.95);
      border: 1px solid rgba(220, 20, 60, 0.14);
      border-radius: 18px;
      padding: 24px;
      text-align: center;
      color: #0a1428;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      display: flex;
      flex-direction: column;
    }

    .price-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 18px 30px rgba(0, 0, 0, 0.2);
    }

    .price-card.featured {
      border: 2px solid #dc143c;
      box-shadow: 0 18px 40px rgba(220, 20, 60, 0.24);
      transform: scale(1.02);
    }

    .price-badge {
      display: inline-block;
      padding: 6px 12px;
      border-radius: 8px;
      font-size: 0.74rem;
      font-weight: 700;
      letter-spacing: 0.2px;
      margin-bottom: 10px;
      background: rgba(220, 20, 60, 0.1);
      color: #dc143c;
    }

    .price-card.featured .price-badge {
      color: #ffffff;
      background: linear-gradient(135deg, #dc143c 0%, #8b0000 100%);
    }

    .price-amount {
      font-size: 2.1rem;
      line-height: 1.2;
      font-weight: 800;
      margin: 4px 0;
    }

    .price-period {
      font-size: 0.9rem;
      color: #6b7c99;
      margin-bottom: 16px;
    }

    .price-features {
      margin: 18px 0 0;
      text-align: left;
      display: grid;
      gap: 8px;
      flex: 1;
    }

    .price-feature {
      display: flex;
      align-items: center;
      gap: 10px;
      color: #1a3a5c;
      font-size: 0.92rem;
      border-bottom: 1px solid rgba(220, 20, 60, 0.1);
      padding-bottom: 8px;
    }

    .price-feature:last-child {
      border-bottom: none;
      padding-bottom: 0;
    }

    .checkmark {
      width: 19px;
      height: 19px;
      border-radius: 999px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: #dc143c;
      color: #ffffff;
      font-size: 12px;
      font-weight: 800;
      flex-shrink: 0;
    }

    .contact-grid {
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    }

    .contact-card {
      text-align: center;
      padding: 12px;
    }

    .contact-icon {
      width: 56px;
      height: 56px;
      margin: 0 auto 12px;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(220, 20, 60, 0.1);
    }

    .contact-icon svg {
      width: 26px;
      height: 26px;
      color: #dc143c;
    }

    .contact-card h3 {
      margin: 0 0 6px;
      color: #0a1428;
      font-size: 1.02rem;
      font-weight: 700;
    }

    .contact-card p {
      margin: 0;
      color: #6b7c99;
      font-weight: 500;
    }

    footer {
      background: #0a1428;
      color: rgba(255, 255, 255, 0.72);
      text-align: center;
      border-top: 1px solid rgba(220, 20, 60, 0.2);
      padding: 24px 20px 30px;
      font-size: 0.9rem;
    }

    .modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.88);
      backdrop-filter: blur(8px);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 999;
      padding: 20px;
    }

    .modal-hidden {
      display: none !important;
    }

    .modal-content {
      width: min(430px, 100%);
      border-radius: 18px;
      border: 1px solid rgba(220, 20, 60, 0.28);
      background: #14223c;
      padding: 26px 22px;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
    }

    .modal-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 18px;
    }

    .modal-title {
      margin: 0;
      font-size: 1.4rem;
      font-weight: 700;
      color: #ffffff;
    }

    .modal-close {
      border: none;
      background: transparent;
      color: #9ca3af;
      font-size: 24px;
      line-height: 1;
      cursor: pointer;
      transition: color 0.2s ease;
    }

    .modal-close:hover {
      color: #ffffff;
    }

    .form-group {
      margin-bottom: 14px;
    }

    .form-label {
      display: block;
      margin-bottom: 6px;
      color: #c8d2e2;
      font-size: 0.94rem;
      font-weight: 600;
    }

    .form-input {
      width: 100%;
      padding: 11px 13px;
      border-radius: 10px;
      border: 1px solid rgba(220, 20, 60, 0.35);
      color: #ffffff;
      background: #0f172a;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .form-input:focus {
      outline: none;
      border-color: #ff4a62;
      box-shadow: 0 0 0 4px rgba(220, 20, 60, 0.16);
    }

    @media (max-width: 768px) {
      .logo-text {
        font-size: 0.88rem;
      }

      .hero-buttons {
        width: 100%;
      }

      .btn-primary,
      .btn-secondary {
        width: 100%;
      }
    }

    @media (min-width: 680px) {
      .hero-buttons {
        flex-direction: row;
        justify-content: center;
      }

      .stats-grid {
        grid-template-columns: repeat(4, minmax(0, 1fr));
      }
    }
  </style>

  <style>
    @view-transition {
      navigation: auto;
    }
  </style>
</head>
<body>
  <div id="notification-container"></div>

  <div id="app">
    <nav>
      <div class="nav-container">
        <div class="logo">
          <img src="{{ asset('assets/images/pms-logo.png') }}" alt="Petroleum Management System Logo" class="site-logo">
          <span id="brand-name" class="logo-text">Petroleum Management System</span>
        </div>

        <div class="nav-links">
          <a href="#home">Home</a>
          <a href="#features">Features</a>
          <a href="#pricing">Pricing</a>
          <a href="#contact">Contact</a>
        </div>

        <div class="nav-buttons">
          <button class="btn-login" onclick="openModal('login')">Log In</button>
        </div>
      </div>
    </nav>

    <section id="home" class="hero-section">
      <video autoplay muted loop playsinline class="hero-video">
        <source src="https://videos.pexels.com/video-files/4256164/4256164-sd_640_360_25fps.mp4" type="video/mp4">
      </video>
      <div class="hero-overlay"></div>

      <div class="hero-content">
        <div class="hero-badge">
          <span class="hero-badge-dot"></span>
          Live System Performance
        </div>

        <h1 class="hero-title">Advanced <span class="hero-title-red">Petroleum Management</span> System</h1>

        <p id="hero-subtitle" class="hero-subtitle">
          Advanced petroleum station management. Track inventory, manage sales, generate invoices, monitor expenses,
          and optimize operations with AI-powered analytics. Everything you need to run your fuel station efficiently and profitably.
        </p>

        <div class="hero-buttons">
          <button class="btn-primary" onclick="openModal('login')">Start Free Trial</button>
          <button class="btn-secondary" onclick="document.getElementById('features').scrollIntoView({ behavior: 'smooth' })">Learn More</button>
        </div>

        <div class="stats-grid">
          <div class="stat-box">
            <div class="stat-number">99.9%</div>
            <div class="stat-label">System Uptime</div>
          </div>
          <div class="stat-box">
            <div class="stat-number">24/7</div>
            <div class="stat-label">Monitoring</div>
          </div>
          <div class="stat-box">
            <div class="stat-number">1000+</div>
            <div class="stat-label">Invoices / Day</div>
          </div>
          <div class="stat-box">
            <div class="stat-number">50+</div>
            <div class="stat-label">Stations Managed</div>
          </div>
        </div>
      </div>
    </section>

    <section id="features" class="features-section">
      <h2 class="section-title">Complete Petroleum Management Suite</h2>
      <p class="section-subtitle">Everything you need to manage your petroleum business efficiently and profitably.</p>

      <div class="features-grid">
        <div class="feature-card">
          <div class="feature-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
          </div>
          <h3 class="feature-title">Real-Time Dashboard</h3>
          <p class="feature-description">Live analytics and comprehensive reports. Monitor sales, inventory, and revenue with instant insights.</p>
        </div>

        <div class="feature-card">
          <div class="feature-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
          </div>
          <h3 class="feature-title">Inventory Management</h3>
          <p class="feature-description">Track fuel stock levels with automated alerts, accurate quantities, and predictive reordering.</p>
        </div>

        <div class="feature-card">
          <div class="feature-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
          </div>
          <h3 class="feature-title">Nozzle Management</h3>
          <p class="feature-description">Record dispensing activity, track nozzle readings, and detect fuel loss in real time.</p>
        </div>

        <div class="feature-card">
          <div class="feature-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
          <h3 class="feature-title">Tax Invoice System</h3>
          <p class="feature-description">Tax-compliant invoicing with streamlined bill generation and clean audit history.</p>
        </div>

        <div class="feature-card">
          <div class="feature-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
          </div>
          <h3 class="feature-title">Advanced Control</h3>
          <p class="feature-description">Role-based access for staff, transaction control, and secure system administration.</p>
        </div>

        <div class="feature-card">
          <div class="feature-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </div>
          <h3 class="feature-title">Financial Reports</h3>
          <p class="feature-description">Detailed expense and revenue insights for healthy cash flow and margin analysis.</p>
        </div>
      </div>
    </section>

    <section id="pricing" class="pricing-section">
      <h2 class="section-title" style="color: #ffffff;">Flexible Pricing Plans</h2>
      <p class="section-subtitle" style="color: rgba(255, 255, 255, 0.85);">Choose the right plan for your station.</p>

      <div class="pricing-grid">
        <div class="price-card">
          <div class="price-badge">Starter</div>
          <div class="price-amount" id="basic-price">NPR 2000</div>
          <div class="price-period">/month (per station)</div>
          <button type="button" class="btn-primary" style="width: 100%; margin-bottom: 18px;" onclick="openLeadModal('Starter Plan')">Get Started</button>

          <div class="price-features">
            <div class="price-feature"><span class="checkmark">✓</span><span>Basic Dashboard</span></div>
            <div class="price-feature"><span class="checkmark">✓</span><span>Inventory Tracking</span></div>
            <div class="price-feature"><span class="checkmark">✓</span><span>Nozzle Management</span></div>
            <div class="price-feature"><span class="checkmark">✓</span><span>Basic Reports</span></div>
          </div>
        </div>

        <div class="price-card featured">
          <div class="price-badge">Most Popular</div>
          <div class="price-amount" id="professional-price">NPR 3500</div>
          <div class="price-period">/month (per station)</div>
          <button type="button" class="btn-primary" style="width: 100%; margin-bottom: 18px;" onclick="openLeadModal('Professional Plan')">Get Started</button>

          <div class="price-features">
            <div class="price-feature"><span class="checkmark">✓</span><span>Advanced Dashboard</span></div>
            <div class="price-feature"><span class="checkmark">✓</span><span>Complete Inventory</span></div>
            <div class="price-feature"><span class="checkmark">✓</span><span>Tax Invoices</span></div>
            <div class="price-feature"><span class="checkmark">✓</span><span>Advanced Analytics</span></div>
            <div class="price-feature"><span class="checkmark">✓</span><span>Priority Support</span></div>
          </div>
        </div>
      </div>
    </section>

    <section id="contact" class="contact-section">
      <h2 class="section-title">Ready to Transform Your Operations?</h2>
      <p class="section-subtitle">Contact us today and start managing your petroleum business smarter.</p>

      <div class="contact-grid">
        <div class="contact-card">
          <div class="contact-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
          </div>
          <h3>Phone Support</h3>
          <p>+977 9856075858</p>
        </div>

        <div class="contact-card">
          <div class="contact-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
          </div>
          <h3>Email Us</h3>
          <p>support@petroflow.com</p>
        </div>

        <div class="contact-card">
          <div class="contact-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
            </svg>
          </div>
          <h3>Office Location</h3>
          <p>Pokhara, Nepal</p>
        </div>
      </div>
    </section>

    <footer>
      <p>© {{ date('Y') }} Complete Petroleum Management System. All rights reserved.</p>
    </footer>
  </div>

  <div id="login-modal" class="modal-overlay modal-hidden">
    <div class="modal-content">
      <div id="login-view">
        <div class="modal-header">
          <h2 class="modal-title">Log In</h2>
          <button class="modal-close" onclick="closeModal('login')">✕</button>
        </div>

        <form method="POST" action="{{ route('login.post') }}">
          @csrf

          <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" required>
          </div>

          <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-input" required>
          </div>

          <button type="submit" class="btn-primary w-full">Login</button>

          @if ($errors->any())
            <p class="text-red-400 mt-3">{{ $errors->first() }}</p>
          @endif
        </form>

        <div class="text-center mt-4">
          <button type="button" onclick="switchToForgotPassword()" class="text-red-500 underline">Forgot Password?</button>
        </div>
      </div>

      <div id="forgot-password-view" style="display: none;">
        <div class="modal-header">
          <h2 class="modal-title">Reset Password</h2>
          <button class="modal-close" onclick="switchToLogin()">✕</button>
        </div>

        <form method="POST" action="{{ route('resetpassword.post') }}">
          @csrf

          <div class="form-group">
            <input type="email" name="email" class="form-input" placeholder="Email" required>
          </div>

          <div class="form-group">
            <input type="password" name="password" class="form-input" placeholder="New password" required>
          </div>

          <div class="form-group">
            <input type="password" name="password_confirmation" class="form-input" placeholder="Confirm password" required>
          </div>

          <button type="submit" class="btn-primary w-full">Reset Password</button>
        </form>
      </div>
    </div>
  </div>

  <div id="lead-modal" class="modal-overlay modal-hidden">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title">Request a Demo</h2>
        <button class="modal-close" onclick="closeModal('lead')">✕</button>
      </div>

      <form id="lead-form">
        @csrf

        <input type="hidden" name="plan_name" id="lead-plan-name">

        <div class="form-group">
          <label class="form-label">Email ID</label>
          <input type="email" name="email" class="form-input" required>
        </div>

        <div class="form-group">
          <label class="form-label">Company Name</label>
          <input type="text" name="company_name" class="form-input" maxlength="255" pattern="[A-Za-z0-9][A-Za-z0-9 .,&'-]*" title="Use letters, numbers, spaces and . , & ' -" required>
        </div>

        <div class="form-group">
          <label class="form-label">Name</label>
          <input type="text" name="name" class="form-input" maxlength="255" pattern="[A-Za-z][A-Za-z .'-]*" title="Use letters and spaces only" required>
        </div>

        <div class="form-group">
          <label class="form-label">Phone Number</label>
          <input type="tel" name="phone_number" class="form-input" inputmode="numeric" maxlength="10" pattern="[0-9]{10}" title="Phone number must be exactly 10 digits" oninput="enforceTenDigitPhoneInput(this)" required>
        </div>

        <div class="form-group">
          <label class="form-label">Address</label>
          <textarea name="address" class="form-input" rows="3" required></textarea>
        </div>

        <button type="submit" class="btn-primary w-full">Save Details</button>
      </form>
    </div>
  </div>

  <script>
    const khaltiPublicKey = '{{ config("services.khalti.public_key") }}';
    const khaltiConfigured = !!khaltiPublicKey;

    function openLeadModal(planName) {
      const planField = document.getElementById('lead-plan-name');
      if (planField) {
        planField.value = planName;
      }

      openModal('lead');
    }

    function openModal(type) {
      const modal = document.getElementById(type + '-modal');
      if (modal) {
        modal.classList.remove('modal-hidden');
        document.body.style.overflow = 'hidden';
      }
    }

    function closeModal(type) {
      const modal = document.getElementById(type + '-modal');
      if (modal) {
        modal.classList.add('modal-hidden');
        document.body.style.overflow = 'auto';
      }
    }

    function switchToForgotPassword() {
      document.getElementById('login-view').style.display = 'none';
      document.getElementById('forgot-password-view').style.display = 'block';
    }

    function switchToLogin() {
      document.getElementById('forgot-password-view').style.display = 'none';
      document.getElementById('login-view').style.display = 'block';
    }

    function showNotification(message, type = 'success') {
      const container = document.getElementById('notification-container');
      if (!container) return;

      const icon = type === 'success' ? '✅' : '❌';
      const notification = document.createElement('div');
      notification.className = `notification ${type}`;
      notification.innerHTML = `<span style="font-size: 18px;">${icon}</span><span>${message}</span>`;

      container.appendChild(notification);

      setTimeout(() => {
        notification.classList.add('hiding');
        setTimeout(() => notification.remove(), 260);
      }, 3500);
    }

    function enforceTenDigitPhoneInput(input) {
      const digits = input.value.replace(/\D/g, '').slice(0, 10);
      if (input.value !== digits) {
        input.value = digits;
      }

      input.setCustomValidity(digits.length === 10 || digits.length === 0 ? '' : 'Phone number must be exactly 10 digits.');
    }

    document.getElementById('lead-form')?.addEventListener('submit', async function (event) {
      event.preventDefault();

      const form = event.currentTarget;
      const formData = new FormData(form);

      try {
        const response = await fetch('{{ route('lead-requests.store') }}', {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
          },
          body: formData,
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
          throw new Error(result.message || 'Unable to save your details.');
        }

        form.reset();
        closeModal('lead');
        showNotification(result.message || 'Details saved successfully.', 'success');
      } catch (error) {
        showNotification(error.message || 'Unable to save your details.', 'error');
      }
    });

    async function initiateKhaltiPayment(amount, planName) {
      if (!khaltiConfigured) {
        showNotification('Khalti is not configured. Please contact support.', 'error');
        return;
      }

      const purchaseOrderId = `PLAN-${Date.now()}`;

      try {
        const response = await fetch('/api/payments/khalti/initiate', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'X-Requested-With': 'XMLHttpRequest',
          },
          body: JSON.stringify({
            amount: amount,
            purchase_order_id: purchaseOrderId,
            purchase_order_name: planName,
            customer_name: 'Petroleum Station',
            customer_phone: '',
          }),
        });

        const result = await response.json();

        if (!result.isOk || !result.data?.payment_url) {
          showNotification('Unable to initiate payment. Please try again.', 'error');
          console.error('Khalti response:', result);
          return;
        }

        window.location.href = result.data.payment_url;
      } catch (error) {
        showNotification('Connection error. Please try again.', 'error');
        console.error('Khalti payment error:', error);
      }
    }

    @if (session('success'))
      showNotification("{{ session('success') }}", 'success');
      closeModal('login');
    @endif

    @if ($errors->any())
      showNotification("{{ $errors->first() }}", 'error');
      openModal('login');
    @endif

    (function () {
      const STORAGE_KEY = 'pms_global_draft_v1:/home';
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

      function saveDraft() {
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

        localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
      }

      function restoreDraft() {
        const raw = localStorage.getItem(STORAGE_KEY);
        if (!raw) return;

        let data;
        try {
          data = JSON.parse(raw);
        } catch (e) {
          localStorage.removeItem(STORAGE_KEY);
          return;
        }

        const fields = Array.from(document.querySelectorAll(fieldSelector));
        fields.forEach((field, index) => {
          if (shouldSkipField(field)) return;

          const key = getFieldKey(field, index);
          if (!(key in data)) return;

          if (field.type === 'checkbox' || field.type === 'radio') {
            field.checked = !!data[key];
          } else {
            field.value = data[key] ?? '';
          }
        });
      }

      let saveTimer = null;
      function scheduleSave() {
        clearTimeout(saveTimer);
        saveTimer = setTimeout(saveDraft, 250);
      }

      document.addEventListener('input', scheduleSave);
      document.addEventListener('change', scheduleSave);
      window.addEventListener('beforeunload', saveDraft);

      restoreDraft();
    })();

  </script>
</body>
</html>
