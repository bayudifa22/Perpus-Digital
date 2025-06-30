<?php
// Include database connection
include_once 'koneksi.php';
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Data Transaksi Peminjaman</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="admin.php">Home</a></li>
                    <li class="breadcrumb-item active">Transaksi</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Transaksi Peminjaman Buku</h3>
                        <div class="card-tools">
                            <a href="admin.php?p=addtransaksi" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah Transaksi Baru
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>ID Pinjam</th>
                                    <th>Nama Peminjam</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Jumlah Buku</th>
                                    <th>Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Query to get all transactions with member information and book count
                                $query = "SELECT b.borrow_id, m.firstname, m.lastname, b.date_borrow, b.due_date, b.status,
                                        (SELECT COUNT(*) FROM borrowdetails bd WHERE bd.borrow_id = b.borrow_id) as book_count
                                        FROM borrow b
                                        JOIN member m ON b.member_id = m.member_id
                                        ORDER BY b.borrow_id DESC";
                                
                                $result = mysqli_query($db, $query);
                                
                                if (!$result) {
                                    die("Query Error: " . mysqli_error($db));
                                }
                                
                                $no = 1;
                                while ($data = mysqli_fetch_assoc($result)) {
                                    // Determine status text and color
                                    if ($data['status'] == 1) {
                                        $status = '<span class="badge badge-warning">Dipinjam</span>';
                                    } else {
                                        $status = '<span class="badge badge-success">Dikembalikan</span>';
                                    }
                                    
                                    // Format dates
                                    $date_borrow = date('d-m-Y', strtotime($data['date_borrow']));
                                    $due_date = date('d-m-Y', strtotime($data['due_date']));
                                ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $data['borrow_id']; ?></td>
                                    <td><?= $data['firstname'] . ' ' . $data['lastname']; ?></td>
                                    <td><?= $date_borrow; ?></td>
                                    <td><?= $due_date; ?></td>
                                    <td><?= $data['book_count']; ?> buku</td>
                                    <td><?= $status; ?></td>
                                    <td>
                                        <a href="admin.php?p=detailtransaksi&id=<?= $data['borrow_id']; ?>" class="btn btn-info btn-sm" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if ($data['status'] == 1): ?>
                                        <a href="admin.php?p=returntransaksi&id=<?= $data['borrow_id']; ?>" class="btn btn-success btn-sm" title="Pengembalian">
                                            <i class="fas fa-undo"></i>
                                        </a>
                                        <?php endif; ?>
                                        <a href="action/deletetransaksi.php?id=<?= $data['borrow_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> 