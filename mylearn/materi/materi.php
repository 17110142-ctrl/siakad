<?php defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!'); ?>
<?php if ($ac == '') { ?>
    <div class='row'>
        <div class='col-md-12'>
            <div class="card">
                <div class="card-header">
                    <h5 class='card-title'>MATERI BELAJAR</h5>
                    <div class='pull-right'>
                        <a href="?pg=<?= enkripsi('inputmateri') ?>" class='btn btn-primary'><i class="material-icons">add</i>Materi</a>
                        <a href="." class='btn btn-outline-danger'><i class="fas fa-times-circle"></i>Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <div id='tablemateri' class='table-responsive'>
                        <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
                            <thead>
                                <tr>
                                    <th width='5px'>#</th>
                                    <th>MATA PELAJARAN</th>
                                    <th>JUDUL MATERI</th>
                                    <th>TANGGAL MULAI</th>
                                    <th>TANGGAL SELESAI</th>
                                    <th>KELAS</th>
                                    <th width="5%">FILE</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($user['level'] == 'guru') {
                                    $materiQ = mysqli_query($koneksi, "SELECT * FROM materi where id_guru='$_SESSION[id_user]' ORDER BY tgl_mulai DESC");
                                } else {
                                    $materiQ = mysqli_query($koneksi, "SELECT * FROM materi ORDER BY tgl_mulai DESC");
                                }
                                $no = 0;
                                while ($materi = mysqli_fetch_array($materiQ)) :
                                    $mpl = mysqli_fetch_array(mysqli_query($koneksi, "select * FROM mata_pelajaran where kode='$materi[mapel]'"));
                                    $no++;
                                ?>
                                    <tr>
                                        <td><?= $no ?></td>
                                        <td><?= $mpl['nama_mapel'] ?></td>
                                        <td><?= $materi['judul'] ?></td>
                                        <td style="text-align:center"><?= date('d/m/Y H:i', strtotime($materi['tgl_mulai'])) ?></td>
                                        <td style="text-align:center"><?= ($materi['tgl_selesai']) ? date('d/m/Y H:i', strtotime($materi['tgl_selesai'])) : '-' ?></td>
                                        <td style="text-align:center">
                                            <?php
                                            $kelas = unserialize($materi['kelas']);
                                            if (is_array($kelas)) {
                                                foreach ($kelas as $k) echo "<span class='badge bg-primary me-1'>$k</span>";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php if ($materi['file']) { ?>
                                                <a href="../materi/<?= $materi['file'] ?>" target="_blank">Lihat</a>
                                            <?php } ?>
                                        </td>
                                        <td style="text-align:center">
                                            <a href='?pg=<?= enkripsi('materi') ?>&ac=<?= enkripsi('absen') ?>&id=<?= $materi['id_materi'] ?>' class='btn btn-sm btn-warning' title="Absen"><i class='material-icons'>crisis_alert</i></a>
                                            <a href='?pg=<?= enkripsi('materi') ?>&ac=<?= enkripsi('lihat') ?>&id=<?= $materi['id_materi'] ?>' class='btn btn-sm btn-success' title="Chat Siswa"><i class='material-icons'>send</i></a>
                                            <a href='?pg=<?= enkripsi('inputmateri') ?>&ac=<?= enkripsi('edit') ?>&id=<?= $materi['id_materi'] ?>' class='btn btn-sm btn-primary' title="Edit Materi"><i class='material-icons'>edit</i></a>
                                            <a href='?pg=<?= enkripsi('materi') ?>&ac=<?= enkripsi('quiz') ?>&id=<?= $materi['id_materi'] ?>' class='btn btn-sm btn-info' title="Tambahkan Quiz"><i class='material-icons'>quiz</i></a>
                                            <button data-id='<?= $materi['id_materi'] ?>' class="hapus btn btn-danger btn-sm" title="Hapus"><i class="material-icons">delete</i></button>
                                        </td>
                                    </tr>
                                <?php endwhile ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#datatable1').on('click', '.hapus', function() {
            var id = $(this).data('id');
            swal({
                title: 'Apa anda yakin?',
                text: "akan menghapus materi ini!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: 'materi/hapus.php',
                        method: "POST",
                        data: 'id=' + id,
                        success: function() { setTimeout(function() { window.location.reload(); }, 1200); }
                    });
                }
            })
        });
    </script>
