<?php
// This partial view contains only the main content section of violation_view.php without sidebar and full HTML structure
?>
<div class="head-title">
    <div class="left">
        <h1>Violation Management</h1>
        <ul class="breadcrumb">
            <li>
                <a href="dashboard.php">Dashboard</a>
            </li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li>
                <a class="active" href="violation.php">Violations</a>
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
    <div class="total-pending-container" style="margin-bottom: 15px; font-weight: bold; font-size: 1.2em;">
        <?= (isset($totalPending) && $totalPending > 0) ? htmlspecialchars($totalPending) : '' ?>
    </div>
    <button id="addViolationBtn" class="btn">Record New Violation</button>

    <!-- Filters -->
    <div class="filters">
        <form action="violation.php" method="post" id="filterCombinedForm" class="filter-form">
            <label for="payment_status">Payment Status:</label>
            <select id="payment_status" name="payment_status">
                <option value="" selected>Pending</option>
                <option value="paid">Resolved</option>
                <option value="unpaid">Escalated</option>
            </select>

            <label for="year_level">Year Level:</label>
            <select id="year_level" name="year_level">
                <option value="" disabled>All</option>
                <option value="1">1st Year</option>
                <option value="2">2nd Year</option>
                <option value="3">3rd Year</option>
                <option value="4">4th Year</option>
            </select>

            <label for="violation_type">Violation Type:</label>
            <select id="violation_type" name="violation_type">
                <option value="" disabled>All</option>
                <?php foreach ($violationTypes as $type): ?>
                    <option value="<?= htmlspecialchars($type) ?>"><?= htmlspecialchars($type) ?></option>
                <?php endforeach; ?>
            </select>
            <button id="addViolationTypeBtn" class="btn small-btn" type="button">Add</button>

            <button type="submit" name="filter_combined" class="btn">Filter</button>
        </form>
    </div>

    <!-- Add Violation Modal -->
    <div id="addViolationModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Record New Violation</h2>
            <form action="violation.php" method="post">
                <div class="form-group">
                    <label for="student_id">Student</label>
                    <select id="student_id" name="student_id" required>
                        <option value="" disabled selected>Select a student</option>
                        <?php foreach ($students as $student): ?>
                            <option value="<?= htmlspecialchars($student['student_id']) ?>">
                                <?= htmlspecialchars($student['last_name'] . ', ' . $student['first_name'] . ' (' . $student['student_id'] . ')') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="violation_type">Violation Type</label>
                    <select id="violation_type" name="violation_type" required>
                        <?php foreach ($violationTypes as $type): ?>
                            <option value="<?= htmlspecialchars($type) ?>"><?= htmlspecialchars($type) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="violation_date">Date</label>
                    <input type="date" id="violation_date" name="violation_date" required value="<?= date('Y-m-d') ?>" />
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="pending" selected>Pending</option>
                        <option value="resolved">Resolved</option>
                        <option value="escalated">Escalated</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4" required></textarea>
                </div>
                <button type="submit" name="add_violation" class="btn">Record Violation</button>
            </form>
        </div>
    </div>

    <!-- Add Violation Type Modal -->
    <div id="addViolationTypeModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add Violation Type</h2>
            <form action="violation.php" method="post" id="addViolationTypeForm">
                <div class="form-group">
                    <label for="violation_type_name">Violation Type Name</label>
                    <input type="text" id="violation_type_name" name="violation_type_name" required />
                </div>
                <button type="submit" name="add_violation_type" class="btn">Add Type</button>
            </form>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Violation Type</th>
                    <th>Date</th>
                    <th>Reported By</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($violations as $violation): ?>
                <tr>
                    <td><?= htmlspecialchars($violation['first_name'] . ' ' . $violation['last_name']) ?></td>
                    <td><?= htmlspecialchars($violation['violation_type']) ?></td>
                    <td><?= date('M d, Y', strtotime($violation['violation_date'])) ?></td>
                    <td><?= htmlspecialchars($violation['reported_by_name']) ?></td>
                    <td><span class="status-badge <?= htmlspecialchars($violation['status']) ?>"><?= ucfirst(htmlspecialchars($violation['status'])) ?></span></td>
                    <td>
                        <a href="#" class="btn-view" onclick="viewViolation(<?= htmlspecialchars($violation['violation_id']) ?>)">View</a>
                        <?php if ($violation['status'] === 'pending'): ?>
                            <a href="#" class="btn-edit" onclick="editViolation(<?= htmlspecialchars($violation['violation_id']) ?>)">Update</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="script_v2.js"></script>
<script src="partials/init_scripts.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof initViolationPage === 'function') {
            initViolationPage();
        }
    });
</script>
