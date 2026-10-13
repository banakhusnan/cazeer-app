$(function () {
    const element = document.getElementById('receiptModal');
    const modal = bootstrap.Modal.getOrCreateInstance(element);
    const frame = document.getElementById('receiptFrame');
    const printButton = document.getElementById('printReceipt');
    const status = document.getElementById('receiptStatus');
    let ready = false, visible = false, autoPrint = false, timer;

    function printReceipt() {
        if (!ready || !visible) return;
        autoPrint = false;
        try {
            frame.contentWindow.focus();
            frame.contentWindow.print();
        } catch (error) {
            notify('Cetak belum tersedia. Tutup lalu buka kembali pratinjau dan coba cetak lagi.', false);
        }
    }

    function showError() {
        clearTimeout(timer);
        ready = false;
        autoPrint = false;
        printButton.disabled = true;
        frame.style.display = 'none';
        status.textContent = 'Nota belum dapat dimuat. Tutup lalu buka kembali pratinjau untuk mencoba lagi.';
        status.hidden = false;
    }

    function openReceipt(href, shouldPrint = false) {
        const url = new URL(href, window.location.href);
        if (url.origin !== window.location.origin) return;
        ready = false;
        autoPrint = shouldPrint;
        printButton.disabled = true;
        frame.style.display = 'none';
        status.textContent = 'Memuat nota…';
        status.hidden = false;
        url.searchParams.delete('autoprint');
        url.searchParams.set('embedded', '1');
        clearTimeout(timer);
        timer = setTimeout(showError, 20000);
        frame.src = url.href;
        modal.show();
    }

    frame.addEventListener('load', function () {
        if (!frame.getAttribute('src')) return;
        try {
            if (!frame.contentDocument?.querySelector('.receipt')) {
                showError();
                return;
            }
            clearTimeout(timer);
            ready = true;
            printButton.disabled = false;
            status.hidden = true;
            frame.style.display = 'block';
            if (autoPrint && visible) printReceipt();
        } catch (error) {
            showError();
        }
    });
    frame.addEventListener('error', showError);
    element.addEventListener('shown.bs.modal', function () {
        visible = true;
        if (autoPrint && ready) printReceipt();
    });
    element.addEventListener('hide.bs.modal', function () {
        visible = false;
        autoPrint = false;
        ready = false;
        clearTimeout(timer);
        printButton.disabled = true;
        frame.removeAttribute('src');
    });
    printButton.addEventListener('click', printReceipt);
    $(document).on('click', 'a[data-receipt-modal]', function (event) {
        if (event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
        event.preventDefault();
        openReceipt(this.href);
    });

    const pageUrl = new URL(window.location.href);
    const trigger = document.querySelector('a[data-receipt-modal]');
    if (pageUrl.searchParams.get('receipt') === '1' && trigger) {
        pageUrl.searchParams.delete('receipt');
        window.history.replaceState(window.history.state, '', pageUrl.href);
        openReceipt(trigger.href, true);
    }
});
