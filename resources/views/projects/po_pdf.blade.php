<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Purchase Order - {{ $project->po->no_po ?? 'PO' }}</title>
    <style>
        @page {
            margin: 30px 40px 40px 40px;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
            color: #000;
            line-height: 1.3;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .header-logo {
            font-size: 26pt;
            font-weight: 900;
            color: #0b3b82;
            letter-spacing: 1px;
            text-align: right;
            vertical-align: top;
        }
        .header-center {
            text-align: center;
            font-size: 8pt;
            line-height: 1.25;
        }
        .header-center .company-title {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .po-title {
            font-size: 16pt;
            font-weight: bold;
            text-align: right;
            margin-top: 10px;
            margin-bottom: 8px;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 8.5pt;
        }
        .meta-table td {
            vertical-align: top;
            padding: 1px 0;
        }
        .meta-left {
            width: 50%;
            padding-right: 15px !important;
        }
        .meta-right {
            width: 50%;
            padding-left: 15px !important;
        }
        .inner-meta {
            width: 100%;
            border-collapse: collapse;
        }
        .inner-meta td {
            padding: 1.5px 0;
            font-size: 8.5pt;
        }
        .label-col {
            width: 32%;
            color: #222;
        }
        .colon-col {
            width: 4%;
            text-align: center;
        }
        .val-col {
            width: 64%;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 8.5pt;
        }
        .items-table th {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            border-left: 1px solid #000;
            border-right: 1px solid #000;
            padding: 5px 4px;
            background-color: #f7f7f7;
            font-weight: bold;
            text-align: center;
        }
        .items-table td {
            border: 1px solid #000;
            padding: 6px 4px;
            vertical-align: top;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }

        /* Summary section below table */
        .summary-wrapper {
            width: 100%;
            margin-top: 0;
            border-collapse: collapse;
        }
        .summary-wrapper td {
            vertical-align: top;
            padding: 0;
        }
        .amount-in-words-box {
            border: 1px solid #000;
            border-top: none;
            padding: 6px 8px;
            font-size: 8.5pt;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
        }
        .totals-table td {
            border: 1px solid #000;
            border-top: none;
            padding: 4px 6px;
        }
        .totals-table .label {
            width: 50%;
            text-align: left;
        }
        .totals-table .value {
            width: 50%;
            text-align: right;
        }

        /* Notes */
        .terms-text {
            font-size: 8pt;
            margin-top: 15px;
            line-height: 1.35;
        }
        .notes-box {
            font-size: 8pt;
            margin-top: 10px;
            line-height: 1.35;
        }

        /* Footer */
        .footer-fixed {
            position: fixed;
            bottom: 0px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8pt;
            color: #333;
        }
        .page-num {
            float: right;
            font-weight: bold;
        }

        .page-break {
            page-break-before: always;
        }

        /* Page 2 signatures */
        .sign-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
            font-size: 8.5pt;
        }
        .sign-table td {
            vertical-align: top;
        }
        .sign-box {
            border: 1px solid #000;
            padding: 8px 10px;
            text-align: center;
            font-size: 8pt;
            width: 220px;
            margin-top: 10px;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>

    {{-- ================= PAGE 1 ================= --}}

    {{-- Header Logo & Address --}}
    <table class="header-table">
        <tr>
            <td style="width: 20%;"></td>
            <td class="header-center" style="width: 60%;">
                <div class="company-title">Purwakarta</div>
                <div>Kawasan Industri Kota Bukit Indah Blok A II, Kav. 1C-1D</div>
                <div>Purwakarta, 41181</div>
                <div>Indonesia</div>
                <div>Phone : +62 264 8631 300 ; Fax : +62 264 8631 333</div>
                <div>www.sulzer.com</div>
            </td>
            <td class="header-logo" style="width: 20%;">
                SULZER
            </td>
        </tr>
    </table>

    <div class="po-title">Purchase Order</div>

    {{-- Metadata Two Columns --}}
    <table class="meta-table">
        <tr>
            {{-- Left Column: Vendor & Delivery --}}
            <td class="meta-left">
                <table class="inner-meta">
                    <tr>
                        <td class="label-col"><strong>Vendor</strong></td>
                        <td class="colon-col">:</td>
                        <td class="val-col"><strong>PT. TECHNODRIVES</strong></td>
                    </tr>
                    <tr>
                        <td class="label-col">Address</td>
                        <td class="colon-col">:</td>
                        <td class="val-col">
                            Bellagio Residence Unit OL3-28A, Kawasan Mega Kuningan Jakarta<br>
                            Jakarta, Indonesia
                        </td>
                    </tr>
                    <tr>
                        <td class="label-col">Phone/Fax</td>
                        <td class="colon-col">:</td>
                        <td class="val-col">021 7364033 / -</td>
                    </tr>
                    <tr>
                        <td class="label-col">Contact</td>
                        <td class="colon-col">:</td>
                        <td class="val-col">{{ $project->pic->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col">Email</td>
                        <td class="colon-col">:</td>
                        <td class="val-col">{{ $project->pic->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col" style="padding-top: 6px;">Delivery Address</td>
                        <td class="colon-col" style="padding-top: 6px;">:</td>
                        <td class="val-col" style="padding-top: 6px;">
                            <strong>{{ $project->endUser->name ?? 'Purwakarta' }}</strong><br>
                            {{ $project->endUser->location ?? 'Kawasan Industri Kota Bukit Indah Blok A II, Kav. 1C-1D Purwakarta, 41181 Indonesia' }}
                        </td>
                    </tr>
                </table>
            </td>

            {{-- Right Column: PO Info --}}
            <td class="meta-right">
                <table class="inner-meta">
                    <tr>
                        <td class="label-col"><strong>PO no</strong></td>
                        <td class="colon-col">:</td>
                        <td class="val-col"><strong>{{ $project->po->no_po ?? 'PO-' . $project->id }}</strong></td>
                    </tr>
                    <tr>
                        <td class="label-col">Vendor reference</td>
                        <td class="colon-col">:</td>
                        <td class="val-col">{{ $project->po->no_po ?? 'PO-' . $project->id }}</td>
                    </tr>
                    <tr>
                        <td class="label-col">Project no</td>
                        <td class="colon-col">:</td>
                        <td class="val-col">P{{ str_pad($project->id, 6, '0', STR_PAD_LEFT) }}.805.01.03</td>
                    </tr>
                    <tr>
                        <td class="label-col">Purchaser</td>
                        <td class="colon-col">:</td>
                        <td class="val-col">{{ $project->customer->contact_person ?? 'Minggus Priagi' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col">Requestor</td>
                        <td class="colon-col">:</td>
                        <td class="val-col">{{ $project->endUser->pic_name ?? 'Ibnul Nanda Rianza' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col">Mode of delivery</td>
                        <td class="colon-col">:</td>
                        <td class="val-col">Land</td>
                    </tr>
                    <tr>
                        <td class="label-col">Term of delivery</td>
                        <td class="colon-col">:</td>
                        <td class="val-col">At Site</td>
                    </tr>
                    <tr>
                        <td class="label-col">Payment term</td>
                        <td class="colon-col">:</td>
                        <td class="val-col">Net 45 Days</td>
                    </tr>
                    <tr>
                        <td class="label-col">Delivery date</td>
                        <td class="colon-col">:</td>
                        <td class="val-col">
                            {{ $project->tgl_selesai ? \Carbon\Carbon::parse($project->tgl_selesai)->format('j M Y') : '1 Nov 2026' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Items Table --}}
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 17%;">Item Number</th>
                <th style="width: 28%;">Description</th>
                <th style="width: 7%;">Qty</th>
                <th style="width: 6%;">UOM</th>
                <th style="width: 5%;">Cur</th>
                <th style="width: 15%;">Price</th>
                <th style="width: 10%;">Discount</th>
                <th style="width: 15%;">Total Price</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1.</td>
                <td class="text-left">SrvGenerator-{{ str_pad($project->id, 3, '0', STR_PAD_LEFT) }}</td>
                <td class="text-left">
                    <strong>{{ $project->name }}</strong>
                    @if($project->description)
                        <div style="font-size: 8pt; color: #444; margin-top: 3px;">
                            {{ $project->description }}
                        </div>
                    @endif
                    @if($project->endUser && $project->endUser->location)
                        <div style="font-size: 8pt; color: #555; margin-top: 2px;">
                            Offline, location {{ $project->endUser->location }}
                        </div>
                    @endif
                </td>
                <td class="text-center">1.00</td>
                <td class="text-center">EA</td>
                <td class="text-center">IDR</td>
                <td class="text-right">{{ number_format($nilaiPekerjaan, 2) }}</td>
                <td class="text-right">{{ number_format($discount, 2) }}</td>
                <td class="text-right">{{ number_format($totalBeforeVat, 2) }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Bottom Summary: Amount in words on left, Totals on right --}}
    <table class="summary-wrapper" style="width: 100%;">
        <tr>
            <td style="width: 63%; vertical-align: top;">
                <div class="amount-in-words-box" style="min-height: 58px;">
                    <em><strong>Amount in words:</strong> {{ $amountInWords }}</em>
                </div>
            </td>
            <td style="width: 37%; vertical-align: top;">
                <table class="totals-table">
                    <tr>
                        <td class="label">Discount</td>
                        <td class="value">{{ number_format($discount, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Total Before VAT</td>
                        <td class="value">{{ number_format($totalBeforeVat, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">VAT</td>
                        <td class="value">{{ number_format($vat, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label"><strong>Grand Total</strong></td>
                        <td class="value"><strong>{{ number_format($grandTotal, 2) }}</strong></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Terms and Notes --}}
    <div class="terms-text">
        This order is subject to PT. Sulzer Indonesia Standard Terms and Conditions of Purchase attached.<br>
        Copy invoice email to : Account Payable (sulzeracct.payable@sulzer.com).
    </div>

    <div class="notes-box">
        <strong>Note :</strong><br>
        Locatiojn: {{ $project->endUser->location ?? ($project->endUser->name ?? 'SEG Wayang Windu') }}<br>
        Reporting: Deadline {{ $project->deadline_laporan ? \Carbon\Carbon::parse($project->deadline_laporan)->format('d F Y') : '-' }}<br>
        @if($project->staff && $project->staff->count())
            Pelaksana: {{ $project->staff->pluck('name')->join(', ') }}<br>
        @endif
        @if($project->equipments && $project->equipments->count())
            Alat Uji: {{ $project->equipments->pluck('name')->join(', ') }}<br>
        @endif
        @if($project->progress_notes)
            Catatan Progres: {{ $project->progress_notes }}
        @endif
    </div>

    {{-- Page 1 Footer --}}
    <div class="footer-fixed">
        <div style="font-weight: bold; letter-spacing: 1px;">SULZER</div>
        <div style="font-size: 7.5pt; color: #444;">CONFIDENTIAL</div>
        <span class="page-num">Page 1 of 2</span>
    </div>

    {{-- ================= PAGE 2 ================= --}}
    <div class="page-break"></div>

    <table class="sign-table">
        <tr>
            {{-- Vendor Acceptance (Left) --}}
            <td style="width: 50%; vertical-align: top; padding-top: 30px;">
                <div style="font-weight: bold; margin-bottom: 15px;">Acknowledge and accepted by vendor</div>
                <table style="width: 100%; font-size: 8.5pt;">
                    <tr>
                        <td style="width: 25%;">Date</td>
                        <td style="width: 5%;">:</td>
                        <td style="width: 70%;">___________________________</td>
                    </tr>
                    <tr>
                        <td style="padding-top: 35px;">Signature</td>
                        <td style="padding-top: 35px;">:</td>
                        <td style="padding-top: 35px;">___________________________</td>
                    </tr>
                    <tr>
                        <td style="padding-top: 35px;">Name & Title</td>
                        <td style="padding-top: 35px;">:</td>
                        <td style="padding-top: 35px;">
                            <strong>{{ $project->pic->name ?? 'PT. TECHNODRIVES' }}</strong><br>
                            <span style="font-size: 8pt; color: #555;">({{ $project->pic->role ?? 'Supervisor' }})</span>
                        </td>
                    </tr>
                </table>
            </td>

            {{-- Customer Issuer (Right) --}}
            <td style="width: 50%; vertical-align: top; text-align: right; padding-top: 30px;">
                <div style="margin-bottom: 10px;">
                    Purwakarta, {{ $project->po->tgl_po ? \Carbon\Carbon::parse($project->po->tgl_po)->format('m/d/Y') : date('m/d/Y') }}
                </div>
                <div style="font-weight: bold; margin-bottom: 10px;">
                    {{ $project->customer->name ?? 'PT. Sulzer Indonesia' }}
                </div>

                <div style="display: inline-block; text-align: right;">
                    <div class="sign-box">
                        This document is computer generated, thus no signature is required
                    </div>
                </div>

                <div style="font-weight: bold; margin-top: 5px;">
                    {{ $project->customer->contact_person ?? 'Ryan Teddy' }}
                </div>
                <div style="font-size: 8pt; color: #333;">
                    (Procurement & Logistic Manager)
                </div>
            </td>
        </tr>
    </table>

    {{-- Page 2 Footer --}}
    <div class="footer-fixed">
        <div style="font-weight: bold; letter-spacing: 1px;">SULZER</div>
        <div style="font-size: 7.5pt; color: #444;">CONFIDENTIAL</div>
        <span class="page-num">Page 2 of 2</span>
    </div>

</body>
</html>
