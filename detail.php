<?php
    require 'function.php';
    require 'cek.php';
    $currentPage = basename($_SERVER['PHP_SELF']); 

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
    $img = '<img src="image/'.$gambar.'" class="zoomable">';
}
$urlview = 'https://ftonando.my.id/view.php?id='.$idbarang;
$qrcode = 'https://quickchart.io/qr?text='.$urlview.'&centerImageUrl=https%3A%2F%2Fi.imgur.com%2FNcARCn4.png&centerImageSizeRatio=0.3&size=200';

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Inventaris CYB - Detail Barang</title>
        <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
        <style>
            .zoomable{
                width: 200px;
                height: 200px;
            }
            .zoomable:hover{
                transform: scale(1.2);
                transition: 0.3s ease;
            }
        </style>
        <link href="css/custom-styles.css" rel="stylesheet">
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark">
        <button class="btn btn-link btn-sm" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
        <a class="navbar-brand" href="index.php" style="margin-left: 20px;">Inventaris CYB</a>
            
            <!-- Navbar-->
            
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <a class="nav-link <?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Data Barang
                            </a>
                            <a class="nav-link <?php echo ($currentPage == 'masuk.php') ? 'active' : ''; ?>" href="masuk.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Barang Masuk
                            </a>
                            <a class="nav-link <?php echo ($currentPage == 'keluar.php') ? 'active' : ''; ?>" href="keluar.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Barang Keluar
                            </a>
                            <a class="nav-link <?php echo ($currentPage == 'admin.php') ? 'active' : ''; ?>" href="admin.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-cog"></i></div>
                                Kelola Admin
                            </a>
                            <a class="nav-link" href="logout.php">Logout</a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        <?php echo htmlspecialchars($email); // Tampilkan email pengguna ?>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">Detail Barang</h1>
                        <div class="card mb-4 mx-auto">
                            <div class="card-header text-center">
                                <h2><?php echo $namabarang;?></h2>
                                 
                            </div>
                            <div class="text-center my-3">
                            <?php echo $img;?>
                            <img src="<?php echo $qrcode;?>">
                            </div>
                            <div class="card-body">
                                <div class="flex justify-center items-center min-h-screen">
                                    <div class="detail-container w-full sm:w-96 p-4">
                                        <div class="detail-item mb-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="flex justify-between">
                                                <span class="label font-semibold"><strong>Deskripsi</strong></span>
                                                <span class="value text-gray-600">: <?php echo $deskripsi; ?></span>
                                            </div>
                                        </div>

                                        <div class="detail-item grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="flex justify-between">
                                                <span class="label font-semibold"><strong>Jumlah Sekarang</strong></span>
                                                <span class="value text-gray-600">: <?php echo $stock; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bagian untuk ekspor PDF -->
                                 <br>
                                 <br>
                                    <div class="card bg-light mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title text-center">Export Detail Barang</h5>
                                            <form method="post" action="export_detail_barang.php?id=<?php echo $idbarang;?>">
                                                <div class="form-group">
                                                    <label for="start_date">Tanggal Mulai</label>
                                                    <input type="date" name="start_date" class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="end_date">Tanggal Selesai</label>
                                                    <input type="date" name="end_date" class="form-control" required>
                                                </div>
                                                <button type="submit" class="btn btn-success">Export to PDF</button>
                                            </form>
                                        </div>
                                    </div>



                                <br>
                                <br>
                                <h3>Tabel Barang Masuk</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTableMasuk" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Keterangan</th>
                                                <th>Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $ambildatamasuk = mysqli_query($conn, "SELECT * FROM masuk WHERE idbarang='$idbarang'");
                                                $i = 1;


                                                while($fetch=mysqli_fetch_array($ambildatamasuk)){
                                                    $tanggal = $fetch['tanggal'];
                                                    $keterangan = $fetch['keterangan'];
                                                    $quantity = $fetch['qty'];
                                                
                                            ?>
                                            <tr>
                                                <td><?php echo $i++;?></td>
                                                <td><?php echo $tanggal;?></td>
                                                <td><?php echo $keterangan;?></td>
                                                <td><?php echo $quantity;?></td>
                                            </tr>
                                            <?php
                                            };
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <br>
                                <br>
                                <h3>Tabel Barang Keluar</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTableKeluar" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>penerima</th>
                                                <th>Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $ambildatakeluar = mysqli_query($conn, "SELECT * FROM keluar WHERE idbarang='$idbarang'");
                                                $i = 1;


                                                while($fetch=mysqli_fetch_array($ambildatakeluar)){
                                                    $tanggal = $fetch['tanggal'];
                                                    $penerima = $fetch['penerima'];
                                                    $quantity = $fetch['qty'];
                                                
                                            ?>
                                            <tr>
                                                <td><?php echo $i++;?></td>
                                                <td><?php echo $tanggal;?></td>
                                                <td><?php echo $penerima;?></td>
                                                <td><?php echo $quantity;?></td>
                                            </tr>
                                            <?php
                                            };
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; PT CYB Media Group 2024</div>
                            <div>
                                <img src="assets/img/logo.png" alt="Logo Perusahaan" style="height: 40px;">
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>
        <script>
            $(document).ready(function() {
                $('#dataTableMasuk').DataTable();
                $('#dataTableKeluar').DataTable();
            });
        </script>
    </body>
    <!-- The Modal -->
    <div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Tambah Barang</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <form method="post" enctype="multipart/form-data">
        <div class="modal-body">
        <input type="text" name="namabarang" placeholder="Nama Barang" class="form-control" required>
            <br>
        <input type="text" name="deskripsi" placeholder="Deskripsi Barang" class="form-control" required>
            <br>
        <input type="number" name="stock" class="form-control" placeholder="Jumlah" required>
            <br>
        <input type="file" name="file" class="form-control">
        <br>
        <button type="submit" class="btn btn-primary" name="addnewbarang">Tambah Barang</button>
        </div>
        </form>

        </div>
    </div>
    </div>
</html>
