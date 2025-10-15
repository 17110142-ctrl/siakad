Mock dataset disimpan dalam format JSON untuk membantu simulasi integrasi Dapodik.

- Gunakan `default.json` sebagai contoh struktur data. Anda bisa menggandakan file ini dengan nama lain, misalnya `rombel_2025.json`.
- Isi field `peserta_didik` dengan `nisn` siswa yang sesuai dengan data lokal agar proses pencocokan berjalan mulus.
- Daftar `rombongan_belajar` harus memuat `pembelajaran` lengkap dengan `mata_pelajaran_id` dan `nama_mata_pelajaran` seperti yang muncul di Dapodik.
- Di halaman integrasi, setel URL webservice menjadi `mock://nama_file` (tanpa `.json`) untuk memuat skenario tertentu. Contoh: `mock://rombel_2025`.
- Setiap kali tombol Kirim Nilai ditekan dalam mode mock, payload akan dicatat ke file `mock/<skenario>_kirim_nilai_log.jsonl` agar dapat ditinjau.
