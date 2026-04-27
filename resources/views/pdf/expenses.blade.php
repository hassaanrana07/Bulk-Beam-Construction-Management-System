<!DOCTYPE html>
<html>

<head>
    <title>Expense Report</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            background-color: #fff;
            color: #333;
        }

        .header {
            background-color: #000;
            color: #ff6b00;
            padding: 20px;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }

        .footer {
            margin-top: 50px;
            text-align: right;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        .total {
            font-weight: bold;
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <div class="header">
        BRICK & BEAM // ENTERPRISE EXPENSE LEDGER
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Project</th>
                <th>Vendor</th>
                <th>Category</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses as $expense)
                <tr>
                    <td>{{ $expense->id }}</td>
                    <td>{{ $expense->portfolio?->title ?? 'N/A' }}</td>
                    <td>{{ $expense->vendor?->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($expense->category) }}</td>
                    <td>{{ ucfirst($expense->status) }}</td>
                    <td>{{ number_format($expense->amount, 2) }}</td>
                    <td>{{ $expense->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total">
                <td colspan="5" style="text-align: right;">GRAND TOTAL</td>
                <td colspan="2">{{ number_format($expenses->sum('amount'), 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Generated on: {{ now()->format('Y-m-d H:i:s') }} | Confidential Industrial Data Matrix
    </div>
</body>

</html>