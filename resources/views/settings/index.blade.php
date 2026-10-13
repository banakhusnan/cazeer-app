@extends('layouts.app')
@section('title', 'Pengaturan Toko · '.config('app.name'))
@section('page-title','Pengaturan Toko')
@section('page-subtitle','Identitas header nota dan batas stok menipis')
@section('content')
<div class="card"><div class="card-header section-card-header"><span class="section-card-icon"><i class="mdi mdi-store-outline"></i></span><span>Identitas dan preferensi toko</span></div><form class="card-body" id="settingForm" enctype="multipart/form-data"><div class="row g-3">
<div class="col-md-6"><label class="form-label">Nama toko</label><input class="form-control" name="store_name" value="{{ $setting?->store_name ?? 'Toko' }}"><div class="field-error" data-error="store_name"></div></div>
<div class="col-md-6"><label class="form-label">Nomor HP</label><input class="form-control" name="phone" value="{{ $setting?->phone }}"><div class="field-error" data-error="phone"></div></div>
<div class="col-12"><label class="form-label">Alamat</label><textarea class="form-control" name="address">{{ $setting?->address }}</textarea><div class="field-error" data-error="address"></div></div>
<div class="col-md-6"><label class="form-label">Logo nota (PNG/JPG monokrom)</label><input class="form-control" type="file" name="logo" accept="image/png,image/jpeg"><div class="field-error" data-error="logo"></div>@if($setting?->logo_path)<img class="mt-2" src="{{ asset('storage/'.$setting->logo_path) }}" style="max-width:120px;max-height:80px" alt="Logo toko">@endif</div>
<div class="col-md-6"><label class="form-label">Batas stok menipis</label><input class="form-control" type="number" min="0" name="low_stock_threshold" value="{{ $setting?->low_stock_threshold ?? 5 }}"><div class="field-error" data-error="low_stock_threshold"></div></div>
</div><button class="btn btn-primary mt-3" id="saveSetting"><i class="mdi mdi-content-save-outline"></i> Simpan Pengaturan</button></form></div>
@endsection
@push('scripts')
<script>
$('#settingForm').submit(function(e){e.preventDefault();const b=$('#saveSetting');buttonLoading(b,true);$.ajax({url:@json(route('settings.update')),type:'POST',data:new FormData(this),processData:false,contentType:false}).done(r=>notify(r.message)).fail(x=>{showErrors('#settingForm',x.responseJSON?.errors);notify(x.responseJSON?.message||'Gagal menyimpan pengaturan.',false)}).always(()=>buttonLoading(b,false))});
</script>
@endpush
