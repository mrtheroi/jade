<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de ventas</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 4px 6px; }
        th { background: #f3f4f6; }
        h1 { font-size: 16px; margin-bottom: 4px; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
<h1>Reporte de ventas</h1>
<p>
    Rango:
    @if($from || $to)
        {{ $from ?? '---' }} &rarr; {{ $to ?? '---' }}
    @else
        Todos los registros
    @endif
</p>

<p><strong>Total ventas:</strong> $ {{ number_format($totalSales, 2) }}</p>

<table>
    <thead>
    <tr>
        <th>Fecha</th>
        <th>Unidad</th>
        <th>Turno</th>
        <th class="text-right">Cash</th>
        <th class="text-right">Débito</th>
        <th class="text-right">Crédito</th>
        <th class="text-right">Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($rows as $row)
        @php
            $total = ($row->cash_sales ?? 0)
                   + ($row->debit_card_sales ?? 0)
                   + ($row->credit_card_sales ?? 0);
        @endphp
        <tr>
            <td>{{ optional($row->operation_date)->format('Y-m-d') }}</td>
            <td>{{ $row->business_unit }}</td>
            <td>{{ $row->turno }}</td>
            <td class="text-right">{{ number_format($row->cash_sales, 2) }}</td>
            <td class="text-right">{{ number_format($row->debit_card_sales, 2) }}</td>
            <td class="text-right">{{ number_format($row->credit_card_sales, 2) }}</td>
            <td class="text-right">{{ number_format($total, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
