<?php
require("../config/koneksi.php");
require("../config/function.php");
require("../config/crud.php");
require_once("wa_helpers.php");

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$today = date('Y-m-d');

function get_api_url($koneksi){
    $r = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT url_api FROM aplikasi LIMIT 1"));
    return $r['url_api'] ?? '';
}

if ($action === 'resend_one') {
    $id = $_POST['id'] ?? $_GET['id'] ?? '';
    $id = is_array($id) ? '' : trim((string)$id);
    if ($id === '') { echo json_encode(['status'=>'error','message'=>'Invalid id']); exit; }
    $event = wa_metrics_get_event($id);
    if (!$event) { echo json_encode(['status'=>'error','message'=>'Not found']); exit; }
    $api = get_api_url($koneksi);
    $num = wa_normalize_number($event['number'] ?? '', '62');
    if ($num === '') { echo json_encode(['status'=>'error','message'=>'Nomor WA kosong']); exit; }
    $msg = $event['message'] ?? '';
    if ($msg === '') { echo json_encode(['status'=>'error','message'=>'Pesan kosong']); exit; }
    list($ok,$code,$err) = wa_send_with_retry_simple($api, $msg, $num, 3);
    wa_metrics_update_log($id, $ok, $err, $code);
    echo json_encode(['status'=>'ok','success'=>$ok,'http_code'=>$code]);
    exit;
}

if ($action === 'resend_ids') {
    $ids = $_POST['ids'] ?? [];
    if (!is_array($ids)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid ids']);
        exit;
    }

    $ids = array_filter(array_map(function($v){ return trim((string)$v); }, $ids), function($v){ return $v !== ''; });
    if (empty($ids)) {
        echo json_encode(['status' => 'error', 'message' => 'No valid ids']);
        exit;
    }

    $api = get_api_url($koneksi);
    $sent = 0; $fail = 0; $total = 0;
    foreach ($ids as $id) {
        $total++;
        $event = wa_metrics_get_event($id);
        if (!$event) { $fail++; continue; }
        $num = wa_normalize_number($event['number'] ?? '', '62');
        if ($num === '') { $fail++; continue; }
        $msg = $event['message'] ?? '';
        if ($msg === '') { $fail++; continue; }
        list($ok, $code, $err) = wa_send_with_retry_simple($api, $msg, $num, 3);
        wa_metrics_update_log($id, $ok, $err, $code);
        if ($ok) { $sent++; } else { $fail++; }
    }

    echo json_encode(['status' => 'ok', 'total' => $total, 'sent' => $sent, 'failed' => $fail]);
    exit;
}

if ($action === 'resend_all_failed') {
    $api = get_api_url($koneksi);
    $okc=0; $failc=0; $total=0;
    $todayData = wa_metrics_get_day($today);
    foreach ($todayData['events'] as $event) {
        if (!empty($event['success'])) {
            continue;
        }
        $total++;
        $id = $event['log_id'] ?? '';
        if ($id === '') { $failc++; continue; }
        $num = wa_normalize_number($event['number'] ?? '', '62');
        if ($num === '') { $failc++; continue; }
        $msg = $event['message'] ?? '';
        if ($msg === '') { $failc++; continue; }
        list($ok,$code,$err) = wa_send_with_retry_simple($api, $msg, $num, 3);
        wa_metrics_update_log($id, $ok, $err, $code);
        if ($ok) $okc++; else $failc++;
    }
    echo json_encode(['status'=>'ok','total'=>$total,'sent'=>$okc,'failed'=>$failc]);
    exit;
}

echo json_encode(['status'=>'error','message'=>'Unknown action']);
?>
