<?php
defined('APK') or exit('No Access');

// Keamanan dasar dan pengambilan data
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id === 0) {
    echo "ID Materi tidak valid.";
    exit;
}

$stmt = $koneksi->prepare("SELECT * FROM materi WHERE id_materi = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo "Materi tidak ditemukan.";
    exit;
}
$materi = $result->fetch_assoc();
$stmt->close();

$guru = fetch($koneksi, 'users', ['id_user' => $materi['id_guru']]);

// Ambil nama mapel lengkap dari tabel mata_pelajaran
$nama_mapel_lengkap = $materi['mapel']; // Nilai default jika tidak ditemukan
$stmt_mapel = $koneksi->prepare("SELECT nama_mapel FROM mata_pelajaran WHERE kode = ?");
if ($stmt_mapel) {
    $stmt_mapel->bind_param("s", $materi['mapel']);
    $stmt_mapel->execute();
    $result_mapel = $stmt_mapel->get_result();
    if ($result_mapel->num_rows > 0) {
        $nama_mapel_data = $result_mapel->fetch_assoc();
        $nama_mapel_lengkap = $nama_mapel_data['nama_mapel'];
    }
    $stmt_mapel->close();
}

// PENAMBAHAN: Ambil semua tugas yang terkait dengan materi ini, dan filter sesuai kelas siswa
$tugas_terkait = [];
// Tentukan kelas siswa dari variabel global $siswa atau ambil dari DB
$kelas_siswa = '';
if (isset($siswa) && !empty($siswa['kelas'])) {
    $kelas_siswa = (string)$siswa['kelas'];
} else if (isset($_SESSION['id_siswa'])) {
    $sx = fetch($koneksi, 'siswa', ['id_siswa' => $_SESSION['id_siswa']]);
    if ($sx && !empty($sx['kelas'])) {
        $kelas_siswa = (string)$sx['kelas'];
    }
}

// Asumsi: tabel 'tugas' memiliki kolom 'id_materi' (int) dan 'kelas' (serialized) untuk relasi & sasaran kelas
$stmt_tugas = $koneksi->prepare("SELECT id_tugas, judul, tgl_mulai, tgl_selesai, kelas FROM tugas WHERE id_materi = ? ORDER BY tgl_mulai ASC");
if ($stmt_tugas) {
    $stmt_tugas->bind_param("i", $id);
    $stmt_tugas->execute();
    $result_tugas = $stmt_tugas->get_result();
    while ($result_tugas && ($row = $result_tugas->fetch_assoc())) {
        $kelas_target = @unserialize($row['kelas']);
        if ($kelas_target === false && $row['kelas'] !== 'b:0;') {
            // Jika bukan serialized valid, abaikan filter kelas (fallback tampilkan)
            $match_kelas = true;
        } else {
            $kelas_target = is_array($kelas_target) ? $kelas_target : [];
            $match_kelas = ($kelas_siswa !== '' && in_array($kelas_siswa, $kelas_target, true));
        }
        if ($match_kelas) {
            $tugas_terkait[] = $row;
        }
    }
    $stmt_tugas->close();
}


// PERBAIKAN: Fungsi Youtube dibuat lebih fleksibel untuk menangani parameter tambahan
function youtube($url) {
    $video_id = '';
    $trimmed_url = trim($url);

    // 1. Coba regex untuk format URL lengkap
    $pattern = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
    if (preg_match($pattern, $trimmed_url, $matches)) {
        $video_id = $matches[1];
    } 
    // 2. Jika gagal, coba parsing untuk format "v=VIDEO_ID"
    elseif (strpos($trimmed_url, 'v=') === 0) {
        $parts = explode('v=', $trimmed_url);
        if (isset($parts[1])) {
             // Ambil 11 karakter pertama setelah 'v='
             $video_id = substr(trim($parts[1]), 0, 11);
        }
    }
    // 3. Jika masih gagal, coba bersihkan parameter dan cek ID
    else {
        // Hapus semua parameter setelah '?' atau '&'
        $cleaned_id = preg_replace('/[?&].*/', '', $trimmed_url);
        if (strlen($cleaned_id) == 11) {
            $video_id = $cleaned_id;
        }
    }

    // Jika ID berhasil didapatkan, tampilkan video
    if (!empty($video_id)) {
        return '<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="https://www.youtube.com/embed/' . htmlspecialchars($video_id) . '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
    } else {
        // Jika format tidak dikenali
        return '<p class="text-danger">Format URL YouTube tidak valid atau tidak didukung.</p>';
    }
}


