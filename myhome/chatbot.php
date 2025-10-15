<?php
// Ambil daftar kelas dari database untuk dropdown
// Pastikan variabel $koneksi sudah ada dari file utama jika diperlukan
// atau buat koneksi baru yang aman di sini.
// @include '../config/koneksi.php'; // Baris ini bisa diaktifkan jika file ini dipanggil terpisah
$query_kelas = "SELECT DISTINCT kelas FROM siswa ORDER BY kelas ASC";
$result_kelas = mysqli_query($koneksi, $query_kelas);
?>

<!-- Bagian CSS untuk Chatbot -->
<style>
    #chatbot-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
    }

    #chatbot-trigger {
        width: auto;
        height: 55px;
        padding: 0 20px;
        background-color: #326698;
        color: white;
        border-radius: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s;
    }

    #chatbot-trigger:hover {
        transform: scale(1.05);
    }

    #chatbot-trigger .fa {
        font-size: 22px;
        margin-right: 10px;
    }

    #chatbot-window {
        display: none;
        width: 350px;
        height: 450px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        flex-direction: column;
        overflow: hidden;
    }

    /* Penyesuaian untuk mobile */
    @media (max-width: 600px) {
        #chatbot-window {
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            border-radius: 0;
        }
    }

    .chatbot-header {
        padding: 15px;
        background-color: #326698;
        color: white;
        font-weight: bold;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    #close-chatbot {
        cursor: pointer;
        font-size: 15px;
        font-weight: normal;
        align-self: center;
    }

    .chatbot-messages {
        flex-grow: 1;
        padding: 15px;
        overflow-y: auto;
        background-color: #f4f7f9;
        display: flex;
        flex-direction: column;
    }

    .message {
        margin-bottom: 12px;
        padding: 8px 12px;
        border-radius: 18px;
        max-width: 85%;
        line-height: 1.4;
    }

    .bot-message {
        background-color: #e9e9eb;
        align-self: flex-start;
        border-bottom-left-radius: 4px;
    }

    .user-message {
        background-color: #007bff;
        color: white;
        align-self: flex-end;
        border-bottom-right-radius: 4px;
    }

    .chatbot-form-container {
        padding: 15px;
        border-top: 1px solid #ddd;
        background-color: #fff;
    }

    .chatbot-form-container .form-group {
        margin-bottom: 10px;
    }
</style>

<!-- Bagian HTML untuk Chatbot -->
<div id="chatbot-container">
    <div id="chatbot-window">
        <div class="chatbot-header">
            <span>Tanya Akun SIAKAD</span>
            <span id="close-chatbot">Tutup &times;</span>
        </div>
        <div class="chatbot-messages" id="chatbot-messages">
            <div class="message bot-message">
                Hai! 👋 Lupa username atau password? Saya bisa bantu. Silakan isi nama lengkap dan kelasmu di bawah ini.
            </div>
        </div>
        <div class="chatbot-form-container">
            <form id="form-chatbot">
                <div class="form-group">
                    <input type="text" class="form-control" id="chatbot-nama" placeholder="Ketik Nama Lengkap...">
                </div>
                <div class="form-group">
                    <select class="form-control" id="chatbot-kelas">
                        <option value="">-- Pilih Kelas --</option>
                        <?php
                        if ($result_kelas && mysqli_num_rows($result_kelas) > 0) {
                            while ($row = mysqli_fetch_assoc($result_kelas)) {
                                echo "<option value='" . htmlspecialchars($row['kelas']) . "'>" . htmlspecialchars($row['kelas']) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <button type="button" id="btn-cari-akun" class="btn btn-primary btn-block">Cari Akun Saya</button>
            </form>
        </div>
    </div>
    <div id="chatbot-trigger">
        <i class="fa fa-comments"></i> TANYA SAYA
    </div>
</div>

<!-- Bagian JavaScript untuk Chatbot -->
<script>
$(document).ready(function() {
    // Fungsi untuk menampilkan/menyembunyikan chatbot
    $('#chatbot-trigger').click(function() {
        $('#chatbot-window').css('display', 'flex').slideDown();
        $(this).hide();
    });

    $('#close-chatbot').click(function() {
        $('#chatbot-window').slideUp(function() {
             $('#chatbot-trigger').show();
        });
    });

    // Fungsi saat tombol cari akun diklik
    $('#btn-cari-akun').click(function(e) {
        e.preventDefault();

        var namaLengkap = $('#chatbot-nama').val().trim();
        var kelas = $('#chatbot-kelas').val();
        
        if (!namaLengkap || !kelas) {
            var errorHtml = '<div class="message bot-message">Harap isi nama lengkap dan pilih kelas terlebih dahulu.</div>';
            $('#chatbot-messages').append(errorHtml).scrollTop($('#chatbot-messages')[0].scrollHeight);
            return;
        }

        var userHtml = '<div class="d-flex justify-content-end"><div class="message user-message">Cek: ' + namaLengkap + ' (' + kelas + ')</div></div>';
        $('#chatbot-messages').append(userHtml);
        $('#chatbot-messages').scrollTop($('#chatbot-messages')[0].scrollHeight);

        $.ajax({
            type: 'POST',
            url: 'chatbot_handler.php',
            data: {
                nama: namaLengkap,
                kelas: kelas
            },
            dataType: 'json',
            beforeSend: function() {
                $('#chatbot-messages').append('<div class="message bot-message" id="loading-msg">Mencari data...</div>');
                $('#chatbot-messages').scrollTop($('#chatbot-messages')[0].scrollHeight);
            },
            success: function(response) {
                $('#loading-msg').remove();
                
                var botHtml = '<div class="message bot-message">' + response.message + '</div>';
                $('#chatbot-messages').append(botHtml);

                if (response.status === 'success') {
                    $('#username').val(response.username);
                    $('#password').val(response.password);
                    
                    var infoHtml = '<div class="message bot-message">Data sudah saya isikan di form login. Silakan klik tombol "Masuk".</div>';
                    $('#chatbot-messages').append(infoHtml);

                    setTimeout(function() {
                        $('#close-chatbot').click();
                    }, 3000);
                }
                
                $('#chatbot-messages').scrollTop($('#chatbot-messages')[0].scrollHeight);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('#loading-msg').remove();
                var errorMsg = "Oops! Terjadi kesalahan teknis. Silakan coba lagi nanti.";

                if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                    errorMsg = jqXHR.responseJSON.message;
                } else if (jqXHR.status == 404) {
                    errorMsg = "Error: File 'chatbot_handler.php' tidak ditemukan.";
                }
                
                var errorHtml = '<div class="message bot-message">' + errorMsg + '</div>';
                $('#chatbot-messages').append(errorHtml);
                $('#chatbot-messages').scrollTop($('#chatbot-messages')[0].scrollHeight);
            }
        });
        
        $('#chatbot-nama').val('');
    });
});
</script>
