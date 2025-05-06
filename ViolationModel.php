<?php
class ViolationModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addViolation($student_id, $violation_type, $violation_date, $description, $status, $reported_by) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO violations (student_id, violation_type, violation_date, description, status, reported_by) 
                                        VALUES (?, ?, ?, ?, ?, ?)");
            $result = $stmt->execute([$student_id, $violation_type, $violation_date, $description, $status, $reported_by]);
            if (!$result) {
                $errorInfo = $stmt->errorInfo();
                error_log("Failed to add violation: " . implode(", ", $errorInfo));
            }
            return $result;
        } catch (PDOException $e) {
            error_log("PDOException in addViolation: " . $e->getMessage());
            return false;
        }
    }

    public function getViolations() {
        $stmt = $this->pdo->query("SELECT v.*, s.first_name, s.last_name, s.course, s.year_level, s.contact_number, s.email, u.username as reported_by_name 
                                  FROM violations v 
                                  JOIN students s ON v.student_id = s.student_id 
                                  JOIN users u ON v.reported_by = u.id 
                                  ORDER BY v.violation_date DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateViolationStatus($violation_id, $status, $resolution_details = null) {
        $resolution_date = ($status === 'resolved') ? date('Y-m-d') : null;
        $stmt = $this->pdo->prepare("UPDATE violations 
                                    SET status = ?, resolution_details = ?, resolution_date = ? 
                                    WHERE violation_id = ?");
        return $stmt->execute([$status, $resolution_details, $resolution_date, $violation_id]);
    }


    // New method to get violations filtered by payment and year
    public function getViolationsByPaymentAndYear($paymentStatus, $year) {
        $query = "SELECT v.*, s.first_name, s.last_name, s.course, s.year_level, u.username as reported_by_name 
                  FROM violations v 
                  JOIN students s ON v.student_id = s.student_id 
                  JOIN users u ON v.reported_by = u.id 
                  WHERE 1=1 ";
        $params = [];

        if ($paymentStatus !== null) {
            $query .= " AND s.payment_status = ? ";
            $params[] = $paymentStatus;
        }
        if ($year !== null) {
            $query .= " AND s.year_level = ? ";
            $params[] = $year;
        }
        $query .= " ORDER BY v.violation_date DESC";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // New method to get violations filtered by payment, year, and violation type
    public function getViolationsByFilters($year, $violationType) {
        $query = "SELECT v.*, s.first_name, s.last_name, s.course, s.year_level, u.username as reported_by_name 
                  FROM violations v 
                  JOIN students s ON v.student_id = s.student_id 
                  JOIN users u ON v.reported_by = u.id 
                  WHERE 1=1 ";
        $params = [];

        if ($year !== null && $year !== '') {
            $query .= " AND s.year_level = ? ";
            $params[] = $year;
        }
        if ($violationType !== null && $violationType !== '') {
            $query .= " AND v.violation_type = ? ";
            $params[] = $violationType;
        }
        $query .= " ORDER BY v.violation_date DESC";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // New method to get violations and absences by violation type
    public function getViolationsAndAbsencesByType($violation_type) {
        // Assuming absences are stored in a separate table 'absences' with student_id and absence_date
        $query = "SELECT v.*, s.first_name, s.last_name, s.course, s.year_level, u.username as reported_by_name 
                  FROM violations v 
                  JOIN students s ON v.student_id = s.student_id 
                  JOIN users u ON v.reported_by = u.id 
                  WHERE v.violation_type = ? 
                  ORDER BY v.violation_date DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$violation_type]);
        $violations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $absenceQuery = "SELECT a.*, s.first_name, s.last_name, s.course, s.year_level 
                         FROM absences a 
                         JOIN students s ON a.student_id = s.student_id 
                         WHERE a.absence_type = ? 
                         ORDER BY a.absence_date DESC";
        $absenceStmt = $this->pdo->prepare($absenceQuery);
        $absenceStmt->execute([$violation_type]);
        $absences = $absenceStmt->fetchAll(PDO::FETCH_ASSOC);

        return ['violations' => $violations, 'absences' => $absences];
    }
    public function getViolationTypes() {
        $stmt = $this->pdo->query("SELECT DISTINCT violation_type FROM violations ORDER BY violation_type ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    public function addViolationType($type_name) {
        $stmt = $this->pdo->prepare("INSERT INTO violation_types (type_name) VALUES (?)");
        try {
            return $stmt->execute([$type_name]);
        } catch (PDOException $e) {
            // Handle duplicate entry or other errors gracefully
            return false;
        }
    }
    public function getTotalPendingViolations() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM violations WHERE status = 'pending'");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
}
?>
