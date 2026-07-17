<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_data extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$this->db->initialize();
	}

	public function runQuery($myQuery)
	{
		$ret = array('pesan' => ['hubungi admin'], 'status' => false, 'queryTime' => 0, 'db' => []);
		try {
			$this->db->db_debug = FALSE;
			$started = microtime(true);
			$runquery = $this->db->query($myQuery);
			$end = microtime(true);
			$difference = $end - $started;
			$queryTime = number_format($difference, 10);
			//$ret['queryTime']=$queryTime;

			//if ($this->db->trans_status() === FALSE || $queryTime>2.5)
			if ($runquery) {
				$ret['pesan'] = array('Berhasil dilakukan');
				$ret['status'] = true;
				$ret['db'] = $runquery;
			} else {
				$db_error = $this->db->error();
				$ret['pesan'] = array('Gagal, Error Code ' . $db_error['code'] . ' ' . $db_error['message']);
				$ret['status'] = false;
				$ret['db'] = [];
			}

			$this->db->db_debug = TRUE;
			return $ret;
		} catch (Exception $e) {
			log_message('error: ', $e->getMessage());
			return;
		}
	}

	public function generateCond($vCari = [])
	{
		if (count($vCari) > 0)
			foreach ($vCari as $fld => $val) {
				if (strcmp($val[0], "wherex") == 0)
					$this->db->where($val[1], null, false);
				elseif (strcmp($val[0], "where") == 0)
					$this->db->where($val[1], $val[2], null, false);
				elseif (strcmp($val[0], "like") == 0)
					$this->db->like($val[1], $val[2], $val[3], null, false);
				elseif (strcmp($val[0], "where_in") == 0)
					$this->db->where_in($val[1], $val[2], null, false);
				elseif (strcmp($val[0], "or_where") == 0)
					$this->db->or_where($val[1], $val[2], null, false);
				elseif (strcmp($val[0], "or_like") == 0)
					$this->db->or_like($val[1], $val[2], $val[3], null, false);
				elseif (strcmp($val[0], "or_where_in") == 0)
					$this->db->or_where_in($val[1], $val[2], null, false);
			}
	}

	public function searchData($vCari, $tblNm, $vColSel)
	{
		$ret = array('pesan' => ['hubungi admin'], 'status' => false, 'queryTime' => 0, 'db' => []);
		try {
			$this->db->db_debug = FALSE;

			$started = microtime(true);
			$this->db->select($vColSel, false);
			if (count($vCari) > 0)
				$this->generateCond($vCari);
			$runquery = $this->db->get($tblNm);

			if ($runquery) {
				$ret['pesan'] = array('Berhasil dilakukan');
				$ret['status'] = true;
				$ret['db'] = $runquery;
			} else {
				$db_error = $this->db->error();
				$ret['pesan'] = array('Gagal, Error Code ' . $db_error['code'] . ' ' . $db_error['message']);
				$ret['status'] = false;
				$ret['db'] = [];
			}

			$this->db->db_debug = TRUE;
			return $ret;
		} catch (Exception $e) {
			log_message('error: ', $e->getMessage());
			return;
		}
	}

	public function save($data, $tbl, $grup = "data", $log = false)
	{
		$ret = array('pesan' => ['hubungi admin'], 'status' => false, 'queryTime' => 0, 'db' => []);
		try {
			$this->db->db_debug = FALSE;

			$started = microtime(true);
			if (!isset($data['owned']) || !isset($data['created'])) {
				$data['owned'] = $this->session->userdata("iduser");
				$data['created'] = date("Y-m-d H:i:s");
			}
			//unset($data['id']);
			$runquery = $this->db->insert($tbl, $data);
			$lastId = $this->db->insert_id();
			$end = microtime(true);
			$difference = $end - $started;
			$queryTime = number_format($difference, 10);
			//$ret['queryTime'] = $queryTime;
			$ret['id'] = $lastId;
			if ($runquery) {
				$ret['pesan'] = array('Tambah ' . $grup . ' berhasil dilakukan');
				$ret['status'] = true;
				/*
				if ($log) {
					$datalog = array(
						'tabel' => $tbl,
						'iduser' => $this->session->userdata("iduser"),
						'idfk' => $lastId,
						'waktu' => date("Y-m-d h:i:s"),
						'aksi' => "tambah",
						'datalog' => "{data : " . json_encode($data) . "}",
					);
					$this->db->insert("log", $datalog);
				}
				*/
			} else {
				$db_error = $this->db->error();
				$ret['pesan'] = array('Gagal, Error Code ' . $db_error['code'] . ' ' . $db_error['message']);
				$ret['status'] = false;
			}

			$this->db->db_debug = TRUE;
			return $ret;
		} catch (Exception $e) {
			log_message('error: ', $e->getMessage());
			return;
		}
	}

	public function update($vCond, $data, $tbl, $grup = "data", $log = false)
	{
		$ret = array('pesan' => ['hubungi admin'], 'status' => false, 'db' => []);
		//untuk log
		//$lastData = $this->searchData($vCond, $tbl, "*")['db']->result_array();
		try {
			$this->db->db_debug = FALSE;
			$jumCond = count($vCond);
			$lastId = "";
			if ($jumCond > 0) {
				$i = 1;
				foreach ($vCond as $fld => $val) {
					$lastId = $lastId . $val[2];
					if (strcmp($val[0], "like") == 0)
						$this->db->like($val[1], $val[2], $val[3]);
					else
						$this->db->where($val[1], $val[2]);
					if ($i < $jumCond)
						$lastId = $lastId . ",";
					$i++;
				}

				$started = microtime(true);
				if (!isset($data['iduser_update'])) {
					$data['iduser_update'] = $this->session->userdata("iduser");
					$data['update'] = date("Y-m-d H:i:s");
				}


				$runquery = $this->db->update($tbl, $data);
				$end = microtime(true);
				$difference = $end - $started;
				$queryTime = number_format($difference, 10);
				//$ret['queryTime'] = $queryTime;
				$ret['id'] = $lastId;

				if ($runquery) {
					$ret['pesan'] = array('Update ' . $grup . ' berhasil dilakukan');
					$ret['status'] = true;
					/*
					if ($log) {
						$datalog = array(
							'tabel' => $tbl,
							'iduser' => $this->session->userdata("iduser"),
							'idfk' => $lastId,
							'waktu' => date("Y-m-d h:i:s"),
							'aksi' => "ganti",
							'datalog' => "{cond:" . json_encode($vCond) . ",datanew:" . json_encode($data) . ", lastdata:" . json_encode($lastData) . "}",
						);
						$this->db->insert("log", $datalog);
					}
					*/
				} else {
					$db_error = $this->db->error();
					$ret['pesan'] = array('Gagal, Error Code ' . $db_error['code'] . ' ' . $db_error['message']);
					$ret['status'] = false;
				}
			}
			$this->db->db_debug = TRUE;
			return $ret;
		} catch (Exception $e) {
			log_message('error: ', $e->getMessage());
			return;
		}
	}

	public function delete($vCond, $tbl, $grup = "data", $log = false)
	{
		//untuk log
		//$lastData = $this->searchData($vCond, $tbl, "*")['db']->result_array();
		try {
			$this->db->db_debug = FALSE;
			if (count($vCond) > 0) {
				$this->generateCond($vCond);

				$started = microtime(true);
				$runquery = $this->db->delete($tbl);
				$end = microtime(true);
				$difference = $end - $started;
				$queryTime = number_format($difference, 10);

				$ret['queryTime'] = $queryTime;

				if ($runquery) {
					$ret['pesan'] = array('Hapus ' . $grup . ' berhasil dilakukan');
					$ret['status'] = true;
					/*
					if ($log) {
						$datalog = array(
							'tabel' => $tbl,
							'iduser' => $this->session->userdata("iduser"),
							'waktu' => date("Y-m-d h:i:s"),
							'aksi' => "hapus",
							'datalog' => "{cond:" . json_encode($vCond) . ", lastdelete:" . json_encode($lastData) . "}",
						);
						$this->db->insert("log", $datalog);
					}
					*/
				} else {
					$db_error = $this->db->error();
					$ret['pesan'] = array('Gagal, Error Code ' . $db_error['code'] . ' ' . $db_error['message']);
					$ret['status'] = false;
				}
			}
			$this->db->db_debug = TRUE;
			return $ret;
		} catch (Exception $e) {
			log_message('error: ', $e->getMessage());
			return;
		}
	}

	public function delete_pendaftar_data($idpendaftar)
	{
		$db_debug_original = $this->db->db_debug;
		$this->db->db_debug = FALSE;

		$peserta_list = $this->db->get_where('peserta', array('idpendaftar' => $idpendaftar))->result();
		foreach ($peserta_list as $peserta) {
			$idpeserta = $peserta->id;
			
			$penempatan_list = $this->db->get_where('penempatan', array('idpeserta' => $idpeserta))->result();
			foreach ($penempatan_list as $penempatan) {
				$idpenempatan = $penempatan->id;
				
				$aktifitas_list = $this->db->get_where('aktifitas', array('idpenempatan' => $idpenempatan))->result();
				foreach ($aktifitas_list as $aktifitas) {
					$idaktifitas = $aktifitas->id;
					
					$this->db->where('idaktifitas', $idaktifitas)->delete('aktifitas_komentar');
					$this->db->where('idaktifitas', $idaktifitas)->delete('aktifitas_upload');
				}
				$this->db->where('idpenempatan', $idpenempatan)->delete('aktifitas');
				$this->db->where('idpenempatan', $idpenempatan)->delete('nilai');
				$this->db->where('idpenempatan', $idpenempatan)->delete('output_penempatan');
			}
			$this->db->where('idpeserta', $idpeserta)->delete('penempatan');
		}
		$this->db->where('idpendaftar', $idpendaftar)->delete('peserta');
		$this->db->where('idpendaftar', $idpendaftar)->delete('berkas_administrasi');
		$this->db->where('id', $idpendaftar)->delete('pendaftar');

		$db_error = $this->db->error();
		$this->db->db_debug = $db_debug_original;

		if ($db_error['code'] !== 0) {
			return $db_error;
		}
		return true;
	}

	public function delete_mahasiswa_data($iduser)
	{
		$db_debug_original = $this->db->db_debug;
		$this->db->db_debug = FALSE;

		@file_put_contents('debug_delete.txt', "Deleting iduser: $iduser\n", FILE_APPEND);
		$mahasiswa = $this->db->get_where('mahasiswa', array('iduser' => $iduser))->row();
		if ($mahasiswa) {
			$idmahasiswa = $mahasiswa->id;
			@file_put_contents('debug_delete.txt', "Found idmahasiswa: $idmahasiswa\n", FILE_APPEND);
			
			$pendaftar_list = $this->db->get_where('pendaftar', array('idmahasiswa' => $idmahasiswa))->result();
			@file_put_contents('debug_delete.txt', "Found pendaftar count: " . count($pendaftar_list) . "\n", FILE_APPEND);
			foreach ($pendaftar_list as $pendaftar) {
				@file_put_contents('debug_delete.txt', "Deleting pendaftar id: " . $pendaftar->id . "\n", FILE_APPEND);
				$status = $this->delete_pendaftar_data($pendaftar->id);
				@file_put_contents('debug_delete.txt', "Delete pendaftar status: " . json_encode($status) . "\n", FILE_APPEND);
				if ($status !== true) {
					$this->db->db_debug = $db_debug_original;
					return $status;
				}
			}
			$this->db->where('iduser', $iduser)->delete('mahasiswa');
			@file_put_contents('debug_delete.txt', "Delete mahasiswa error: " . json_encode($this->db->error()) . "\n", FILE_APPEND);
		} else {
			@file_put_contents('debug_delete.txt', "No mahasiswa profile found for iduser: $iduser\n", FILE_APPEND);
		}
		
		$this->db->where(array('iduser' => $iduser, 'idgrup' => 4))->delete('hakakses');

		$db_error = $this->db->error();
		$this->db->db_debug = $db_debug_original;

		if ($db_error['code'] !== 0) {
			return $db_error;
		}
		return true;
	}

	public function delete_pembimbing_data($iduser)
	{
		$db_debug_original = $this->db->db_debug;
		$this->db->db_debug = FALSE;

		$pembimbing = $this->db->get_where('pembimbing', array('iduser' => $iduser))->row();
		if ($pembimbing) {
			$idpembimbing = $pembimbing->id;
			
			$pkkn_list = $this->db->get_where('pembimbing_kkn', array('idpembimbing' => $idpembimbing))->result();
			foreach ($pkkn_list as $pkkn) {
				$this->db->where('idpembimbing_kkn', $pkkn->id)->update('kelompok', array('idpembimbing_kkn' => null));
				$this->db->where('id', $pkkn->id)->delete('pembimbing_kkn');
			}
			$this->db->where('id', $idpembimbing)->delete('pembimbing');
		}
		$this->db->where(array('iduser' => $iduser, 'idgrup' => 3))->delete('hakakses');

		$db_error = $this->db->error();
		$this->db->db_debug = $db_debug_original;

		if ($db_error['code'] !== 0) {
			return $db_error;
		}
		return true;
	}

	public function delete_admin_role_data($iduser)
	{
		$db_debug_original = $this->db->db_debug;
		$this->db->db_debug = FALSE;

		$this->db->where('iduser', $iduser)->delete('admin');
		$this->db->where(array('iduser' => $iduser, 'idgrup' => 1))->delete('hakakses');

		$db_error = $this->db->error();
		$this->db->db_debug = $db_debug_original;

		if ($db_error['code'] !== 0) {
			return $db_error;
		}
		return true;
	}

	public function delete_user_data($iduser)
	{
		$db_debug_original = $this->db->db_debug;
		$this->db->db_debug = FALSE;

		$status = $this->delete_mahasiswa_data($iduser);
		if ($status !== true) {
			$this->db->db_debug = $db_debug_original;
			return $status;
		}

		$status = $this->delete_pembimbing_data($iduser);
		if ($status !== true) {
			$this->db->db_debug = $db_debug_original;
			return $status;
		}

		$status = $this->delete_admin_role_data($iduser);
		if ($status !== true) {
			$this->db->db_debug = $db_debug_original;
			return $status;
		}
		
		$this->db->where('iduser', $iduser)->update('berita', array('iduser' => null));
		$this->db->where('iduser', $iduser)->delete('hakakses');
		$this->db->where('iduser', $iduser)->delete('aktifitas_komentar');
		$this->db->where('id', $iduser)->delete('user');

		$db_error = $this->db->error();
		$this->db->db_debug = $db_debug_original;

		if ($db_error['code'] !== 0) {
			return $db_error;
		}
		return true;
	}
}

/* End of file model_data.php */
/* Location: ./application/models/model_data.php */