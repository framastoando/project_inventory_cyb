<?php
session_name('inventory_web');

// Mulai sesi jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
//membuat koneksi ke database
$conn = mysqli_connect("sql210.infinityfree.com","if0_37550033", "Texecutor322", "if0_37550033_inventaris_db");

//menambah barang baru
if(isset($_POST['addnewbarang'])){
    $barcode = $_POST['barcode'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];

    // Mengatur ekstensi gambar dan maksimal ukuran gambar
    $allowed_extensions = array('png','jpg','jpeg'); 
    $nama_file = $_FILES['file']['name'];  
    $ukuran = $_FILES['file']['size'];
    $file_tmp = $_FILES['file']['tmp_name'];

    // Penamaan file -> enkripsi jika ada gambar
    if(!empty($nama_file)) { 
        $ext = pathinfo($nama_file, PATHINFO_EXTENSION);
        $esktensi = strtolower($ext);
        
        if(in_array($esktensi, $allowed_extensions) === true){
            if($ukuran < 5000000) {  // Maksimal 5 MB
                $image = md5(uniqid($nama_file, true) . time()) . '.' . $esktensi;
                move_uploaded_file($file_tmp, 'image/' . $image);
            } else {
                echo '
                <script>
                    alert("Ukuran File Terlalu Besar");
                    window.location.href="index.php";
                </script>';
                exit(); // Stop eksekusi jika ukuran file terlalu besar
            }
        } else {
            echo '
            <script>
                alert("Ekstensi file tidak sesuai, harus png/jpg/jpeg");
                window.location.href="index.php";
            </script>';
            exit(); // Stop eksekusi jika ekstensi file salah
        }
    } else {
        $image = null; // Set null jika tidak ada gambar diunggah
    }

    // Validasi apakah barcode atau nama barang sudah terdaftar
    $cekbarcode = mysqli_query($conn, "SELECT * FROM stock WHERE barcode='$barcode'");
    $cekbarang = mysqli_query($conn, "SELECT * FROM stock WHERE namabarang='$namabarang'");
    
    if(mysqli_num_rows($cekbarcode) > 0) {
        echo '
        <script>
            alert("Barcode sudah terdaftar");
            window.location.href="index.php";
        </script>';
    } elseif(mysqli_num_rows($cekbarang) > 0) {
        echo '
        <script>
            alert("Nama barang sudah terdaftar");
            window.location.href="index.php";
        </script>';
    } else {
        $addtotable = mysqli_query($conn,"INSERT INTO stock (barcode, namabarang, deskripsi, stock, image) VALUES ('$barcode','$namabarang', '$deskripsi', '$stock', '$image')");
        if($addtotable){
            header('location:index.php');
        } else {
            echo 'Gagal input';
            header('location:index.php');
        }
    }
}





//menambah barang masuk
if (isset($_POST['barangmasuk'])) {
    $barangnya = $_POST['barangnya'];  // ID barang dari selector
    $barcode = $_POST['barcode'];     // Barcode yang diinputkan
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    // Cek apakah barcode yang dimasukkan sesuai dengan barang yang dipilih
    $cekbarang = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang = '$barangnya' AND barcode = '$barcode'");
    $databarang = mysqli_fetch_array($cekbarang);

    if ($databarang) {
        // Ambil stok sekarang
        $stocksekarang = $databarang['stock'];
        $tambahkanstocksekarang = $stocksekarang + $qty;

        // Masukkan data ke tabel 'masuk'
        $addtomasuk = mysqli_query($conn, "INSERT INTO masuk (idbarang, keterangan, qty) VALUES ('$barangnya', '$penerima', '$qty')");
        
        // Update stok barang di tabel 'stock'
        $updatestockmasuk = mysqli_query($conn, "UPDATE stock SET stock = '$tambahkanstocksekarang' WHERE idbarang = '$barangnya'");

        if ($addtomasuk && $updatestockmasuk) {
            header('location:masuk.php');
        } else {
            echo 'Gagal input';
            header('location:masuk.php');
        }
    } else {
        echo 'Barcode tidak sesuai dengan barang yang dipilih';
    }
}


