<?php include 'components/processes/connect.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


</head>

<body>

    <div class="main-card">

        <div class="card-header-custom">
            <h3 class="mb-0"><i class="fas fa-calendar-plus me-2"></i>Book Appointment</h3>
            <p class="mb-0 opacity-75 small mt-1">Please fill in your details below</p>
        </div>

        <div class="card-body p-4">

            <?php
            if (isset($_POST['submitAppointment'])) {

                $customerName = $_POST['customerName'];
                $contactNumber = $_POST['contactNumber'];
                $bookingDateTime = $_POST['bookingDateTime'];

                $errors = array();

                // Validate Number
                if (!ctype_digit($contactNumber)) {
                    $errors[] = "Contact number must contain only digits (0-9).";
                } elseif (strlen($contactNumber) < 10 || strlen($contactNumber) > 15) {
                    $errors[] = "Contact number must be between 10 and 15 digits long.";
                }

                // Validate Date
                $minAllowedDate = strtotime('+2 days');
                $inputDate = strtotime($bookingDateTime);

                if ($inputDate < $minAllowedDate) {
                    $errors[] = "Bookings must be made at least 2 days in advance.";
                }

                // Process Insert
                if (empty($errors)) {
                    $query = "INSERT INTO appointments (name, contactNumber, bookingDate)
                              VALUES ('$customerName', '$contactNumber', '$bookingDateTime')";

                    if (executeQuery($query)) {
                        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                <i class='fas fa-check-circle me-2'></i> Appointment booked successfully!
                                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                              </div>";
                    } else {
                        echo "<div class='alert alert-danger'>
                                <i class='fas fa-exclamation-circle me-2'></i> Database Error. Failed to add appointment.
                              </div>";
                    }
                } else {
                    foreach ($errors as $error) {
                        echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                                <i class='fas fa-exclamation-triangle me-2'></i> $error
                                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                              </div>";
                    }
                }
            }
            ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-user me-2 text-primary"></i>Full Name</label>
                    <input type="text" name="customerName" required class="form-control" placeholder="John Doe">
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-phone me-2 text-primary"></i>Contact Number</label>
                    <input type="text" name="contactNumber" required class="form-control"
                        placeholder="Enter 10-15 digits" minlength="10" maxlength="15"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>

                <div class="mb-4">
                    <label class="form-label"><i class="fas fa-clock me-2 text-primary"></i>Date & Time</label>
                    <input type="datetime-local" name="bookingDateTime" required class="form-control"
                        min="<?= date('Y-m-d\TH:i', strtotime('+2 days')); ?>">
                    <div class="form-text text-muted small">
                        <i class="fas fa-info-circle me-1"></i> Bookings must be 2 days in advance.
                    </div>
                </div>

                <button name="submitAppointment" class="btn btn-primary-custom shadow-sm">
                    Confirm Appointment
                </button>
            </form>

            <a href="admin.php" class="admin-link">
                <i class="fas fa-lock me-1"></i> Go to Admin Dashboard
            </a>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>