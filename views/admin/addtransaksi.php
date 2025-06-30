<?php
// Include database connection
include_once 'koneksi.php';
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Tambah Transaksi Peminjaman</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="admin.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="admin.php?p=listtransaksi">Transaksi</a></li>
                    <li class="breadcrumb-item active">Tambah Transaksi</li>
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
                        <h3 class="card-title">Form Peminjaman Buku</h3>
                    </div>
                    <form action="action/addtransaksi.php" method="POST">
                        <div class="card-body">
                            <?php if(isset($_GET['pesan'])): ?>
                                <?php if($_GET['pesan'] == 'success'): ?>
                                    <div class="alert alert-success">Berhasil menambahkan transaksi peminjaman.</div>
                                <?php elseif($_GET['pesan'] == 'error'): ?>
                                    <div class="alert alert-danger">Gagal menambahkan transaksi peminjaman.</div>
                                <?php endif; ?>
                            <?php endif; ?>

                            <div class="form-group">
                                <label for="member_id">Peminjam</label>
                                <select name="member_id" id="member_id" class="form-control" required>
                                    <option value="">-- Pilih Anggota --</option>
                                    <?php
                                    $query = "SELECT member_id, firstname, lastname FROM member WHERE status = 1 ORDER BY firstname ASC";
                                    $result = mysqli_query($db, $query);
                                    
                                    while($data = mysqli_fetch_assoc($result)) {
                                        echo '<option value="'.$data['member_id'].'">'.$data['firstname'].' '.$data['lastname'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="date_borrow">Tanggal Peminjaman</label>
                                <input type="date" name="date_borrow" id="date_borrow" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="due_date">Tanggal Pengembalian</label>
                                <input type="date" name="due_date" id="due_date" class="form-control" value="<?= date('Y-m-d', strtotime('+7 days')); ?>" required>
                            </div>
                            
                            <hr>
                            <h4>Daftar Buku yang Dipinjam</h4>
                            
                            <div class="books-container">
                                <div class="row book-item mb-3">
                                    <div class="col-11">
                                        <select name="books[]" class="form-control" required>
                                            <option value="">-- Pilih Buku --</option>
                                            <?php
                                            $query_book = "SELECT book_id, book_title, author FROM book WHERE status = '1' ORDER BY book_title ASC";
                                            $result_book = mysqli_query($db, $query_book);
                                            
                                            while($book = mysqli_fetch_assoc($result_book)) {
                                                echo '<option value="'.$book['book_id'].'">'.$book['book_title'].' - '.$book['author'].'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-1">
                                        <button type="button" class="btn btn-danger remove-book"><i class="fas fa-times"></i></button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <button type="button" class="btn btn-secondary add-book"><i class="fas fa-plus"></i> Tambah Buku</button>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                            <a href="admin.php?p=listtransaksi" class="btn btn-default">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    // Add book button
    $('.add-book').on('click', function() {
        var bookOptions = $('select[name="books[]"]:first').html();
        var newBookItem = `
            <div class="row book-item mb-3">
                <div class="col-11">
                    <select name="books[]" class="form-control" required>
                        ${bookOptions}
                    </select>
                </div>
                <div class="col-1">
                    <button type="button" class="btn btn-danger remove-book"><i class="fas fa-times"></i></button>
                </div>
            </div>
        `;
        
        $('.books-container').append(newBookItem);
    });
    
    // Remove book button (using event delegation)
    $(document).on('click', '.remove-book', function() {
        if ($('.book-item').length > 1) {
            $(this).closest('.book-item').remove();
        } else {
            alert('Minimal satu buku harus dipilih');
        }
    });
});
</script> 