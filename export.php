<?php
    require 'function.php';
    require 'cek.php';
?>
<html>
<head>
  <title>Export Data Barang</title>
  <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
</head>

<body>
<div class="container">
  <h2>Export Data Barang</h2>
  <h4>(Inventory)</h4>
  <div class="data-tables datatable-dark">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Barang</th>
          <th>Deskripsi</th>
          <th>Jumlah</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $i = 1;
        $ambildatastock = mysqli_query($conn, "SELECT * FROM stock");
        while ($data = mysqli_fetch_array($ambildatastock)) {
          $namabarang = $data['namabarang'];
          $deskripsi = $data['deskripsi'];
          $stock = $data['stock'];
          $idb = $data['idbarang'];
        ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo $namabarang; ?></td>
            <td><?php echo $deskripsi; ?></td>
            <td><?php echo $stock; ?></td>
          </tr>
        <?php }; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
$(document).ready(function() {
    var currentDate = new Date();
    var formattedDate = currentDate.toLocaleString(); // Format tanggal saat ini

    $('#dataTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                text: 'Copy',
                footer: true,
                title: 'Export Data Barang - ' + formattedDate
            },
            {
                extend: 'csv',
                text: 'CSV',
                footer: true,
                title: 'Export Data Barang - ' + formattedDate
            },
            {
                extend: 'excel',
                text: 'Excel',
                footer: true,
                title: 'Export Data Barang - ' + formattedDate
            },
            {
                extend: 'pdf',
                text: 'PDF',
                footer: true,
                title: 'Export Data Barang - ' + formattedDate,
                customize: function(doc) {
                    doc.content[1].table.widths = ['*', '*', '*', '*'];
                    doc.content.unshift({
                        text: 'Export Data Barang - ' + formattedDate,
                        alignment: 'center',
                        fontSize: 16,
                        margin: [0, 0, 0, 20]
                    });
                }
            },
            {
                extend: 'print',
                text: 'Print',
                customize: function(win) {
                    var date = new Date();
                    var formattedDate = date.toLocaleString(); // Format tanggal saat ini
                    $(win.document.body)
                        .find('h1').after('<p><strong>Date Printed: </strong>' + formattedDate + '</p>');
                    $(win.document.body).find('table').addClass('table-bordered');
                }
            }
        ]
    });
});
</script>

<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>

</body>
</html>
