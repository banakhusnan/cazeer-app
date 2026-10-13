@extends('layouts.app')
@section('title', 'Produk · '.config('app.name'))
@section('page-title','Produk')
@section('page-subtitle','Kelola katalog produk dan status penjualan')
@section('page-actions')<button class="btn btn-primary" id="addProduct"><i class="mdi mdi-plus"></i> Tambah Produk</button>@endsection
@section('content')
<div class="card"><div class="card-header section-card-header"><span class="section-card-icon"><i class="mdi mdi-package-variant"></i></span><span>Daftar produk</span></div><div class="card-body table-responsive"><table id="productTable" class="table align-middle w-100"><thead><tr><th>Gambar</th><th>Kode</th><th>Nama</th><th>Kategori</th><th>Harga jual</th><th>Stok</th><th>Status</th><th>Aksi</th></tr></thead></table></div></div>
<div class="modal fade" id="productModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><form class="modal-content" id="productForm" enctype="multipart/form-data">
    <div class="modal-header"><div class="fw-bold modal-title">Tambah Produk</div><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body"><input type="hidden" id="productId"><div class="row g-2">
        <div class="col-12"><label class="form-label">Gambar produk</label><div class="d-flex align-items-center gap-3"><img id="productPreview" class="product-form-preview" src="{{ asset('assets/images/samples/300x300/1.jpg') }}" alt="Preview produk"><div class="flex-grow-1"><input class="form-control" type="file" name="image" accept="image/jpeg,image/png,image/webp"><div class="text-muted mt-1">JPG, PNG, atau WebP; maksimal 2 MB.</div><div class="field-error" data-error="image"></div></div></div></div>
        <div class="col-5"><label class="form-label">Kode</label><input class="form-control" name="code"><div class="field-error" data-error="code"></div></div>
        <div class="col-7"><label class="form-label">Nama</label><input class="form-control" name="name"><div class="field-error" data-error="name"></div></div>
        <div class="col-6"><label class="form-label">Kategori</label><input class="form-control" name="category"><div class="field-error" data-error="category"></div></div>
        <div class="col-6"><label class="form-label">Satuan</label><input class="form-control" name="unit" value="pot"><div class="field-error" data-error="unit"></div></div>
        <div class="col-6"><label class="form-label">Harga jual</label><div class="input-group"><span class="input-group-text">Rp</span><input class="form-control" type="number" min="1" step="1" inputmode="numeric" name="selling_price"></div><div class="field-error" data-error="selling_price"></div></div>
        <div class="col-6" id="stockField"><label class="form-label">Stok awal</label><input class="form-control" type="number" min="0" name="initial_stock" value="0"><div class="field-error" data-error="initial_stock"></div></div>
        <div class="col-6"><label class="form-label">Status</label><select class="form-select" name="is_active"><option value="1">Aktif</option><option value="0">Nonaktif</option></select></div>
        <div class="col-12"><label class="form-label">Deskripsi</label><textarea class="form-control" name="description" rows="3"></textarea><div class="field-error" data-error="description"></div></div>
    </div></div>
    <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary" id="saveProduct" type="submit"><i class="mdi mdi-content-save-outline"></i> Simpan</button></div>
</form></div></div>
@endsection
@push('scripts')
<script>
$(function () {
    const base = @json(route('products.index'));
    const fallbackImage = @json(asset('assets/images/samples/300x300/1.jpg'));
    const modal = bootstrap.Modal.getOrCreateInstance('#productModal');
    const table = $('#productTable').DataTable({ajax:{url:base,dataSrc:'data'},order:[[2,'asc']],columns:[
        {data:'image_url',orderable:false,searchable:false,render:v=>`<img class="product-table-image" src="${escapeHtml(v)}" alt="Gambar produk">`},
        {data:'code',render:escapeHtml},{data:'name',render:escapeHtml},{data:'category',defaultContent:'-',render:escapeHtml},{data:'selling_price',render:v=>Number(v)>0?`<span class="money">Rp ${money(v)}</span>`:'<span class="badge bg-warning text-dark">Belum diatur</span>'},{data:null,render:d=>`${d.stock} ${escapeHtml(d.unit)}`},
        {data:'is_active',render:v=>v?'<span class="badge bg-success">Aktif</span>':'<span class="badge bg-secondary">Nonaktif</span>'},
        {data:null,orderable:false,searchable:false,render:d=>`<div class="d-flex gap-1"><button class="btn btn-light btn-sm edit-product" data-id="${d.id}"><i class="mdi mdi-pencil"></i></button><button class="btn btn-danger btn-sm delete-product" data-id="${d.id}"><i class="mdi mdi-delete"></i></button></div>`}
    ]});
    $('#addProduct').on('click',function(){ $('#productForm')[0].reset(); $('#productId').val(''); $('#productPreview').attr('src',fallbackImage); $('#stockField').show(); clearErrors('#productForm'); $('.modal-title').text('Tambah Produk'); modal.show(); });
    $('#productTable').on('click','.edit-product',function(){ $.get(`${base}/${$(this).data('id')}`).done(r=>{ const d=r.data; $('#productForm')[0].reset(); $('#productId').val(d.id); Object.entries(d).forEach(([k,v])=>$('#productForm').find(`[name="${k}"]:not([type="file"])`).val(v===true?1:v===false?0:v)); $('#productPreview').attr('src',d.image_url||fallbackImage); $('#stockField').hide(); clearErrors('#productForm'); $('.modal-title').text('Ubah Produk'); modal.show(); }).fail(x=>notify(x.responseJSON?.message||'Data gagal dimuat.',false)); });
    $('#productForm [name="image"]').on('change',function(){ if(this.files[0]) $('#productPreview').attr('src',URL.createObjectURL(this.files[0])); });
    $('#productForm').on('submit',function(e){ e.preventDefault(); const id=$('#productId').val(); const button=$('#saveProduct'); const data=new FormData(this); if(id)data.append('_method','PUT'); buttonLoading(button,true); $.ajax({url:id?`${base}/${id}`:base,type:'POST',data,processData:false,contentType:false}).done(r=>{modal.hide();table.ajax.reload(null,false);notify(r.message)}).fail(x=>{showErrors('#productForm',x.responseJSON?.errors);notify(x.responseJSON?.message||'Gagal menyimpan produk.',false)}).always(()=>buttonLoading(button,false)); });
    let deleteId=null; const deleteModal=bootstrap.Modal.getOrCreateInstance('#deleteModal');
    $('#productTable').on('click','.delete-product',function(){const row=table.row($(this).closest('tr')).data();deleteId=row.id;$('#deleteName').text(row.name);deleteModal.show();});
    $('#confirmDelete').on('click',function(){const button=this;buttonLoading(button,true);$.ajax({url:`${base}/${deleteId}`,type:'DELETE'}).done(r=>{deleteModal.hide();table.ajax.reload(null,false);notify(r.message)}).fail(x=>notify(x.responseJSON?.message||'Gagal menghapus produk.',false)).always(()=>buttonLoading(button,false));});
});
</script>
@endpush