//menambah barang keluar
if (isset($_POST['addbarangkeluar'])) {
    $barangnya = $_POST['barangnya'];  // ID barang dari dropdown
    $barcode = $_POST['barcode'];     // Barcode dari input
    $penerima = $_POST['penerima'];
    $qty = intval($_POST['qty']);     // Pastikan qty berupa integer

    if ($qty <= 0) {
        echo '
        <script>
            alert("Qty harus lebih dari 0");
            window.location.href="keluar.php";
        </script>
        ';
        exit;
    }

    // Validasi kecocokan barcode dengan idbarang
    $cekbarang = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$barangnya' AND barcode='$barcode'");
    $ambildatanya = mysqli_fetch_array($cekbarang);

    if (!$ambildatanya) {
        echo '
        <script>
            alert("Barcode tidak sesuai dengan barang yang dipilih");
            window.location.href="keluar.php";
        </script>
        ';
        exit;
    }

    $stocksekarang = $ambildatanya['stock'];

    if ($stocksekarang >= $qty) {
        // Kalau stok mencukupi, lanjutkan proses pengurangan
        $stokbaru = $stocksekarang - $qty;

        // Insert ke tabel keluar
        $addtokeluar = mysqli_query($conn, "INSERT INTO keluar (idbarang, penerima, qty) VALUES ('$barangnya', '$penerima', '$qty')");

        // Update stok di tabel stock
        $updatestockkeluar = mysqli_query($conn, "UPDATE stock SET stock='$stokbaru' WHERE idbarang='$barangnya'");

        if ($addtokeluar && $updatestockkeluar) {
            header('location:keluar.php');
            exit;
        } else {
            echo '
            <script>
                alert("Gagal memproses barang keluar");
                window.location.href="keluar.php";
            </script>
            ';
            exit;
        }
    } else {
        // Jika stok tidak mencukupi
        echo '
        <script>
            alert("Jumlah barang saat ini tidak mencukupi");
            window.location.href="keluar.php";
        </script>
        ';
        exit;
    }
}


//update barang
if(isset($_POST['updatebarang'])) {
    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];

    $allowed_extensions = array('png','jpg','jpeg'); // Menambahkan ekstensi jpeg
    $nama_file = $_FILES['file']['name'];  // pastikan input name adalah 'file'
    $ext = pathinfo($nama_file, PATHINFO_EXTENSION); // Mendapatkan ekstensi dengan lebih aman
    $esktensi = strtolower($ext);  // ubah ekstensi menjadi lowercase
    $ukuran = $_FILES['file']['size'];
    $file_tmp = $_FILES['file']['tmp_name'];

    //penamaan file -> enkripsi
    $image = md5(uniqid($nama_file,true) . time()).'.'.$esktensi; 

    
    if($ukuran==0){
        //pastikan semua data tidak kosong
        $update = mysqli_query($conn, "UPDATE stock SET namabarang='$namabarang', deskripsi='$deskripsi' WHERE idbarang='$idb'");
        if($update){
            header('location:index.php');
        } else {
            echo 'Gagal'; 
        }

    } else {
        // Pastikan semua data tidak kosong jika ingin upload edit gambar
        move_uploaded_file($file_tmp, 'image/'.$image);
        $update = mysqli_query($conn, "UPDATE stock SET namabarang='$namabarang', deskripsi='$deskripsi', image='$image' WHERE idbarang='$idb'");
        if($update){
            header('location:index.php');
        } else {
            echo 'Gagal'; 
        }
    }

    
}


// hapus barang
if(isset($_POST['hapusbarang'])){
    $idb = $_POST['idb'];

    $gambar = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idb'");
    $getgambar = mysqli_fetch_array($gambar);
    $img = 'image/'.$getgambar['image'];
    unlink($img);

    $hapus = mysqli_query($conn, "DELETE FROM stock WHERE idbarang='$idb'");
    if($hapus){
        header('location:index.php');
    } else {
        echo 'gagal';
        header('location:index.php');
    }
}

