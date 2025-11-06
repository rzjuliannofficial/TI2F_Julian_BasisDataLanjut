<?php
/**
 * FILE: views/tenure_stats.php
 * FUNGSI: Menampilkan statistik masa kerja (tenure) karyawan
 */

include 'views/header.php';

$all_stats = $tenure_stats->fetchAll(PDO::FETCH_ASSOC);
$total_employees = array_sum(array_column($all_stats, 'total_employees'));
?>

<h2>Statistik Masa Kerja Karyawan</h2>
<p style="margin-bottom: 2rem; color: #666;">
    Kategorisasi masa kerja menggunakan fungsi PostgreSQL <code>COUNT()</code> dan <code>CASE WHEN</code> berdasarkan tanggal mulai bekerja (hire_date).
</p>

<?php if (count($all_stats) > 0): ?>
    <div class="dashboard-cards" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
        <div class="card" style="border-left: 4px solid #3498db;">
            <h3>Total Karyawan</h3>
            <div class="number"><?php echo $total_employees; ?></div>
        </div>
        <?php 
        $colors = ['Junior' => '#f39c12', 'Middle' => '#2ecc71', 'Senior' => '#e74c3c'];
        foreach ($all_stats as $tenure): 
            $category_key = explode(' ', $tenure['tenure_category'])[0];
            $color = $colors[$category_key] ?? '#667eea';
        ?>
        <div class="card" style="border-left: 4px solid <?php echo $color; ?>;">
            <h3><?php echo htmlspecialchars($tenure['tenure_category']); ?></h3>
            <div class="number"><?php echo $tenure['total_employees']; ?></div>
            <p style="margin-top: 0.5rem; color: #666;"><?php echo number_format(($tenure['total_employees'] / $total_employees) * 100, 1, ',', '.'); ?>%</p>
        </div>
        <?php endforeach; ?>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Kategori Masa Kerja</th>
                <th>Jumlah Karyawan</th>
                <th>Persentase</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($all_stats as $tenure): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($tenure['tenure_category']); ?></strong></td>
                    <td style="text-align: center;">
                        <span style="padding: 0.25rem 0.75rem; background: #667eea; color: white; border-radius: 20px;">
                            <?php echo $tenure['total_employees']; ?>
                        </span>
                    </td>
                    <td>
                        <?php 
                        $category_key = explode(' ', $tenure['tenure_category'])[0];
                        $color = $colors[$category_key] ?? '#667eea';
                        $percentage = number_format(($tenure['total_employees'] / $total_employees) * 100, 1, '.', '');
                        ?>
                        <div style="width: 100%; background: #f0f0f0; border-radius: 4px; height: 20px;">
                            <div style="background: <?php echo $color; ?>; height: 100%; border-radius: 4px; width: <?php echo $percentage; ?>%;"></div>
                        </div>
                        <span style="margin-left: 0.5rem;"><?php echo number_format($percentage, 1, ',', '.'); ?>%</span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php else: ?>
    <div style="text-align: center; padding: 3rem; background: #f8f9fa; border-radius: 8px;">
        <p style="font-size: 1.2rem; color: #666;">❌ Tidak ada data masa kerja untuk ditampilkan.</p>
    </div>
<?php endif; ?>

<?php include 'views/footer.php'; ?>