<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="receiptModalTitle">Pratinjau Nota</h5>
                    <small class="text-muted">Nota siap dicetak pada kertas thermal 58 mm</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-0">
                <div id="receiptStatus" class="p-4 text-center text-muted" role="status">Memuat nota…</div>
                <iframe id="receiptFrame" title="Pratinjau nota transaksi" class="w-100 border-0" style="height:60vh;display:none"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="printReceipt" disabled><i class="mdi mdi-printer"></i> Cetak Nota</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="{{ asset('assets/js/receipt-modal.js') }}"></script>
@endpush
