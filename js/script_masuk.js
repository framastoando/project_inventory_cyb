document.addEventListener('DOMContentLoaded', function () {
    // Mendapatkan elemen input barcode dan dropdown selector
    const barcodeInput = document.getElementById('barcodeInput');
    const barangSelect = document.getElementById('barangSelector'); // Gunakan id sesuai dengan form Anda

    $('#myModal').on('shown.bs.modal', function () {
    var barcodeInput = document.getElementById('barcodeInput');
    barcodeInput.focus(); // Fokuskan ke input barcode
    barcodeInput.select(); // Pilih teks di dalam input (jika ada)
    });

    if (barcodeInput && barangSelect) {
        // Event listener untuk input barcode
        barcodeInput.addEventListener('input', function () {
            const barcode = barcodeInput.value.trim(); // Ambil nilai barcode dan hapus spasi

            if (barcode) {
                // Kirim request ke server untuk mendapatkan idbarang berdasarkan barcode
                fetch('get_barang_by_barcode.php?barcode=' + barcode)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.idbarang) {
                            barangSelect.value = data.idbarang; // Pilih dropdown sesuai idbarang
                        } else {
                            barangSelect.value = ''; // Reset dropdown jika barcode tidak ditemukan
                            console.warn('Barang dengan barcode ini tidak ditemukan.');
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                    });
            }
        });

        // Event listener untuk perubahan pada dropdown barang
        barangSelect.addEventListener('change', function () {
            const selectedOption = barangSelect.options[barangSelect.selectedIndex]; // Ambil opsi yang dipilih
            const barcode = selectedOption.getAttribute('data-barcode'); // Ambil data-barcode dari opsi

            if (barcode) {
                barcodeInput.value = barcode; // Isi input barcode sesuai barang yang dipilih
            } else {
                barcodeInput.value = ''; // Reset input jika barang tidak memiliki barcode
            }
        });
    } else {
        console.error('Elemen input barcode atau dropdown barang tidak ditemukan.');
    }
});
