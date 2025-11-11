<?php 
include 'layout.php';

// Handle message actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['mark_read'])) {
        $message_id = (int)$_POST['message_id'];
        $query = "UPDATE contact_messages SET is_read = 1 WHERE message_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $message_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_msg = "Message marked as read!";
        }
        mysqli_stmt_close($stmt);
    }
    
    if (isset($_POST['delete_message'])) {
        $message_id = (int)$_POST['message_id'];
        $query = "DELETE FROM contact_messages WHERE message_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $message_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_msg = "Message deleted successfully!";
        }
        mysqli_stmt_close($stmt);
    }
}

// Get filter parameters
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Build query
$query = "SELECT * FROM contact_messages WHERE 1=1";

if ($status_filter == 'unread') {
    $query .= " AND is_read = 0";
} elseif ($status_filter == 'read') {
    $query .= " AND is_read = 1";
}

if ($search) {
    $query .= " AND (name LIKE '%$search%' OR email LIKE '%$search%' OR subject LIKE '%$search%' OR message LIKE '%$search%')";
}

$query .= " ORDER BY created_at DESC";
$messages_result = mysqli_query($conn, $query);

// Get message counts
$total_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM contact_messages");
$total_count = mysqli_fetch_assoc($total_query)['count'];

$unread_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM contact_messages WHERE is_read = 0");
$unread_count = mysqli_fetch_assoc($unread_query)['count'];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-envelope me-2"></i>Contact Messages</h2>
</div>

<?php if (isset($success_msg)): ?>
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle me-2"></i><?php echo $success_msg; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="btn-group" role="group">
                    <a href="manage_messages.php" class="btn btn-<?php echo empty($status_filter) ? 'primary' : 'outline-primary'; ?>">
                        All Messages (<?php echo $total_count; ?>)
                    </a>
                    <a href="manage_messages.php?status=unread" class="btn btn-<?php echo $status_filter == 'unread' ? 'warning' : 'outline-warning'; ?>">
                        Unread (<?php echo $unread_count; ?>)
                    </a>
                    <a href="manage_messages.php?status=read" class="btn btn-<?php echo $status_filter == 'read' ? 'success' : 'outline-success'; ?>">
                        Read (<?php echo $total_count - $unread_count; ?>)
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <form method="GET" class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search messages..." value="<?php echo htmlspecialchars($search); ?>">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Messages List -->
<div class="card">
    <div class="card-body">
        <?php if (mysqli_num_rows($messages_result) > 0): ?>
            <?php while ($msg = mysqli_fetch_assoc($messages_result)): ?>
            <div class="card mb-3 <?php echo !$msg['is_read'] ? 'border-warning' : ''; ?>">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h5 class="mb-1">
                                <?php if (!$msg['is_read']): ?>
                                <span class="badge bg-warning text-dark me-2">NEW</span>
                                <?php endif; ?>
                                <?php echo htmlspecialchars($msg['name']); ?>
                            </h5>
                            <p class="text-muted mb-0">
                                <i class="fas fa-envelope me-2"></i><?php echo htmlspecialchars($msg['email']); ?>
                            </p>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                <?php echo date('d M Y, h:i A', strtotime($msg['created_at'])); ?>
                            </small>
                        </div>
                    </div>
                    
                    <?php if ($msg['subject']): ?>
                    <p class="mb-2"><strong>Subject:</strong> <?php echo htmlspecialchars($msg['subject']); ?></p>
                    <?php endif; ?>
                    
                    <p class="mb-3"><?php echo nl2br(htmlspecialchars($msg['message'])); ?></p>
                    
                    <div class="d-flex gap-2">
                        <?php if (!$msg['is_read']): ?>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="message_id" value="<?php echo $msg['message_id']; ?>">
                            <button type="submit" name="mark_read" class="btn btn-sm btn-success">
                                <i class="fas fa-check me-1"></i>Mark as Read
                            </button>
                        </form>
                        <?php endif; ?>
                        
                        <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-reply me-1"></i>Reply via Email
                        </a>
                        
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $msg['message_id']; ?>">
                            <i class="fas fa-trash me-1"></i>Delete
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Delete Modal -->
            <div class="modal fade" id="deleteModal<?php echo $msg['message_id']; ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Message</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="message_id" value="<?php echo $msg['message_id']; ?>">
                                <p>Are you sure you want to delete this message from <strong><?php echo htmlspecialchars($msg['name']); ?></strong>?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" name="delete_message" class="btn btn-danger">Delete</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="text-center text-muted py-5">
                <i class="fas fa-inbox fa-4x mb-3 d-block"></i>
                <h5>No messages found</h5>
                <p>Contact form messages will appear here</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
