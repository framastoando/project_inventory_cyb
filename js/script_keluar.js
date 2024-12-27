document.addEventListener('DOMContentLoaded', function () {
    const barcodeInput = document.getElementById('barcodeInputKeluar');
    const barangSelect = document.getElementById('barangSelectorKeluar');

    $('#myModal').on('shown.bs.modal', function () {
    var barcodeInput = document.getElementById('barcodeInput');
    barcodeInput.focus(); // Fokuskan ke input barcode
    barcodeInput.select(); // Pilih teks di dalam input (jika ada)
    });

    if (barcodeInput && barangSelect) {
        barcodeInput.addEventListener('input', function () {
            const barcode = barcodeInput.value.trim();

            if (barcode) {
                fetch('get_barang_by_barcode.php?barcode=' + barcode)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.idbarang) {
                            barangSelect.value = data.idbarang;
                        } else {
                            barangSelect.value = '';
                            console.warn('Barang dengan barcode ini tidak ditemukan.');
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                    });
            }
        });

        barangSelect.addEventListener('change', function () {
            const selectedOption = barangSelect.options[barangSelect.selectedIndex];
            const barcode = selectedOption.getAttribute('data-barcode');

            if (barcode) {
                barcodeInput.value = barcode;
            } else {
                barcodeInput.value = '';
            }
        });
    } else {
        console.error('Elemen input barcode atau dropdown barang tidak ditemukan.');
    }
});
