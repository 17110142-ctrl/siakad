<?php

interface DapodikClientInterface
{
    public function getSekolah(): array;

    public function getPesertaDidik(): array;

    public function getRombonganBelajar(): array;

    public function kirimNilai(array $payload): array;

    public function kirimMatev(array $payload): array;
}
