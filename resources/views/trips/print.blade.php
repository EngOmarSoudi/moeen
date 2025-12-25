<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trip Details - {{ $trip->code }}</title>
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #B8860B;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #B8860B;
            text-transform: uppercase;
        }
        .trip-code {
            text-align: right;
        }
        .trip-code h1 {
            margin: 0;
            font-size: 20px;
            color: #555;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            background: #f8f8f8;
            padding: 5px 10px;
            border-left: 4px solid #B8860B;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .info-group {
            margin-bottom: 8px;
        }
        .label {
            font-weight: 600;
            color: #666;
            display: block;
            margin-bottom: 2px;
        }
        .value {
            font-size: 13px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        th {
            background: #fafafa;
            color: #666;
            font-weight: 600;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #888;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-scheduled { background: #fef3c7; color: #92400e; }
        .status-in_progress { background: #dbeafe; color: #1e40af; }
        .status-completed { background: #dcfce7; color: #166534; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        
        @media print {
            .no-print {
                display: none;
            }
            body {
                margin: 0;
                padding: 0;
            }
        }
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #B8860B;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">Print Trip Details</button>

    <div class="container">
        <div class="header">
            <div class="logo">MOEAN System</div>
            <div class="trip-code">
                <h1>TRIP VOUCHER</h1>
                <div>Code: <strong>{{ $trip->code }}</strong></div>
                <div>Created: {{ $trip->created_at->format('M d, Y H:i') }}</div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Trip Information</div>
            <div class="grid">
                <div class="info-group">
                    <span class="label">Status</span>
                    <span class="value status-badge status-{{ $trip->status }}">
                        {{ str_replace('_', ' ', $trip->status) }}
                    </span>
                </div>
                <div class="info-group">
                    <span class="label">Service Type</span>
                    <span class="value">{{ ucfirst($trip->service_kind) }}</span>
                </div>
                <div class="info-group">
                    <span class="label">Scheduled Start</span>
                    <span class="value">{{ $trip->start_at->format('M d, Y H:i') }}</span>
                </div>
                <div class="info-group">
                    <span class="label">Completed At</span>
                    <span class="value">{{ $trip->completed_at ? $trip->completed_at->format('M d, Y H:i') : '-' }}</span>
                </div>
                <div class="info-group">
                    <span class="label">Booking Agent</span>
                    <span class="value">{{ $trip->agent?->name ?? 'Main Company' }}</span>
                </div>
                <div class="info-group">
                    <span class="label">Passenger Count</span>
                    <span class="value">{{ $trip->passenger_count }}</span>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Customer Details</div>
            <div class="grid">
                <div class="info-group">
                    <span class="label">Name</span>
                    <span class="value">{{ $trip->customer?->name }}</span>
                </div>
                <div class="info-group">
                    <span class="label">Phone</span>
                    <span class="value">{{ $trip->customer?->phone }}</span>
                </div>
                <div class="info-group">
                    <span class="label">Email</span>
                    <span class="value">{{ $trip->customer?->email ?? 'N/A' }}</span>
                </div>
                <div class="info-group">
                    <span class="label">Nationality</span>
                    <span class="value">{{ $trip->customer?->nationality ?? 'N/A' }}</span>
                </div>
            </div>
            <div style="margin-top: 15px; padding: 10px; border: 1px dashed #ddd; border-radius: 4px;">
                <div style="font-weight: 600; font-size: 11px; margin-bottom: 5px; color: #B8860B;">EMERGENCY CONTACT</div>
                <div class="grid">
                    <div class="info-group">
                        <span class="label">Contact Name</span>
                        <span class="value">{{ $trip->customer?->emergency_contact_name ?? 'Not Set' }}</span>
                    </div>
                    <div class="info-group">
                        <span class="label">Contact Phone</span>
                        <span class="value">{{ $trip->customer?->emergency_contact_phone ?? 'Not Set' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Route Information</div>
            <div class="grid">
                <div class="info-group">
                    <span class="label">Pickup Location (Origin)</span>
                    <span class="value">{{ $trip->origin }}</span>
                </div>
                <div class="info-group">
                    <span class="label">Drop-off Location (Destination)</span>
                    <span class="value">{{ $trip->destination }}</span>
                </div>
                <div class="info-group">
                    <span class="label">Hotel Name</span>
                    <span class="value">{{ $trip->hotel_name ?? 'N/A' }}</span>
                </div>
                <div class="info-group">
                    <span class="label">Predefined Route</span>
                    <span class="value">{{ $trip->travelRoute?->name ?? 'None' }}</span>
                </div>
            </div>
        </div>



        <div class="section">
            <div class="section-title">Pricing & Payment</div>
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th style="text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Base Trip Amount</td>
                        <td style="text-align: right;">SAR {{ number_format($trip->amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Discount</td>
                        <td style="text-align: right;">- SAR {{ number_format($trip->discount, 2) }}</td>
                    </tr>
                    <tr style="font-weight: bold; border-top: 2px solid #eee;">
                        <td>TOTAL AMOUNT</td>
                        <td style="text-align: right; color: #B8860B;">SAR {{ number_format($trip->final_amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if($trip->notes)
        <div class="section">
            <div class="section-title">Notes</div>
            <div class="value" style="font-style: italic;">{{ $trip->notes }}</div>
        </div>
        @endif

        <div class="footer">
            <p>This is a computer-generated voucher for MOEAN Transportation System.</p>
            <p>&copy; {{ date('Y') }} MOEAN System. All rights reserved.</p>
        </div>
    </div>

    <script>
        // Auto-print on load if query param set
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('print')) {
                window.print();
            }
        };
    </script>
</body>
</html>
