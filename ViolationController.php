<?php
require_once 'models/ViolationModel.php';
require_once 'models/StudentModel.php';

class ViolationController {
    public $error = '';
    public $success = '';
    public $violations = [];
    public $students = [];
    public $absences = [];
    public $totalPending = 0;

    private $violationModel;
    private $studentModel;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->violationModel = new ViolationModel($pdo);
        $this->studentModel = new StudentModel($pdo);
        $this->students = $this->studentModel->getStudents();
        $this->violations = $this->violationModel->getViolations();
        $this->totalPending = $this->violationModel->getTotalPendingViolations();
    }

    public function getViolationTypes() {
        return $this->violationModel->getViolationTypes();
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add_violation'])) {
                $this->addViolation();
            } elseif (isset($_POST['update_status'])) {
                $this->updateViolationStatus();
            } elseif (isset($_POST['filter_combined'])) {
                $this->filterByCombinedFilters();
            } elseif (isset($_POST['issue_ticket'])) {
                $this->issueTicket();
            } elseif (isset($_POST['add_violation_type'])) {
                $this->addViolationType();
            }
        }
    }

    private function filterByCombinedFilters() {
        $year = $_POST['year_level'] ?? null;
        $violationType = $_POST['violation_type'] ?? null;
        $this->violations = $this->violationModel->getViolationsByFilters($year, $violationType);
    }

    private function issueTicket() {
        // Currently not implemented. Disable or implement as needed.
        $this->error = 'Issue ticket feature is not implemented yet.';
    }

    private function addViolation() {
        $student_id = $_POST['student_id'] ?? null;
        $violation_type = $_POST['violation_type'] ?? '';
        $violation_date = $_POST['violation_date'] ?? '';
        $description = $_POST['description'] ?? '';
        $status = $_POST['status'] ?? 'pending';
        $reported_by = $_SESSION['user_id'] ?? null;

        if (!$student_id || !$violation_type || !$violation_date || !$status) {
            $this->error = 'Please fill in all required fields.';
            return;
        }

        $success = $this->violationModel->addViolation($student_id, $violation_type, $violation_date, $description, $status, $reported_by);
        if ($success) {
            $this->success = 'Violation added successfully.';
            $this->violations = $this->violationModel->getViolations();
        } else {
            $this->error = 'Failed to add violation.';
        }
    }

    private function updateViolationStatus() {
        $violation_id = $_POST['violation_id'] ?? null;
        $status = $_POST['status'] ?? '';
        $resolution_details = $_POST['resolution_details'] ?? null;

        if (!$violation_id || !$status) {
            $this->error = 'Invalid request.';
            return;
        }

        $success = $this->violationModel->updateViolationStatus($violation_id, $status, $resolution_details);
        if ($success) {
            $this->success = 'Violation status updated successfully.';
            $this->violations = $this->violationModel->getViolations();
        } else {
            $this->error = 'Failed to update violation status.';
        }
    }

    private function addViolationType() {
        $type_name = trim($_POST['violation_type_name'] ?? '');
        if (empty($type_name)) {
            $this->error = 'Violation type name cannot be empty.';
            return;
        }
        $success = $this->violationModel->addViolationType($type_name);
        if ($success) {
            $this->success = 'Violation type added successfully.';
        } else {
            $this->error = 'Failed to add violation type. It may already exist.';
        }
    }

    public function getViolations() {
        return $this->violations;
    }
}
?>
