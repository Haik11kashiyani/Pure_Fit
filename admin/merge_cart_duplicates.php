<?php
// Admin-only utility to merge duplicate cart rows (same user, product, variant) into a single row.
// Usage: Log in as admin and open this page in the browser OR run via CLI: php admin/merge_cart_duplicates.php

include 'auth_check.php';
include '../connection.php';

// Only allow admin users through auth_check; still, keep a "confirm" step to avoid accidental runs.
$confirm = isset($_GET['confirm']) ? (int)$_GET['confirm'] : 0;

if (php_sapi_name() !== 'cli' && $confirm !== 1) {
    echo "<h2>Merge Duplicate Cart Rows</h2>\n";
    echo "<p>This script will merge duplicate rows in the <code>cart</code> table for the same (user_id, product_id, variant_id) by summing quantities and keeping one row.</p>\n";
    echo "<p>To proceed, click: <a href=\"?confirm=1\">Run merge now</a> (admin only)</p>";
    exit;
}

try {
    mysqli_begin_transaction($conn);

    // Find duplicate groups
    $dup_q = "SELECT user_id, product_id, variant_id, GROUP_CONCAT(cart_id ORDER BY cart_id) as ids, COUNT(*) as cnt, SUM(quantity) as total_qty, MIN(cart_id) as keep_id
              FROM cart
              GROUP BY user_id, product_id, variant_id
              HAVING COUNT(*) > 1";

    $dup_res = mysqli_query($conn, $dup_q);

    if (!$dup_res) throw new Exception('Query failed: ' . mysqli_error($conn));

    $merged = 0;
    while ($row = mysqli_fetch_assoc($dup_res)) {
        $ids = explode(',', $row['ids']);
        $keep_id = (int)$row['keep_id'];
        $total_qty = (int)$row['total_qty'];

        // Update the keep row with the summed quantity
        $u = mysqli_prepare($conn, "UPDATE cart SET quantity = ? WHERE cart_id = ?");
        mysqli_stmt_bind_param($u, 'ii', $total_qty, $keep_id);
        mysqli_stmt_execute($u);
        mysqli_stmt_close($u);

        // Delete other rows
        foreach ($ids as $cid) {
            $cid = (int)$cid;
            if ($cid === $keep_id) continue;
            $d = mysqli_prepare($conn, "DELETE FROM cart WHERE cart_id = ?");
            mysqli_stmt_bind_param($d, 'i', $cid);
            mysqli_stmt_execute($d);
            mysqli_stmt_close($d);
        }

        $merged++;
    }

    mysqli_commit($conn);
    echo "<h3>Done</h3>";
    echo "<p>Merged groups: " . $merged . "</p>";
    echo "<p>If you see 0, there were no duplicate groups.</p>";
} catch (Exception $e) {
    mysqli_rollback($conn);
    error_log('Merge cart duplicates error: ' . $e->getMessage());
    echo "<p style=\"color:red\">Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

$conn->close();

if (php_sapi_name() === 'cli') echo "\nFinished.\n";

?>
