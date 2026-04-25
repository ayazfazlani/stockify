<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $invoice_number }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 14px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            margin: auto;
            padding: 30px;
        }
        .header {
            margin-bottom: 40px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #4f46e5;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .invoice-title {
            float: right;
            font-size: 32px;
            font-weight: 300;
            color: #999;
            margin-top: -10px;
        }
        .clear {
            clear: both;
        }
        .details {
            margin-bottom: 30px;
        }
        .column {
            float: left;
            width: 50%;
        }
        .label {
            font-weight: bold;
            color: #666;
            margin-bottom: 5px;
            font-size: 12px;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        th {
            background-color: #f9fafb;
            text-align: left;
            padding: 12px;
            font-size: 12px;
            color: #666;
            border-bottom: 2px solid #edf2f7;
        }
        td {
            padding: 15px 12px;
            border-bottom: 1px solid #edf2f7;
        }
        .total-section {
            float: right;
            width: 300px;
            margin-top: 30px;
        }
        .total-row {
            padding: 10px 0;
            border-bottom: 1px solid #edf2f7;
        }
        .total-label {
            float: left;
            font-weight: bold;
        }
        .total-value {
            float: right;
        }
        .grand-total {
            background-color: #f9fafb;
            padding: 15px 10px;
            font-size: 18px;
            font-weight: bold;
            color: #4f46e5;
            margin-top: 10px;
        }
        .footer {
            margin-top: 100px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #edf2f7;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">STOCKIFY</div>
            <div class="invoice-title">INVOICE</div>
            <div class="clear"></div>
        </div>

        <div class="details">
            <div class="column">
                <div class="label">Billed To:</div>
                <strong>{{ $tenant->name }}</strong><br>
                {{ $tenant->owner->name }}<br>
                {{ $tenant->owner->email }}
            </div>
            <div class="column" style="text-align: right;">
                <div class="label">Invoice Details:</div>
                <strong>Invoice #:</strong> {{ $invoice_number }}<br>
                <strong>Date:</strong> {{ $date }}<br>
                <strong>Stripe Ref:</strong> {{ $payment->stripe_invoice_id }}
            </div>
            <div class="clear"></div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align: right;">Quantity</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>{{ $tenant->subscription_plan ?: 'Standard Plan' }} Subscription</strong><br>
                        <span style="font-size: 12px; color: #666;">
                            Subscription for the period starting {{ $date }}
                        </span>
                    </td>
                    <td style="text-align: right;">1</td>
                    <td style="text-align: right;">{{ number_format($payment->amount, 2) }} {{ strtoupper($payment->currency) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-row">
                <div class="total-label">Subtotal</div>
                <div class="total-value">{{ number_format($payment->amount, 2) }} {{ strtoupper($payment->currency) }}</div>
                <div class="clear"></div>
            </div>
            <div class="total-row">
                <div class="total-label">Tax (0%)</div>
                <div class="total-value">0.00 {{ strtoupper($payment->currency) }}</div>
                <div class="clear"></div>
            </div>
            <div class="grand-total">
                <div class="total-label">Total Amount Paid</div>
                <div class="total-value">{{ number_format($payment->amount, 2) }} {{ strtoupper($payment->currency) }}</div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="clear"></div>

        <div class="footer">
            Thank you for choosing Stockify. If you have any questions, please contact support@stockify.io<br>
            Stockify Inc. &bull; 123 Business Way &bull; San Francisco, CA 94107
        </div>
    </div>
</body>
</html>
