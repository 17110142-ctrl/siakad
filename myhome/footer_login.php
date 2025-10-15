<!-- footer_login.php (Revisi) -->

<!-- HTML untuk menampilkan teks footer -->
<footer style="
    background-color: rgba(40, 40, 40, 0.7); /* Latar belakang semi-transparan gelap */
    color: white;
    text-align: center;
    padding: 8px 0; /* Padding diperkecil */
    position: fixed;
    left: 0;
    bottom: 0;
    width: 100%;
    font-size: 12px; /* Ukuran font diperkecil */
    z-index: 1000; /* Memastikan footer tampil di atas elemen lain */
    border-top: 1px solid #555; /* Garis tipis di atas footer */
">
    <p style="margin: 0; padding: 2px;">
        Created By : Aji Bagaskoro, S.Pd &copy;2025
    </p>
</footer>


<!-- Berisi skrip-skrip yang hanya dibutuhkan oleh halaman login -->

<!-- Popper.js (diperlukan oleh komponen Bootstrap seperti dropdown dan tooltip) -->
<script src="<?= $homeurl ?>/assets/plugins/bootstrap/js/popper.min.js"></script>

<!-- Bootstrap JS (untuk fungsionalitas komponen Bootstrap seperti carousel) -->
<script src="<?= $homeurl ?>/assets/plugins/bootstrap/js/bootstrap.min.js"></script>

<!-- 
    CATATAN: 
    - jQuery diasumsikan sudah Anda muat di dalam file 'toplog.php'.
    - SweetAlert2 sudah dimuat langsung di 'mulai.php' melalui CDN.
-->