<?php } elseif ($ac == enkripsi('quiz')) { ?>
    <?php $id_materi = $_GET['id']; $materi = fetch($koneksi,'materi',['id_materi'=>$id_materi]); ?>
    <div class='row'>
        <div class='col-md-12'>
            <div class="card" data-id-materi="<?= $id_materi ?>">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class='card-title'>Tambahkan Quiz: <?= htmlspecialchars($materi['judul']) ?></h5>
                    <a href="?pg=<?= enkripsi('materi') ?>" class='btn btn-outline-danger'>Back</a>
                </div>
                <div class="card-body">
                    <div id="questions_container"></div>
                    <div class="mt-3">
                        <label class="form-label">Jenis Soal</label>
                        <select id="quiz_type" class="form-select">
                            <option value="pg">Pilihan Ganda</option>
                            <option value="pgc">Pilihan Ganda Kompleks</option>
                            <option value="menjodohkan">Menjodohkan</option>
                            <option value="benar_salah">Benar/Salah</option>
                            <option value="isian_singkat">Isian Singkat</option>
                            <option value="uraian">Uraian</option>
                        </select>
                        <div class="mt-2">
                            <button id="btn-add-question" class="btn btn-primary btn-sm">Tambah Soal</button>
                            <button id="btn-save-quiz" class="btn btn-success btn-sm"><i class="material-icons">save</i> Simpan Quiz</button>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle" id="table-saved">
                                <thead>
                                    <tr>
                                        <th style="width:60px">No</th>
                                        <th>Jenis</th>
                                        <th>Pertanyaan</th>
                                        <th style="width:260px">Opsi</th>
                                        <th style="width:110px">Kunci</th>
                                        <th style="width:90px">Gambar</th>
                                        <th style="width:90px">Skor</th>
                                        <th style="width:160px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="saved-tbody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEdit" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Edit Soal</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <input type="hidden" id="edit-id">
                    <div class="mb-2"><label class="form-label">Jenis</label>
                        <select id="edit-jenis" class="form-select" disabled>
                            <option value="pg">Pilihan Ganda</option>
                            <option value="pgc">Pilihan Ganda Kompleks</option>
                            <option value="menjodohkan">Menjodohkan</option>
                            <option value="benar_salah">Benar/Salah</option>
                            <option value="isian_singkat">Isian Singkat</option>
                            <option value="uraian">Uraian</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Pertanyaan</label>
                        <textarea id="edit-text" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-2 d-flex align-items-center gap-2">
                        <input type="hidden" id="edit-media-url">
                        <button class="btn btn-outline-secondary btn-edit-q-upload" type="button">Tambahkan Gambar</button>
                        <img id="edit-thumb" style="display:none;width:72px;height:72px;object-fit:cover;border:1px solid #eee;border-radius:8px">
                    </div>
                    <div id="edit-opsi-wrap" class="mb-2"></div>
                    <div class="row">
                        <div class="col-md-6" id="wrap-kunci"></div>
                        <div class="col-md-6">
                            <label class="form-label">Skor per Soal / Maksimal</label>
                            <input type="number" id="edit-skor" class="form-control" min="0" step="0.01" value="1">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button data-bs-dismiss="modal" class="btn btn-secondary">Batal</button>
                    <button id="btn-update" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .q-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:6px}
        .badge-no{background:#0d6efd;color:#fff;border-radius:8px;padding:2px 8px;font-size:12px}
        .opt-thumb{width:44px;height:44px;object-fit:cover;border-radius:6px;border:1px solid #eee;margin-left:6px}
        .q-thumb{width:72px;height:72px;object-fit:cover;border-radius:8px;border:1px solid #eee;margin-left:8px}
        .options .input-group-text{width:70px}
    </style>

    <script>
        (function(){
            function optRow(idx,val,img){
                var label = String.fromCharCode(65+idx);
                return '<div class="input-group input-group-sm mb-2 opt-row">'
                    + '<span class="input-group-text">Opsi '+label+'</span>'
                    + '<input type="text" class="form-control opt-text" placeholder="Teks opsi '+label+'" value="'+(val||'')+'">'
                    + '<input type="hidden" class="opt-img-url" value="'+(img||'')+'">'
                    + '<button class="btn btn-outline-secondary btn-opt-upload" type="button">Tambahkan Gambar</button>'
                    + '<img class="opt-thumb" '+(img?('src="'+img+'"'):'style="display:none"')+' />'
                    + '<button class="btn btn-outline-danger btn-del-opt" type="button">&times;</button>'
                    + '</div>'
            }
            function pairRow(a,b){
                return '<div class="row g-2 align-items-center mb-2 pair-row">'
                    + '<div class="col"><input type="text" class="form-control pair-left" placeholder="Kiri" value="'+(a||'')+'"></div>'
                    + '<div class="col-auto">↔</div>'
                    + '<div class="col"><input type="text" class="form-control pair-right" placeholder="Kanan" value="'+(b||'')+'"></div>'
                    + '<div class="col-auto"><button class="btn btn-sm btn-outline-danger btn-del-pair" type="button">&times;</button></div>'
                    + '</div>'
            }
            function getLastSaved(){
                var max = 0;
                $('#saved-tbody tr').each(function(){ var n=parseInt($(this).find('td:eq(0)').text()||'0'); if(n>max) max=n; });
                return max;
            }
            function renumber(){
                var base = getLastSaved();
                $('#questions_container .question-item').each(function(i){
                    $(this).find('.q-number').text('Soal #'+(base+i+1));
                });
            }
            function refreshPGKeys(card){
                var sel=card.find('.q-key-single'); sel.empty();
                card.find('.opt-row').each(function(i){ sel.append('<option value="'+i+'">'+String.fromCharCode(65+i)+'</option>') })
            }
            function refreshPGCKeys(card){
                var wrap=card.find('.q-key-multi'); wrap.empty();
                card.find('.opt-row').each(function(i){ wrap.append('<div class="form-check"><input class="form-check-input q-key-check" type="checkbox" value="'+i+'"> <label class="form-check-label">'+String.fromCharCode(65+i)+'</label></div>') })
            }
            function uploadMedia(file, cb){
                var fd=new FormData(); fd.append('file',file);
                $.ajax({ url:'materi/quiz.php?mode=upload', type:'POST', data:fd, processData:false, contentType:false, dataType:'json',
                    success:function(r){ cb(r&&r.status==='ok'?r.url:null) }, error:function(){ cb(null) } })
            }
            function cardTpl(type){
                if(type==='pg'){
                    return $('<div class="card mb-3 question-item" data-type="pg"><div class="card-body"><div class="q-head"><span class="q-number badge-no">Soal #</span><button type="button" class="btn btn-sm btn-outline-danger btn-remove">Hapus</button></div><textarea class="form-control q-text" placeholder="Pertanyaan"></textarea><div class="d-flex align-items-center mt-2"><input type="hidden" class="q-media-url"><button class="btn btn-sm btn-outline-secondary btn-q-upload" type="button">Tambahkan Gambar</button><img class="q-thumb" style="display:none" /></div><div class="row mt-2"><div class="col-md-6"><label class="form-label">Pilihan</label><div class="options"></div><button class="btn btn-sm btn-outline-primary mt-2 btn-add-option" type="button">Tambah Opsi</button></div><div class="col-md-3"><label class="form-label">Kunci Jawaban</label><select class="form-select q-key-single"></select></div><div class="col-md-3"><label class="form-label">Skor per Soal</label><input type="number" class="form-control q-score" value="1" min="0" step="0.01"></div></div></div></div>')
                }
                if(type==='pgc'){
                    return $('<div class="card mb-3 question-item" data-type="pgc"><div class="card-body"><div class="q-head"><span class="q-number badge-no">Soal #</span><button type="button" class="btn btn-sm btn-outline-danger btn-remove">Hapus</button></div><textarea class="form-control q-text" placeholder="Pertanyaan"></textarea><div class="d-flex align-items-center mt-2"><input type="hidden" class="q-media-url"><button class="btn btn-sm btn-outline-secondary btn-q-upload" type="button">Tambahkan Gambar</button><img class="q-thumb" style="display:none" /></div><div class="row mt-2"><div class="col-md-6"><label class="form-label">Pilihan</label><div class="options"></div><button class="btn btn-sm btn-outline-primary mt-2 btn-add-option" type="button">Tambah Opsi</button></div><div class="col-md-3"><label class="form-label">Kunci Jawaban</label><div class="q-key-multi"></div></div><div class="col-md-3"><label class="form-label">Skor per Soal</label><input type="number" class="form-control q-score" value="1" min="0" step="0.01"></div></div></div></div>')
                }
                if(type==='menjodohkan'){
                    return $('<div class="card mb-3 question-item" data-type="menjodohkan"><div class="card-body"><div class="q-head"><span class="q-number badge-no">Soal #</span><button type="button" class="btn btn-sm btn-outline-danger btn-remove">Hapus</button></div><textarea class="form-control q-text" placeholder="Petunjuk"></textarea><div class="d-flex align-items-center mt-2"><input type="hidden" class="q-media-url"><button class="btn btn-sm btn-outline-secondary btn-q-upload" type="button">Tambahkan Gambar</button><img class="q-thumb" style="display:none" /></div><div class="pairs mt-2"></div><button class="btn btn-sm btn-outline-primary mt-2 btn-add-pair" type="button">Tambah Pasangan</button><div class="row mt-2"><div class="col-md-3 ms-auto"><label class="form-label">Skor per Pasangan</label><input type="number" class="form-control q-score" value="1" min="0" step="0.01"></div></div></div></div>')
                }
                if(type==='benar_salah'){
                    return $('<div class="card mb-3 question-item" data-type="benar_salah"><div class="card-body"><div class="q-head"><span class="q-number badge-no">Soal #</span><button type="button" class="btn btn-sm btn-outline-danger btn-remove">Hapus</button></div><textarea class="form-control q-text" placeholder="Pernyataan"></textarea><div class="d-flex align-items-center mt-2"><input type="hidden" class="q-media-url"><button class="btn btn-sm btn-outline-secondary btn-q-upload" type="button">Tambahkan Gambar</button><img class="q-thumb" style="display:none" /></div><div class="row mt-2"><div class="col-md-3"><label class="form-label">Kunci</label><select class="form-select q-key-bool"><option value="benar">Benar</option><option value="salah">Salah</option></select></div><div class="col-md-3"><label class="form-label">Skor per Soal</label><input type="number" class="form-control q-score" value="1" min="0" step="0.01"></div></div></div></div>')
                }
                if(type==='isian_singkat' || type==='uraian'){
                    return $('<div class="card mb-3 question-item" data-type="'+type+'"><div class="card-body"><div class="q-head"><span class="q-number badge-no">Soal #</span><button type="button" class="btn btn-sm btn-outline-danger btn-remove">Hapus</button></div><textarea class="form-control q-text" placeholder="Pertanyaan"></textarea><div class="d-flex align-items-center mt-2"><input type="hidden" class="q-media-url"><button class="btn btn-sm btn-outline-secondary btn-q-upload" type="button">Tambahkan Gambar</button><img class="q-thumb" style="display:none" /></div><div class="row mt-2"><div class="col-md-4 ms-auto"><label class="form-label">Skor Maksimal</label><input type="number" class="form-control q-score" value="1" min="0" step="0.01"></div></div></div></div>')
                }
            }
            function addQuestion(type){
                var card=cardTpl(type);
                $('#questions_container').append(card);
                if(type==='pg'||type==='pgc'){
                    var opts=card.find('.options');
                    for(var i=0;i<4;i++){ opts.append(optRow(i,'','')); }
                    if(type==='pg') refreshPGKeys(card); else refreshPGCKeys(card);
                }
                if(type==='menjodohkan'){
                    var pairs=card.find('.pairs');
                    for(var j=0;j<2;j++){ pairs.append(pairRow('','')); }
                }
                renumber();
            }
            function collectQuestions(){
                var out=[]; var err=null;
                $('#questions_container .question-item').each(function(){
                    if(err) return;
                    var c=$(this),t=c.data('type'); var noLabel=c.find('.q-number').text();
                    var text=c.find('.q-text').val().trim();
                    var score=parseFloat(c.find('.q-score').val()||0);
                    if(!text){ err=noLabel+': pertanyaan wajib diisi'; return; }
                    if(!(score>0)){ err=noLabel+': skor harus > 0'; return; }
                    var q={type:t,text:text,media:{url:c.find('.q-media-url').val()},score:score};
                    if(t==='pg'){
                        q.options=[]; c.find('.opt-row').each(function(){ q.options.push({text:$(this).find('.opt-text').val().trim(),img:$(this).find('.opt-img-url').val()}); });
                        if(q.options.length<2){ err=noLabel+': minimal 2 opsi'; return; }
                        if(q.options.some(function(o){return !o.text;})){ err=noLabel+': semua opsi harus diisi'; return; }
                        q.key=parseInt(c.find('.q-key-single').val()||0);
                        if(isNaN(q.key) || q.key<0 || q.key>=q.options.length){ err=noLabel+': kunci tidak valid'; return; }
                    } else if(t==='pgc'){
                        q.options=[]; c.find('.opt-row').each(function(){ q.options.push({text:$(this).find('.opt-text').val().trim(),img:$(this).find('.opt-img-url').val()}); });
                        if(q.options.length<2){ err=noLabel+': minimal 2 opsi'; return; }
                        if(q.options.some(function(o){return !o.text;})){ err=noLabel+': semua opsi harus diisi'; return; }
                        q.keys=c.find('.q-key-check:checked').map(function(){return parseInt($(this).val());}).get();
                        if(!q.keys.length){ err=noLabel+': kunci minimal 1 opsi'; return; }
                    } else if(t==='menjodohkan'){
                        q.pairs=[]; c.find('.pair-row').each(function(){ q.pairs.push({left:$(this).find('.pair-left').val().trim(), right:$(this).find('.pair-right').val().trim()}); });
                        if(!q.pairs.length){ err=noLabel+': minimal 1 pasangan'; return; }
                        if(q.pairs.some(function(p){return !p.left || !p.right;})){ err=noLabel+': isi kiri dan kanan'; return; }
                    } else if(t==='benar_salah'){
                        q.key=''+c.find('.q-key-bool').val();
                        if(q.key!=='benar' && q.key!=='salah'){ err=noLabel+': kunci tidak valid'; return; }
                    } else {
                    }
                    out.push(q);
                });
                if(err) return {error:err};
                return {data:out};
            }
            function listOptionsForRow(row){
                if(row.jenis==='pg' || row.jenis==='pgc'){
                    var out=[]; if(Array.isArray(row.opsi)) for(var i=0;i<row.opsi.length;i++){ out.push(String.fromCharCode(65+i)+'. '+(row.opsi[i].text||'')); }
                    return out.join('<br>');
                }
                if(row.jenis==='menjodohkan'){
                    var out2=[]; if(Array.isArray(row.opsi)) for(var j=0;j<row.opsi.length;j++){ var p=row.opsi[j]||{}; out2.push((p.left||'')+' ↔ '+(p.right||'')); }
                    return out2.length?out2.join('<br>'):'-';
                }
                return '-';
            }
            function keyCell(row){
                if(row.jenis==='pg'){
                    if(typeof row.kunci==='number'){ return String.fromCharCode(65 + row.kunci); }
                    return '-';
                }
                if(row.jenis==='pgc'){
                    if(Array.isArray(row.kunci) && row.kunci.length){ return row.kunci.map(function(i){return String.fromCharCode(65+i)}).join(', '); }
                    return '-';
                }
                if(row.jenis==='benar_salah'){
                    return (row.kunci==='salah') ? 'Salah' : (row.kunci==='benar' ? 'Benar' : '-');
                }
                return '-';
            }
            function thumbCell(row){
                var url = (row.media && row.media.url)?row.media.url:''; if(!url) return '-';
                return '<img src="'+url+'" style="width:64px;height:64px;object-fit:cover;border-radius:6px;border:1px solid #eee">';
            }
            function renderSaved(list){
                var tb = $('#saved-tbody'); tb.empty();
                if(!Array.isArray(list) || list.length===0){ tb.append('<tr><td colspan="8" class="text-center text-muted">Belum ada soal.</td></tr>'); return; }
                list.forEach(function(r){
                    tb.append(
                        '<tr data-id="'+r.id+'" data-raw=\''+JSON.stringify(r).replaceAll("'","&apos;")+'\'>'+
                        '<td>'+r.nomor+'</td>'+
                        '<td>'+r.jenis.replace('_',' ')+'</td>'+
                        '<td>'+($('<div>').html(r.pertanyaan||'').text())+'</td>'+
                        '<td>'+listOptionsForRow(r)+'</td>'+
                        '<td>'+keyCell(r)+'</td>'+
                        '<td>'+thumbCell(r)+'</td>'+
                        '<td>'+(r.skor_max||0)+'</td>'+
                        '<td><button class="btn btn-sm btn-primary btn-edit">Edit</button> <button class="btn btn-sm btn-danger btn-del-question">Hapus</button></td>'+
                        '</tr>'
                    );
                });
            }
            function fetchList(){
                $.getJSON('materi/quiz.php',{mode:'list_json',id_materi:'<?= $id_materi ?>'},function(r){
                    if(r && r.status==='ok'){ renderSaved(r.data||[]); renumber(); }
                    else { $('#saved-tbody').html('<tr><td colspan="8" class="text-center text-danger">Gagal memuat.</td></tr>'); }
                }).fail(function(){ $('#saved-tbody').html('<tr><td colspan="8" class="text-center text-danger">Gagal memuat.</td></tr>'); });
            }

            $('#btn-add-question').on('click', function(e){ e.preventDefault(); addQuestion($('#quiz_type').val()); });
            $('#questions_container').on('click','.btn-remove',function(){ $(this).closest('.question-item').remove(); renumber(); });
            $('#questions_container').on('click','.btn-add-option',function(e){ e.preventDefault(); var card=$(this).closest('.question-item'); var count=card.find('.opt-row').length; card.find('.options').append(optRow(count,'','')); if(card.data('type')==='pg') refreshPGKeys(card); else refreshPGCKeys(card); });
            $('#questions_container').on('click','.btn-del-opt',function(){ var card=$(this).closest('.question-item'); $(this).closest('.opt-row').remove(); card.find('.opt-row').each(function(i){ $(this).find('.input-group-text').text('Opsi '+String.fromCharCode(65+i)); }); if(card.data('type')==='pg') refreshPGKeys(card); else refreshPGCKeys(card); });
            $('#questions_container').on('click','.btn-add-pair',function(e){ e.preventDefault(); $(this).closest('.question-item').find('.pairs').append(pairRow('','')); });
            $('#questions_container').on('click','.btn-del-pair',function(){ $(this).closest('.pair-row').remove(); });
            $('#questions_container').on('click','.btn-q-upload',function(){ var card=$(this).closest('.question-item'); var input=$('<input type="file" accept="image/*" style="display:none">'); $('body').append(input); input.on('change',function(){ var f=this.files[0]; if(!f) return; uploadMedia(f,function(url){ if(url){ card.find('.q-media-url').val(url); card.find('.q-thumb').attr('src',url).show(); } input.remove(); }); }); input.trigger('click'); });
            $('#questions_container').on('click','.btn-opt-upload',function(){ var row=$(this).closest('.opt-row'); var input=$('<input type="file" accept="image/*" style="display:none">'); $('body').append(input); input.on('change',function(){ var f=this.files[0]; if(!f) return; uploadMedia(f,function(url){ if(url){ row.find('.opt-img-url').val(url); row.find('.opt-thumb').attr('src',url).show(); } input.remove(); }); }); input.trigger('click'); });

            $('#btn-save-quiz').on('click', function(){
                var res=collectQuestions();
                if(res.error){ swal('Validasi', res.error, 'warning'); return; }
                var data=res.data;
                if(!data.length){ swal('Validasi','Tambahkan minimal satu soal.','warning'); return; }
                $.ajax({
                    url:'materi/quiz.php',
                    type:'POST',
                    data:{mode:'save', id_materi:'<?= $id_materi ?>', questions:JSON.stringify(data)},
                    dataType:'json',
                    beforeSend:function(){ $('#btn-save-quiz').prop('disabled',true).text('Menyimpan...'); },
                    success:function(r){
                        if(r.status==='ok'){
                            $('#questions_container').empty();
                            fetchList();
                            swal('Berhasil','Soal ditambahkan.','success');
                        } else {
                            swal('Gagal', r.message||'Gagal menyimpan.','error');
                        }
                    },
                    error:function(){ swal('Error','Koneksi gagal.','error'); },
                    complete:function(){ $('#btn-save-quiz').prop('disabled',false).html('<i class="material-icons">save</i> Simpan Quiz'); }
                });
            });

            $('#table-saved').on('click','.btn-del-question',function(){
                var id=$(this).closest('tr').data('id');
                swal({title:'Hapus soal?', text:'Tidak bisa dibatalkan.', type:'warning', showCancelButton:true}).then(function(res){
                    if(res.value){
                        $.post('materi/quiz.php',{mode:'delete',id:id},function(r){
                            if(r.status==='ok'){ fetchList(); }
                            else { swal('Gagal', r.message||'Gagal menghapus','error'); }
                        },'json');
                    }
                });
            });

            function buildEditOptions(opts, jenis, selectedKeys){
                var wrap = $('#edit-opsi-wrap'); wrap.empty();
                if(jenis==='pg' || jenis==='pgc'){
                    wrap.append('<label class="form-label">Pilihan</label>');
                    var container = $('<div class="options"></div>');
                    wrap.append(container);
                    (opts||[]).forEach(function(o,idx){
                        container.append(optRow(idx, o.text||'', o.img||'')); 
                    });
                    wrap.append('<button class="btn btn-sm btn-outline-primary mt-2 btn-edit-add-opt" type="button">Tambah Opsi</button>');
                    var kunci = $('<div class="mt-2"></div>');
                    if(jenis==='pg'){
                        kunci.append('<label class="form-label">Kunci Jawaban</label><select id="edit-key-single" class="form-select"></select>');
                        setTimeout(function(){
                            var sel = $('#edit-key-single'); sel.empty();
                            container.find('.opt-row').each(function(i){ sel.append('<option value="'+i+'">'+String.fromCharCode(65+i)+'</option>'); });
                            if(typeof selectedKeys==='number') sel.val(String(selectedKeys));
                        },0);
                    }else{
                        kunci.append('<label class="form-label">Kunci Jawaban</label><div id="edit-key-multi"></div>');
                        setTimeout(function(){
                            var box = $('#edit-key-multi'); box.empty();
                            container.find('.opt-row').each(function(i){
                                var chk = $('<div class="form-check"><input class="form-check-input edit-key-check" type="checkbox" value="'+i+'"> <label class="form-check-label">'+String.fromCharCode(65+i)+'</label></div>');
                                if(Array.isArray(selectedKeys) && selectedKeys.indexOf(i)>=0) chk.find('input').prop('checked',true);
                                box.append(chk);
                            });
                        },0);
                    }
                    $('#wrap-kunci').html(kunci);
                }else if(jenis==='menjodohkan'){
                    wrap.append('<label class="form-label">Pasangan</label>');
                    var container = $('<div class="pairs"></div>');
                    wrap.append(container);
                    (opts||[]).forEach(function(p){ container.append(pairRow(p.left||'', p.right||'')); });
                    wrap.append('<button class="btn btn-sm btn-outline-primary mt-2 btn-edit-add-pair" type="button">Tambah Pasangan</button>');
                    $('#wrap-kunci').html('');
                }else if(jenis==='benar_salah'){
                    wrap.html('');
                    $('#wrap-kunci').html('<label class="form-label">Kunci</label><select id="edit-key-bool" class="form-select"><option value="benar">Benar</option><option value="salah">Salah</option></select>');
                    if(selectedKeys==='salah') $('#edit-key-bool').val('salah');
                }else{
                    wrap.html('');
                    $('#wrap-kunci').html('');
                }
            }

            $('#table-saved').on('click','.btn-edit',function(){
                var tr = $(this).closest('tr');
                var data = JSON.parse(tr.attr('data-raw').replaceAll('&apos;','\''));
                $('#edit-id').val(data.id);
                $('#edit-jenis').val(data.jenis);
                $('#edit-text').val($('<div>').html(data.pertanyaan||'').text());
                var url = data.media && data.media.url ? data.media.url : '';
                $('#edit-media-url').val(url);
                if(url){ $('#edit-thumb').attr('src',url).show(); } else { $('#edit-thumb').hide(); }
                var selectedKeys = null;
                if(typeof data.kunci === 'number') selectedKeys = data.kunci;
                else if(Array.isArray(data.kunci)) selectedKeys = data.kunci;
                else if(typeof data.kunci === 'string') selectedKeys = data.kunci;
                buildEditOptions(data.opsi || data.pairs || [], data.jenis, selectedKeys);
                $('#edit-skor').val(data.skor_max||1);
                var m = new bootstrap.Modal(document.getElementById('modalEdit')); m.show();
            });

            $(document).on('click','.btn-edit-add-opt',function(){
                var container = $('#edit-opsi-wrap .options');
                var count = container.find('.opt-row').length;
                container.append(optRow(count,'',''));
                var jenis = $('#edit-jenis').val();
                if(jenis==='pg'){
                    var sel = $('#edit-key-single'); sel.empty();
                    container.find('.opt-row').each(function(i){ sel.append('<option value="'+i+'">'+String.fromCharCode(65+i)+'</option>'); });
                }else{
                    var box = $('#edit-key-multi');
                    box.append('<div class="form-check"><input class="form-check-input edit-key-check" type="checkbox" value="'+count+'"> <label class="form-check-label">'+String.fromCharCode(65+count)+'</label></div>');
                }
            });
            $(document).on('click','.btn-edit-add-pair',function(){ $('#edit-opsi-wrap .pairs').append(pairRow('','')); });
            $(document).on('click','#edit-opsi-wrap .btn-opt-upload',function(){
                var row=$(this).closest('.opt-row'); var input=$('<input type="file" accept="image/*" style="display:none">'); $('body').append(input);
                input.on('change',function(){ var f=this.files[0]; if(!f) return; uploadMedia(f,function(url){ if(url){ row.find('.opt-img-url').val(url); row.find('.opt-thumb').attr('src',url).show(); } input.remove(); }); }); input.trigger('click');
            });
            $('.btn-edit-q-upload').on('click',function(){
                var input=$('<input type="file" accept="image/*" style="display:none">'); $('body').append(input);
                input.on('change',function(){ var f=this.files[0]; if(!f) return; uploadMedia(f,function(url){ if(url){ $('#edit-media-url').val(url); $('#edit-thumb').attr('src',url).show(); } input.remove(); }); }); input.trigger('click');
            });

            $('#btn-update').on('click',function(){
                var jenis = $('#edit-jenis').val();
                var text = $('#edit-text').val().trim();
                var skor = parseFloat($('#edit-skor').val()||0);
                if(!text){ swal('Validasi','Pertanyaan wajib diisi','warning'); return; }
                if(!(skor>0)){ swal('Validasi','Skor harus > 0','warning'); return; }
                var payload = {
                    id: $('#edit-id').val(),
                    jenis: jenis,
                    text: text,
                    media: {url: $('#edit-media-url').val()},
                    skor: skor
                };
                if(jenis==='pg'){
                    payload.options=[]; $('#edit-opsi-wrap .opt-row').each(function(){ payload.options.push({text:$(this).find('.opt-text').val().trim(), img:$(this).find('.opt-img-url').val()}); });
                    if(payload.options.length<2 || payload.options.some(function(o){return !o.text;})){ swal('Validasi','Isi minimal 2 opsi dan semua teks opsi.','warning'); return; }
                    payload.key = parseInt($('#edit-key-single').val()||0);
                }else if(jenis==='pgc'){
                    payload.options=[]; $('#edit-opsi-wrap .opt-row').each(function(){ payload.options.push({text:$(this).find('.opt-text').val().trim(), img:$(this).find('.opt-img-url').val()}); });
                    if(payload.options.length<2 || payload.options.some(function(o){return !o.text;})){ swal('Validasi','Isi minimal 2 opsi dan semua teks opsi.','warning'); return; }
                    payload.keys = $('#edit-key-multi .edit-key-check:checked').map(function(){return parseInt($(this).val());}).get();
                    if(!payload.keys.length){ swal('Validasi','Pilih minimal satu kunci.','warning'); return; }
                }else if(jenis==='menjodohkan'){
                    payload.pairs=[]; $('#edit-opsi-wrap .pair-row').each(function(){ payload.pairs.push({left:$(this).find('.pair-left').val().trim(), right:$(this).find('.pair-right').val().trim()}); });
                    if(!payload.pairs.length || payload.pairs.some(function(p){return !p.left || !p.right;})){ swal('Validasi','Isi minimal satu pasangan dan keduanya harus terisi.','warning'); return; }
                }else if(jenis==='benar_salah'){
                    payload.key_bool = $('#edit-key-bool').val();
                }
                $.ajax({
                    url:'materi/quiz.php',
                    type:'POST',
                    data:{mode:'update', data: JSON.stringify(payload)},
                    dataType:'json',
                    success:function(r){
                        if(r.status==='ok'){
                            bootstrap.Modal.getInstance(document.getElementById('modalEdit')).hide();
                            fetchList();
                            swal('Berhasil','Soal diperbarui.','success');
                        }else{
                            swal('Gagal', r.message||'Gagal menyimpan.','error');
                        }
                    },
                    error:function(){ swal('Error','Koneksi gagal.','error'); }
                });
            });

            fetchList();
        })();
    </script>
<?php } ?>
<script>
    tinymce.init({
        selector: '.editor1',
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools uploadimage paste formula'
        ],
        toolbar: 'bold italic fontselect fontsizeselect | alignleft aligncenter alignright bullist numlist  backcolor forecolor | formula code | imagetools link image paste ',
        fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
        paste_data_images: true,
        images_upload_handler: function(blobInfo, success, failure) { success('data:' + blobInfo.blob().type + ';base64,' + blobInfo.base64()); },
        image_class_list: [{ title: 'Responsive', value: 'img-responsive' }],
        setup: function(editor) { editor.on('change', function() { tinymce.triggerSave(); }); }
    });
    $('.select2').select2();
</script>
