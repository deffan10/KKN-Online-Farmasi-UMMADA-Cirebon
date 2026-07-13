<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    private $d = array(
        "otentikasi" => array(),
        "web" => array(
            "title" => "Dashboard",
            "modul" => "app/dashboard",
            "view"  => "app/dashboard",
            "page"  => "Dashboard",
        ),
    );

    function __construct()
    {
        parent::__construct();
        $this->otentikasi = otentikasi($this->d);
    }

    public function session()
    {
        debug($this->session->userdata());
    }

    public function index()
    {
        $this->d['web']['loadview'] = "app/dashboard";
        $this->d['web']['importPlugins'] = array(
            loadPlugins("datatables"),
            loadPlugins("datetime"),
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("myapp"),
        );

        // Fetch active KKN schedules
        $sekarang = date("Y-m-d");
        $q = $this->db->query("
            SELECT * FROM kkn 
            WHERE aktif = 'Y' 
              AND (daftarselesai >= ? OR kknselesai >= ? OR daftarselesai IS NULL OR kknselesai IS NULL)
            ORDER BY kknmulai ASC
        ", array($sekarang, $sekarang));
        
        $jadwal_aktif = array();
        foreach ($q->result_array() as $row) {
            $status_label = "Akan Aktif";
            $badge_color = "warning";
            
            if ($row['daftarmulai'] && $row['daftarselesai'] && $sekarang >= $row['daftarmulai'] && $sekarang <= $row['daftarselesai']) {
                $status_label = "Pendaftaran Terbuka";
                $badge_color = "success";
            } elseif ($row['kknmulai'] && $row['kknselesai'] && $sekarang >= $row['kknmulai'] && $sekarang <= $row['kknselesai']) {
                $status_label = "Sedang Berjalan";
                $badge_color = "primary";
            } elseif ($row['daftarmulai'] && $sekarang < $row['daftarmulai']) {
                $status_label = "Pendaftaran Belum Buka";
                $badge_color = "secondary";
            } elseif ($row['kknmulai'] && $sekarang < $row['kknmulai']) {
                $status_label = "Akan Aktif";
                $badge_color = "info";
            }

            // Get counts
            $pendaftar_count = $this->db->query("SELECT COUNT(id) as total FROM pendaftar WHERE idkkn = ?", array($row['id']))->row()->total;
            $lokasi_count = $this->db->query("SELECT COUNT(id) as total FROM lokasi WHERE idkkn = ?", array($row['id']))->row()->total;
            $aktifitas_count = $this->db->query("
                SELECT COUNT(a.id) as total
                FROM aktifitas a
                JOIN penempatan p ON a.idpenempatan = p.id
                JOIN kelompok k ON p.idkelompok = k.id
                JOIN lokasi l ON k.idlokasi = l.id
                WHERE l.idkkn = ?
            ", array($row['id']))->row()->total;

            $row['status_label'] = $status_label;
            $row['badge_color'] = $badge_color;
            $row['pendaftar_count'] = $pendaftar_count;
            $row['lokasi_count'] = $lokasi_count;
            $row['aktifitas_count'] = $aktifitas_count;

            $jadwal_aktif[] = $row;
        }

        $this->d['jadwal_aktif'] = $jadwal_aktif;

        // Fetch student's active KKN placement details if they are a participant
        $this->load->library("dataweb");
        $iduser = $this->session->userdata("iduser");
        $vCariPeserta = array(
            array("cond" => "where", "fld" => "u.id", "val" => $iduser),
        );
        $pesertaKKN = $this->dataweb->pesertakkn($vCariPeserta);
        
        $this->d['peserta_kkn'] = array();
        if ($pesertaKKN['status']) {
            $this->d['peserta_kkn'] = $pesertaKKN['db'];
        }

        // Fetch DPL status and active KKN groups
        $this->db->from("pembimbing as p");
        $this->db->join("hakakses as h", "h.id=p.idhakakses");
        $this->db->where("h.iduser", $iduser);
        $is_dpl = $this->db->get()->num_rows() > 0;
        
        $this->d['is_dpl'] = $is_dpl;
        $this->d['dpl_kkn'] = array();
        
        if ($is_dpl) {
            $this->db->from("kelompok as k");
            $this->db->select("
                k.id as idkelompok, k.namakelompok,
                wd.desa, kec.kecamatan, kab.kabupaten,
                kkn.tema, kkn.jenis, kkn.tahun, kkn.kknmulai, kkn.kknselesai
            ");
            $this->db->join("pembimbing_kkn as pk", "pk.id=k.idpembimbing_kkn", "inner");
            $this->db->join("sk_pembimbing as sp", "sp.id=pk.idsk_pembimbing", "inner");
            $this->db->join("kkn as kkn", "kkn.id=sp.idkkn", "inner");
            $this->db->join("pembimbing as p", "p.id=pk.idpembimbing", "inner");
            $this->db->join("hakakses as h", "h.id=p.idhakakses", "inner");
            $this->db->join("user as u", "u.id=h.iduser", "inner");
            $this->db->join("lokasi as l", "l.id=k.idlokasi", "left");
            $this->db->join("wilayah_desa as wd", "wd.id=l.iddesa", "left");
            $this->db->join("wilayah_kec as kec", "kec.id=wd.idkecamatan", "left");
            $this->db->join("wilayah_kab as kab", "kab.id=kec.idkabupaten", "left");
            $this->db->where("u.id", $iduser);
            $this->db->where("kkn.aktif", "Y");
            $this->db->where("kkn.tahun", date("Y"));
            $dplQuery = $this->db->get();
            if ($dplQuery->num_rows() > 0) {
                $this->d['dpl_kkn'] = $dplQuery->result_array();
            }
        }

        $this->load->view('app/index', $this->d);
    }

    public function switchrole($idgrup = null)
    {
        $this->load->library("dataweb");
        $cari = array(
            array("cond" => "where_in", "fld" => "u.id", "val" => $this->session->userdata("iduser")),
            array("cond" => "where_in", "fld" => "h.idgrup", "val" => $idgrup),
        );
        $grup = $this->dataweb->cariGrupUser_new($cari);
        //debug($grup);
        if ($grup['status']) {
            $data = array(
                "role" => $idgrup,
            );
            $this->session->set_userdata($data);
        }
        redirect("app/dashboard");
    }

    public function loadgrup()
    {
        $this->load->library("dataweb");
        $cari = array(
            array("cond" => "where_in", "fld" => "g.id", "val" => json_decode($this->session->userdata("idgrup"))),
        );
        $grup = $this->dataweb->cariGrup($cari);
        //echo $this->db->last_query();
        //debug($grup);
        $html = "";
        if ($grup['status']) {
            $html = "<ul>";
            foreach ($grup['db'] as $data => $dp) {
                $html .= "<li><a href='" . base_url('app/dashboard/switchrole/' . $dp['id']) . "'>" . $dp['nama_grup'] . "</a></li>";
            }
            $html .= "</ul>";
        }
        $retVal['html'] = $html;

        die(json_encode($retVal));
    }

    public function identitas()
    {
        $this->d['web']['vContent'] = "app/vIdentitas";
        $this->d['web']['importPlugins'] = array(
            loadPlugins("notify"),
            loadPlugins("loading"),
        );
        $identitas = $this->db->query("SELECT * FROM user WHERE id='" . $this->session->userdata('iduser') . "'");
        $this->d['identitas'] = $identitas->row();
        $this->load->view('app/index', $this->d);
    }

    public function menuweb()
    {
        debug(menuweb());
    }
}
