<!DOCTYPE html>
<html>

<head>
    <title>Fiscal Integrity Report - Brick & Beam</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            color: #1a1a1a;
            margin: 40px;
        }

        .header {
            border-bottom: 4px solid #ff6b00;
            padding-bottom: 20px;
            margin-bottom: 40px;
        }

        .logo {
            font-size: 24px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: -1px;
        }

        .logo span {
            color: #ff6b00;
        }

        .meta {
            color: #666;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 5px;
        }

        .report-title {
            font-size: 28px;
            font-weight: 900;
            text-transform: uppercase;
            margin: 0;
            text-align: center;
            margin-bottom: 40px;
            background: #f9f9f9;
            padding: 20px;
        }

        .stats-grid {
            display: table;
            width: 100%;
            border-spacing: 10px;
            margin-bottom: 40px;
        }

        .stat-card {
            display: table-cell;
            background: #000;
            color: #fff;
            padding: 20px;
            text-align: center;
            border-radius: 4px;
        }

        .stat-label {
            font-size: 8px;
            font-weight: 900;
            text-transform: uppercase;
            tracking: 2px;
            color: #888;
            margin-bottom: 10px;
        }

        .stat-value {
            font-size: 18px;
            font-weight: 900;
        }

        .table-title {
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            color: #ff6b00;
            letter-spacing: 3px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            color: #888;
            padding: 12px;
            border-bottom: 2px solid #000;
        }

        .data-table td {
            padding: 12px;
            font-size: 10px;
            border-bottom: 1px solid #eee;
        }

        .data-table tr:nth-child(even) {
            background: #fcfcfc;
        }

        .summary-box {
            margin-top: 50px;
            border: 2px solid #000;
            padding: 20px;
        }

        .summary-title {
            font-weight: 900;
            text-transform: uppercase;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #ccc;
            text-transform: uppercase;
            letter-spacing: 4px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo">Brick<span>&</span>Beam</div>
        <div class="meta">Corporate Fiscal Record // Executive Summary // {{ date('Y-m-d H:i') }}</div>
    </div>

    <div class="report-title">Fiscal Integrity Audit Report</div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value">${{ number_format($stats['total_revenue'], 2) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Investment</div>
            <div class="stat-value">${{ number_format($stats['total_invested'], 2) }}</div>
        </div>
        <div class="stat-card" style="background: #ff6b00;">
            <div class="stat-label" style="color: #000;">Net Profit</div>
            <div class="stat-value">${{ number_format($stats['total_profit'], 2) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Pending Cap.</div>
            <div class="stat-value">${{ number_format($stats['total_pending'], 2) }}</div>
        </div>
    </div>

    <div class="table-title">Product Asset Ledger</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Asset / Product Name</th>
                <th>Capital Budget</th>
                <th>Actual Revenue</th>
                <th>Expected Rev.</th>
                <th>Net Profit</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td style="font-weight: bold;">{{ strtoupper($product->title) }}</td>
                    <td>${{ number_format($product->total_budget, 2) }}</td>
                    <td>${{ number_format($product->received_payment, 2) }}</td>
                    <td>${{ number_format($product->expected_revenue, 2) }}</td>
                    <td style="font-weight: bold; color: {{ $product->project_profit >= 0 ? '#22c55e' : '#ef4444' }};">
                        ${{ number_format($product->project_profit, 2) }}</td>
                    <td>{{ strtoupper($product->execution_status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-box">
        <div class="summary-title">Executive Summary Overview</div>
        <p style="font-size: 11px; line-height: 1.6;">
            The current fiscal period shows a net profitability margin of
            <strong>{{ number_format($stats['profit_margin'], 1) }}%</strong>.
            Total outstanding arrears stand at <strong>${{ number_format($stats['total_pending'], 2) }}</strong>.
            Performance across <strong>{{ count($products) }}</strong> active assets remains within executive
            thresholds.
        </p>
    </div>

    <div class="footer">Brick & Beam // Proprietary Financial Intelligence Document</div>
</body>

</html>