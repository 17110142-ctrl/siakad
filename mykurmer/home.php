<?php 
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$tanggal = date('Y-m-d');
$bulan = date('m');
$tahun = date('Y');
$sam = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM nilai_sumatif WHERE khp='SAM'"));
$sas = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM nilai_sumatif WHERE khp='SAS'"));
$formatif = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM nilai_formatif"));
$lingkup = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM lingkup WHERE smt='$setting[semester]'"));
$tuju = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tujuan WHERE smt='$setting[semester]'"));

if (!function_exists('user_is_adminlike')) {
    function user_is_adminlike($user) {
        $level = strtolower(trim($user['level'] ?? ''));
        $tugas = strtolower(trim($user['tugas'] ?? ''));
        return in_array($level, ['admin', 'kurikulum', 'kepala'], true) || $tugas === 'kurikulum';
    }
}
?>

<?php include"top.php"; ?>

<?php
// Renderer: satu kartu progres per scope ('own' | 'walas' | 'all')
function render_progress_card($koneksi, $semester, $tapel, $user, $title, $scope) {
    $level_user = isset($user['level']) ? $user['level'] : '';
    $id_user_login = isset($user['id_user']) ? (int)$user['id_user'] : 0;
    $wali_kelas_user = isset($user['walas']) ? trim($user['walas']) : '';

    // 1) Kelas header dari jadwal_mapel sesuai scope
    $kelas_headers = [];
    $teach_map = [];
    if ($scope === 'own') {
        $sqljm = "SELECT kelas, mapel, tingkat FROM jadwal_mapel WHERE guru=".$id_user_login." AND kuri='2' AND semester='".$semester."' AND tp='".$tapel."'";
        if ($qjm = mysqli_query($koneksi, $sqljm)) {
            while ($rjm = mysqli_fetch_assoc($qjm)) {
                $mid = (int)$rjm['mapel'];
                $kls = $rjm['kelas'];
                $teach_map[$mid][$kls] = $rjm['tingkat'];
                if (!in_array($kls, $kelas_headers, true)) $kelas_headers[] = $kls;
            }
        }
    } elseif ($scope === 'walas' && $wali_kelas_user !== '') {
        $kelas_headers = [$wali_kelas_user];
    } else {
        $qkh = mysqli_query($koneksi, "SELECT DISTINCT kelas FROM jadwal_mapel WHERE kuri='2' AND semester='".$semester."' AND tp='".$tapel."' ORDER BY kelas");
        if ($qkh) {
            while ($rh = mysqli_fetch_assoc($qkh)) {
                $kelas_headers[] = $rh['kelas'];
            }
        }
    }
    if (empty($kelas_headers)) return;

    // 2) Daftar mapel dari jadwal_mapel sesuai scope
    if ($scope === 'own') {
        $sql_mapel = "SELECT DISTINCT mp.id AS id_mapel, mp.nama_mapel FROM jadwal_mapel jm JOIN mata_pelajaran mp ON mp.id=jm.mapel WHERE jm.kuri='2' AND jm.semester='".$semester."' AND jm.tp='".$tapel."' AND jm.guru=".$id_user_login." ORDER BY mp.id";
    } elseif ($scope === 'walas' && $wali_kelas_user !== '') {
        $kelas_esc = mysqli_real_escape_string($koneksi, $wali_kelas_user);
        $sql_mapel = "SELECT DISTINCT mp.id AS id_mapel, mp.nama_mapel FROM jadwal_mapel jm JOIN mata_pelajaran mp ON mp.id=jm.mapel WHERE jm.kuri='2' AND jm.semester='".$semester."' AND jm.tp='".$tapel."' AND jm.kelas='".$kelas_esc."' ORDER BY mp.id";
    } else {
        $sql_mapel = "SELECT DISTINCT mp.id AS id_mapel, mp.nama_mapel FROM jadwal_mapel jm JOIN mata_pelajaran mp ON mp.id=jm.mapel WHERE jm.kuri='2' AND jm.semester='".$semester."' AND jm.tp='".$tapel."' ORDER BY mp.id";
    }
    $mapel_rows = [];
    if ($qmp = mysqli_query($koneksi, $sql_mapel)) { while ($rm = mysqli_fetch_assoc($qmp)) { $mapel_rows[] = $rm; } }
    if (empty($mapel_rows)) return;

    // Guru pengampu per mapel (total dan per kelas)
    $mapel_teacher_names = [];
    $mapel_teacher_by_class = [];
    $mapel_ids_for_query = [];
    foreach ($mapel_rows as $row_id) {
        $mapel_ids_for_query[] = (int)$row_id['id_mapel'];
    }
    $mapel_ids_for_query = array_values(array_unique(array_filter($mapel_ids_for_query)));
    if (!empty($mapel_ids_for_query)) {
        $mapel_in = implode(',', $mapel_ids_for_query);
        $kelas_filter_sql = '';
        if (!empty($kelas_headers)) {
            $esc_kelas_filter = array_map(function($x){ return "'".mysqli_real_escape_string($GLOBALS['koneksi'],$x)."'"; }, $kelas_headers);
            $kelas_filter_sql = ' AND jm.kelas IN ('.implode(',', $esc_kelas_filter).')';
        }
        $sql_guru = "SELECT jm.mapel, jm.kelas, u.nama FROM jadwal_mapel jm LEFT JOIN users u ON u.id_user=jm.guru WHERE jm.kuri='2' AND jm.semester='".$semester."' AND jm.tp='".$tapel."' AND jm.mapel IN (".$mapel_in.")".$kelas_filter_sql;
        if ($qguru = mysqli_query($koneksi, $sql_guru)) {
            while ($rg = mysqli_fetch_assoc($qguru)) {
                $mid = (int)($rg['mapel'] ?? 0);
                $kls = $rg['kelas'] ?? '';
                $gnm = trim($rg['nama'] ?? '');
                if ($mid <= 0 || $gnm === '') continue;
                if (!isset($mapel_teacher_names[$mid])) $mapel_teacher_names[$mid] = [];
                if (!in_array($gnm, $mapel_teacher_names[$mid], true)) $mapel_teacher_names[$mid][] = $gnm;
                if ($kls !== '') {
                    if (!isset($mapel_teacher_by_class[$mid][$kls])) $mapel_teacher_by_class[$mid][$kls] = [];
                    if (!in_array($gnm, $mapel_teacher_by_class[$mid][$kls], true)) $mapel_teacher_by_class[$mid][$kls][] = $gnm;
                }
            }
        }
    }

    // 3) Prefetch siswa per kelas (ids + nama + jumlah)
    $kelas_siswa_ids = $kelas_siswa_names = $kelas_siswa_count = [];
    $esc_kelas = array_map(function($x){ return "'".mysqli_real_escape_string($GLOBALS['koneksi'],$x)."'"; }, $kelas_headers);
    $in_kelas_siswa = implode(',', $esc_kelas);
    if ($qsis = mysqli_query($koneksi, "SELECT id_siswa, nama, kelas FROM siswa WHERE kelas IN ($in_kelas_siswa)")) {
        while ($rs = mysqli_fetch_assoc($qsis)) {
            $k = $rs['kelas']; $sid = (int)$rs['id_siswa'];
            if (!isset($kelas_siswa_ids[$k])) { $kelas_siswa_ids[$k] = []; $kelas_siswa_names[$k] = []; }
            $kelas_siswa_ids[$k][] = $sid;
            $kelas_siswa_names[$k][$sid] = $rs['nama'];
        }
    }
    foreach ($kelas_headers as $k) { $kelas_siswa_count[$k] = isset($kelas_siswa_ids[$k]) ? count($kelas_siswa_ids[$k]) : 0; }

    // 4) Render kartu tabel
    ?>
    <div class="col-md-12">
      <div class="card">
        <div class="card-header"><h5 class="card-title" style="margin:0;"><?= htmlspecialchars($title) ?></h5></div>
        <div class="card-body">
          <div class="card-box table-responsive">
            <table class="table table-bordered table-hover" style="width:100%;font-size:12px">
              <thead>
                <tr>
                  <th width="5%">NO</th>
                  <th>TKT</th>
                  <th>MATA PELAJARAN</th>
                  <?php foreach ($kelas_headers as $kh) { echo '<th>'.htmlspecialchars($kh).'</th>'; } ?>
                </tr>
              </thead>
              <tbody>
              <?php $no=0; foreach ($mapel_rows as $data): $no++; $id_mapel=(int)$data['id_mapel']; $nama_mapel=$data['nama_mapel'];
                  $guru_mapel = $mapel_teacher_names[$id_mapel] ?? [];
                  $guru_mapel_label = !empty($guru_mapel) ? implode(', ', $guru_mapel) : '';
                  $class_teacher_map = $mapel_teacher_by_class[$id_mapel] ?? [];
                  // TKT
                  $tkt_arr=[];
                  if ($scope==='own') { if (isset($teach_map[$id_mapel])) { foreach ($teach_map[$id_mapel] as $k=>$t) { if ($t!=='') $tkt_arr[]=$t; } } }
                  else { $escaped=array_map(function($x){ return "'".mysqli_real_escape_string($GLOBALS['koneksi'],$x)."'"; },$kelas_headers); $in_kelas=implode(',',$escaped); $qt=mysqli_query($koneksi,"SELECT DISTINCT tingkat FROM jadwal_mapel WHERE mapel='".$id_mapel."' AND kuri='2' AND kelas IN ($in_kelas)"); if($qt){ while($rt=mysqli_fetch_assoc($qt)){ if($rt['tingkat']!=='') $tkt_arr[]=$rt['tingkat']; } } }
                  $tkt_label=!empty($tkt_arr)?implode('/',array_unique($tkt_arr)):'-';
                  // nilai_sts prefetched per mapel
                  $has_sts=$has_sas=$has_rap=[]; $esc_kelas2=array_map(function($x){return "'".mysqli_real_escape_string($GLOBALS['koneksi'],$x)."'";},$kelas_headers); $in_kelas2=implode(',',$esc_kelas2);
                  $qns=mysqli_query($koneksi,"SELECT idsiswa,kelas,nilai_sts,nilai_sas,nilai_raport FROM nilai_sts WHERE mapel='".$id_mapel."' AND semester='".$semester."' AND tp='".$tapel."' AND kelas IN ($in_kelas2)");
                  if($qns){ while($rn=mysqli_fetch_assoc($qns)){ $kls=$rn['kelas']; $sid=(int)$rn['idsiswa']; if((int)$rn['nilai_sts']>0){$has_sts[$kls][$sid]=true;} if((int)$rn['nilai_sas']>0){$has_sas[$kls][$sid]=true;} if((int)$rn['nilai_raport']>0){$has_rap[$kls][$sid]=true;} } }
                  // indikator own
                  $indicator_html=''; if($scope==='own' && !empty($teach_map[$id_mapel])){ $all_complete=true; $incomplete=[]; foreach(array_keys($teach_map[$id_mapel]) as $khx){ $tot=isset($kelas_siswa_count[$khx])?(int)$kelas_siswa_count[$khx]:0; $c1=isset($has_sts[$khx])?count($has_sts[$khx]):0; $c2=isset($has_sas[$khx])?count($has_sas[$khx]):0; $c3=isset($has_rap[$khx])?count($has_rap[$khx]):0; if($tot>0 && ($c1<$tot || $c2<$tot || $c3<$tot)){ $all_complete=false; $incomplete[]=$khx; } } if($all_complete){ $indicator_html="<div style='font-size:16px;color:#16a34a;margin-top:4px'><i class='material-icons'>check_circle</i></div>"; } else { $titlex='Masih kurang di: '.implode(', ',$incomplete); $indicator_html="<div style='font-size:16px;color:#dc2626;margin-top:4px' title='".htmlspecialchars($titlex,ENT_QUOTES)."'>X</div>"; } }
              ?>
                <tr>
                  <td><?= $no; ?></td>
                  <td><h5><span class="badge badge-dark"><?= htmlspecialchars($tkt_label) ?></span></h5><?= $indicator_html ?></td>
                  <td><?= htmlspecialchars($nama_mapel) ?><?php if ($guru_mapel_label !== ''): ?><br><small class="text-muted"><?= htmlspecialchars($guru_mapel_label) ?></small><?php endif; ?></td>
                  <?php foreach($kelas_headers as $kelasnm):
                      if($scope==='own' && !(isset($teach_map[$id_mapel]) && array_key_exists($kelasnm,$teach_map[$id_mapel]))) { echo "<td class=\"text-muted\">&ndash;</td>"; continue; }
                      $jsiswa=isset($kelas_siswa_count[$kelasnm])?(int)$kelas_siswa_count[$kelasnm]:0;
                      $miss_sts=$miss_sas=$miss_rap=[];
                      if(!empty($kelas_siswa_ids[$kelasnm])){
                          foreach($kelas_siswa_ids[$kelasnm] as $sid0){
                              if(!isset($has_sts[$kelasnm][$sid0])) $miss_sts[]=$kelas_siswa_names[$kelasnm][$sid0]??('ID '.$sid0);
                              if(!isset($has_sas[$kelasnm][$sid0])) $miss_sas[]=$kelas_siswa_names[$kelasnm][$sid0]??('ID '.$sid0);
                              if(!isset($has_rap[$kelasnm][$sid0])) $miss_rap[]=$kelas_siswa_names[$kelasnm][$sid0]??('ID '.$sid0);
                          }
                      }
                      $pst=$jsiswa>0?round((($jsiswa-count($miss_sts))/$jsiswa)*100):0;
                      $pss=$jsiswa>0?round((($jsiswa-count($miss_sas))/$jsiswa)*100):0;
                      $prp=$jsiswa>0?round((($jsiswa-count($miss_rap))/$jsiswa)*100):0;
                      $class_teacher_list=$class_teacher_map[$kelasnm] ?? [];
                      if(empty($class_teacher_list) && !empty($guru_mapel)) $class_teacher_list=$guru_mapel;
                      $class_teacher_label=!empty($class_teacher_list)?implode(', ', $class_teacher_list):'';
                  ?>
                  <td>
                    <div><span class="badge badge-success" style="cursor:pointer" onclick='showMissing("STS - <?= htmlspecialchars($kelasnm) ?> - <?= htmlspecialchars($nama_mapel) ?>", this.dataset.miss, this.dataset.guru)' data-miss='<?= htmlspecialchars(json_encode($miss_sts),ENT_QUOTES) ?>' data-guru='<?= htmlspecialchars($class_teacher_label,ENT_QUOTES) ?>'>STS : <?= $pst ?>%</span></div>
                    <div><span class="badge badge-warning" style="cursor:pointer" onclick='showMissing("SAS - <?= htmlspecialchars($kelasnm) ?> - <?= htmlspecialchars($nama_mapel) ?>", this.dataset.miss, this.dataset.guru)' data-miss='<?= htmlspecialchars(json_encode($miss_sas),ENT_QUOTES) ?>' data-guru='<?= htmlspecialchars($class_teacher_label,ENT_QUOTES) ?>'>SAS : <?= $pss ?>%</span></div>
                    <div><span class="badge badge-primary" style="cursor:pointer" onclick='showMissing("Raport - <?= htmlspecialchars($kelasnm) ?> - <?= htmlspecialchars($nama_mapel) ?>", this.dataset.miss, this.dataset.guru)' data-miss='<?= htmlspecialchars(json_encode($miss_rap),ENT_QUOTES) ?>' data-guru='<?= htmlspecialchars($class_teacher_label,ENT_QUOTES) ?>'>Raport : <?= $prp ?>%</span></div>
                  </td>
                  <?php endforeach; ?>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <?php }
