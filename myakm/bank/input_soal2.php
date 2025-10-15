<?php
// Pastikan file ini di-include dari file utama, jangan diakses langsung
// defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

// Asumsikan variabel $koneksi (koneksi database) dan fungsi enkripsi() sudah ada
// Jika belum, sertakan file koneksi dan fungsi Anda di sini.
// include_once('../config/koneksi.php'); 
// include_once('../config/fungsi.php');

// Pencegahan error jika parameter GET tidak ada
$id_bank = $_GET['id'] ?? null;
$nomor_url = $_GET['no'] ?? null;
$jenis = $_GET['jenis'] ?? null;

if (!$id_bank || !$nomor_url || !$jenis) {
    die("Parameter tidak lengkap. Anda tidak dizinkan mengakses script ini!");
}

// Mengambil nomor soal terakhir + 1
$nom_query = mysqli_query($koneksi, "SELECT MAX(nomor) AS nomer FROM soal WHERE id_bank='$id_bank'");
$nom = mysqli_fetch_array($nom_query);
$nomor = ($nom['nomer']) ? $nom['nomer'] + 1 : 1;

// Mengambil data bank soal
$mapel_query = mysqli_query($koneksi, "SELECT * FROM banksoal WHERE id_bank='$id_bank'");
$mapel = mysqli_fetch_array($mapel_query);

// Mengambil data soal yang sudah ada (jika dalam mode edit)
$soal_query = mysqli_query($koneksi, "SELECT * FROM soal WHERE id_bank='$id_bank' AND nomor='$nomor' AND jenis='$jenis'");
$soal = mysqli_fetch_array($soal_query);