// Proses absensi
$where = ['idsiswa' => $_SESSION['id_siswa'], 'idmateri' => $id];
if (rowcount($koneksi, 'absen_daringmapel', $where) == 0) {
    $datax = [
        'idmateri' => $id, 'mapel' => $materi['mapel'], 'idsiswa' => $_SESSION['id_siswa'],
        'tanggal' => date('Y-m-d'), 'jam' => date('H:i:s'), 'bulan' => date('m'),
        'ket' => 'H', 'guru' => $materi['id_guru'], 'tahun' => date('Y')
    ];
    insert($koneksi, 'absen_daringmapel', $datax);
}
?>

<!-- Import Font dari Google -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<!-- Pastikan Font Awesome sudah dimuat -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
    }
    .card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        margin-bottom: 25px;
    }
    .card-header {
        background-color: #fff;
        border-bottom: 1px solid #f0f0f0;
        font-weight: 600;
        padding: 1rem 1.5rem;
    }
    .card-body {
        padding: 1.5rem;
    }
    /* PERBAIKAN: Gaya untuk panel info materi */
    .info-panel {
        background-color: #eef5ff; /* Warna panel biru muda */
        border-left: 5px solid #007bff;
        padding: 1.25rem;
        border-radius: 8px;
        display: flex;
        align-items: center;
    }
    .info-panel .avatar {
        width: 50px;
        height: 50px;
        margin-right: 1.25rem; /* Jarak antara ikon dan teks */
    }
    .info-panel .author-name {
        font-weight: 600;
        font-size: 1.1rem;
        color: #333;
    }
    .info-panel .author-about {
        font-size: 0.9rem;
        color: #555;
    }
    .guru-pengampu-box {
        background-color: #28a745; /* Warna hijau seperti di gambar */
        color: white;
        padding: 15px;
        border-radius: 10px;
        display: flex;
        align-items: center;
    }
    .guru-pengampu-box img {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        margin-right: 15px;
        border: 2px solid white;
    }
    .guru-pengampu-box .nama {
        font-weight: 600;
    }
    .guru-pengampu-box .label {
        font-size: 0.8rem;
        opacity: 0.9;
    }
    .materi-content {
        color: #444;
        line-height: 1.8;
    }
    .materi-content h3 {
        font-weight: 600;
        margin-top: 20px;
        margin-bottom: 15px;
    }
    .no-file {
        color: #dc3545;
        font-weight: 500;
        margin-top: 15px;
    }
    /* Modal Notifikasi */
    .feedback-modal {
        display: none; position: fixed; z-index: 1050; left: 0; top: 0;
        width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5);
        align-items: center; justify-content: center;
    }
    .feedback-modal-content {
        background-color: #fff; padding: 30px;
        border-radius: 10px; text-align: center; width: 90%; max-width: 400px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.2);
    }
    .feedback-modal-content img {
        width: 80px;
        margin-bottom: 20px;
    }
    .feedback-modal-content h4 {
        font-weight: 600; margin-bottom: 25px;
    }
    /* PENAMBAHAN: Gaya untuk tombol tugas */
    .floating-task-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        background-color: #ffc107; /* Warna kuning untuk tugas */
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 1040;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    .floating-task-btn:hover {
        transform: scale(1.1) rotate(10deg);
        box-shadow: 0 6px 16px rgba(0,0,0,0.3);
        color: #fff;
    }
    /* Panel Tugas Terkait */
    .related-task-panel {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: #fff;
        border-radius: 12px;
        padding: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }
    .related-task-panel .title {
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .related-task-panel .title i {
        font-size: 1.1rem;
    }
    .related-task-panel .controls { display: flex; align-items: center; gap: 8px; }
    .related-task-panel select.form-control { max-width: 360px; }
</style>

<div class="row">
    <!-- Kolom Kiri: Konten Materi -->
    <div class="col-xl-7">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">MATERI BELAJAR</h5>
            </div>
            <div class="card-body">
                <!-- PERBAIKAN: Menggunakan gaya panel baru -->
                <div class="info-panel mb-4">
                    <img src="../images/icon/buku.png" class="avatar" alt="icon">
                    <div>
                        <div class="author-name"><?= htmlspecialchars($nama_mapel_lengkap) ?></div>
                        <div class="author-about"><?= htmlspecialchars($materi['judul']) ?></div>
                    </div>
                </div>

                <?php $jumlah_tugas_terkait = count($tugas_terkait); ?>
                <?php if ($jumlah_tugas_terkait > 0): ?>
                    <div class="mb-3 related-task-panel">
                        <h6 class="title mb-0"><i class="fas fa-tasks"></i> Tugas Terkait</h6>
                        <div class="controls">
                        <?php if ($jumlah_tugas_terkait === 1): ?>
                            <?php $only = $tugas_terkait[0]; ?>
                            <a href="?pg=bukatugas&id=<?= (int)$only['id_tugas'] ?>" class="btn btn-light text-primary" style="font-weight:600;">
                                Kerjakan
                            </a>
                        <?php else: ?>
                            <select id="pilih-tugas" class="form-control" onchange="if(this.value){window.location.href='?pg=bukatugas&id='+this.value;}">
                                <option value="">Pilih tugas…</option>
                                <?php foreach ($tugas_terkait as $tg): ?>
                                    <option value="<?= (int)$tg['id_tugas'] ?>"><?= htmlspecialchars($tg['judul']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="guru-pengampu-box my-4">
                    <img src="<?= (empty($guru['foto'])) ? '../images/guru.png' : '../path/to/guru/photos/' . htmlspecialchars($guru['foto']) ?>" alt="Guru">
                    <div>
                        <div class="label">Guru Pengampu</div>
                        <div class="nama"><?= htmlspecialchars($guru['nama']) ?></div>
                    </div>
                </div>

                <?php if(empty($materi['file'])): ?>
                    <p class="no-file">Tidak ada File Download</p>
                <?php else: ?>
                    <a href="../materi/<?= htmlspecialchars($materi['file']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">Download File</a>
                <?php endif; ?>

                <hr class="my-4">

                <div class="materi-content">
                    <h3><?= htmlspecialchars($materi['judul']) ?></h3>
                    <?= $materi['materi'] // Konten dari editor ?>
                </div>

                <?php if(!empty($materi['file'])):
                    $pecah = explode('.', $materi['file']);
                    $ekstensi = strtolower(end($pecah));
                    $file_rel = "../materi/" . htmlspecialchars($materi['file']);
                ?>
                    <div class="mt-4">
                        <?php if($ekstensi == 'mp4'): ?>
                            <video src="<?= $file_rel ?>" controls width="100%" class="rounded"></video>
                        <?php elseif(in_array($ekstensi, ['jpg', 'png'])): ?>
                            <img src="<?= $file_rel ?>" class="img-fluid rounded">
                        <?php elseif($ekstensi == 'pdf'): ?>
                            <iframe src="<?= $file_rel ?>" width="100%" height="500" class="rounded border"></iframe>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($materi['youtube'])): ?>
                    <div class="mt-4">
                        <h5 class="font-weight-bold">Video Youtube</h5>
                        <?= youtube($materi['youtube']) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Komentar -->
    <div class="col-xl-5">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">KOMENTAR</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Jika ada materi yang tidak paham, silahkan kirim pertanyaan.</p>
                <form id="formpesan">
                    <input type='hidden' name='id_materi' value="<?= $materi['id_materi'] ?>">
                    <input type='hidden' name='guru' value="<?= $materi['id_guru'] ?>">
                    <div class="form-group">
                        <textarea id='editor2' name='komentar' class='form-control' rows='5' required></textarea>
                    </div>
                    <div class="text-right">
                        <button type="submit" name="submit" class="btn btn-primary">Kirim</button>
                    </div>
                </form>
            </div>
            <div class="card-body border-top">
                <?php
                    $query_komen = mysqli_query($koneksi, "SELECT * FROM komentar WHERE id_materi='$materi[id_materi]' ORDER BY tgl ASC");
                    if(mysqli_num_rows($query_komen) > 0):
                        while ($data = mysqli_fetch_array($query_komen)):
                            $siswa_komen = fetch($koneksi, 'siswa', ['id_siswa' => $data['id_user']]);
                ?>
                    <div class="mb-3">
                        <div class="d-flex align-items-start">
                            <img src="../images/user.png" width="35" class="rounded-circle mr-3">
                            <div class="w-100">
                                <strong class="d-block"><?= htmlspecialchars($siswa_komen['nama']) ?></strong>
                                <p class="bg-light p-2 rounded mb-1"><?= htmlspecialchars($data['komentar']) ?></p>
                                <small class="text-muted"><?= date('d M Y, H:i', strtotime($data['tgl'])) ?></small>
                            </div>
                        </div>
                        <?php if(!empty($data['balasan'])): ?>
                            <div class="d-flex align-items-start mt-2" style="margin-left: 50px;">
                                <img src="../images/guru.png" width="35" class="rounded-circle mr-3">
                                <div class="w-100">
                                    <strong class="d-block"><?= htmlspecialchars($guru['nama']) ?></strong>
                                    <p class="bg-success text-white p-2 rounded mb-1"><?= htmlspecialchars($data['balasan']) ?></p>
                                    <small class="text-muted">Balasan</small>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php 
                        endwhile;
                    else:
                        echo "<p class='text-center text-muted'>Belum ada komentar.</p>";
                    endif;
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Notifikasi -->
<div id="feedbackModal" class="feedback-modal" style="display: none;">
    <div class="feedback-modal-content">
        <div id="modal-content-area">
            <!-- Konten dinamis akan dimuat di sini oleh JavaScript -->
        </div>
    </div>
</div>

<?php if (isset($jumlah_tugas_terkait) && $jumlah_tugas_terkait === 1): ?>
<?php $only = $tugas_terkait[0]; ?>
<!-- PENAMBAHAN: Tombol Floating untuk Tugas -->
<a href="?pg=bukatugas&id=<?= (int)$only['id_tugas'] ?>" class="floating-task-btn" data-toggle="tooltip" data-placement="left" title="Kerjakan Tugas">
    <i class="fas fa-pencil-alt"></i>
</a>
<?php endif; ?>

<script>
    // Inisialisasi Tooltip untuk tombol tugas
    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })

    $('#formpesan').submit(function(e) {
        e.preventDefault();
        
        if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances.editor2) {
             CKEDITOR.instances.editor2.updateElement();
        }
        
        var data = new FormData(this);
        var modal = $('#feedbackModal');
        var modalContent = $('#modal-content-area');

        $.ajax({
            type: 'POST',
            url: 'E-Learning/tkomen.php?pg=tambah', 
            data: data,
            dataType: 'json', 
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                modalContent.html(
                    '<div>' +
                    '<img src="../images/animasi.gif" alt="Loading...">' +
                    '<h4>Sedang memproses...</h4>' +
                    '<p class="text-muted">Mohon tunggu, komentar Anda sedang dikirim.</p>' +
                    '</div>'
                );
                modal.css('display', 'flex').hide().fadeIn();
            },
            success: function(response) {
                if(response.status === 'success') {
                    modalContent.html(
                        '<div>' +
                        '<img src="https://i.ibb.co/P9t3g0j/success-icon.png" alt="Success">' +
                        '<h4>Komentar Berhasil Terkirim!</h4>' +
                        '<button onclick="window.location.reload()" class="btn btn-success">Oke</button>' +
                        '</div>'
                    );
                } else {
                    modalContent.html(
                        '<div>' +
                        '<img src="https://i.ibb.co/RjLJr1q/error-icon.png" alt="Error">' +
                        '<h4>Gagal Mengirim Komentar</h4>' +
                        '<p class="text-muted">' + (response.message || 'Terjadi kesalahan di server.') + '</p>' +
                        '<button onclick="$(\'#feedbackModal\').fadeOut()" class="btn btn-danger">Tutup</button>' +
                        '</div>'
                    );
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                modalContent.html(
                    '<div>' +
                    '<img src="https://i.ibb.co/RjLJr1q/error-icon.png" alt="Error">' +
                    '<h4>Gagal Mengirim Komentar</h4>' +
                    '<p class="text-muted">Error: ' + jqXHR.status + ' - ' + errorThrown + '.<br>Pastikan file tkomen.php ada di folder E-Learning.</p>' +
                    '<button onclick="$(\'#feedbackModal\').fadeOut()" class="btn btn-danger">Tutup</button>' +
                    '</div>'
                );
            }
        });
    });
</script>
