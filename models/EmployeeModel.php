<?php
/**
 * FILE: models/EmployeeModel.php
 * FUNGSI: Berisi semua operasi database untuk tabel employees
 */

class EmployeeModel {
    private $conn;
    private $table_name = "employees";

    // Constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    // METHOD 1: Read semua employees
    public function getAllEmployees() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // METHOD 2: Create employee baru
    public function createEmployee($data) {
        $query = "INSERT INTO " . $this->table_name . " (first_name, last_name, email, department, position, salary, hire_date) VALUES (:first_name, :last_name, :email, :department, :position, :salary, :hire_date)";

        $stmt = $this->conn->prepare($query);

        // Bind parameters untuk keamanan (mencegah SQL injection)
        $stmt->bindParam(":first_name", $data['first_name']);
        $stmt->bindParam(":last_name", $data['last_name']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":department", $data['department']);
        $stmt->bindParam(":position", $data['position']);
        $stmt->bindParam(":salary", $data['salary']);
        $stmt->bindParam(":hire_date", $data['hire_date']);

        return $stmt->execute();
    }

    // METHOD 3: Update employee
    public function updateEmployee($id, $data) {
        $query = "UPDATE " . $this->table_name . " SET first_name = :first_name, last_name = :last_name, email = :email, department = :department, position = :position, salary = :salary, hire_date = :hire_date WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":first_name", $data['first_name']);
        $stmt->bindParam(":last_name", $data['last_name']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":department", $data['department']);
        $stmt->bindParam(":position", $data['position']);
        $stmt->bindParam(":salary", $data['salary']);
        $stmt->bindParam(":hire_date", $data['hire_date']);

        return $stmt->execute();
    }

    // METHOD 4: Delete employee
    public function deleteEmployee($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // METHOD 5: Get single employee by ID
    public function getEmployeeById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // METHOD 6: Get data dari VIEW employee_summary
    public function getEmployeeSummary() {
        $query = "SELECT * FROM employee_summary";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // METHOD 7: Get data dari VIEW department_stats
    public function getDepartmentStats() {
        $query = "SELECT * FROM department_stats";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // METHOD 8: Get data dari MATERIALIZED VIEW dashboard_summary
    public function getDashboardSummary() {
        $query = "SELECT * FROM dashboard_summary";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // METHOD 9: Refresh materialized view
    public function refreshDashboard() {
        $query = "REFRESH MATERIALIZED VIEW dashboard_summary";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }

    // METHOD 10: Get Salary Stats (AVG, MIN, MAX) per Department
    public function getSalaryStatsByDepartment() {
        $query = "
            SELECT 
                department, 
                AVG(salary) as avg_salary, 
                MAX(salary) as max_salary, 
                MIN(salary) as min_salary,
                COUNT(id) as total_employees_dept
            FROM " . $this->table_name . "
            GROUP BY department
            ORDER BY department
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // METHOD 11: Get Tenure Stats (Masa Kerja)
    public function getTenureStats() {
    // Menggunakan subquery untuk mendefinisikan 'tenure_category' terlebih dahulu,
    // sehingga alias dapat digunakan dengan aman di GROUP BY dan ORDER BY di query luar.
    $query = "
        SELECT 
            tenure_category, 
            COUNT(id) AS total_employees
        FROM (
            SELECT
                id,
                CASE
                    WHEN AGE(NOW(), hire_date) < INTERVAL '1 year' THEN 'Junior (< 1 tahun)'
                    WHEN AGE(NOW(), hire_date) >= INTERVAL '1 year' AND AGE(NOW(), hire_date) <= INTERVAL '3 years' THEN 'Middle (1 - 3 tahun)'
                    ELSE 'Senior (> 3 tahun)'
                END AS tenure_category
            FROM " . $this->table_name . "
        ) AS tenure_data
        GROUP BY tenure_category
        ORDER BY
            CASE 
                WHEN tenure_category = 'Senior (> 3 tahun)' THEN 1
                WHEN tenure_category = 'Middle (1 - 3 tahun)' THEN 2
                ELSE 3
            END;
    ";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
}

    // METHOD 12: Get Employee Overview (Total, Total Salary, Avg Tenure)
    public function getEmployeeOverview() {
        $query = "
            SELECT
                COUNT(id) AS total_employees,
                SUM(salary) AS total_monthly_salary,
                AVG(EXTRACT(EPOCH FROM AGE(NOW(), hire_date))) / (60*60*24*365.25) AS avg_years_service
            FROM " . $this->table_name . "
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
?>
