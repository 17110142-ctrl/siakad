<?php
/**
 * Helper utilities for integrating with Dapodik Web Service.
 *
 * This file centralises configuration storage plus commonly used helpers
 * (URL normalisation, semester id derivation, etc.) so both the UI page
 * and AJAX handlers can share the same logic.
 */

if (!function_exists('dapodik_ensure_tables')) {
    /**
     * Ensure configuration table exists and seeded with default row.
     */
    function dapodik_ensure_tables(mysqli $koneksi): void
    {
        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS dapodik_config (
    id INT(11) NOT NULL AUTO_INCREMENT,
    base_url VARCHAR(255) NOT NULL DEFAULT 'http://localhost:5774',
    token VARCHAR(255) NOT NULL DEFAULT '',
    npsn VARCHAR(32) NOT NULL DEFAULT '',
    semester_id VARCHAR(16) NOT NULL DEFAULT '',
    semester_label VARCHAR(64) NOT NULL DEFAULT '',
    last_test_at DATETIME DEFAULT NULL,
    last_test_status VARCHAR(32) DEFAULT NULL,
    last_test_message TEXT DEFAULT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;

        mysqli_query($koneksi, $sql);

        $defaultCheck = mysqli_query($koneksi, "SELECT id FROM dapodik_config WHERE id=1 LIMIT 1");
        if ($defaultCheck) {
            $exists = mysqli_fetch_assoc($defaultCheck);
            mysqli_free_result($defaultCheck);
            if ($exists) {
                return;
            }
        }

        $now = date('Y-m-d H:i:s');
        $stmt = mysqli_prepare(
            $koneksi,
            "INSERT INTO dapodik_config (id, base_url, token, npsn, semester_id, semester_label, created_at, updated_at)
             VALUES (1, 'http://localhost:5774', '', '', '', '', ?, ?)"
        );
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ss', $now, $now);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}

if (!function_exists('dapodik_get_config')) {
    /**
     * Fetch current configuration row.
     *
     * @return array<string,mixed>
     */
    function dapodik_get_config(mysqli $koneksi): array
    {
        dapodik_ensure_tables($koneksi);
        $res = mysqli_query($koneksi, "SELECT * FROM dapodik_config WHERE id=1 LIMIT 1");
        if (!$res) {
            return [];
        }
        $row = mysqli_fetch_assoc($res) ?: [];
        mysqli_free_result($res);
        return $row;
    }
}

if (!function_exists('dapodik_save_config')) {
    /**
     * Persist configuration updates.
     *
     * @param array<string,string> $data
     */
    function dapodik_save_config(mysqli $koneksi, array $data): bool
    {
        dapodik_ensure_tables($koneksi);
        $now = date('Y-m-d H:i:s');

        $baseUrl = trim((string)($data['base_url'] ?? ''));
        $token = trim((string)($data['token'] ?? ''));
        $npsn = trim((string)($data['npsn'] ?? ''));
        $semesterId = trim((string)($data['semester_id'] ?? ''));
        $semesterLabel = trim((string)($data['semester_label'] ?? ''));

        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE dapodik_config
             SET base_url=?, token=?, npsn=?, semester_id=?, semester_label=?, updated_at=?
             WHERE id=1"
        );
        if (!$stmt) {
            return false;
        }
        mysqli_stmt_bind_param($stmt, 'ssssss', $baseUrl, $token, $npsn, $semesterId, $semesterLabel, $now);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }
}

if (!function_exists('dapodik_update_test_status')) {
    /**
     * Store latest connection test result.
     */
    function dapodik_update_test_status(mysqli $koneksi, string $status, string $message = ''): void
    {
        dapodik_ensure_tables($koneksi);
        $now = date('Y-m-d H:i:s');
        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE dapodik_config
             SET last_test_at=?, last_test_status=?, last_test_message=?, updated_at=?
             WHERE id=1"
        );
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ssss', $now, $status, $message, $now);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}

if (!function_exists('dapodik_normalize_base_url')) {
    /**
     * Normalise base URL so it never ends with trailing slash.
     */
    function dapodik_normalize_base_url(string $url): string
    {
        $url = trim($url);
        if ($url === '') {
            return 'http://localhost:5774';
        }
        return rtrim($url, "/ \t\n\r\0\x0B");
    }
}

if (!function_exists('dapodik_semester_label_from_setting')) {
    /**
     * Generate a human friendly semester label from aplikasi setting.
     */
    function dapodik_semester_label_from_setting(array $setting): string
    {
        $tp = trim((string)($setting['tp'] ?? ''));
        $semester = trim((string)($setting['semester'] ?? ''));
        $semester = $semester === '2' ? 'Genap' : 'Ganjil';
        if ($tp === '') {
            return $semester;
        }
        return $tp . ' ' . $semester;
    }
}

if (!function_exists('dapodik_semester_id_from_setting')) {
    /**
     * Build Dapodik semester_id (YYYY1 / YYYY2) using aplikasi setting.
     */
    function dapodik_semester_id_from_setting(array $setting): string
    {
        $tp = trim((string)($setting['tp'] ?? ''));
        if (preg_match('/^(\\d{4})/', $tp, $match)) {
            $year = $match[1];
        } else {
            $year = date('Y');
        }

        $semester = trim((string)($setting['semester'] ?? '1'));
        $semesterDigit = $semester === '2' ? '2' : '1';
        return $year . $semesterDigit;
    }
}

/**
 * Merge stored semester data with current aplikasi default when stored values empty.
 *
 * @return array{semester_id:string, semester_label:string}
 */
if (!function_exists('dapodik_resolve_semester_info')) {
    function dapodik_resolve_semester_info(array $config, array $setting): array
    {
        $id = trim((string)($config['semester_id'] ?? ''));
        $label = trim((string)($config['semester_label'] ?? ''));

        if ($id === '') {
            $id = dapodik_semester_id_from_setting($setting);
        }
        if ($label === '') {
            $label = dapodik_semester_label_from_setting($setting);
        }

        return [
            'semester_id' => $id,
            'semester_label' => $label,
        ];
    }
}
