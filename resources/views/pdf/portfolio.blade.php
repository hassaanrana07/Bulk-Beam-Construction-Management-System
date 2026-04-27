<!DOCTYPE html>
<html>

<head>
    <title>Asset Audit - {{ $portfolio->title }}</title>
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

        .title {
            font-size: 32px;
            font-weight: 900;
            text-transform: uppercase;
            margin: 0;
        }

        .meta {
            color: #666;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 5px;
        }

        .grid {
            display: block;
            margin-top: 40px;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            color: #ff6b00;
            letter-spacing: 3px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        .data-row {
            margin-bottom: 10px;
        }

        .label {
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            color: #888;
            width: 150px;
            display: inline-block;
        }

        .value {
            font-size: 12px;
            font-weight: bold;
        }

        .financial-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .financial-table th {
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            color: #888;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .financial-table td {
            padding: 10px;
            font-size: 12px;
            border-bottom: 1px solid #f9f9f9;
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
        <div class="meta">Automated Project Audit Transcript // {{ date('Y-m-d H:i') }}</div>
    </div>

    <h1 class="title">{{ $portfolio->title }}</h1>

    <div class="grid">
        <div class="section">
            <div class="section-title">Operational Data</div>
            <div class="data-row"><span class="label">Project Slug:</span> <span
                    class="value">{{ $portfolio->slug }}</span></div>
            <div class="data-row"><span class="label">Client Identity:</span> <span
                    class="value">{{ $portfolio->client_name ?? 'N/A' }}</span></div>
            <div class="data-row"><span class="label">Status:</span> <span
                    class="value">{{ strtoupper($portfolio->execution_status) }}</span></div>
            <div class="data-row"><span class="label">Location:</span> <span
                    class="value">{{ $portfolio->location ?? 'N/A' }}</span></div>
            <div class="data-row"><span class="label">Project Type:</span> <span
                    class="value">{{ $portfolio->project_type }}</span></div>
            <div class="data-row"><span class="label">Project Manager:</span> <span
                    class="value">{{ $portfolio->manager?->name ?? 'UNASSIGNED' }}</span></div>
        </div>

        <div class="section">
            <div class="section-title">Timeline & Schedule</div>
            <div class="data-row"><span class="label">Start Date:</span> <span
                    class="value">{{ $portfolio->start_date?->format('F d, Y') ?? 'N/A' }}</span></div>
            <div class="data-row"><span class="label">Completion Date:</span> <span
                    class="value">{{ $portfolio->completion_date?->format('F d, Y') ?? 'Ongoing' }}</span></div>
            @if($portfolio->cs_duration_weeks)
            <div class="data-row"><span class="label">Project Duration:</span> <span
                    class="value">{{ $portfolio->cs_duration_weeks }} Weeks</span></div>
            @endif
        </div>

        <div class="section">
            <div class="section-title">Development Milestones</div>
            <table class="financial-table">
                <thead>
                    <tr>
                        <th>Milestone Name</th>
                        <th>Target Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($portfolio->milestones as $milestone)
                    <tr>
                        <td>{{ $milestone->title }}</td>
                        <td>{{ $milestone->due_date?->format('Y-m-d') ?? 'N/A' }}</td>
                        <td>{{ strtoupper($milestone->status) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: #ccc;">No milestones recorded.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Fiscal Performance Parameters</div>
            <table class="financial-table">
                <thead>
                    <tr>
                        <th>Capital Budget</th>
                        <th>Project Revenue</th>
                        <th>Received Sum</th>
                        <th>Pending Arrears</th>
                        <th>Net Profit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>${{ number_format($portfolio->total_budget, 2) }}</td>
                        <td>${{ number_format($portfolio->expected_revenue, 2) }}</td>
                        <td style="color: #22c55e; font-weight: bold;">
                            ${{ number_format($portfolio->received_payment, 2) }}</td>
                        <td style="color: #ef4444;">${{ number_format($portfolio->pending_payment, 2) }}</td>
                        <td style="background: #fdfdfd; font-weight: 900;">
                            ${{ number_format($portfolio->project_profit, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if($portfolio->expenses->count() > 0)
        <div class="section">
            <div class="section-title">Expense Log (Top Entries)</div>
            <table class="financial-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($portfolio->expenses->take(10) as $expense)
                    <tr>
                        <td>{{ $expense->date?->format('Y-m-d') }}</td>
                        <td>{{ $expense->description }}</td>
                        <td>{{ $expense->category }}</td>
                        <td style="color: #ef4444;">-${{ number_format($expense->amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if(isset($auditLogs) && $auditLogs->count() > 0)
        <div class="section">
            <div class="section-title">System Audit Log (Last 20 Activities)</div>
            <table class="financial-table">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>User</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($auditLogs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $log->user?->name ?? 'System' }}</td>
                        <td>{{ strtoupper($log->action) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <div class="section">
            <div class="section-title">Project Narrative</div>
            <p style="font-size: 11px; line-height: 1.6; color: #444;">{{ $portfolio->short_description }}</p>
            @if($portfolio->description)
            <div style="font-size: 10px; line-height: 1.5; color: #666; margin-top: 10px;">
                {!! nl2br(e($portfolio->description)) !!}
            </div>
            @endif
        </div>
    </div>

    <div class="footer">
        Brick & Beam Enterprise Resource Control // Secured PDF Document // Internal Use Only
    </div>
</body>

</html>