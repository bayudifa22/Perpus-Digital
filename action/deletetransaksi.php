<?php
// Start session
session_start();

// Include database connection
include "../koneksi.php";

// Check if ID is provided
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $borrow_id = $_GET['id'];
    
    // Begin transaction
    mysqli_begin_transaction($db);
    
    try {
        // First delete records from borrowdetails
        $query_details = "DELETE FROM borrowdetails WHERE borrow_id = ?";
        $stmt_details = mysqli_prepare($db, $query_details);
        mysqli_stmt_bind_param($stmt_details, "i", $borrow_id);
        $result_details = mysqli_stmt_execute($stmt_details);
        
        if (!$result_details) {
            throw new Exception("Failed to delete borrow details");
        }
        
        mysqli_stmt_close($stmt_details);
        
        // Then delete the borrow record
        $query_borrow = "DELETE FROM borrow WHERE borrow_id = ?";
        $stmt_borrow = mysqli_prepare($db, $query_borrow);
        mysqli_stmt_bind_param($stmt_borrow, "i", $borrow_id);
        $result_borrow = mysqli_stmt_execute($stmt_borrow);
        
        if (!$result_borrow) {
            throw new Exception("Failed to delete borrow record");
        }
        
        mysqli_stmt_close($stmt_borrow);
        
        // Commit transaction
        mysqli_commit($db);
        
        // Redirect to transaction list with success message
        header("Location: ../admin.php?p=listtransaksi&pesan=deleted");
        exit();
        
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($db);
        
        // Log the error
        error_log("Error in deletetransaksi.php: " . $e->getMessage());
        
        // Redirect with error message
        header("Location: ../admin.php?p=listtransaksi&pesan=error");
        exit();
    }
    
    mysqli_close($db);
    
} else {
    // If no ID provided, redirect to transaction list
    header("Location: ../admin.php?p=listtransaksi");
    exit();
}
?> 