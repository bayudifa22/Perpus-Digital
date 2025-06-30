<?php
// Start session
session_start();

// Include database connection
include "../koneksi.php";

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Get form data
    $member_id = $_POST['member_id'];
    $date_borrow = $_POST['date_borrow'];
    $due_date = $_POST['due_date'];
    $books = isset($_POST['books']) ? $_POST['books'] : [];
    
    // Validate data
    if (empty($member_id) || empty($date_borrow) || empty($due_date) || empty($books)) {
        header("Location: ../admin.php?p=addtransaksi&pesan=error");
        exit();
    }
    
    // Begin transaction
    mysqli_begin_transaction($db);
    
    try {
        // Insert into borrow table
        $query = "INSERT INTO borrow (member_id, date_borrow, due_date, status) VALUES (?, ?, ?, 1)";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "iss", $member_id, $date_borrow, $due_date);
        $result = mysqli_stmt_execute($stmt);
        
        if (!$result) {
            throw new Exception("Failed to insert borrow data");
        }
        
        // Get the borrow_id
        $borrow_id = mysqli_insert_id($db);
        
        // Insert book details
        foreach ($books as $book_id) {
            $query_detail = "INSERT INTO borrowdetails (book_id, borrow_id, borrow_status) VALUES (?, ?, 1)";
            $stmt_detail = mysqli_prepare($db, $query_detail);
            mysqli_stmt_bind_param($stmt_detail, "ii", $book_id, $borrow_id);
            $result_detail = mysqli_stmt_execute($stmt_detail);
            
            if (!$result_detail) {
                throw new Exception("Failed to insert borrow details for book ID: " . $book_id);
            }
            
            mysqli_stmt_close($stmt_detail);
        }
        
        // Commit transaction
        mysqli_commit($db);
        
        // Redirect to transaction list with success message
        header("Location: ../admin.php?p=listtransaksi&pesan=success");
        exit();
        
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($db);
        
        // Log the error
        error_log("Error in addtransaksi.php: " . $e->getMessage());
        
        // Redirect with error message
        header("Location: ../admin.php?p=addtransaksi&pesan=error");
        exit();
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($db);
    
} else {
    // If not submitted via form, redirect to add transaction page
    header("Location: ../admin.php?p=addtransaksi");
    exit();
}
?> 