<?php

namespace App\Libraries;

use CodeIgniter\Database\ConnectionInterface;

class ForeignKeyChecker
{
    protected ConnectionInterface $db;

    protected array $referenceMap = [
        'tbl_guru' => [
            ['table' => 'tbl_jadwal', 'column' => 'id_guru'],
            ['table' => 'tbl_jurusan', 'column' => 'id_kaprog'],
            ['table' => 'tbl_kelas', 'column' => 'id_wali_kelas'],
            ['table' => 'tbl_users', 'column' => 'id_guru'],
        ],
        'tbl_kelas' => [
            ['table' => 'tbl_jadwal', 'column' => 'id_kelas'],
            ['table' => 'tbl_siswa', 'column' => 'id_kelas'],
            ['table' => 'tbl_absensi', 'column' => 'id_kelas'],
        ],
        'tbl_jurusan' => [
            ['table' => 'tbl_kelas', 'column' => 'id_jurusan'],
        ],
        'tbl_mata_pelajaran' => [
            ['table' => 'tbl_jadwal', 'column' => 'id_mapel'],
        ],
        'tbl_siswa' => [
            ['table' => 'tbl_absensi', 'column' => 'id_siswa'],
            ['table' => 'tbl_users', 'column' => 'id_siswa'],
        ],
        'tbl_jadwal' => [
            ['table' => 'tbl_absensi', 'column' => 'id_jadwal'],
        ],
        'tbl_users' => [],
        'tbl_absensi' => [],
    ];

    public function __construct(ConnectionInterface $db = null)
    {
        $this->db = $db ?? db_connect();
    }

    public function isReferenced(string $table, int|string $id): bool
    {
        return ! empty($this->getReferencingTables($table, $id));
    }

    public function getReferencingTables(string $table, int|string $id): array
    {
        $references = $this->referenceMap[$table] ?? [];
        $found = [];

        foreach ($references as $reference) {
            if ($this->hasReference($reference['table'], $reference['column'], $id)) {
                $found[] = $reference;
            }
        }

        return $found;
    }

    protected function hasReference(string $table, string $column, int|string $id): bool
    {
        $builder = $this->db->table($table)
            ->select($column)
            ->where($column, $id)
            ->limit(1);

        return $builder->get()->getRow() !== null;
    }
}