?>
                      <div class="row">
							  <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-primary">
                                                <i class="material-icons-outlined">face</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">SUMATIF LINGKUP MATERI</span>
                                                <span class="widget-stats-amount"><?= $sam; ?></span>
                                                <span class="widget-stats-info"><?= substr($setting['sekolah'],0,19) ?></span>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-warning">
                                                <i class="material-icons-outlined">face</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">SUMATIF AKHIR SEMESTER</span>
                                                <span class="widget-stats-amount"><?= $sas; ?></span>
                                                <span class="widget-stats-info"><?= substr($setting['sekolah'],0,19) ?></span>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-danger">
                                                <i class="material-icons-outlined">school</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">FORMATIF SEMESTER <?= $setting['semester'] ?></span>
                                                <span class="widget-stats-amount"><?= $formatif ?> </span>
                                                <span class="widget-stats-info"><?= substr($setting['sekolah'],0,19) ?></span>
                                            </div>
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>
						</div>
                       <div class="row">
                            <!-- NEW: multiple cards by role -->
                            <?php
                              $level_user = $user['level'] ?? '';
                              $id_user_login = (int)($user['id_user'] ?? 0);
                              $wali_kelas_user = trim($user['walas'] ?? '');
                              $is_adminlike = user_is_adminlike($user);
                              $has_kurikulum_task = strtolower(trim($user['tugas'] ?? '')) === 'kurikulum';

                              if ($has_kurikulum_task) {
                                  render_progress_card($koneksi, $semester, $tapel, $user, 'PROGRES RAPOR — Semua Data', 'all');
                              } else if ($is_adminlike) {
                                  if ($wali_kelas_user !== '') {
                                      render_progress_card($koneksi, $semester, $tapel, $user, 'PROGRES RAPOR — Kelas Wali '.$wali_kelas_user, 'walas');
                                  }
                                  $qt = mysqli_query($koneksi, "SELECT 1 FROM jadwal_mapel WHERE guru=".$id_user_login." AND kuri='2' AND semester='".$semester."' AND tp='".$tapel."' LIMIT 1");
                                  if ($qt && mysqli_num_rows($qt)>0) {
                                      render_progress_card($koneksi, $semester, $tapel, $user, 'PROGRES RAPOR — Milik Saya', 'own');
                                  }
                                  render_progress_card($koneksi, $semester, $tapel, $user, 'PROGRES RAPOR — Semua Data', 'all');
                              } else if ($level_user === 'guru' && $wali_kelas_user !== '') {
                                  render_progress_card($koneksi, $semester, $tapel, $user, 'PROGRES RAPOR — Kelas Wali '.$wali_kelas_user, 'walas');
                                  render_progress_card($koneksi, $semester, $tapel, $user, 'PROGRES RAPOR — Milik Saya', 'own');
                              } else if ($level_user === 'guru') {
                                  render_progress_card($koneksi, $semester, $tapel, $user, 'PROGRES RAPOR — Milik Saya', 'own');
                              } else if ($wali_kelas_user !== '') {
                                  render_progress_card($koneksi, $semester, $tapel, $user, 'PROGRES RAPOR — Kelas Wali '.$wali_kelas_user, 'walas');
                              }
                            ?>
                            <!-- HIDE old single-card block by CSS below if still present -->
                            <div class="col-md-12" style="display:none;">
                                <div class="card" style="display:none;">
                                    <div class="card-header">
                                        <h5 class="card-title" style="margin:0">PROGRES RAPOR K-MERDEKA</h5>
                                        <?php 
                                        $level_user = isset($user['level']) ? $user['level'] : '';
                                        $is_adminlike = user_is_adminlike($user);
                                        $has_kurikulum_task = strtolower(trim($user['tugas'] ?? '')) === 'kurikulum';
                                        if ($is_adminlike): 
                                            $scope = 'all';
                                            if (!$has_kurikulum_task) {
                                                $scope = (isset($_GET['scope']) && in_array($_GET['scope'], ['all','own'])) ? $_GET['scope'] : 'all';
                                            }
                                        ?>
                                        <?php if (!$has_kurikulum_task): ?>
                                        <div class="kanan">
                                            <a class="btn btn-sm <?= $scope==='all'?'btn-primary':'btn-outline-primary' ?>" href="?scope=all">Semua</a>
                                            <a class="btn btn-sm <?= $scope==='own'?'btn-primary':'btn-outline-primary' ?>" href="?scope=own">Milik Saya</a>
                                        </div>
                                        <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                             <th width="5%">NO</th>                                               
                                             <th>TKT</th>
                                             <th>MATA PELAJARAN</th>
                                                <?php
                                                // Tentukan scope tampilan
                                                $level_user = isset($user['level']) ? $user['level'] : '';
                                                $id_user_login = isset($user['id_user']) ? (int)$user['id_user'] : 0;
                                                $wali_kelas_user = isset($user['walas']) ? trim($user['walas']) : '';
                                                $is_adminlike = user_is_adminlike($user);
                                                $has_kurikulum_task = strtolower(trim($user['tugas'] ?? '')) === 'kurikulum';
                                                $scope = 'all';
                                                if ($is_adminlike) {
                                                    if (!$has_kurikulum_task) {
                                                        $scope = (isset($_GET['scope']) && in_array($_GET['scope'], ['all','own'])) ? $_GET['scope'] : 'all';
                                                    }
                                                } else {
                                                    if (!empty($wali_kelas_user)) { $scope = 'walas'; }
                                                    elseif ($level_user === 'guru') { $scope = 'own'; }
                                                }

                                                // Kelas header sesuai scope
                                                $kelas_headers = [];
                                                if ($scope === 'walas' && $wali_kelas_user !== '') {
                                                    $kelas_headers = [$wali_kelas_user];
                                                } elseif ($scope === 'own') {
                                                    $qkh = mysqli_query($koneksi, "SELECT DISTINCT kelas FROM jadwal_mapel WHERE guru=".$id_user_login." AND kuri='2' AND semester='".$semester."' AND tp='".$tapel."'");
                                                    while ($rh = mysqli_fetch_assoc($qkh)) { $kelas_headers[] = $rh['kelas']; }
                                                } else {
                                                    // admin/kurikulum/kepala (Semua)
                                                    $qkh = mysqli_query($koneksi, "SELECT DISTINCT kelas FROM jadwal_mapel WHERE kuri='2' AND semester='".$semester."' AND tp='".$tapel."' ORDER BY kelas");
                                                    while ($rh = mysqli_fetch_assoc($qkh)) { $kelas_headers[] = $rh['kelas']; }
                                                }
                                                foreach ($kelas_headers as $kh) { echo '<th>'.htmlspecialchars($kh).'</th>'; }
                                                ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $no=0;
                                                // Ambil mapel sesuai scope
                                                if ($scope === 'own') {
                                                    $sql_mapel = "SELECT DISTINCT mp.id AS id_mapel, mp.nama_mapel "/**/
                                                                ."FROM jadwal_mapel jm JOIN mata_pelajaran mp ON mp.id=jm.mapel "
                                                                ."WHERE jm.kuri='2' AND jm.semester='".$semester."' AND jm.tp='".$tapel."' AND jm.guru=".$id_user_login." "
                                                                ."ORDER BY mp.id";
                                                } elseif ($scope === 'walas' && $wali_kelas_user !== '') {
                                                    $kelas_esc = mysqli_real_escape_string($koneksi, $wali_kelas_user);
                                                    $sql_mapel = "SELECT DISTINCT mp.id AS id_mapel, mp.nama_mapel "
                                                                ."FROM jadwal_mapel jm JOIN mata_pelajaran mp ON mp.id=jm.mapel "
                                                                ."WHERE jm.kuri='2' AND jm.semester='".$semester."' AND jm.tp='".$tapel."' AND jm.kelas='".$kelas_esc."' "
                                                                ."ORDER BY mp.id";
                                                } else {
                                                    $sql_mapel = "SELECT DISTINCT mp.id AS id_mapel, mp.nama_mapel "
                                                                ."FROM jadwal_mapel jm JOIN mata_pelajaran mp ON mp.id=jm.mapel "
                                                                ."WHERE jm.kuri='2' AND jm.semester='".$semester."' AND jm.tp='".$tapel."' "
                                                                ."ORDER BY mp.id";
                                                }
                                                $query = mysqli_query($koneksi, $sql_mapel);
                                                while ($data = mysqli_fetch_assoc($query)) :
                                                    $no++;
                                                    $id_mapel = (int)$data['id_mapel'];
                                                    $nama_mapel = $data['nama_mapel'];
                                                    // TKT: distinct tingkat dari jadwal_mapel
                                                    $tkt_arr = [];
                                                    if (!empty($kelas_headers)) {
                                                        $escaped = array_map(function($x){ return "'".mysqli_real_escape_string($GLOBALS['koneksi'],$x)."'"; }, $kelas_headers);
                                                        $in_kelas = implode(',', $escaped);
                                                        $qt = mysqli_query($koneksi, "SELECT DISTINCT tingkat FROM jadwal_mapel WHERE mapel='".$id_mapel."' AND kuri='2' AND kelas IN ($in_kelas)");
                                                    } else {
                                                        $qt = mysqli_query($koneksi, "SELECT DISTINCT tingkat FROM jadwal_mapel WHERE mapel='".$id_mapel."' AND kuri='2'");
                                                    }
                                                    while ($rt = mysqli_fetch_assoc($qt)) { if ($rt['tingkat']!=='') $tkt_arr[] = $rt['tingkat']; }
                                                    $tkt_label = !empty($tkt_arr) ? implode('/', array_unique($tkt_arr)) : '-';
                                                    // indikator lengkap (scope own) untuk rapor
                                                    $indicator_html = '';
                                                    if ($scope === 'own' && !empty($kelas_headers)) {
                                                        $all_complete = true; $incomplete_classes = [];
                                                        foreach ($kelas_headers as $khx) {
                                                            $total_siswa = (int)mysqli_num_rows(mysqli_query($koneksi, "SELECT 1 FROM siswa WHERE kelas='".mysqli_real_escape_string($koneksi,$khx)."'"));
                                                            $c_sts = (int)mysqli_num_rows(mysqli_query($koneksi, "SELECT 1 FROM nilai_sts WHERE kelas='".mysqli_real_escape_string($koneksi,$khx)."' AND mapel='".$id_mapel."' AND semester='".$semester."' AND tp='".$tapel."' AND nilai_sts>0"));
                                                            $c_sas = (int)mysqli_num_rows(mysqli_query($koneksi, "SELECT 1 FROM nilai_sts WHERE kelas='".mysqli_real_escape_string($koneksi,$khx)."' AND mapel='".$id_mapel."' AND semester='".$semester."' AND tp='".$tapel."' AND nilai_sas>0"));
                                                            $c_rap = (int)mysqli_num_rows(mysqli_query($koneksi, "SELECT 1 FROM nilai_sts WHERE kelas='".mysqli_real_escape_string($koneksi,$khx)."' AND mapel='".$id_mapel."' AND semester='".$semester."' AND tp='".$tapel."' AND nilai_raport>0"));
                                                            if ($total_siswa>0 && ($c_sts < $total_siswa || $c_sas < $total_siswa || $c_rap < $total_siswa)) { $all_complete=false; $incomplete_classes[]=$khx; }
                                                        }
                                                        if ($all_complete) {
                                                            $indicator_html = "<div style='font-size:16px;color:#16a34a;margin-top:4px'><i class='material-icons'>check_circle</i></div>";
                                                        } else {
                                                            $title = 'Masih kurang di: '.implode(', ', $incomplete_classes);
                                                            $indicator_html = "<div style='font-size:16px;color:#dc2626;margin-top:4px' title='".htmlspecialchars($title,ENT_QUOTES)."'>X</div>";
                                                        }
                                                    }
                                                   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                     <td><h5><span class="badge badge-dark"><?= htmlspecialchars($tkt_label) ?></span></h5><?= $indicator_html ?></td>
                                                      <td><?= htmlspecialchars($nama_mapel) ?></td>
                                                    <?php
                                                 foreach ($kelas_headers as $kelasnm) :
                                                 $mapelid = (string)$id_mapel;
                                                 $jsiswa = (int)mysqli_num_rows(mysqli_query($koneksi, "SELECT kelas FROM siswa WHERE kelas='".mysqli_real_escape_string($koneksi,$kelasnm)."'"));
                                                 // STS
                                                 $miss_sts = [];
                                                 $qmiss = mysqli_query($koneksi, "SELECT s.nama FROM siswa s LEFT JOIN nilai_sts n ON n.idsiswa=s.id_siswa AND n.mapel='".$mapelid."' AND n.kelas='".mysqli_real_escape_string($koneksi,$kelasnm)."' AND n.semester='".$semester."' AND n.tp='".$tapel."' WHERE s.kelas='".mysqli_real_escape_string($koneksi,$kelasnm)."' AND (n.nilai_sts IS NULL OR n.nilai_sts='' OR n.nilai_sts='0')");
                                                 while ($rm = mysqli_fetch_assoc($qmiss)) { $miss_sts[] = $rm['nama']; }
                                                 $present_sts = max(0, $jsiswa - count($miss_sts));
                                                 $pst = $jsiswa>0 ? round(($present_sts/$jsiswa)*100) : 0;
                                                 // SAS
                                                 $miss_sas = [];
                                                 $qmiss2 = mysqli_query($koneksi, "SELECT s.nama FROM siswa s LEFT JOIN nilai_sts n ON n.idsiswa=s.id_siswa AND n.mapel='".$mapelid."' AND n.kelas='".mysqli_real_escape_string($koneksi,$kelasnm)."' AND n.semester='".$semester."' AND n.tp='".$tapel."' WHERE s.kelas='".mysqli_real_escape_string($koneksi,$kelasnm)."' AND (n.nilai_sas IS NULL OR n.nilai_sas='' OR n.nilai_sas='0')");
                                                 while ($rm2 = mysqli_fetch_assoc($qmiss2)) { $miss_sas[] = $rm2['nama']; }
                                                 $present_sas = max(0, $jsiswa - count($miss_sas));
                                                 $pss = $jsiswa>0 ? round(($present_sas/$jsiswa)*100) : 0;
                                                 // Raport
                                                 $miss_rap = [];
                                                 $qmiss3 = mysqli_query($koneksi, "SELECT s.nama FROM siswa s LEFT JOIN nilai_sts n ON n.idsiswa=s.id_siswa AND n.mapel='".$mapelid."' AND n.kelas='".mysqli_real_escape_string($koneksi,$kelasnm)."' AND n.semester='".$semester."' AND n.tp='".$tapel."' WHERE s.kelas='".mysqli_real_escape_string($koneksi,$kelasnm)."' AND (n.nilai_raport IS NULL OR n.nilai_raport=0)");
                                                 while ($rm3 = mysqli_fetch_assoc($qmiss3)) { $miss_rap[] = $rm3['nama']; }
                                                 $present_rap = max(0, $jsiswa - count($miss_rap));
                                                 $prp = $jsiswa>0 ? round(($present_rap/$jsiswa)*100) : 0;
                                                 ?>
                                                   <td>
                                                   <div>
                                                     <span class="badge badge-success" style="cursor:pointer" title="Klik untuk lihat siapa yang belum" onclick='showMissing("STS - <?= htmlspecialchars($kelasnm) ?> - <?= htmlspecialchars($nama_mapel) ?>", this.dataset.miss, this.dataset.guru)'
                                                       data-miss='<?= htmlspecialchars(json_encode($miss_sts), ENT_QUOTES) ?>' data-guru="">STS : <?= $pst ?>%</span>
                                                   </div>
                                                   <div>
                                                     <span class="badge badge-warning" style="cursor:pointer" title="Klik untuk lihat siapa yang belum" onclick='showMissing("SAS - <?= htmlspecialchars($kelasnm) ?> - <?= htmlspecialchars($nama_mapel) ?>", this.dataset.miss, this.dataset.guru)'
                                                       data-miss='<?= htmlspecialchars(json_encode($miss_sas), ENT_QUOTES) ?>' data-guru="">SAS : <?= $pss ?>%</span>
                                                   </div>
                                                   <div>
                                                     <span class="badge badge-primary" style="cursor:pointer" title="Klik untuk lihat siapa yang belum" onclick='showMissing("Raport - <?= htmlspecialchars($kelasnm) ?> - <?= htmlspecialchars($nama_mapel) ?>", this.dataset.miss, this.dataset.guru)'
                                                       data-miss='<?= htmlspecialchars(json_encode($miss_rap), ENT_QUOTES) ?>' data-guru="">Raport : <?= $prp ?>%</span>
                                                   </div>
                                                   </td>
                                                   <?php endforeach; ?> 
                                                </tr>
                                                <?php endwhile; ?>
                                                </table>
												 </div>
											</div>
										</div>
									</div>
                              
							
							 
                            </div>
						</div>	
                      </div>	
                               
	                  <script>

							var autoRefresh = setInterval(
								function() {
									$('#logabs').load('logabsen.php');
									$('#logabsen').load('logsis.php');
									$('#logabsenpeg').load('logpeg.php');
									$('#logpesan').load('logpesan.php');
								}, 1000
							);
						</script>
