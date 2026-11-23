<?php
class Siswa extends BaseModel
{
    private $nisn;
    private $nama;
    private $alamat;
    private $idKelas;

    public function __construct(PDO $db, $nisn = '', $nama = '', $alamat = '', $idKelas = '')
    {
        parent::__construct($db);
        $this->nisn    = $nisn;
        $this->nama    = $nama;
        $this->alamat  = $alamat;
        $this->idKelas = $idKelas;
    }

    public function getNisn()    { return $this->nisn; }
    public function getNama()    { return $this->nama; }
    public function getAlamat()  { return $this->alamat; }
    public function getIdKelas() { return $this->idKelas; }
    public function setNisn($v)    { $this->nisn = $v; }
    public function setNama($v)    { $this->nama = $v; }
    public function setAlamat($v)  { $this->alamat = $v; }
    public function setIdKelas($v) { $this->idKelas = $v; }

    // INNER JOIN siswa + kelas, dengan pencarian nama/NISN
    public function getAll($keyword = '')
    {
        $sql = "SELECT s.nisn, s.nama, s.alamat, k.nama_kelas
                FROM siswa s INNER JOIN kelas k ON s.id_kelas = k.id_kelas";
        $params = [];
        if ($keyword !== '') {
            $sql .= " WHERE s.nama LIKE :q1 OR s.nisn LIKE :q2";
            $params = ['q1' => "%$keyword%", 'q2' => "%$keyword%"];
        }
        $st = $this->db->prepare($sql . " ORDER BY s.nama");
        $st->execute($params);
        return $st->fetchAll();
    }

    public function find($nisn)
    {
        $st = $this->db->prepare("SELECT * FROM siswa WHERE nisn = ?");
        $st->execute([$nisn]);
        return $st->fetch();
    }

    // Validasi server-side, hasilnya array pesan per kolom
    public function validate(bool $isEdit = false): array
    {
        $err = [];
        if ($this->nisn === '') {
            $err['nisn'] = 'NISN wajib diisi.';
        } elseif (!preg_match('/^\d{10}$/', $this->nisn)) {
            $err['nisn'] = 'NISN harus 10 digit angka.';
        } elseif (!$isEdit && $this->find($this->nisn)) {
            $err['nisn'] = 'NISN sudah terdaftar.';
        }
        if (trim($this->nama) === '') {
            $err['nama'] = 'Nama wajib diisi.';
        } elseif (strlen($this->nama) > 100) {
            $err['nama'] = 'Nama maksimal 100 karakter.';
        }
        if (strlen($this->alamat) > 255) {
            $err['alamat'] = 'Alamat maksimal 255 karakter.';
        }
        if ($this->idKelas === '') {
            $err['id_kelas'] = 'Kelas wajib dipilih.';
        } elseif (!(new Kelas($this->db))->find((int) $this->idKelas)) {
            $err['id_kelas'] = 'Kelas tidak valid.';
        }
        return $err;
    }

    public function create()
    {
        $st = $this->db->prepare("INSERT INTO siswa (nisn, nama, alamat, id_kelas) VALUES (?, ?, ?, ?)");
        return $st->execute([$this->nisn, $this->nama, $this->alamat ?: null, $this->idKelas]);
    }

    public function update()
    {
        $st = $this->db->prepare("UPDATE siswa SET nama = ?, alamat = ?, id_kelas = ? WHERE nisn = ?");
        return $st->execute([$this->nama, $this->alamat ?: null, $this->idKelas, $this->nisn]);
    }

    public function delete()
    {
        $st = $this->db->prepare("DELETE FROM siswa WHERE nisn = ?");
        return $st->execute([$this->nisn]);
    }

    public function countAll()
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM siswa")->fetchColumn();
    }
}
