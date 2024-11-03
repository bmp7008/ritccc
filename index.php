<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RIT Mandarin Conversation Club</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">RIT Mandarin Chinese Conversation Club</h1>
        <p class="text-center">Join our mailing list and share your suggestions!</p>

        <?php
        $currentDate = new DateTime();
        $dateFormatted = $currentDate->format('F j, Y');
        $attendanceFile = 'protected/attendance/attendance_' . $currentDate->format('Ymd') . '.txt';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['topic'])) {
                // Handling topic suggestion
                $topic = trim($_POST['topic']);
                file_put_contents('protected/suggestions.txt', $topic . PHP_EOL, FILE_APPEND);
                echo '<div class="alert alert-success">Suggestion submitted!</div>';
            }
            // Handling email subscription
            if (isset($_POST['email'])) {
                $email = trim($_POST['email']);
                $emailsFile = 'protected/emails.txt';

                // Check email format and spaces
                if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strpos($email, ' ') !== false) {
                    echo '<div class="alert alert-danger">Please enter a valid email address without spaces.</div>';
                } else {
                    // Convert email to lowercase for case-insensitive comparison
                    $existingEmails = array_map('strtolower', file($emailsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
                    
                    if (in_array(strtolower($email), $existingEmails)) {
                        echo '<div class="alert alert-warning">You are already on the mailing list!</div>';
                    } else {
                        file_put_contents($emailsFile, $email . PHP_EOL, FILE_APPEND);
                        echo '<div class="alert alert-success">You have joined the mailing list!</div>';
                    }
                }
            }

            // Handling attendance entry
            if (isset($_POST['attendance_name'])) {
                $attendanceName = trim($_POST['attendance_name']);

                // Check if name already exists in today's attendance file
                if (file_exists($attendanceFile)) {
                    $existingAttendance = array_map('strtolower', file($attendanceFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
                } else {
                    $existingAttendance = [];
                }

                if (in_array(strtolower($attendanceName), $existingAttendance)) {
                    echo '<div class="alert alert-warning">You have already recorded your attendance!</div>';
                } else {
                    file_put_contents($attendanceFile, $attendanceName . PHP_EOL, FILE_APPEND);
                    echo '<div class="alert alert-success">Attendance recorded!</div>';
                }
            }
        }

        // Check if current day is Saturday
        $isSaturday = $currentDate->format('N') == 6; // 6 = Saturday
        ?>

        <?php if ($isSaturday): ?>
        <div class="card mt-4">
            <div class="card-header">
                <h2>Enter your name for attendance on <?php echo $dateFormatted; ?></h2>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <div class="form-group">
                        <label for="attendance_name">Name</label>
                        <input type="text" class="form-control" id="attendance_name" name="attendance_name" placeholder="Enter your name" required>
                    </div>
                    <button type="submit" class="btn btn-info">Record Attendance</button>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <div class="card mt-4">
            <div class="card-header">
                <h2>Join Our Mailing List</h2>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <button type="submit" class="btn btn-success">Join</button>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h2>Suggest a Topic</h2>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <div class="form-group">
                        <label for="topic">Suggestion Topic</label>
                        <input type="text" class="form-control" id="topic" name="topic" placeholder="Enter your suggestion" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>