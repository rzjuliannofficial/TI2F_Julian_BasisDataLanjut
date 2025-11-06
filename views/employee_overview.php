<?php
/**
 * FILE: views/employee_overview.php
 * FUNGSI: Menampilkan ringkasan karyawan (total, total gaji, rata-rata masa kerja)
 */

include 'views/header.php';
?>

<h2>Ringkasan Karyawan (Global)</h2>
<p style="margin-bottom: 2rem; color: #666;">
    Ringkasan total perusahaan menggunakan fungsi agregat PostgreSQL <code>COUNT()</code>, <code>SUM()</code>, dan <code>AVG()</code>.
</p>

<?php if ($overview && $overview['total_employees'] > 0): ?>
    <div class="dashboard-cards" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
        <div class="card" style="border-left: 4px solid #3498db;">
            <h3>Total Karyawan</h3>
            <div class="number"><?php echo $overview['total_employees']; ?> orang</div>
        </div>
        <div class="card" style="border-left: 4px solid #2ecc71;">
            <h3>Total Budget Gaji Bulanan</h3>
            <div class="number">Rp <?php echo number_format($overview['total_monthly_salary'], 0, ',', '.'); ?></div>
            <p style="margin-top: 0.5rem; color: #666;">
                Ini adalah total gaji yang dikeluarkan setiap bulan.
            </p>
        </div>
        <div class="card" style="border-left: 4px solid #f39c12;">
            <h3>Rata-rata Masa Kerja</h3>
            <div class="number"><?php echo number_format($overview['avg_years_service'], 1, ',', '.'); ?> tahun</div>
            <p style="margin-top: 0.5rem; color: #666;">
                Rata-rata waktu karyawan telah bekerja di perusahaan.
            </p>
        </div>
    </div>

<?php else: ?>
    <div style="text-align: center; padding: 3rem; background: #f8f9fa; border-radius: 8px;">
        <p style="font-size: 1.2rem; color: #666;">❌ Tidak ada data karyawan untuk dihitung.</p>
        <a href="index.php?action=create" class="btn btn-primary" style="margin-top: 1rem;">Tambah Data Karyawan</a>
    </div>
<?php endif; ?>

<?php include 'views/footer.php'; ?>