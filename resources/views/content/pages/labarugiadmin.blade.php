<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Laba Rugi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border: 1px solid #ddd;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .header h2 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 10px;
            text-align: left;
            font-size: 13px;
        }
        th {
            background: #f9f9f9;
            font-weight: bold;
        }
        .section-title {
            background: #f0f0f0;
            font-weight: bold;
            padding: 10px;
        }
        .total-row {
            background: #ffeb3b;
            font-weight: bold;
        }
        .number {
            text-align: right;
        }
        .section-header {
            margin-top: 20px;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 14px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }
        .detail-table {
            font-size: 12px;
        }
        .detail-table th,
        .detail-table td {
            padding: 6px;
        }
        .return-row {
            background: #fff3cd;
        }
        .loading {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        .error {
            color: red;
            padding: 20px;
            background: #ffebee;
            border: 1px solid #ff0000;
        }
        .print-btn {
            background-color: #4a6fdc;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-right: 10px;
            margin-bottom: 15px;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .container {
                border: none;
                padding: 0;
                max-width: 100%;
            }
            .noprint {
            display: none !important;
        }
        }
    </style>
</head>
<body>
    <div class="container" id="reportContainer">
        <div class="loading">Loading data...</div>
    </div>

    <div class="noprint container">
            <button onclick="window.print();" class="print-btn">Cetak PDF</button>
        </div>

    <script>
        const API_URL = '{{ env("API_URL") }}';

        function getCookie(name) {
            const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
            return match ? decodeURIComponent(match[2]) : null;
        }

        function authHeaders() {
            const headers = { 'Content-Type': 'application/json' };
            const token = getCookie('token');
            if (token) headers['Authorization'] = `Bearer ${token}`;
            return headers;
        }

        function formatCurrency(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(value);
        }

        function getDateRange() {
            const params = new URLSearchParams(window.location.search);
            const startDate = params.get('start_date') || '';
            const endDate = params.get('end_date') || '';
            return { startDate, endDate };
        }

        function formatDateRange(startDate, endDate) {
            if (!startDate || !endDate) return 'TANPA BATAS TANGGAL';
            
            const start = new Date(startDate);
            const end = new Date(endDate);
            
            const monthNames = ['JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI', 
                               'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOVEMBER', 'DESEMBER'];
            
            const startMonth = monthNames[start.getMonth()];
            const endMonth = monthNames[end.getMonth()];
            const startYear = start.getFullYear();
            const endYear = end.getFullYear();
            
            if (startMonth === endMonth && startYear === endYear) {
                return `BULAN ${startMonth} ${startYear}`;
            } else {
                return `${startDate} HINGGA ${endDate}`;
            }
        }

        async function fetchAndRender() {
            try {
                const { startDate, endDate } = getDateRange();
                const params = new URLSearchParams(window.location.search);
                const branchId = params.get('branch_id');
                let url = branchId ? `${API_URL}/branches/${branchId}/labarugi` : `${API_URL}/branch/labarugi`;
                const qp = [];
                if (startDate) qp.push(`start_date=${encodeURIComponent(startDate)}`);
                if (endDate) qp.push(`end_date=${encodeURIComponent(endDate)}`);
                if (qp.length) url += `?${qp.join('&')}`;

                const res = await fetch(url, { 
                    headers: authHeaders(),
                    credentials: 'include'
                });

                if (!res.ok) throw new Error('Failed to fetch data');
                const data = await res.json();

                renderReport(data);
            } catch (err) {
                console.error(err);
                document.getElementById('reportContainer').innerHTML = 
                    `<div class="error">Error loading report: ${err.message}</div>`;
            }
        }

        function renderReport(data) {
            const { startDate, endDate } = getDateRange();
            const dateRange = formatDateRange(startDate, endDate);

            let html = `
                <div class="header">
                    <h1>LAPORAN LABA RUGI</h1>
                    <h2>${data.branch_name}</h2>
                    <p>PERIODE : ${dateRange}</p>
                </div>

                <div class="section-header">PENDAPATAN USAHA</div>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 40%;">Keterangan</th>
                            <th style="width: 15%;">Satuan</th>
                            <th style="width: 20%;">Jumlah</th>
                            <th style="width: 20%;">Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            // Products
            let productIndex = 1;
            data.products.forEach(product => {
                const percentage = ((product.income / data.total_income) * 100).toFixed(2);
                html += `
                    <tr>
                        <td>${productIndex}</td>
                        <td>${product.name}</td>
                        <td>Rp.</td>
                        <td class="number">${parseInt(product.income).toLocaleString('id-ID')}</td>
                        <td class="number">${percentage}%</td>
                    </tr>
                `;
                productIndex++;
            });

            // Total Income
            html += `
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">TOTAL PENDAPATAN</td>
                    <td class="number">Rp.</td>
                    <td class="number">${parseInt(data.total_income).toLocaleString('id-ID')}</td>
                </tr>
            `;

            html += `
                    </tbody>
                </table>

                <div class="section-header">PENGELUARAN USAHA</div>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 40%;">Keterangan</th>
                            <th style="width: 15%;">Satuan</th>
                            <th style="width: 20%;">Jumlah</th>
                            <th style="width: 20%;">Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            // Expenses
            let expenseIndex = 1;
            data.expenses.forEach(expense => {
                const percentage = ((expense.nominal / data.total_outcome) * 100).toFixed(2);
                html += `
                    <tr>
                        <td>${expenseIndex}</td>
                        <td>${expense.name}</td>
                        <td>Rp.</td>
                        <td class="number">${parseInt(expense.nominal).toLocaleString('id-ID')}</td>
                        <td class="number">${percentage}%</td>
                    </tr>
                `;
                expenseIndex++;
            });

            // Total Outcome
            html += `
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">TOTAL PENGELUARAN</td>
                    <td class="number">Rp.</td>
                    <td class="number">${parseInt(data.total_outcome).toLocaleString('id-ID')}</td>
                </tr>
            `;

            // Net Profit/Loss
            html += `
                <tr class="return-row">
                    <td colspan="3" style="text-align: right;">Return</td>
                    <td class="number">Rp.</td>
                    <td class="number">${parseInt(data.total).toLocaleString('id-ID')}</td>
                </tr>
            `;

            html += `
                    </tbody>
                </table>

                <div class="section-header">ANALISIS SHIFT - PENDAPATAN</div>
                <table class="detail-table">
                    <thead>
                        <tr>
                            <th>Shift</th>
                            <th style="width: 50%;">Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            // Overall Product Shift
            Object.entries(data.overall_products_shift).forEach(([shift, percent]) => {
                html += `
                    <tr>
                        <td>${shift.charAt(0).toUpperCase() + shift.slice(1)}</td>
                        <td>${parseFloat(percent).toFixed(2)}%</td>
                    </tr>
                `;
            });

            html += `
                    </tbody>
                </table>

                <div class="section-header">ANALISIS TIPE TRANSAKSI - PENDAPATAN</div>
                <table class="detail-table">
                    <thead>
                        <tr>
                            <th>Tipe Transaksi</th>
                            <th style="width: 50%;">Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            // Overall Transaction Type
            Object.entries(data.overall_products_transaction_type).forEach(([type, percent]) => {
                const typeLabel = type.charAt(0).toUpperCase() + type.slice(1);
                html += `
                    <tr>
                        <td>${typeLabel}</td>
                        <td>${parseFloat(percent).toFixed(2)}%</td>
                    </tr>
                `;
            });

            html += `
                    </tbody>
                </table>

                <div class="section-header">ANALISIS SHIFT - PENGELUARAN</div>
                <table class="detail-table">
                    <thead>
                        <tr>
                            <th>Shift</th>
                            <th style="width: 50%;">Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            // Overall Expense Shift
            Object.entries(data.overall_expenses_shift).forEach(([shift, percent]) => {
                html += `
                    <tr>
                        <td>${shift.charAt(0).toUpperCase() + shift.slice(1)}</td>
                        <td>${parseFloat(percent).toFixed(2)}%</td>
                    </tr>
                `;
            });

            html += `
                    </tbody>
                </table>
            `;

            document.getElementById('reportContainer').innerHTML = html;
            
            // Auto-trigger print dialog
            setTimeout(() => {
                window.print();
            }, 500);
        }

        // Fetch and render on load
        document.addEventListener('DOMContentLoaded', fetchAndRender);
    </script>
</body>
</html>
