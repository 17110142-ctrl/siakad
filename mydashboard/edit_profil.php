<?php
// Pastikan file koneksi dan sesi dimulai
include_once "../config/koneksi.php";
session_start();

// Periksa apakah sesi id_siswa ada
if (!isset($_SESSION['id_siswa'])) {
    die("Sesi ID Siswa tidak ditemukan. Silakan login kembali.");
}

$id_siswa = $_SESSION['id_siswa'];

// Cek status kunci biodata (diterima)
$isLocked = false;
// Buat tabel status jika belum ada (aman dipanggil berkali-kali)
mysqli_query($koneksi, "CREATE TABLE IF NOT EXISTS biodata_status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_siswa INT NOT NULL UNIQUE,
    status ENUM('accepted','rejected') NOT NULL,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$resLock = mysqli_query($koneksi, "SELECT status FROM biodata_status WHERE id_siswa='" . mysqli_real_escape_string($koneksi, $id_siswa) . "' LIMIT 1");
if ($resLock && mysqli_num_rows($resLock) > 0) {
    $st = mysqli_fetch_assoc($resLock);
    if (strtolower($st['status']) === 'accepted') { $isLocked = true; }
}

// Menggunakan prepared statement untuk mengambil data siswa
$stmt = mysqli_prepare($koneksi, "SELECT * FROM siswa WHERE id_siswa = ?");
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id_siswa);
    mysqli_stmt_execute($stmt);
    $query_result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($query_result);
    mysqli_stmt_close($stmt);

    if (!$data) {
        die("Data siswa tidak ditemukan untuk ID ini.");
    }
    // Tambahan: jika kolom validation_status tersedia pada tabel siswa,
    // gunakan nilai tersebut sebagai sumber kebenaran (override biodata_status lama)
    if (array_key_exists('validation_status', $data)) {
        $isLocked = (strtolower($data['validation_status'] ?? '') === 'validated');
    }
} else {
    die("Error preparing statement: " . mysqli_error($koneksi));
}

// Definisikan opsi-opsi yang digunakan dalam form
const GENDER_OPTIONS = ['L' => 'Laki-laki', 'P' => 'Perempuan'];
const RELIGION_OPTIONS = ["Islam", "Kristen", "Katholik", "Hindu", "Budha", "Konghucu"];
const CITIZENSHIP_OPTIONS = ["WNI", "WNA"];
const BEASISWA_OPTIONS = ['TIDAK ADA' => 'TIDAK ADA', 'KIP' => 'KIP', 'PKH' => 'PKH'];
const PARENT_STATUS_OPTIONS = ['MASIH HIDUP', 'SUDAH MENINGGAL'];
const PARENT_CITIZENSHIP_OPTIONS = ['WNI', 'WNA'];
const PARENT_EDUCATION_OPTIONS = ['TIDAK SEKOLAH', 'SD', 'SMP', 'SMA/SMK', 'D1', 'D2', 'D3', 'S1', 'S2', 'S3'];
const PARENT_OCCUPATION_OPTIONS = ['TIDAK BEKERJA', 'PNS', 'TNI/POLRI', 'GURU', 'PETANI', 'NELAYAN', 'PEDAGANG', 'WIRASWASTA', 'BURUH', 'KARYAWAN SWASTA'];
const PARENT_INCOME_OPTIONS = ['< RP 500.000', 'RP 500.000 - RP 999.999', 'RP 1.000.000 - RP 1.999.999', 'RP 2.000.000 - RP 4.999.999', '>= RP 5.000.000'];

// Fungsi helper untuk membuat elemen <select>
function createSelect($name, $options, $selectedValue, $required = true) {
    $html = "<select name='{$name}' id='{$name}' class='form-select'" . ($required ? " required" : "") . "><option value=''>-- PILIH --</option>";
    foreach ($options as $value => $label) {
        $optValue = is_numeric($value) ? $label : $value;
        $optLabel = $label;
        
        $selected = (strtoupper($selectedValue) == strtoupper($optValue)) ? " selected" : "";
        
        $escapedOptValue = htmlspecialchars($optValue, ENT_QUOTES, 'UTF-8');
        $escapedOptLabel = htmlspecialchars($optLabel, ENT_QUOTES, 'UTF-8');
        
        $html .= "<option value='{$escapedOptValue}'{$selected}>{$escapedOptLabel}</option>";
    }
    $html .= "</select>";
    return $html;
}

