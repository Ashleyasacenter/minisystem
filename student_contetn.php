<?php
// This partial view contains only the main content section of students_view.php without sidebar and full HTML structure
// Define $error, $success, and $students if not set to avoid undefined variable warnings
if (!isset($error)) {
    $error = '';
}
if (!isset($success)) {
    $success = '';
}
if (!isset($students)) {
    $students = [];
}
?>
<div class="head-title">
    <div class="left">
        <h1>Student Management</h1>
        <ul class="breadcrumb">
            <li>
                <a href="dashboard.php">Dashboard</a>
            </li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li>
                <a class="active" href="students.php">Students</a>
            </li>
        </ul>
    </div>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="content-section">
    <button id="addStudentBtn" class="btn">Add New Student</button>

    <!-- Inline Add Student Form (hidden by default) -->
    <div id="addStudentFormContainer" style="display:none; margin-top: 20px;">
        <h2>Add New Student</h2>
        <form action="students.php" method="post">
            <div class="form-group">
                <label for="student_id">Student ID</label>
                <input type="text" id="student_id" name="student_id" required />
            </div>
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" required />
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" required />
            </div>
            <div class="form-group">
                <label for="course">Course</label>
                <input type="text" id="course" name="course" required />
            </div>
            <div class="form-group">
                <label for="year_level">Year Level</label>
                <select id="year_level" name="year_level" required>
                    <option value="1">1st Year</option>
                    <option value="2">2nd Year</option>
                    <option value="3">3rd Year</option>
                    <option value="4">4th Year</option>
                </select>
            </div>
            <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" id="contact_number" name="contact_number" />
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" />
            </div>
            <button type="submit" name="add_student" class="btn">Add Student</button>
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Year Level</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                <tr>
                    <td><?= htmlspecialchars($student['student_id']) ?></td>
                    <td><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></td>
                    <td><?= htmlspecialchars($student['year_level']) ?></td>
                    <td><?= htmlspecialchars($student['email']) ?></td>
                    <td>
                        <a href="edit_student.php?id=<?= urlencode($student['student_id']) ?>" class="btn-edit">Edit</a>
                        <a href="delete_student.php?id=<?= urlencode($student['student_id']) ?>" class="btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="script.js"></script>
<script src="partials/init_scripts.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof initStudentPage === 'function') {
            initStudentPage();
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addStudentBtn = document.getElementById('addStudentBtn');
        const addStudentFormContainer = document.getElementById('addStudentFormContainer');
        if (addStudentBtn && addStudentFormContainer) {
            addStudentBtn.addEventListener('click', function() {
                if (addStudentFormContainer.style.display === 'none' || addStudentFormContainer.style.display === '') {
                    addStudentFormContainer.style.display = 'block';
                } else {
                    addStudentFormContainer.style.display = 'none';
                }
            });
        }
    });
</script>
