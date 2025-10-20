<?php

interface DapodikClientInterface
{
    public function getSekolah(): array;

    public function getPesertaDidik(): array;

    public function getRombonganBelajar(): array;

    /**
     * REST endpoint to read MATEV rows for a rombel/semester.
     * @param array<string,mixed> $params
     */
    public function getMatevRapor(array $params = []): array;

    /**
     * Optional warm-up endpoint used by official e-Rapor before seeding MATEV.
     * Implemented as a GET with query params (e.g., a_dari_template=1).
     *
     * @param array<string,mixed> $params
     */
    public function getMatevNilai(array $params = []): array;

    public function kirimNilai(array $payload): array;

    public function kirimMatev(array $payload): array;
}