// Fungsi helper untuk membuat elemen <input>
function createInputField($field, $label, $value, $type = 'text', $class = '', $required = true, $readonly = false) {
    $inputClass = "form-control " . ($class ? $class : '');
    $readonlyAttr = $readonly ? 'readonly' : '';
    $requiredAttr = $required ? 'required' : '';
    return "
        <div class='mb-3 has-feedback'>
            <label class='form-label'>" . strtoupper($label) . "</label>
            <input type='{$type}' name='{$field}' id='{$field}' class='{$inputClass}' {$requiredAttr} value='{$value}' {$readonlyAttr}>
        </div>
    ";
}

// Fungsi untuk generate field data orang tua (sudah termasuk radio button)
function generateParentFields($parentType, $data, $options) {
    $html = "<h5 class='mt-4'>DATA " . strtoupper($parentType) . "</h5>";
    $html .= createInputField("nama_{$parentType}", "NAMA " . strtoupper($parentType), htmlspecialchars($data["nama_{$parentType}"] ?? ''));
    
    $html .= "<div class='mb-3 has-feedback'><label>STATUS " . strtoupper($parentType) . "</label>" . createSelect("status_{$parentType}", array_combine($options['status'], $options['status']), $data["status_{$parentType}"] ?? '') . "</div>";
    $html .= "<div class='mb-3 has-feedback'><label>KEWARGANEGARAAN " . strtoupper($parentType) . "</label>" . createSelect("kewarganegaraan_{$parentType}", array_combine($options['kewargaan'], $options['kewargaan']), $data["kewarganegaraan_{$parentType}"] ?? '') . "</div>";
    $html .= createInputField("tempat_lahir_{$parentType}", "TEMPAT LAHIR " . strtoupper($parentType), htmlspecialchars($data["tempat_lahir_{$parentType}"] ?? ''), 'text', 'capitalize');
    $html .= createInputField("tgl_lahir_{$parentType}", "TANGGAL LAHIR " . strtoupper($parentType), htmlspecialchars($data["tgl_lahir_{$parentType}"] ?? ''), 'date');
    $html .= "<div class='mb-3 has-feedback'><label>PENDIDIKAN " . strtoupper($parentType) . "</label>" . createSelect("pendidikan_{$parentType}", array_combine($options['pendidikan'], $options['pendidikan']), $data["pendidikan_{$parentType}"] ?? '') . "</div>";
    $html .= "<div class='mb-3 has-feedback'><label>PEKERJAAN " . strtoupper($parentType) . "</label>" . createSelect("pekerjaan_{$parentType}", array_combine($options['pekerjaan'], $options['pekerjaan']), $data["pekerjaan_{$parentType}"] ?? '') . "</div>";
    $html .= "<div class='mb-3 has-feedback'><label>PENGHASILAN " . strtoupper($parentType) . "</label>" . createSelect("penghasilan_{$parentType}", array_combine($options['penghasilan'], $options['penghasilan']), $data["penghasilan_{$parentType}"] ?? '') . "</div>";
    $html .= createInputField("no_hp_{$parentType}", "NO. HP " . strtoupper($parentType), htmlspecialchars($data["no_hp_{$parentType}"] ?? ''));

    $no_hp_ortu = htmlspecialchars($data["no_hp_{$parentType}"] ?? '');
    $nowa_siswa = htmlspecialchars($data['nowa'] ?? '');
    $checked = (!empty($no_hp_ortu) && $no_hp_ortu === $nowa_siswa) ? 'checked' : '';

    $html .= "
        <div class='form-check'>
            <input class='form-check-input' type='radio' name='use_for_nowa' id='use_for_nowa_{$parentType}' value='{$parentType}' {$checked}>
            <label class='form-check-label' for='use_for_nowa_{$parentType}'>
                Gunakan No. ini untuk komunikasi dengan sekolah
            </label>
        </div>
    ";
    return $html;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Biodata Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .card { border: none; }
        input.capitalize, textarea.capitalize { text-transform: uppercase; }
        .nav-link.active { background: #198754 !important; color: #fff !important; font-weight: bold; }
        .is-valid { border-color: #198754 !important; }
        .is-invalid { border-color: #dc3545 !important; }
        .has-feedback { position: relative; }
        .has-feedback .bi { position: absolute; top: 70%; right: 1rem; transform: translateY(-50%); font-size: 1.2em; pointer-events: none; }
        select + .bi { top: 50%; }
    </style>
</head>
<body>
<div class="container my-5">
    <div class="card shadow-lg">
        <div class="card-body p-4">
            <?php if ($isLocked): ?>
            <div class="alert alert-success d-flex align-items-center" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-lock-fill me-2" viewBox="0 0 16 16">
                  <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2"/>
                </svg>
                <div>
                    Biodata Anda telah diterima dan dikunci. Jika ada kesalahan data, silakan hubungi operator untuk membuka kunci.
                </div>
            </div>
            <?php endif; ?>
            <h3 class="mb-4 text-center text-success">Formulir Data Siswa</h3>

            <ul class="nav nav-tabs nav-fill mb-4" id="biodataTabs" role="tablist">
                <li class="nav-item"><button class="nav-link active" id="biodata-tab" data-bs-toggle="tab" data-bs-target="#biodata" type="button">1. Biodata</button></li>
                <li class="nav-item"><button class="nav-link" id="alamat-tab" data-bs-toggle="tab" data-bs-target="#alamat" type="button">2. Alamat</button></li>
                <li class="nav-item"><button class="nav-link" id="ortu-tab" data-bs-toggle="tab" data-bs-target="#ortu" type="button">3. Orang Tua</button></li>
            </ul>

            <form id="formBiodataSiswa" enctype="multipart/form-data">
                <input type="hidden" name="id_siswa" value="<?= htmlspecialchars($data['id_siswa']) ?>">
                <div class="tab-content border p-3 rounded">

                    <!-- ==================== TAB BIODATA ==================== -->
                    <div class="tab-pane fade show active" id="biodata" role="tabpanel">
                        <?php
                        $fields_biodata = [
                            "nama" => "Nama Lengkap (Sesuai Akta)", "nisn" => "NISN (10 digit)", "nokk" => "NO. KK",
                            "nik" => "NIK", "t_lahir" => "Tempat Lahir", "tgl_lahir" => "Tanggal Lahir",
                            "jk" => "Jenis Kelamin", "agama" => "Agama", "kewarganegaraan" => "Kewarganegaraan",
                            "email" => 'Email Siswa', "t_badan" => "Tinggi Badan (cm)", "b_badan" => "Berat Badan (kg)",
                            "l_kepala" => "Lingkar Kepala (cm)", "anakke" => "Anak Ke", "jumlah_saudara" => "Jumlah Saudara Kandung",
                            "cita_cita" => "Cita-Cita", "hobi" => "Hobi", "asal_sek" => "Asal Sekolah (Contoh: SDN 1 PLUMBON)",
                            'thn_lulus' => "Tahun Lulus",
                        ];

                        foreach ($fields_biodata as $field => $label) {
                            $value = $data[$field] ?? ''; // Dapatkan nilai mentah
                            if ($field == 'tgl_lahir') {
                                echo createInputField($field, $label, htmlspecialchars($value), 'date');
                            } elseif ($field == 'jk') {
                                echo "<div class='mb-3 has-feedback'><label class='form-label'>" . strtoupper($label) . "</label>";
                                echo createSelect('jk', GENDER_OPTIONS, $value);
                                echo "</div>";
                            } elseif ($field == 'agama') {
                                echo "<div class='mb-3 has-feedback'><label class='form-label'>" . strtoupper($label) . "</label>";
                                echo createSelect('agama', array_combine(RELIGION_OPTIONS, RELIGION_OPTIONS), $value);
                                echo "</div>";
                            } elseif ($field == 'kewarganegaraan') {
                                echo "<div class='mb-3 has-feedback'><label class='form-label'>" . strtoupper($label) . "</label>";
                                echo createSelect('kewarganegaraan', array_combine(CITIZENSHIP_OPTIONS, CITIZENSHIP_OPTIONS), $value);
                                echo "</div>";
                            } else {
                                $class = is_numeric($data[$field] ?? '') ? "" : "capitalize";
                                echo createInputField($field, $label, htmlspecialchars($value), 'text', $class);
                            }
                        }
                        ?>
                        <div class="mb-3">
                            <label class="form-label" for="beasiswa">BEASISWA YANG DIPEROLEH</label>
                            <?= createSelect('beasiswa', BEASISWA_OPTIONS, $data['beasiswa'] ?? '') ?>
                        </div>
                        <div id="kip_field" class="mb-3" style="display:none;">
                            <?= createInputField('no_kip', 'No. KIP (6 Digit)', htmlspecialchars($data['no_kip'] ?? ''), 'text', '', false) ?>
                        </div>
                        <div id="kks_field" class="mb-3" style="display:none;">
                            <?= createInputField('no_kks', 'No. KKS', htmlspecialchars($data['no_kks'] ?? ''), 'text', '', false) ?>
                        </div>
                        <div class="mt-3 text-end">
                            <button type="button" class="btn btn-success btnSimpan" data-tab="biodata">SIMPAN BIODATA</button>
                        </div>
                    </div>

                    <!-- ==================== TAB ALAMAT ==================== -->
                    <div class="tab-pane fade" id="alamat" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <?= createInputField('rt', 'RT', htmlspecialchars($data['rt'] ?? '')) ?>
                            </div>
                            <div class="col-md-6">
                                <?= createInputField('rw', 'RW', htmlspecialchars($data['rw'] ?? '')) ?>
                            </div>
                        </div>
                        <div class="mb-3 has-feedback">
                            <label class="form-label">PROVINSI</label>
                            <select id="provinsi_select" class="form-select" required>
                                <option value="">-- MEMUAT PROVINSI --</option>
                            </select>
                            <input type="hidden" name="provinsi" id="provinsi_hidden" value="<?= htmlspecialchars($data['provinsi'] ?? '') ?>">
                        </div>
                        <div class="mb-3 has-feedback">
                            <label class="form-label">KABUPATEN / KOTA</label>
                            <select id="kabupaten_select" name="kabupaten" class="form-select" required disabled>
                                <option value="">-- PILIH PROVINSI TERLEBIH DAHULU --</option>
                            </select>
                        </div>
                        <div class="mb-3 has-feedback">
                            <label class="form-label">KECAMATAN</label>
                            <select id="kecamatan_select" name="kecamatan" class="form-select" required disabled>
                                <option value="">-- PILIH KABUPATEN TERLEBIH DAHULU --</option>
                            </select>
                        </div>
                        <div class="mb-3 has-feedback">
                            <label class="form-label">KELURAHAN / DESA</label>
                            <select id="kelurahan_select" name="kelurahan" class="form-select" required disabled>
                                <option value="">-- PILIH KECAMATAN TERLEBIH DAHULU --</option>
                            </select>
                        </div>
                        
                        <?= createInputField('kode_pos', 'KODE POS', htmlspecialchars($data['kode_pos'] ?? ''), 'text', '', true, true) ?>

                        <?= createInputField('lintang', 'LINTANG', htmlspecialchars($data['lintang'] ?? ''), 'text', '', false, true) ?>
                        <?= createInputField('bujur', 'BUJUR', htmlspecialchars($data['bujur'] ?? ''), 'text', '', false, true) ?>
                        
                        <div id="spinner-alamat" class="spinner-border spinner-border-sm ms-2" role="status" style="display:none;"><span class="visually-hidden">Loading...</span></div>

                        <div class="mt-3 text-end">
                            <button type="button" class="btn btn-success btnSimpan" data-tab="alamat">SIMPAN ALAMAT</button>
                        </div>
                    </div>

                    <!-- ==================== TAB ORANG TUA ==================== -->
                    <div class="tab-pane fade" id="ortu" role="tabpanel">
                        <?php
                        $parent_options = ['status' => PARENT_STATUS_OPTIONS, 'kewargaan' => PARENT_CITIZENSHIP_OPTIONS, 'pendidikan' => PARENT_EDUCATION_OPTIONS, 'pekerjaan' => PARENT_OCCUPATION_OPTIONS, 'penghasilan' => PARENT_INCOME_OPTIONS];
                        echo generateParentFields('ayah', $data, $parent_options);
                        echo generateParentFields('ibu', $data, $parent_options);
                        ?>
                        
                        <!-- Logika untuk menampilkan input file atau info file dengan tombol hapus -->
                        <div class="mb-3 mt-4">
                            <label class="form-label">KARTU KELUARGA (KK)</label>
                            <?php if (!empty($data['kk_ibu'])): ?>
                                <div class="alert alert-success d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="bi bi-file-earmark-check-fill"></i>
                                        <a href="../uploads/kk/<?= htmlspecialchars($data['kk_ibu']) ?>" target="_blank" class="fw-bold ms-2">
                                            <?= htmlspecialchars($data['kk_ibu']) ?>
                                        </a>
                                    </div>
                                    <button type="button" id="btnHapusKk" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </div>
                            <?php else: ?>
                                <input type='file' name='kk_ibu' id='kk_ibu' class='form-control'>
                                <small class="form-text text-muted">Upload file KK Anda. Tipe file yang diizinkan: PDF, JPG, PNG.</small>
                            <?php endif; ?>
                        </div>

                        <div class="mt-3 text-end">
                            <button type="button" class="btn btn-success btnSimpan" data-tab="ortu">SIMPAN DATA ORANG TUA</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(function() {
    const IS_LOCKED = <?= $isLocked ? 'true' : 'false' ?>;

    if (IS_LOCKED) {
        const $form = $('#formBiodataSiswa');
        $form.find('input, select, textarea, button').prop('disabled', true);
        // tetap izinkan melihat tab
        $('.nav-link').prop('disabled', false);
        $('.btnSimpan').hide();
    }
    /**
     * Memvalidasi field dalam tab tertentu.
     * @param {string} tabId ID dari elemen tab (misal, 'biodata')
     * @returns {boolean} true jika semua field wajib valid
     */
    function validateTab(tabId) {
        let isValid = true;
        $(`#${tabId} .has-feedback`).each(function() {
            const $field = $(this).find('input, select, textarea');
            if (!$field.length || $field.prop('disabled') || $field.prop('readonly')) return;

            $field.removeClass('is-valid is-invalid');
            $(this).find('.bi').remove();

            const isRequired = $field.prop('required');
            const value = ($field.val() || '').toString().trim();

            if (isRequired && !value) {
                isValid = false;
                $field.addClass('is-invalid');
                $(this).append('<i class="bi bi-x-circle-fill text-danger"></i>');
            } else if (isRequired && value) {
                $field.addClass('is-valid');
                $(this).append('<i class="bi bi-check-circle-fill text-success"></i>');
            }
        });
        return isValid;
    }

    // Tombol Simpan
    $('.btnSimpan').on('click', function() {
        const tab = $(this).data('tab');
        if (!validateTab(tab)) {
            alert('Harap lengkapi semua field wajib pada tab ini!');
            return;
        }

        const formData = new FormData($('#formBiodataSiswa')[0]);
        formData.append('tab', tab);

        $.ajax({
            type: 'POST',
            url: 'aksi_simpan.php',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                if (response.status === 'success') {
                    location.reload();
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat menyimpan data.');
            }
        });
    });

    // --- [PERBAIKAN V2] LOGIKA ALAMAT BERTINGKAT ---
    const LOKAL_API = 'get_wilayah.php';
    
    const $provinsi = $('#provinsi_select');
    const $kabupaten = $('#kabupaten_select');
    const $kecamatan = $('#kecamatan_select');
    const $kelurahan = $('#kelurahan_select');
    const $spinner = $('#spinner-alamat');

    const dataAlamat = {
        provinsi: "<?= htmlspecialchars($data['provinsi'] ?? '') ?>",
        kabupaten: "<?= htmlspecialchars($data['kabupaten'] ?? '') ?>",
        kecamatan: "<?= htmlspecialchars($data['kecamatan'] ?? '') ?>",
        kelurahan: "<?= htmlspecialchars($data['kelurahan'] ?? '') ?>"
    };

    /**
     * Helper function to select an option in a dropdown by its text, case-insensitively.
     * @param {jQuery} $selectElement The jQuery object for the select dropdown.
     * @param {string} textToSelect The text of the option to select.
     * @returns {boolean} True if an option was selected, false otherwise.
     */
    function selectOptionByText($selectElement, textToSelect) {
        if (!textToSelect) return false;
        let found = false;
        $selectElement.find('option').each(function() {
            if ($(this).text().toUpperCase() === textToSelect.toUpperCase()) {
                $(this).prop('selected', true);
                found = true;
                return false; // Break the loop
            }
        });
        return found;
    }

    // --- Event Handlers untuk Interaksi Pengguna ---
    $provinsi.on('change', function() {
        const provName = $(this).val();
        $('#provinsi_hidden').val(provName || '');
        loadKabupaten(provName);
    });

    $kabupaten.on('change', function() {
        const provName = $provinsi.val();
        const kabName = $(this).val();
        loadKecamatan(provName, kabName);
    });

    $kecamatan.on('change', function() {
        const provName = $provinsi.val();
        const kabName = $kabupaten.val();
        const kecName = $(this).val();
        loadKelurahan(provName, kabName, kecName);
    });

    $kelurahan.on('change', function() {
        const provName = $provinsi.val();
        const kabName = $kabupaten.val();
        const kecName = $kecamatan.val();
        const kelName = $(this).val();
        loadDetail(provName, kabName, kecName, kelName);
    });

    // --- Fungsi untuk Memuat Data Wilayah ---
    function populateDropdown($element, data, placeholder) {
        $element.empty().append(`<option value="">${placeholder}</option>`);
        data.forEach(item => $element.append(`<option value="${item}">${item}</option>`));
        $element.prop('disabled', false);
    }

    function loadKabupaten(provName, callback) {
        $kabupaten.empty().prop('disabled', true);
        $kecamatan.empty().prop('disabled', true);
        $kelurahan.empty().prop('disabled', true);
        $('#kode_pos, #lintang, #bujur').val('');
        if (!provName) return;

        $kabupaten.html('<option>MEMUAT...</option>');
        $.getJSON(LOKAL_API, { action: 'kabupaten', provinsi: provName }).done(function(res) {
            if (res && !res.error) {
                populateDropdown($kabupaten, res, '-- PILIH KABUPATEN/KOTA --');
                if (callback) callback();
            }
        });
    }

    function loadKecamatan(provName, kabName, callback) {
        $kecamatan.empty().prop('disabled', true);
        $kelurahan.empty().prop('disabled', true);
        $('#kode_pos, #lintang, #bujur').val('');
        if (!kabName) return;

        $kecamatan.html('<option>MEMUAT...</option>');
        $.getJSON(LOKAL_API, { action: 'kecamatan', provinsi: provName, kabupaten: kabName }).done(function(res) {
            if (res && !res.error) {
                populateDropdown($kecamatan, res, '-- PILIH KECAMATAN --');
                if (callback) callback();
            }
        });
    }

    function loadKelurahan(provName, kabName, kecName, callback) {
        $kelurahan.empty().prop('disabled', true);
        $('#kode_pos, #lintang, #bujur').val('');
        if (!kecName) return;

        $kelurahan.html('<option>MEMUAT...</option>');
        $.getJSON(LOKAL_API, { action: 'kelurahan', provinsi: provName, kabupaten: kabName, kecamatan: kecName }).done(function(res) {
            if (res && !res.error) {
                populateDropdown($kelurahan, res, '-- PILIH KELURAHAN/DESA --');
                if (callback) callback();
            }
        });
    }

    function loadDetail(provName, kabName, kecName, kelName) {
        $('#kode_pos, #lintang, #bujur').val('');
        if (!kelName) return;

        $spinner.show();
        $.getJSON(LOKAL_API, { action: 'detail', provinsi: provName, kabupaten: kabName, kecamatan: kecName, kelurahan: kelName })
            .done(function(res) {
                if (res && !res.error) {
                    $('#kode_pos').val(res.kodepos || 'TIDAK DITEMUKAN');
                    const alamatLengkap = `${kelName}, ${kecName}, ${kabName}, ${provName}`;
                    $.getJSON('https://nominatim.openstreetmap.org/search', { q: alamatLengkap, format: 'json', limit: 1 })
                        .done(function(nominatimRes) {
                            if (nominatimRes.length > 0) {
                                $('#lintang').val(nominatimRes[0].lat);
                                $('#bujur').val(nominatimRes[0].lon);
                            }
                        })
                        .always(function() { $spinner.hide(); });
                } else {
                    $('#kode_pos').val('TIDAK DITEMUKAN');
                    $spinner.hide();
                }
            })
            .fail(function() { $spinner.hide(); });
    }

    // --- Fungsi Inisialisasi Saat Halaman Dimuat ---
    function initAlamat() {
        // 1. Muat Provinsi
        $.getJSON(LOKAL_API, { action: 'provinsi' }).done(function(res) {
            if (!res || res.error) {
                alert(res.error || 'Gagal memuat data provinsi.');
                return;
            }
            populateDropdown($provinsi, res, '-- PILIH PROVINSI --');

            // 2. Jika ada data provinsi, pilih dan muat kabupaten
            if (dataAlamat.provinsi && selectOptionByText($provinsi, dataAlamat.provinsi)) {
                loadKabupaten(dataAlamat.provinsi, function() {
                    // 3. Jika ada data kabupaten, pilih dan muat kecamatan
                    if (dataAlamat.kabupaten && selectOptionByText($kabupaten, dataAlamat.kabupaten)) {
                        loadKecamatan(dataAlamat.provinsi, dataAlamat.kabupaten, function() {
                            // 4. Jika ada data kecamatan, pilih dan muat kelurahan
                            if (dataAlamat.kecamatan && selectOptionByText($kecamatan, dataAlamat.kecamatan)) {
                                loadKelurahan(dataAlamat.provinsi, dataAlamat.kabupaten, dataAlamat.kecamatan, function() {
                                    // 5. Jika ada data kelurahan, pilih dan muat detail
                                    if (dataAlamat.kelurahan && selectOptionByText($kelurahan, dataAlamat.kelurahan)) {
                                        loadDetail(dataAlamat.provinsi, dataAlamat.kabupaten, dataAlamat.kecamatan, dataAlamat.kelurahan);
                                    }
                                });
                            }
                        });
                    }
                });
            }
        });
    }

    initAlamat(); // Panggil fungsi inisialisasi

    // --- AKHIR LOGIKA ALAMAT ---

    // [PERUBAHAN] Tombol Hapus KK sekarang memanggil aksi_simpan.php
    $(document).on('click', '#btnHapusKk', function() {
        if (!confirm('Apakah Anda yakin ingin menghapus file Kartu Keluarga ini? Tindakan ini tidak dapat dibatalkan.')) {
            return;
        }

        const id_siswa = $('input[name="id_siswa"]').val();

        $.ajax({
            type: 'POST',
            url: 'aksi_simpan.php', // Mengarah ke file simpan utama
            data: { 
                id_siswa: id_siswa,
                action: 'hapus_kk' // Menambahkan parameter aksi
            },
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                if (response.status === 'success') {
                    location.reload(); // Muat ulang halaman untuk memperbarui UI
                }
            },
            error: function() {
                alert('Terjadi kesalahan. Gagal menghubungi server.');
            }
        });
    });

    // Toggle KIP/KKS
    $('#beasiswa').on('change', function() {
        $('#kip_field').toggle(this.value === 'KIP').find('input').prop('required', this.value === 'KIP');
        $('#kks_field').toggle(this.value === 'PKH').find('input').prop('required', this.value === 'PKH');
    }).trigger('change');

    // Toggle field orang tua jika meninggal
    $('#status_ayah, #status_ibu').on('change', function() {
        const parentType = this.id.includes('ayah') ? 'ayah' : 'ibu';
        const isDeceased = $(this).val() === 'SUDAH MENINGGAL';
        const fieldsToToggle = ['kewarganegaraan', 'tempat_lahir', 'tgl_lahir', 'pendidikan', 'pekerjaan', 'penghasilan', 'no_hp'];

        fieldsToToggle.forEach(field => {
            const $targetField = $(`#${field}_${parentType}`);
            $targetField.prop('disabled', isDeceased).prop('required', !isDeceased);
            if (isDeceased) $targetField.val('');
        });
    }).trigger('change');

    // Input uppercase
    $('input.capitalize, textarea.capitalize').on('input blur', function() {
        this.value = this.value.toUpperCase();
    });
});
</script>
</body>
</html>