// Inisialisasi variabel jawaban untuk menghindari error
$jwbA = $jwbB = $jwbC = $jwbD = $jwbE = '';
if ($soal) {
    $jawaban_array = is_string($soal['jawaban']) ? explode(',', $soal['jawaban']) : [];
    $jwbA = in_array('A', $jawaban_array) ? 'checked' : '';
    $jwbB = in_array('B', $jawaban_array) ? 'checked' : '';
    $jwbC = in_array('C', $jawaban_array) ? 'checked' : '';
    $jwbD = in_array('D', $jawaban_array) ? 'checked' : '';
    if ($mapel['opsi'] == 5) {
        $jwbE = in_array('E', $jawaban_array) ? 'checked' : '';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Soal</title>
    <!-- Asumsikan CSS library (seperti Bootstrap) sudah dimuat di halaman utama -->

    <!-- =================================================================== -->
    <!-- BAGIAN 1: CSS & FONT UNTUK CHATBOT (DARI KODE PERTAMA) -->
    <!-- =================================================================== -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <style>
        /* Styling untuk radio button custom */
        .radio-label { display: inline-block; cursor: pointer; }
        .hidden { display: none; }
        .check { display: inline-block; width: 20px; height: 20px; border: 2px solid #007bff; border-radius: 50%; position: relative; }
        .hidden:checked + .check::before { content: ''; display: block; width: 12px; height: 12px; background: #007bff; border-radius: 50%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); }
        .kanan { float: right; margin-left: 5px; }

        /* Styling untuk Chatbot */
        .chatbot-toggler {
            position: fixed;
            right: 35px;
            bottom: 30px;
            height: 50px;
            width: 50px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            z-index: 1050;
            transition: all 0.2s ease;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }
        body.show-chatbot .chatbot-toggler {
            transform: rotate(90deg);
        }
        .chatbot-toggler span {
            position: absolute;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }
        .chatbot-toggler span:last-child {
            opacity: 0;
            transform: rotate(-90deg);
        }
        body.show-chatbot .chatbot-toggler span:first-child {
            opacity: 0;
            transform: rotate(90deg);
        }
        body.show-chatbot .chatbot-toggler span:last-child {
            opacity: 1;
            transform: rotate(0deg);
        }
        .chatbot {
            position: fixed;
            right: 35px;
            bottom: 100px;
            width: 420px;
            max-width: 90%;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 0 128px 0 rgba(0,0,0,0.1), 0 32px 64px -48px rgba(0,0,0,0.5);
            transform: scale(0.5);
            opacity: 0;
            pointer-events: none;
            overflow: hidden;
            z-index: 1040;
            transition: all 0.2s ease;
            transform-origin: bottom right;
        }
        body.show-chatbot .chatbot {
            transform: scale(1);
            opacity: 1;
            pointer-events: auto;
        }
        .chatbot .chatbox-header {
            background: #007bff;
            padding: 16px 0;
            text-align: center;
            color: #fff;
            position: relative;
        }
        .chatbot .chatbox-header h2 {
            font-size: 1.4rem;
            margin: 0;
        }
        .chatbot .chatbox {
            height: 400px;
            overflow-y: auto;
            padding: 30px 20px 100px;
            background-color: #f1f1f1;
            list-style: none;
            margin: 0;
        }
        .chatbox .chat {
            display: flex;
            margin-bottom: 15px;
        }
        .chatbox .chat p {
            max-width: 75%;
            font-size: 0.95rem;
            padding: 12px 16px;
            border-radius: 18px;
            background: #007bff;
            color: #fff;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .chatbox .incoming p {
            background: #e9e9e9;
            color: #000;
            border-radius: 18px 18px 18px 0;
        }
        .chatbox .chat.outgoing {
            justify-content: flex-end;
        }
        .chatbox .outgoing p {
             border-radius: 18px 18px 0 18px;
        }
        .chat-input {
            position: absolute;
            bottom: 0;
            width: 100%;
            display: flex;
            gap: 5px;
            background: #fff;
            padding: 8px 20px;
            border-top: 1px solid #ccc;
        }
        .chat-input textarea {
            height: 55px;
            width: 100%;
            border: none;
            outline: none;
            font-size: 0.95rem;
            resize: none;
            padding: 16px 15px 16px 0;
        }
        .chat-input span {
            font-size: 1.75rem;
            color: #007bff;
            cursor: pointer;
            align-self: center;
            line-height: 55px;
            visibility: hidden;
        }
        .chat-input textarea:valid ~ span {
            visibility: visible;
        }
    </style>
</head>
<body>

<form id='formsoal' action='?pg=<?= enkripsi('soal_save') ?>' method='post' enctype='multipart/form-data'>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class='btn-group' style='margin-top:-5px'>
                        <label class='btn btn-sm btn-outline-primary'>Mapel</label>
                        <label class='btn btn-sm btn-outline-primary'><?= $mapel['nama'] ?></label>
                        <label class='btn btn-sm btn-danger'>No Soal: <b><?= $nomor ?></b></label>
                    </div>
                    <button type='submit' name='simpansoal' onclick="tinyMCE.triggerSave(true,true);" class='btn btn-sm btn-primary kanan'>Simpan Soal</button>
                    <a href='?pg=<?= enkripsi('banksoal') ?>&ac=lihat&id=<?= $id_bank ?>' class='btn btn-sm btn-outline-danger kanan'>Kembali</a>
                </div>
            </div>
        </div>
    </div>

    <input type='hidden' name='id_bank' value='<?= $id_bank ?>'>
    <input type='hidden' name='jenis' value='<?= $jenis ?>'>
    <input type='hidden' name='nomor' value='<?= $nomor ?>'>
    <!-- Jika ini adalah form edit, sertakan ID soal -->
    <?php if ($soal): ?>
        <input type='hidden' name='id_soal' value='<?= $soal['id_soal'] ?>'>
    <?php endif; ?>

    <div class='row'>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h5 class="card-title">Pertanyaan Soal</h5></div>
                <div class="card-body">
                    <textarea name='isi_soal' class='editor1' rows='10' cols='80' style='width:100%;'><?= $soal['soal'] ?? '' ?></textarea>
                    <!-- Lanjutan untuk file upload -->
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h5 class="card-title">Opsi dan Kunci Jawaban</h5></div>
                <div class="card-body">
                    <div class="row mb-3">
                        <label class="col-md-4 col-form-label fw-bold">Skor Jawaban Benar</label>
                        <div class="col-md-4">
                            <input type='number' name='skor' value="<?= $soal['bobot'] ?? 1 ?>" class='form-control' required />
                        </div>
                    </div>
                    
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation"><button class="nav-link active" id="pills-A-tab" data-bs-toggle="pill" data-bs-target="#pills-A" type="button" role="tab">A</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link" id="pills-B-tab" data-bs-toggle="pill" data-bs-target="#pills-B" type="button" role="tab">B</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link" id="pills-C-tab" data-bs-toggle="pill" data-bs-target="#pills-C" type="button" role="tab">C</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link" id="pills-D-tab" data-bs-toggle="pill" data-bs-target="#pills-D" type="button" role="tab">D</button></li>
                        <?php if ($mapel['opsi'] == 5): ?>
                        <li class="nav-item" role="presentation"><button class="nav-link" id="pills-E-tab" data-bs-toggle="pill" data-bs-target="#pills-E" type="button" role="tab">E</button></li>
                        <?php endif; ?>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        <!-- OPSI A -->
                        <div class="tab-pane fade show active" id="pills-A" role="tabpanel">
                            <label class="radio-label"><input class='hidden' type='checkbox' name='jawaban[]' value='A' <?= $jwbA ?> /><span class="check"></span> Kunci Jawaban</label>
                            <textarea name='pilA' class='editor1 pilihan form-control'><?= $soal['pilA'] ?? '' ?></textarea>
                        </div>
                        <!-- OPSI B -->
                        <div class="tab-pane fade" id="pills-B" role="tabpanel">
                            <label class="radio-label"><input class='hidden' type='checkbox' name='jawaban[]' value='B' <?= $jwbB ?> /><span class="check"></span> Kunci Jawaban</label>
                            <textarea name='pilB' class='editor1 pilihan form-control'><?= $soal['pilB'] ?? '' ?></textarea>
                        </div>
                        <!-- OPSI C -->
                        <div class="tab-pane fade" id="pills-C" role="tabpanel">
                            <label class="radio-label"><input class='hidden' type='checkbox' name='jawaban[]' value='C' <?= $jwbC ?> /><span class="check"></span> Kunci Jawaban</label>
                            <textarea name='pilC' class='editor1 pilihan form-control'><?= $soal['pilC'] ?? '' ?></textarea>
                        </div>
                        <!-- OPSI D -->
                        <div class="tab-pane fade" id="pills-D" role="tabpanel">
                            <label class="radio-label"><input class='hidden' type='checkbox' name='jawaban[]' value='D' <?= $jwbD ?> /><span class="check"></span> Kunci Jawaban</label>
                            <textarea name='pilD' class='editor1 pilihan form-control'><?= $soal['pilD'] ?? '' ?></textarea>
                        </div>
                        <!-- OPSI E -->
                        <?php if ($mapel['opsi'] == 5): ?>
                        <div class="tab-pane fade" id="pills-E" role="tabpanel">
                            <label class="radio-label"><input class='hidden' type='checkbox' name='jawaban[]' value='E' <?= $jwbE ?> /><span class="check"></span> Kunci Jawaban</label>
                            <textarea name='pilE' class='editor1 pilihan form-control'><?= $soal['pilE'] ?? '' ?></textarea>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- =================================================================== -->
<!-- BAGIAN 2: HTML UNTUK CHATBOT (DARI KODE PERTAMA) -->
<!-- =================================================================== -->
<button class="chatbot-toggler">
    <span class="material-symbols-outlined">smart_toy</span>
    <span class="material-symbols-outlined">close</span>
</button>
<div class="chatbot">
    <div class="chatbox-header">
        <h2>Asisten AI 🤖</h2>
    </div>
    <ul class="chatbox">
        <li class="chat incoming">
            <p>Halo! 👋<br>Ada yang bisa saya bantu untuk membuat soal ini?</p>
        </li>
    </ul>
    <div class="chat-input">
        <textarea placeholder="Ketik pertanyaan Anda..." required></textarea>
        <span id="send-btn" class="material-symbols-outlined">send</span>
    </div>
</div>

<!-- Asumsikan jQuery dan TinyMCE sudah dimuat -->
<script src="path/to/jquery.min.js"></script>
<script src="path/to/tinymce/tinymce.min.js"></script>

<script>
// Inisialisasi TinyMCE
tinymce.init({
    selector: '.editor1',
    plugins: [
        'advlist autolink lists link image charmap print preview hr anchor pagebreak',
        'searchreplace wordcount visualblocks visualchars code fullscreen',
        'insertdatetime media nonbreaking save table contextmenu directionality',
        'emoticons template paste textcolor colorpicker textpattern imagetools'
    ],
    toolbar: 'bold italic fontselect fontsizeselect | alignleft aligncenter alignright bullist numlist backcolor forecolor | code | link image media',
    fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
    paste_data_images: true,
    images_upload_handler: function(blobInfo, success, failure) {
        success('data:' + blobInfo.blob().type + ';base64,' + blobInfo.base64());
    },
    setup: function(editor) {
        editor.on('change', function() {
            tinymce.triggerSave();
        });
    }
});

// Script untuk form submission (jika menggunakan AJAX)
$('#formsoal').submit(function(e) {
    // Jika Anda ingin menggunakan AJAX, letakkan kodenya di sini.
    // Jika tidak, biarkan form melakukan submit normal ke action yang ditentukan.
});
</script>

<!-- =================================================================== -->
<!-- BAGIAN 3: JAVASCRIPT UNTUK CHATBOT (DARI KODE PERTAMA) -->
<!-- =================================================================== -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const chatbotToggler = document.querySelector(".chatbot-toggler");
    const chatInput = document.querySelector(".chat-input textarea");
    const sendChatBtn = document.querySelector(".chat-input #send-btn");
    const chatbox = document.querySelector(".chatbox");
    const body = document.querySelector("body");

    let userMessage;
    
    // 💡 PENTING: Pastikan path ini benar sesuai struktur folder Anda!
    // Path ini harus menunjuk ke file PHP yang memproses permintaan AI.
    const API_URL = "materi/ai_search.php"; 

    const createChatLi = (message, className) => {
        const chatLi = document.createElement("li");
        chatLi.classList.add("chat", className);
        let chatContent = `<p>${message}</p>`;
        chatLi.innerHTML = chatContent;
        return chatLi;
    }

    const generateResponse = (incomingChatLi) => {
        const messageElement = incomingChatLi.querySelector("p");
        const requestOptions = {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ message: userMessage })
        }

        // Mengirim request ke backend AI
        fetch(API_URL, requestOptions)
            .then(res => res.json())
            .then(data => {
                messageElement.textContent = data.reply || "Maaf, terjadi kesalahan di server.";
            }).catch(() => {
                messageElement.textContent = "Oops! Tidak dapat terhubung ke server. Silakan coba lagi nanti.";
            }).finally(() => chatbox.scrollTo(0, chatbox.scrollHeight));
    }

    const handleChat = () => {
        userMessage = chatInput.value.trim();
        if(!userMessage) return;
        
        chatInput.value = "";
        chatInput.style.height = "auto";
        chatInput.style.height = `${chatInput.scrollHeight}px`;

        chatbox.appendChild(createChatLi(userMessage, "outgoing"));
        chatbox.scrollTo(0, chatbox.scrollHeight);

        setTimeout(() => {
            const incomingChatLi = createChatLi("Sedang berpikir...", "incoming");
            chatbox.appendChild(incomingChatLi);
            chatbox.scrollTo(0, chatbox.scrollHeight);
            generateResponse(incomingChatLi);
        }, 600);
    }
    
    chatInput.addEventListener("input", () => {
        // Atur tinggi textarea secara dinamis
        chatInput.style.height = "auto";
        chatInput.style.height = `${chatInput.scrollHeight}px`;
    });

    sendChatBtn.addEventListener("click", handleChat);
    chatbotToggler.addEventListener("click", () => body.classList.toggle("show-chatbot"));
    chatInput.addEventListener("keydown", (e) => {
        // Kirim pesan dengan menekan Enter (tanpa Shift)
        if(e.key === "Enter" && !e.shiftKey) {
            e.preventDefault();
            handleChat();
        }
    });
});
</script>

</body>
</html>
