<?php
// Include database connection
include_once 'koneksi.php';

// Get transaction ID from URL parameter
$borrow_id = isset($_GET['id']) ? $_GET['id'] : 0;

// Query to get transaction details
$query = "SELECT b.borrow_id, m.member_id, m.firstname, m.lastname, 
          b.date_borrow, b.due_date, b.status
          FROM borrow b
          JOIN member m ON b.member_id = m.member_id
          WHERE b.borrow_id = $borrow_id";

$result = mysqli_query($db, $query);

if (mysqli_num_rows($result) > 0) {
    $transaction = mysqli_fetch_assoc($result);
    
    // Check if transaction is already returned
    if ($transaction['status'] == 0) {
        echo '<div class="alert alert-info">Transaksi ini sudah dikembalikan.</div>';
        echo '<a href="admin.php?p=listtransaksi" class="btn btn-primary">Kembali ke Daftar Transaksi</a>';
        exit;
    }
} else {
    echo '<div class="alert alert-danger">Transaksi tidak ditemukan</div>';
    echo '<a href="admin.php?p=listtransaksi" class="btn btn-primary">Kembali ke Daftar Transaksi</a>';
    exit;
}

// Query to get books in this transaction
$query_books = "SELECT bd.borrow_details_id, bd.borrow_status, bd.date_return, 
                bk.book_id, bk.book_title, bk.author
                FROM borrowdetails bd
                JOIN book bk ON bd.book_id = bk.book_id
                WHERE bd.borrow_id = $borrow_id";

$result_books = mysqli_query($db, $query_books);
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Proses Pengembalian Buku</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="admin.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="admin.php?p=listtransaksi">Transaksi</a></li>
                    <li class="breadcrumb-item active">Pengembalian</li>
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
                        <h3 class="card-title">Pengembalian Buku untuk Transaksi #<?= $transaction['borrow_id']; ?></h3>
                        <div class="card-tools">
                            <a href="admin.php?p=listtransaksi" class="btn btn-default btn-sm">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <form action="action/returntransaksi.php" method="POST">
                        <input type="hidden" name="borrow_id" value="<?= $borrow_id; ?>">
                        
                        <div class="card-body">
                            <?php if(isset($_GET['pesan'])): ?>
                                <?php if($_GET['pesan'] == 'success'): ?>
                                    <div class="alert alert-success">Berhasil memproses pengembalian buku.</div>
                                <?php elseif($_GET['pesan'] == 'error'): ?>
                                    <div class="alert alert-danger">Gagal memproses pengembalian buku.</div>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Informasi Peminjam</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 30%">Nama</th>
                                            <td><?= $transaction['firstname'] . ' ' . $transaction['lastname']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Pinjam</th>
                                            <td><?= date('d-m-Y', strtotime($transaction['date_borrow'])); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Jatuh Tempo</th>
                                            <td><?= date('d-m-Y', strtotime($transaction['due_date'])); ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h5>Informasi Pengembalian</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 30%">Tanggal Kembali</th>
                                            <td>
                                                <input type="date" name="date_return" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Catatan</th>
                                            <td>
                                                <textarea name="notes" class="form-control" rows="3" placeholder="Catatan pengembalian (opsional)"></textarea>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <h5 class="mt-4">Daftar Buku yang Dikembalikan</h5>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Judul Buku</th>
                                        <th>Pengarang</th>
                                        <th>Status</th>
                                        <th width="15%">Kembalikan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    while ($book = mysqli_fetch_assoc($result_books)) {
                                        // Skip books that are already returned
                                        if ($book['borrow_status'] == 0) {
                                            continue;
                                        }
                                    ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $book['book_title']; ?></td>
                                        <td><?= $book['author']; ?></td>
                                        <td><span class="badge badge-warning">Dipinjam</span></td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="book_ids[]" value="<?= $book['book_id']; ?>" checked>
                                                <input type="hidden" name="borrow_details_ids[]" value="<?= $book['borrow_details_id']; ?>">
                                                <label class="form-check-label">Kembalikan</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="card-footer">
                            <button type="submit" name="submit" class="btn btn-success">Proses Pengembalian</button>
                            <a href="admin.php?p=listtransaksi" class="btn btn-default">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section> 