<?php
require_once 'function.php';

// Ambil barcode dari query parameter
$barcode = $_GET['barcode'] ?? '';
$barcode_partial = substr($barcode, 0, -4);

// Jika barcode tidak kosong
if (!empty($barcode)) {
    // Query untuk mendapatkan barang berdasarkan barcode
    $query = mysqli_query($conn, "SELECT idbarang FROM stock WHERE barcode LIKE '$barcode_partial%' LIMIT 1");
    $result = mysqli_fetch_assoc($query);

    // Jika barang ditemukan
    if ($result) {
        echo json_encode($result); // Mengembalikan idbarang
    } else {
        echo json_encode(null); // Barang tidak ditemukan
    }
} else {
    echo json_encode(null); // Barcode kosong
}
