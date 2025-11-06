<?php
/**
 * FILE: views/salary_stats.php
 * FUNGSI: Menampilkan statistik gaji (AVG, MIN, MAX) per departemen
 */

include 'views/header.php';

// Fetch all results for overall stats calculation
$all_stats = $salary_stats->fetchAll(PDO::FETCH_ASSOC);
$total_employees = array_sum(array_column($all_stats, 'total_employees_dept'));
$total_avg_salary = count($all_stats) > 0 ? array_sum(array_column($all_stats, 'avg_salary')) / count($all_stats) : 0;
?>

<h2>Statistik Gaji Karyawan</h2>
<p style="margin-bottom: 2rem; color: #666;">
    Perhitungan rata-rata, gaji tertinggi, dan terendah per departemen menggunakan fungsi agregat PostgreSQL <code>AVG()</code>, <code>MAX()</code>, <code>MIN()</code>, dan <code>GROUP BY</code>.
</p>

<?php if (count($all_stats) > 0): ?>
    <div class="dashboard-cards">
        <div class="card">
            <h3>Total Departemen</h3>
            <div class="number"><?php echo count($all_stats); ?></div>
        </div>
        <div class="card">
            <h3>Rata-rata Gaji Global</h3>
            <div class="number">Rp <?php echo number_format($total_avg_salary, 0, ',', '.'); ?></div>
        </div>
        <div class="card">
            <h3>Gaji Tertinggi (Global)</h3>
            <div class="number">Rp <?php echo number_format(max(array_column($all_stats, 'max_salary')), 0, ',', '.'); ?></div>
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Departemen</th>
                <th>Jumlah Karyawan</th>
                <th>Gaji Rata-rata (AVG)</th>
                <th>Gaji Terendah (MIN)</th>
                <th>Gaji Tertinggi (MAX)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($all_stats as $dept): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($dept['department']); ?></strong></td>
                    <td style="text-align: center;">
                        <span style="padding: 0.25rem 0.75rem; background: #667eea; color: white; border-radius: 20px;">
                            <?php echo $dept['total_employees_dept']; ?>
                        </span>
                    </td>
                    <td><strong>Rp <?php echo number_format($dept['avg_salary'], 0, ',', '.'); ?></strong></td>
                    <td>Rp <?php echo number_format($dept['min_salary'], 0, ',', '.'); ?></td>
                    <td>Rp <?php echo number_format($dept['max_salary'], 0, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php else: ?>
    <div style="text-align: center; padding: 3rem; background: #f8f9fa; border-radius: 8px;">
        <p style="font-size: 1.2rem; color: #666;">❌ Tidak ada data gaji untuk ditampilkan.</p>
    </div>
<?php endif; ?>

<?php include 'views/footer.php'; ?>