//update data barang masuk
if(isset($_POST['updatebarangmasuk'])){
    $idb = $_POST['idb'];
    $idm = $_POST['idm'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['keterangan'];
    $qty = $_POST['qty'];

    // Ambil stok saat ini dari tabel `stock`
    $lihatstock = mysqli_query($conn,"SELECT * FROM stock WHERE idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrng = $stocknya['stock'];

    // Ambil qty saat ini dari tabel `masuk`
    $qtyskrng = mysqli_query($conn,"SELECT * FROM masuk WHERE idmasuk='$idm'");
    $qtynya = mysqli_fetch_array($qtyskrng);
    $qtyskrng = $qtynya['qty'];

    // Hitung stok baru
    if($qty > $qtyskrng) {
        // Tambahkan selisih ke stok (stok berkurang karena qty sebelumnya dikurangi stok terlalu banyak)
        $selisih = $qty - $qtyskrng;
        $stokbaru = $stockskrng + $selisih;
    } else {
        // Kurangi selisih dari stok
        $selisih = $qtyskrng - $qty;
        $stokbaru = $stockskrng - $selisih;
    }

    // Update stok di tabel `stock`
    $updatestock = mysqli_query($conn,"UPDATE stock SET stock='$stokbaru' WHERE idbarang='$idb'");

    // Update data di tabel `masuk`
    $updatemasuk = mysqli_query($conn,"UPDATE masuk SET qty='$qty', keterangan='$deskripsi' WHERE idmasuk='$idm'");

    // Redirect jika sukses atau tampilkan error
    if($updatestock && $updatemasuk) {
        header('location:masuk.php');
    } else {
        echo 'Gagal: ' . mysqli_error($conn);
        exit(); // Berhenti untuk debugging
    }
}

//menghapus barang masuk
if(isset($_POST['hapusbarangmasuk'])){
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idm = $_POST['idm'];

    $getdatastock = mysqli_query($conn,"SELECT * FROM stock WHERE idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stok = $data['stock'];

    $selisih = $stok-$qty;

    $update = mysqli_query($conn,"UPDATE stock SET  stock='$selisih' WHERE idbarang='$idb'");
    $hapusdata = mysqli_query($conn, "DELETE FROM masuk WHERE idmasuk='$idm'");

    if($update&&$hapusdata){
        header('location:masuk.php');
    } else {
        echo 'gagal hapus barang';
        header('location:masuk.php');
    }
}


//edit data barang keluar 
if(isset($_POST['updatebarangkeluar'])){
    $idb = $_POST['idb'];  // ID barang
    $idk = $_POST['idk'];  // ID keluar
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];  // Jumlah baru yang diinput

    // Ambil stok saat ini dari tabel `stock`
    $lihatstock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrng = $stocknya['stock'];

    // Ambil qty lama dari tabel `keluar`
    $qtyskrng_query = mysqli_query($conn, "SELECT * FROM keluar WHERE idkeluar='$idk'");
    $qtynya = mysqli_fetch_array($qtyskrng_query);
    $qtyskrng = $qtynya['qty'];  // Jumlah lama yang tersimpan

    // Hitung selisih
    $selisih = $qty - $qtyskrng;

    // Periksa apakah stok mencukupi
    if ($selisih > 0) {
        // Jika jumlah baru lebih besar dari jumlah lama, stok harus cukup untuk selisihnya
        if ($selisih > $stockskrng) {
            echo '<script>alert("Jumlah saat ini tidak mencukupi untuk jumlah barang keluar yang baru!"); window.location.href="keluar.php";</script>';
            exit();  // Hentikan proses jika stok tidak mencukupi
        }
        $stokbaru = $stockskrng - $selisih;
    } else {
        // Jika jumlah baru lebih kecil dari jumlah lama, stok bertambah
        $stokbaru = $stockskrng + abs($selisih);
    }

    // Update stok di tabel `stock`
    $updatestock = mysqli_query($conn, "UPDATE stock SET stock='$stokbaru' WHERE idbarang='$idb'");

    // Update data di tabel `keluar`
    $updatekeluar = mysqli_query($conn, "UPDATE keluar SET qty='$qty', penerima='$penerima' WHERE idkeluar='$idk'");

    // Redirect jika sukses atau tampilkan error
    if($updatestock && $updatekeluar) {
        header('location:keluar.php');
    } else {
        echo 'Gagal: ' . mysqli_error($conn);
        exit();  // Berhenti untuk debugging
    }
}


//menghapus data barang keluar
if(isset($_POST['hapusbarangkeluar'])){
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idk = $_POST['idk'];

    $getdatastock = mysqli_query($conn,"SELECT * FROM stock WHERE idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stok = $data['stock'];

    $selisih = $stok+$qty;

    $update = mysqli_query($conn,"UPDATE stock SET  stock='$selisih' WHERE idbarang='$idb'");
    $hapusdata = mysqli_query($conn, "DELETE FROM keluar WHERE idkeluar='$idk'");

    if($update&&$hapusdata){
        header('location:keluar.php');
    } else {
        echo 'gagal hapus barang';
        header('location:keluar.php');
    }
}

//menambah admin baru
if(isset($_POST['addadmin'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $queryinsert = mysqli_query($conn, "INSERT INTO login (email, password) values ('$email','$password')");
    if($queryinsert){
        header('location:admin.php');
    } else {
        header('location:admin.php');
    }
}

if(isset($_POST['updateadmin'])){
    $emailbaru = $_POST['emailadmin'];
    $passwordbaru = $_POST['passwordbaru'];
    $idusernya = $_POST['id'];

    $queryupdate = mysqli_query($conn, "UPDATE login SET email='$emailbaru', password='$passwordbaru' WHERE iduser='$idusernya'");

    if($queryupdate){
        header('location:admin.php');
    } else {
        header('location:admin.php');
    }
}

if(isset($_POST['hapusadmin'])){
    $id = $_POST['id'];

    $querydelete = mysqli_query($conn, "DELETE FROM login WHERE iduser='$id'");

    if($querydelete){
        header('location:admin.php');
    } else {
        header('location:admin.php');
    }
}
?> 