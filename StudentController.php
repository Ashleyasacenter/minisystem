<?php
require_once 'models/StudentModel.php';

class StudentController {
    private $studentModel;
    public $error = '';
    public $success = '';

    public function __construct($pdo) {
        $this->studentModel = new StudentModel($pdo);
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add_student'])) {
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    // Handle AJAX request
                    $response = $this->addStudent(true);
                    header('Content-Type: application/json');
                    echo json_encode($response);
                    exit;
                } else {
                    // Handle normal POST request
                    $this->addStudent();
                }
            } elseif (isset($_POST['update_student'])) {
                $this->updateStudent();
            }
        }
    }

    public function addStudent($returnResponse = false) {
        $student_id = trim($_POST['student_id'] ?? '');
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $course = trim($_POST['course'] ?? '');
        $year_level = trim($_POST['year_level'] ?? '');
        $contact_number = trim($_POST['contact_number'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if (empty($student_id) || empty($first_name) || empty($last_name) || empty($course) || empty($year_level)) {
            error_log("AddStudent validation failed: Required fields missing. Data: " . json_encode($_POST));
            if ($returnResponse) {
                return ['success' => false, 'error' => 'Required fields are missing'];
            }
            $this->error = 'Required fields are missing';
            header('Location: students.php');
            exit;
        } else {
            try {
                if ($this->studentModel->addStudent($student_id, $first_name, $last_name, $course, $year_level, $contact_number, $email)) {
                    if ($returnResponse) {
                        return ['success' => true, 'message' => 'Student added successfully'];
                    }
                    $this->success = 'Student added successfully';
                    header('Location: students.php');
                    exit;
                } else {
                    error_log("AddStudent failed: Model addStudent returned false. Data: " . json_encode($_POST));
                    if ($returnResponse) {
                        return ['success' => false, 'error' => 'Failed to add student'];
                    }
                    $this->error = 'Failed to add student';
                    header('Location: students.php');
                    exit;
                }
            } catch (Exception $e) {
                error_log("AddStudent exception: " . $e->getMessage() . ". Data: " . json_encode($_POST));
                if ($returnResponse) {
                    return ['success' => false, 'error' => 'Exception occurred: ' . $e->getMessage()];
                }
                $this->error = 'Exception occurred: ' . $e->getMessage();
                header('Location: students.php');
                exit;
            }
        }
    }

    public function updateStudent() {
        $student_id = trim($_POST['student_id'] ?? '');
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $course = trim($_POST['course'] ?? '');
        $year_level = trim($_POST['year_level'] ?? '');
        $contact_number = trim($_POST['contact_number'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if (empty($student_id) || empty($first_name) || empty($last_name) || empty($course) || empty($year_level)) {
            $this->error = 'Required fields are missing';
            return;
        }

        if ($this->studentModel->updateStudent($student_id, $first_name, $last_name, $course, $year_level, $contact_number, $email)) {
            $this->success = 'Student updated successfully';
        } else {
            $this->error = 'Failed to update student';
        }
    }

    public function getStudents() {
        return $this->studentModel->getStudents();
    }
}
?>
