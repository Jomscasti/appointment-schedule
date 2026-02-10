<?php include 'components/processes/connect.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .main-card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); background: #ffffff; overflow: hidden; }
        .card-header-custom { background-color: #4e73df; color: white; padding: 20px; }
        .table thead { background-color: #2c3e50; color: white; }
        .table-hover tbody tr:hover { background-color: #f8f9fc; transition: 0.2s; }
        .action-btn { border-radius: 5px; padding: 5px 10px; transition: transform 0.2s; }
        .action-btn:hover { transform: translateY(-2px); }
        .btn-back { text-decoration: none; color: #6c757d; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; transition: color 0.2s; }
        .btn-back:hover { color: #4e73df; }
        /* Make status badge look clickable */
        .status-link { text-decoration: none; cursor: pointer; transition: opacity 0.2s; }
        .status-link:hover { opacity: 0.8; }
    </style>
</head>
<body>

<div class="container mt-5 mb-5">
    <div class="mb-3">
        <a href="index.php" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Customer Page</a>
    </div>

    <div class="card main-card">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <h3 class="mb-0"><i class="fas fa-user-cog me-2"></i>Appointment Dashboard</h3>
            <span class="badge bg-light text-primary">Admin Access</span>
        </div>

        <div class="card-body p-4">
            <?php
            if (isset($_GET['deleteId'])) {
                $deleteId = $_GET['deleteId'];
                executeQuery("DELETE FROM appointments WHERE id = $deleteId");
                echo "<div class='alert alert-success alert-dismissible fade show'><i class='fas fa-check-circle me-2'></i> Appointment deleted successfully.<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
            }
            $appointmentList = executeQuery("SELECT * FROM appointments ORDER BY id DESC");
            ?>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="py-3">Customer Name</th>
                            <th class="py-3">Contact</th>
                            <th class="py-3">Date & Time</th>
                            <th class="py-3 text-center">Status (Click to Edit)</th>
                            <th class="py-3 text-center" width="180px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($appointmentList) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($appointmentList)): ?>
                            <?php 
                                $badgeColor = 'secondary';
                                if($row['status'] == 'In Progress') { $badgeColor = 'primary'; } 
                                elseif ($row['status'] == 'Completed') { $badgeColor = 'success'; }
                            ?>
                            <tr>
                                <td class="fw-bold text-secondary"><?= htmlspecialchars($row['name']) ?></td>
                                <td><span class="badge bg-light text-dark border"><i class="fas fa-phone-alt me-1 text-secondary"></i><?= htmlspecialchars($row['contactNumber']) ?></span></td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold"><?= date("M j, Y", strtotime($row['bookingDate'])) ?></span>
                                        <small class="text-muted"><?= date("g:i A", strtotime($row['bookingDate'])) ?></small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="components/edit.php?appointmentId=<?= $row['id'] ?>" class="status-link" title="Click to update status">
                                        <span class="badge bg-<?= $badgeColor ?> p-2">
                                            <?= htmlspecialchars($row['status']) ?> <i class="fas fa-pencil-alt ms-1" style="font-size: 0.7em;"></i>
                                        </span>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="components/edit.php?appointmentId=<?= $row['id'] ?>" class="btn btn-warning btn-sm action-btn text-white me-1"><i class="fas fa-edit"></i></a>
                                    <button type="button" class="btn btn-danger btn-sm action-btn delete-btn" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $row['id'] ?>"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center py-4 text-muted">No appointments found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Confirm Deletion</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body text-center py-4">Are you sure you want to delete this appointment?</div><div class="modal-footer justify-content-center"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><a href="#" id="confirmDeleteBtn" class="btn btn-danger">Yes, Delete</a></div></div></div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            document.getElementById('confirmDeleteBtn').href = 'admin.php?deleteId=' + id;
        });
    });
</script>
</body>
</html>