<?php
class Kelas extends BaseModel
{
    private $idKelas;
    private $namaKelas;
    private $jurusan;

    public function __construct(PDO $db, $idKelas = null, $namaKelas = '', $jurusan = '')
    {
        parent::__construct($db);
        $this->idKelas   = $idKelas;
        $this->namaKelas = $namaKelas;
        $this->jurusan   = $jurusan;
    }

    public function getIdKelas()   { return $this->idKelas; }
    public function getNamaKelas() { return $this->namaKelas; }
    public function getJurusan()   { return $this->jurusan; }
    public function setIdKelas($v)   { $this->idKelas = $v; }
    public function setNamaKelas($v) { $this->namaKelas = $v; }
    public function setJurusan($v)   { $this->jurusan = $v; }

    public function getAll()
    {
        $sql = "SELECT k.*, (SELECT COUNT(*) FROM siswa s WHERE s.id_kelas = k.id_kelas) AS jumlah_siswa
                FROM kelas k ORDER BY k.nama_kelas";
        return $this->db->query($sql)->fetchAll();
    }

    public function find($id)
    {
        $st = $this->db->prepare("SELECT * FROM kelas WHERE id_kelas = ?");
        $st->execute([$id]);
        return $st->fetch();
    }

    public function namaExists($nama, $exceptId = 0)
    {
        $st = $this->db->prepare("SELECT COUNT(*) FROM kelas WHERE nama_kelas = ? AND id_kelas <> ?");
        $st->execute([$nama, $exceptId]);
        return $st->fetchColumn() > 0;
    }

    public function countSiswa()
    {
        $st = $this->db->prepare("SELECT COUNT(*) FROM siswa WHERE id_kelas = ?");
        $st->execute([$this->idKelas]);
        return (int) $st->fetchColumn();
    }

    public function create()
    {
        $st = $this->db->prepare("INSERT INTO kelas (nama_kelas, jurusan) VALUES (?, ?)");
        return $st->execute([$this->namaKelas, $this->jurusan]);
    }

    public function update()
    {
        $st = $this->db->prepare("UPDATE kelas SET nama_kelas = ?, jurusan = ? WHERE id_kelas = ?");
        return $st->execute([$this->namaKelas, $this->jurusan, $this->idKelas]);
    }

    public function delete()
    {
        $st = $this->db->prepare("DELETE FROM kelas WHERE id_kelas = ?");
        return $st->execute([$this->idKelas]);
    }
}
