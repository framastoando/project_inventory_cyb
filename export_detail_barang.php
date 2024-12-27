<?php
require('libs/fpdf.php');  // Pastikan path sesuai dengan tempat Anda menyimpan fpdf.php
require 'function.php';
require 'cek.php';

$admin_email = isset($_SESSION['email']) ? $_SESSION['email'] : 'Tidak Diketahui';

// Memfilter data yang dicetak sesuai range tanggal yang ditentukan
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

// Mengambil id barang dari URL
$idbarang = $_GET['id'];
$get = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idbarang'");
$fetch = mysqli_fetch_array($get);

// Informasi barang
$namabarang = $fetch['namabarang'];
$deskripsi = $fetch['deskripsi'];
$stock = $fetch['stock'];
$gambar = $fetch['image'];

// Jika gambar ada, set path lengkap untuk file gambar
$imagePath = $gambar ? 'image/'.$gambar : '';

// URL untuk QR Code
$urlview = 'https://ftonando.my.id/view.php?id='.$idbarang;
$qrcode_url = 'https://quickchart.io/qr?text='.$urlview.'&centerImageUrl=https%3A%2F%2Fi.imgur.com%2FNcARCn4.png&centerImageSizeRatio=0.3&size=200';

// Tentukan path lokal untuk menyimpan QR Code
$qrcode_local_path = 'qrcode/qrcode_image.png';

// Cek jika folder 'qrcode' ada, jika belum buat folder
if (!is_dir('qrcode')) {
    mkdir('qrcode', 0777, true);  // Membuat folder qrcode jika belum ada
}

// Unduh gambar QR Code dari URL dan simpan di server
file_put_contents($qrcode_local_path, file_get_contents($qrcode_url));

// Inisialisasi objek FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Set font
$pdf->SetFont('Arial', 'B', 16);

// Judul barang
$pdf->SetFont('Arial', 'B', 14); // Font besar untuk judul
$pdf->Cell(0, 10, 'Detail Barang - ' . $namabarang, 0, 1, 'C');

// Menampilkan periode rentang tanggal
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 8, 'Periode: ' . $start_date . ' - ' . $end_date, 0, 1, 'C');
$pdf->Ln(5); // Menambahkan jarak kecil setelah periode tanggal

// Gambar barang
if ($gambar) {
    $pdf->Image($imagePath, 10, $pdf->GetY() + 10, 50); // Gambar ditampilkan setelah jarak 10 mm dari teks terakhir
}

// QR Code yang telah diunduh (di kanan gambar)
$pdf->Image($qrcode_local_path, 70, $pdf->GetY() + 10, 40); // Koordinat vertikal sama seperti gambar

// Detail Barang (Set posisi setelah gambar dan QR code)
$pdf->SetY($pdf->GetY() + 70); // Atur Y untuk detail barang secara eksplisit agar tidak tumpang tindih
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Deskripsi: ' . $deskripsi, 0, 1);
$pdf->Cell(0, 10, 'Jumlah Sekarang: ' . $stock, 0, 1);

// Memfilter data Barang Masuk
$whereMasuk = ($start_date && $end_date) ? "AND tanggal BETWEEN '$start_date' AND '$end_date'" : "";
$ambilmasuk = mysqli_query($conn, "SELECT * FROM masuk WHERE idbarang='$idbarang' $whereMasuk");

// Tabel Barang Masuk
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Tabel Barang Masuk', 0, 1, 'L'); // Judul tabel barang masuk

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, 'Tanggal', 1);
$pdf->Cell(60, 10, 'Keterangan', 1);
$pdf->Cell(40, 10, 'Jumlah', 1);
$pdf->Ln();

// Data Barang Masuk
$pdf->SetFont('Arial', '', 12);
while ($row = mysqli_fetch_array($ambilmasuk)) {
    $pdf->Cell(60, 10, $row['tanggal'], 1);
    $pdf->Cell(60, 10, $row['keterangan'], 1);
    $pdf->Cell(40, 10, $row['qty'], 1);
    $pdf->Ln();
}

// Memfilter data Barang Keluar
$whereKeluar = ($start_date && $end_date) ? "AND tanggal BETWEEN '$start_date' AND '$end_date'" : "";
$ambilkeluar = mysqli_query($conn, "SELECT * FROM keluar WHERE idbarang='$idbarang' $whereKeluar");

// Tabel Barang Keluar
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Tabel Barang Keluar', 0, 1, 'L'); // Judul tabel barang keluar

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, 'Tanggal', 1);
$pdf->Cell(60, 10, 'Penerima', 1);
$pdf->Cell(40, 10, 'Jumlah', 1);
$pdf->Ln();

// Data Barang Keluar
$pdf->SetFont('Arial', '', 12);
while ($row = mysqli_fetch_array($ambilkeluar)) {
    $pdf->Cell(60, 10, $row['tanggal'], 1);
    $pdf->Cell(60, 10, $row['penerima'], 1);
    $pdf->Cell(40, 10, $row['qty'], 1);
    $pdf->Ln();
}

// Menampilkan Tanggal Pencetakan dan admin yang mencetak dokumen
$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 10, 'Tanggal Pencetakan: ' . date('d-m-Y H:i:s').'    |   Dicetak oleh: '. $admin_email, 0, 1, 'C');

// Output PDF ke browser dengan nama file menggunakan rentang tanggal
$fileName = $start_date . ' - ' . $end_date . ' - Detail Barang - ' . $namabarang . '.pdf';

// Output PDF ke browser
$pdf->Output('I', $fileName); // 'I' berarti ditampilkan di browser
exit;