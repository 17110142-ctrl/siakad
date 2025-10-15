<form action="proses_upload.php" method="post" enctype="multipart/form-data">
    <label>Nama File:</label>
    <input type="text" name="nama_file" required class="form-control"><br>

    <label>Kategori:</label>
    <select name="kategori" required class="form-control">
        <option value="Surat Masuk">Surat Masuk</option>
        <option value="Surat Keluar">Surat Keluar</option>
        <option value="Surat Tugas">Surat Tugas</option>
    </select><br>

    <label>Upload File (PDF/DOCX):</label>
    <input type="file" name="file" required class="form-control"><br>

    <button type="submit" class="btn btn-primary">Upload</button>
</form>
