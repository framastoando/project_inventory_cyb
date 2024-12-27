<?php
    require 'function.php';
    require 'cek.php';
    $currentPage = basename($_SERVER['PHP_SELF']); 
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard - Inventaris CYB</title>
        <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="stylesheet" href="css\custom-styles.css">
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/2a67515660.js" crossorigin="anonymous"></script>
        <style>
            .zoomable{
                width: 100px;
            }
            .zoomable:hover{
                transform: scale(1.8);
                transition: 0.3s ease;
            }
        </style>
        <link href="css/custom-styles.css" rel="stylesheet">
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark">
        <button class="btn btn-link btn-sm " id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
        <a class="navbar-brand" href="index.php" style="margin-left: 20px;">Inventaris CYB</a>
            
            <!-- Navbar-->
            
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <a class="nav-link <?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-boxes-stacked" style="color:rgb(111, 114, 120);"></i></i></div>
                                Data Barang
                            </a>
                            <a class="nav-link <?php echo ($currentPage == 'masuk.php') ? 'active' : ''; ?>" href="masuk.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-arrow-right-to-bracket fa-flip-horizontal" style="color:rgb(111, 114, 120);"></i></i></div>
                                Barang Masuk
                            </a>
                            <a class="nav-link <?php echo ($currentPage == 'keluar.php') ? 'active' : ''; ?>" href="keluar.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-arrow-right-from-bracket" style="color:rgb(111, 114, 120);"></i></div>
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
                        <h1 class="mt-4">Data Inventaris Barang</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Data Inventaris Barang</li>
                        </ol> 
                        <div class="card mb-4">
                            <div class="card-header">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                    Tambah Barang
                                </button>
                                <a href="export.php" class="btn btn-info">Export Data</a>
                            </div>
                            <div class="card-body">

                                <?php
                                    $ambildatastock = mysqli_query($conn, "SELECT * FROM stock WHERE stock < 1");

                                    while($fetch=mysqli_fetch_array($ambildatastock)){
                                        $barang = $fetch['namabarang'];
                                    
                                ?>
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong>Peringatan!</strong> Jumlah Barang <strong><?php echo $barang; ?></strong> saat ini kosong.
                                </div>

                                <?php
                                    };
                                ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Gambar</th>
                                                <th>Nama Barang</th>
                                                <th>Deskripsi</th>
                                                <th>Jumlah</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            $ambildatastock = mysqli_query($conn,"SELECT * FROM stock");
                                            while($data=mysqli_fetch_array($ambildatastock)){
                                                $namabarang = $data['namabarang'];
                                                $deskripsi = $data['deskripsi'];
                                                $stock = $data['stock'];
                                                $idb = $data['idbarang'];
                                                

                                                // cek apakah ada gambar pada table
                                                $gambar = $data['image'];
                                                if($gambar==null){
                                                    //jika tidak ada gambar 
                                                    $img = 'No Image';
                                                } else {
                                                    $img = '<img src="image/'.$gambar.'" class="zoomable">';
                                                }
                                            ?>
                                            <tr>
                                                <td><?php echo $i++;?></td>
                                                <td><?php echo $img;?></td>
                                                <td><strong><a href="detail.php?id=<?=$idb;?>"><?php echo $namabarang;?></a></strong></td>
                                                <td><?php echo $deskripsi;?></td>
                                                <td><?php echo $stock;?></td>
                                                <td>
                                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit<?php echo $idb;?>">
                                                        Edit
                                                    </button>
                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?php echo $idb;?>">
                                                        Hapus
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- edit modal -->
                                            <div class="modal" id="edit<?php echo $idb;?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Edit Barang</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <form method="post" enctype="multipart/form-data">
                                                        <div class="modal-body">
                                                            <input type="text" name="namabarang" value="<?php echo $namabarang;?>" class="form-control" required>
                                                            <br>
                                                            <input type="text" name="deskripsi" value="<?php echo $deskripsi;?>" class="form-control" required>
                                                            <br>
                                                            <!-- Perbaiki hidden input -->
                                                             <input type="file" name="file" class="form-control">
                                                            <br>
                                                            <input type="hidden" name="idb" value="<?php echo $idb;?>">
                                                            <button type="submit" class="btn btn-primary" name="updatebarang">Submit</button>
                                                        </div>
                                                    </form>


                                                    </div>
                                                </div>
                                            </div>

                                            <!-- hapus modal -->
                                            <div class="modal" id="delete<?php echo $idb;?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Hapus Barang</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <form method="post">
                                                    <div class="modal-body">
                                                    Apakah yakin ingin menghapus <strong><?php echo $namabarang;?>?</strong>
                                                    <input type="hidden" name="idb" value="<?php echo $idb;?>">
                                                        <br>
                                                        <br>
                                                    <button type="submit" class="btn btn-danger" name="hapusbarang">Hapus</button>
                                                    </div>
                                                    </form>

                                                    </div>
                                                </div>
                                            </div>



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
            document.addEventListener('DOMContentLoaded', function () {
            const barcodeInput = document.getElementById('barcodeInput');

            // Fokus dan auto select saat modal terbuka
            $('#myModal').on('shown.bs.modal', function () {
                barcodeInput.focus();
                barcodeInput.select();
            });
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
            <div class="input-container">
                <input type="text" id="barcodeInput" name="barcode" class="form-control" placeholder="Kode Barcode" required>
                <i class="fa-solid fa-barcode"></i>
            </div>
            <br>
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
