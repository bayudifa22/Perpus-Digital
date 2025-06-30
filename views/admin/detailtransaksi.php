<?php
// Include database connection
include_once 'koneksi.php';

// Get transaction ID from URL parameter
$borrow_id = isset($_GET['id']) ? $_GET['id'] : 0;

// Query to get transaction details
$query = "SELECT b.borrow_id, m.member_id, m.firstname, m.lastname, m.contact, 
          b.date_borrow, b.due_date, b.status
          FROM borrow b
          JOIN member m ON b.member_id = m.member_id
          WHERE b.borrow_id = $borrow_id";

$result = mysqli_query($db, $query);

if (mysqli_num_rows($result) > 0) {
    $transaction = mysqli_fetch_assoc($result);
} else {
    echo '<div class="alert alert-danger">Transaksi tidak ditemukan</div>';
    exit;
}

// Query to get books in this transaction
$query_books = "SELECT bd.borrow_details_id, bd.borrow_status, bd.date_return, 
                bk.book_id, bk.book_title, bk.author, bk.isbn, bk.category
                FROM borrowdetails bd
                JOIN book bk ON bd.book_id = bk.book_id
                WHERE bd.borrow_id = $borrow_id";

$result_books = mysqli_query($db, $query_books);
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Detail Transaksi Peminjaman</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="admin.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="admin.php?p=listtransaksi">Transaksi</a></li>
                    <li class="breadcrumb-item active">Detail Transaksi</li>
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
                        <h3 class="card-title">Detail Transaksi #<?= $transaction['borrow_id']; ?></h3>
                        <div class="card-tools">
                            <a href="admin.php?p=listtransaksi" class="btn btn-default btn-sm">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <?php if ($transaction['status'] == 1): ?>
                            <a href="admin.php?p=returntransaksi&id=<?= $transaction['borrow_id']; ?>" class="btn btn-success btn-sm">
                                <i class="fas fa-undo"></i> Proses Pengembalian
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Informasi Peminjam</h5>
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width: 30%">ID Anggota</th>
                                        <td><?= $transaction['member_id']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Nama</th>
                                        <td><?= $transaction['firstname'] . ' ' . $transaction['lastname']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Kontak</th>
                                        <td><?= $transaction['contact']; ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5>Informasi Transaksi</h5>
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width: 30%">ID Pinjam</th>
                                        <td><?= $transaction['borrow_id']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Pinjam</th>
                                        <td><?= date('d-m-Y', strtotime($transaction['date_borrow'])); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Kembali</th>
                                        <td><?= date('d-m-Y', strtotime($transaction['due_date'])); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <?php if ($transaction['status'] == 1): ?>
                                                <span class="badge badge-warning">Dipinjam</span>
                                            <?php else: ?>
                                                <span class="badge badge-success">Dikembalikan</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <h5 class="mt-4">Daftar Buku yang Dipinjam</h5>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Judul Buku</th>
                                    <th>Pengarang</th>
                                    <th>Kategori</th>
                                    <th>ISBN</th>
                                    <th>Status</th>
                                    <th>Tanggal Kembali</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                while ($book = mysqli_fetch_assoc($result_books)) {
                                    // Determine book status
                                    if ($book['borrow_status'] == 1) {
                                        if ($transaction['status'] == 1) {
                                            $book_status = '<span class="badge badge-warning">Dipinjam</span>';
                                        } else {
                                            $book_status = '<span class="badge badge-success">Dikembalikan</span>';
                                        }
                                    } else {
                                        $book_status = '<span class="badge badge-success">Dikembalikan</span>';
                                    }
                                    
                                    // Format return date
                                    $return_date = $book['date_return'] ? date('d-m-Y', strtotime($book['date_return'])) : '-';
                                ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $book['book_title']; ?></td>
                                    <td><?= $book['author']; ?></td>
                                    <td><?= $book['category']; ?></td>
                                    <td><?= $book['isbn']; ?></td>
                                    <td><?= $book_status; ?></td>
                                    <td><?= $return_date; ?></td>
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