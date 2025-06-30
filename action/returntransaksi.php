<?php
// Start session
session_start();

// Include database connection
include "../koneksi.php";

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Get form data
    $borrow_id = $_POST['borrow_id'];
    $date_return = $_POST['date_return'];
    $notes = isset($_POST['notes']) ? $_POST['notes'] : '';
    $book_ids = isset($_POST['book_ids']) ? $_POST['book_ids'] : [];
    $borrow_details_ids = isset($_POST['borrow_details_ids']) ? $_POST['borrow_details_ids'] : [];
    
    // Validate data
    if (empty($borrow_id) || empty($date_return) || empty($book_ids) || count($book_ids) != count($borrow_details_ids)) {
        header("Location: ../admin.php?p=returntransaksi&id=$borrow_id&pesan=error");
        exit();
    }
    
    // Begin transaction
    mysqli_begin_transaction($db);
    
    try {
        // Update each selected book as returned
        for ($i = 0; $i < count($book_ids); $i++) {
            $book_id = $book_ids[$i];
            $borrow_details_id = $borrow_details_ids[$i];
            
            $query_update = "UPDATE borrowdetails SET borrow_status = 0, date_return = ? WHERE borrow_details_id = ?";
            $stmt_update = mysqli_prepare($db, $query_update);
            mysqli_stmt_bind_param($stmt_update, "si", $date_return, $borrow_details_id);
            $result_update = mysqli_stmt_execute($stmt_update);
            
            if (!$result_update) {
                throw new Exception("Failed to update borrow details for book ID: " . $book_id);
            }
            
            mysqli_stmt_close($stmt_update);
        }
        
        // Check if all books in this transaction are returned
        $query_check = "SELECT COUNT(*) as unreturned FROM borrowdetails WHERE borrow_id = ? AND borrow_status = 1";
        $stmt_check = mysqli_prepare($db, $query_check);
        mysqli_stmt_bind_param($stmt_check, "i", $borrow_id);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);
        $row = mysqli_fetch_assoc($result_check);
        
        // If all books are returned, update the borrow status as completed
        if ($row['unreturned'] == 0) {
            $query_borrow = "UPDATE borrow SET status = 0 WHERE borrow_id = ?";
            $stmt_borrow = mysqli_prepare($db, $query_borrow);
            mysqli_stmt_bind_param($stmt_borrow, "i", $borrow_id);
            $result_borrow = mysqli_stmt_execute($stmt_borrow);
            
            if (!$result_borrow) {
                throw new Exception("Failed to update borrow status");
            }
            
            mysqli_stmt_close($stmt_borrow);
        }
        
        mysqli_stmt_close($stmt_check);
        
        // Commit transaction
        mysqli_commit($db);
        
        // Redirect to transaction list with success message
        header("Location: ../admin.php?p=listtransaksi&pesan=success");
        exit();
        
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($db);
        
        // Log the error
        error_log("Error in returntransaksi.php: " . $e->getMessage());
        
        // Redirect with error message
        header("Location: ../admin.php?p=returntransaksi&id=$borrow_id&pesan=error");
        exit();
    }
    
    mysqli_close($db);
    
} else {
    // If not submitted via form, redirect to transaction list
    header("Location: ../admin.php?p=listtransaksi");
    exit();
}
?> 