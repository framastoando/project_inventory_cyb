<?php
    require 'function.php';

    //mengambil id barang dari index.php
    $idbarang = $_GET['id'];
    $get = mysqli_query($conn,"SELECT * FROM stock WHERE idbarang='$idbarang'");
    $fetch = mysqli_fetch_array($get);

    $namabarang = $fetch['namabarang'];
    $deskripsi = $fetch['deskripsi'];
    $stock = $fetch['stock'];

    $gambar = $fetch['image'];
    if($gambar==null){
        //jika tidak ada gambar 
        $img = 'No Image';
    } else {
        $img = '<img class="card-img-top" src="image/'.$gambar.'" alt="Card image" style="width:100%">';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Barang Inventory</title>
    <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container">
        <h3><strong>Detail Barang</strong></h3>
    <div class="card mt-4" style="width:400px">
        <?php echo $img;?>
    <div class="card-body">
      <h4 class="card-title"><?php echo $namabarang;?></h4>
      <p class="card-text"><strong>Deskripsi Barang : </strong><?php echo $deskripsi;?></p>
      <p class="card-text"><strong>Jumlah Saat Ini : </strong><?php echo $stock;?></p>
    </div>
  </div>
    </div>
    
</body>
</html>