<div id="missing-overlay" class="no-print" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:1050; padding:5vh 1rem; box-sizing:border-box; overflow:auto; align-items:flex-start; justify-content:center;" onclick="if(event.target===this) hideMissing();">
  <div style="background:#fff; border-radius:8px; width:100%; max-width:560px; box-shadow:0 10px 25px rgba(0,0,0,.2); max-height:90vh; display:flex; flex-direction:column;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin:0; padding:16px 16px 8px; gap:.5rem;">
      <h5 id="missing-title" style="margin:0">Detail</h5>
      <button type="button" onclick="hideMissing()" class="btn btn-sm btn-dark">Tutup</button>
    </div>
    <div id="missing-body" style="flex:1; overflow:auto; border:1px solid #eee; margin:0 16px 16px; padding:8px;">
      <ol id="missing-list" style="margin:0; padding-left:18px;"></ol>
    </div>
  </div>
</div>
<script>
  function showMissing(title, jsonList, guru){
    var arr=[]; try{ arr=JSON.parse(jsonList||'[]'); }catch(e){ arr=[]; }
    var fullTitle = title;
    if(guru){ fullTitle += ' — ' + guru; }
    document.getElementById('missing-title').textContent = fullTitle;
    var list = document.getElementById('missing-list');
    list.innerHTML = '';
    if(!arr || arr.length===0){
      var li = document.createElement('li'); li.textContent = 'Semua siswa sudah terisi.'; list.appendChild(li);
    } else {
      arr.forEach(function(n){ var li=document.createElement('li'); li.textContent=n; list.appendChild(li); });
    }
    var overlay = document.getElementById('missing-overlay');
    overlay.style.display = 'flex';
    var body = document.getElementById('missing-body');
    if(body){ body.scrollTop = 0; }
  }
  function hideMissing(){ document.getElementById('missing-overlay').style.display='none'; }
</script>
