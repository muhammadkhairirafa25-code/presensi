<?php
class Presensi extends BaseModel
{
    private $id;
    private $nisn;

    public function __construct(PDO $db, $id = null, $nisn = '')
    {
        parent::__construct($db);
        $this->id   = $id;
        $this->nisn = $nisn;
    }

    public function getId()   { return $this->id; }
    public function getNisn() { return $this->nisn; }
    public function setId($v)   { $this->id = $v; }
    public function setNisn($v) { $this->nisn = $v; }

    public function getAll($tanggal = null, $idKelas = '', $keyword = '')
    {
        $tanggal = $tanggal ?: date('Y-m-d');
        // INNER JOIN tiga tabel: presensi, siswa, kelas
        $sql = "SELECT p.id, s.nisn, s.nama, k.nama_kelas, p.tanggal_waktu
                FROM presensi p
                INNER JOIN siswa s ON p.nisn = s.nisn
                INNER JOIN kelas k ON s.id_kelas = k.id_kelas
                WHERE DATE(p.tanggal_waktu) = :tgl";
        $params = ['tgl' => $tanggal];
        if ($idKelas !== '') {
            $sql .= " AND k.id_kelas = :kelas";
            $params['kelas'] = $idKelas;
        }
        if ($keyword !== '') {
            $sql .= " AND (s.nama LIKE :q1 OR s.nisn LIKE :q2)";
            $params['q1'] = "%$keyword%";
            $params['q2'] = "%$keyword%";
        }
        $st = $this->db->prepare($sql . " ORDER BY p.tanggal_waktu DESC");
        $st->execute($params);
        return $st->fetchAll();
    }

    // Hanya NISN yang dikirim; id & tanggal_waktu diisi otomatis oleh database
    public function create()
    {
        $st = $this->db->prepare("INSERT INTO presensi (nisn) VALUES (?)");
        return $st->execute([$this->nisn]);
    }

    public function delete()
    {
        $st = $this->db->prepare("DELETE FROM presensi WHERE id = ?");
        return $st->execute([$this->id]);
    }

    // Jumlah siswa unik yang hadir pada tanggal tertentu
    public function countHadir($tanggal)
    {
        $st = $this->db->prepare("SELECT COUNT(DISTINCT nisn) FROM presensi WHERE DATE(tanggal_waktu) = ?");
        $st->execute([$tanggal]);
        return (int) $st->fetchColumn();
    }

    // Total siswa dan jumlah hadir per kelas
    public function rekapPerKelas($tanggal)
    {
        $sql = "SELECT k.nama_kelas,
                       COUNT(DISTINCT s.nisn) AS total,
                       COUNT(DISTINCT CASE WHEN DATE(p.tanggal_waktu) = :tgl THEN p.nisn END) AS hadir
                FROM kelas k
                LEFT JOIN siswa s ON s.id_kelas = k.id_kelas
                LEFT JOIN presensi p ON p.nisn = s.nisn
                GROUP BY k.id_kelas, k.nama_kelas
                ORDER BY k.nama_kelas";
        $st = $this->db->prepare($sql);
        $st->execute(['tgl' => $tanggal]);
        return $st->fetchAll();
    }

    // Tap terbaru hari ini
    public function terbaru($tanggal, $limit = 5)
    {
        $limit = (int) $limit;
        $sql = "SELECT s.nama, k.nama_kelas, p.tanggal_waktu
                FROM presensi p
                INNER JOIN siswa s ON p.nisn = s.nisn
                INNER JOIN kelas k ON s.id_kelas = k.id_kelas
                WHERE DATE(p.tanggal_waktu) = ?
                ORDER BY p.tanggal_waktu DESC LIMIT $limit";
        $st = $this->db->prepare($sql);
        $st->execute([$tanggal]);
        return $st->fetchAll();
    }
}
