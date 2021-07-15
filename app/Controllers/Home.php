<?php

namespace App\Controllers;

use App\Models\MBuku;
use App\Models\MDenda;
use App\Models\MHome;
use App\Models\MKategori;
use App\Models\MPinjaman;
use App\Models\MUser;

class Home extends BaseController
{
	protected $m_home;
	protected $m_kategori;
	protected $m_user;
	protected $m_buku;
	protected $m_pinjaman;
	protected $m_denda;

	public function __construct()
	{
		$this->m_home = new MHome();
		$this->m_kategori = new MKategori();
		$this->m_user = new MUser();
		$this->m_buku = new MBuku();
		$this->m_pinjaman = new MPinjaman();
		$this->m_denda = new MDenda();
	}
	public function index()
	{
		$search = htmlentities($this->request->getGet('search'), ENT_QUOTES, 'UTF-8');
		$kategori = htmlentities($this->request->getGet('kategori'), ENT_QUOTES, 'UTF-8');
		if($kategori != null) {
			$this->m_buku->where('kategori_id', $kategori);
		}
		if($search != null) {
			$this->m_buku->like('nama_buku', $search);
		}
		$data = [
			"title" => "Buku Perpustakaan",
			"buku" => $this->m_buku->paginate(20),
			"pager" => $this->m_buku->pager,
			"keranjang" => $this->m_buku->get_total_keranjang_buku(),
			"kategori" => $this->m_kategori->get_kategori()
		];
		return view('index', $data + $this->data_session());
	}

	public function admin()
	{
		$data = [
			"title" => "Dashboard",
			"user_today" => $this->m_user->user_today(),
			"total_user" => $this->m_user->get_total_user(),
			"pinjaman_today" => $this->m_pinjaman->pinjaman_today(),
			"total_pinjaman" => $this->m_pinjaman->get_total_pinjaman(),
			"total_jatuh_tempo" => $this->m_pinjaman->pinjaman_jatuh_tempo(),
			"total_denda" => $this->m_denda->get_total_pendapatan_denda(),
			"pinjaman" => $this->m_pinjaman->week()
		];
		return view('admin/index', $data + $this->data_session());
	}

	public function pengaturan()
	{
		$data = [
			"title" => "Pengaturan"
		];
		return view('admin/pengaturan', $data + $this->data_session());
	}

	public function update_setting()
	{
		$validation = \Config\Services::validation();
		$validation->setRules(
			[
				'nama_app' => 'required|alpha_numeric_space'
			],
			[
				'nama_app' => [
					'required' => 'Nama App harus diisi.',
					'alpha_numeric_space' => 'Nama App harus terdiri dari huruf, angka dan spasi.'
				]
			]
		);
		if ($validation->withRequest($this->request)->run() == false) {
			return json_encode([
				"error" => true,
				"msg" => $validation->getError('nama_app')
			]);
		}
		$nama_app = htmlentities($this->request->getPost('nama_app'), ENT_QUOTES, 'UTF-8');
		$file = $this->request->getFile('logo');
		$foto = [];
		if ($file->getName() != "") {
			$mime = ['image/png', 'image/jpg', 'image/jpeg'];
			if (!$file->isValid() || !in_array($file->getMimeType(), $mime)) {
				return json_encode([
					"error" => true,
					"msg" => "File tidak valid."
				]);
			}
			if ($file->getSize() / 1024 / 1024 > 1) {
				return json_encode([
					"error" => true,
					"msg" => "File tidak boleh lebih besar dari 1MB."
				]);
			}
			$filename = $file->getName();
			$file->move('assets/img', $filename, true);
			$foto = ["logo" => $filename];
		} else {
			$filename = 'logo.png';
		}
		$data = ["nama_app" => $nama_app] + $foto;
		if ($this->m_home->update_setting($data)) {
			session()->set([
				"nama_app" => $nama_app,
				"logo" => $filename
			]);
			return json_encode([
				"error" => false,
				"msg" => "Pengaturan berhasil diupdate."
			]);
		} else {
			return json_encode([
				"error" => true,
				"msg" => "Pengaturan gagal diupdate,"
			]);
		}
	}
}
