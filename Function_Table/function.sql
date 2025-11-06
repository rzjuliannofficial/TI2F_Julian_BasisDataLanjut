--fungsi 1
CREATE OR REPLACE FUNCTION get_employees_by_salary_range(
    min_salary DECIMAL, 
    max_salary DECIMAL
)
RETURNS TABLE (
    id INT,
    full_name TEXT,
    department VARCHAR,
    position VARCHAR,
    salary DECIMAL
)
LANGUAGE plpgsql
AS $$
BEGIN
    -- Mengembalikan hasil query yang mencari karyawan berdasarkan range gaji
    RETURN QUERY
    SELECT
        e.id,
        (e.first_name || ' ' || e.last_name)::TEXT AS full_name,
        e.department,
        e.position,
        e.salary
    FROM
        employees e
    WHERE
        e.salary >= min_salary AND e.salary <= max_salary
    ORDER BY
        e.salary DESC;
END;
$$;
SELECT * FROM get_employees_by_salary_range(5000000, 10000000);

--Fungsi 2
CREATE OR REPLACE FUNCTION get_department_summary()
RETURNS TABLE (
    department VARCHAR,
    employee_count BIGINT,
    avg_salary DECIMAL,
    total_budget DECIMAL
)
LANGUAGE plpgsql
AS $$
BEGIN
    -- Mengembalikan hasil query yang menghitung statistik agregat per departemen
    RETURN QUERY
    SELECT
        e.department,
        COUNT(e.id) AS employee_count,
        CAST(AVG(e.salary) AS DECIMAL) AS avg_salary,
        CAST(SUM(e.salary) AS DECIMAL) AS total_budget
    FROM
        employees e
    GROUP BY
        e.department
    ORDER BY
        employee_count DESC, total_budget DESC;
END;
$$;
SELECT * FROM get_department_summary();