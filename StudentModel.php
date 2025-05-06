<?php
class StudentModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getStudents() {
        $stmt = $this->pdo->query("SELECT * FROM students ORDER BY last_name, first_name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addStudent($student_id, $first_name, $last_name, $course, $year_level, $contact_number = null, $email = null) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO students (student_id, first_name, last_name, course, year_level, contact_number, email) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?)");
            return $stmt->execute([$student_id, $first_name, $last_name, $course, $year_level, $contact_number, $email]);
        } catch (PDOException $e) {
            error_log("Failed to add student: " . $e->getMessage());
            return false;
        }
    }

    public function getStudentById($student_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM students WHERE student_id = ?");
        $stmt->execute([$student_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStudent($student_id, $first_name, $last_name, $course, $year_level, $contact_number = null, $email = null) {
        try {
            $stmt = $this->pdo->prepare("UPDATE students SET first_name = ?, last_name = ?, course = ?, year_level = ?, contact_number = ?, email = ? WHERE student_id = ?");
            return $stmt->execute([$first_name, $last_name, $course, $year_level, $contact_number, $email, $student_id]);
        } catch (PDOException $e) {
            error_log("Failed to update student: " . $e->getMessage());
            return false;
        }
    }
}
?>
