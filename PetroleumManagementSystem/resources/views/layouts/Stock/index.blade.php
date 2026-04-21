@extends('layouts.app')

@section('title', 'Stock Management - PMS')

@section('content')
<div class="stock-container">

    {{-- Sidebar --}}
    <aside class="stock-sidebar">
        <h2>⛽ PMS</h2>

        <div class="sidebar-menu">
            <a href="{{ route('dashboard.index') }}" class="sidebar-link">Dashboard</a>
            <a href="{{ route('stock.index') }}" class="sidebar-link active">Stock</a>
            <a href="{{ route('inventory.index') }}" class="sidebar-link">Inventory</a>
            <a href="{{ route('expenses.index') }}" class="sidebar-link">Expenses</a>
            <a href="{{ route('reports.index') }}" class="sidebar-link">Reports</a>
        </div>
    </aside>

    {{-- Main Section --}}
    <div class="stock-main">

        {{-- Header --}}
        <div class="stock-header">
            <h1>Stock Overview</h1>
        </div>

        {{-- Content --}}
        <div class="stock-content">

            {{-- Alert --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Stock Cards --}}
            <div class="stock-grid">

                {{-- Petrol --}}
                <div class="stock-card">
                    <div class="stock-card-header">
                        <div class="fuel-info">
                            <h3>Petrol</h3>
                            <div class="fuel-code">PMS-PETROL</div>
                        </div>
                        <div class="fuel-icon petrol">P</div>
                    </div>

                    <div class="stock-metrics">
                        <div class="metric">
                            <label>Available Stock</label>
                            <span class="metric-value highlight">
                                {{ number_format($petrolStock ?? 0) }} L
                            </span>
                        </div>

                        <div class="metric">
                            <label>Last Updated</label>
                            <span class="metric-value">Today</span>
                        </div>
                    </div>

                    <button class="btn-card">
                        Update Stock
                    </button>
                </div>

                {{-- Diesel --}}
                <div class="stock-card">
                    <div class="stock-card-header">
                        <div class="fuel-info">
                            <h3>Diesel</h3>
                            <div class="fuel-code">PMS-DIESEL</div>
                        </div>
                        <div class="fuel-icon diesel">D</div>
                    </div>

                    <div class="stock-metrics">
                        <div class="metric">
                            <label>Available Stock</label>
                            <span class="metric-value highlight">
                                {{ number_format($dieselStock ?? 0) }} L
                            </span>
                        </div>

                        <div class="metric">
                            <label>Last Updated</label>
                            <span class="metric-value">Today</span>
                        </div>
                    </div>

                    <button class="btn-card">
                        Update Stock
                    </button>
                </div>

                {{-- Lubricants --}}
                <div class="stock-card">
                    <div class="stock-card-header">
                        <div class="fuel-info">
                            <h3>Lubricants</h3>
                            <div class="fuel-code">PMS-LUBE</div>
                        </div>
                        <div class="fuel-icon lubricant">L</div>
                    </div>

                    <div class="stock-metrics">
                        <div class="metric">
                            <label>Available Stock</label>
                            <span class="metric-value highlight">
                                {{ number_format($lubricantStock ?? 0) }} L
                            </span>
                        </div>

                        <div class="metric">
                            <label>Last Updated</label>
                            <span class="metric-value">Today</span>
                        </div>
                    </div>

                    <button class="btn-card">
                        Update Stock
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
