<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Nota {{ $sale->invoice_number }}</title>
<style>
@page{size:58mm auto;margin:0}*{box-sizing:border-box}html,body{margin:0;padding:0;width:58mm;font-family:Tahoma,Arial,sans-serif;font-size:13px;font-weight:500;line-height:1.25;color:#000;-webkit-print-color-adjust:exact;print-color-adjust:exact}.receipt{width:58mm;max-width:58mm;padding:2mm}.center{text-align:center}.logo{display:block;max-width:28mm;max-height:18mm;object-fit:contain;margin:0 auto 1mm}.store-name{font-size:15px;font-weight:800}.address{overflow-wrap:anywhere}.divider{border-top:2px dashed #000;margin:1.5mm 0}.row{display:flex;justify-content:space-between;gap:2mm}.item-name{overflow-wrap:anywhere}.right{text-align:right;white-space:nowrap}.bold{font-weight:800}.actions{display:flex;gap:8px;justify-content:center;padding:12px}.actions button,.actions a{font:inherit;padding:8px 12px;text-decoration:none;border:1px solid #333;background:#fff;color:#000}@media print{html,body{font-weight:500}.actions{display:none}.receipt{padding:1.5mm}}
</style>
<style>
@media screen{html,body{width:100%;min-height:100%;background:#f3f4f8}.receipt{margin:20px auto;background:#fff;padding:2mm;box-shadow:0 4px 20px #00000012}.actions{padding-bottom:24px}}
</style>
</head>
<body>
<div class="receipt">
<header class="center">
@if($store?->logo_path)<img class="logo" src="{{ asset('storage/'.$store->logo_path) }}" alt="{{ $store->store_name }}">@endif
<div class="store-name">{{ $store?->store_name ?? 'TOKO' }}</div>
@if($store?->address)<div class="address">{{ $store->address }}</div>@endif
@if($store?->phone)<div>HP: {{ $store->phone }}</div>@endif
</header>
<div class="divider"></div><div>No: {{ $sale->invoice_number }}</div><div>Kasir: {{ $sale->cashier->name }}</div><div>Waktu: {{ $sale->transaction_at->format('d/m/Y H:i') }}</div>
@if($sale->customer_name)<div>Pelanggan: {{ $sale->customer_name }}</div>@endif
<div class="divider"></div>
@foreach($sale->items as $item)
<div class="item-name">{{ $item->product->name }}</div><div class="row"><span>{{ $item->quantity }} x {{ number_format($item->price,0,',','.') }}</span><span class="right">{{ number_format($item->subtotal,0,',','.') }}</span></div>
@if($item->notes)<div>{{ $item->notes }}</div>@endif
@endforeach
<div class="divider"></div><div class="row bold"><span>TOTAL</span><span>{{ number_format($sale->total_amount,0,',','.') }}</span></div><div class="row"><span>{{ strtoupper($sale->payment_method) }}</span><span>{{ number_format($sale->paid_amount,0,',','.') }}</span></div><div class="row"><span>KEMBALI</span><span>{{ number_format($sale->change_amount,0,',','.') }}</span></div><div class="divider"></div><div class="center">Terima kasih</div>
</div>
@unless(request()->boolean('embedded'))
<div class="actions"><a href="{{ route('sales.show',$sale) }}">Kembali</a><button onclick="window.print()">Cetak Nota</button></div>
@if(request()->boolean('autoprint'))<script>window.addEventListener('load',()=>window.print());</script>@endif
@endunless
</body></html>
