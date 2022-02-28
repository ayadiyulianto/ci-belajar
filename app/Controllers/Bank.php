<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\BankModel;

class Bank extends BaseController
{

	protected $bankModel;
	protected $validation;

	public function __construct()
	{
		$this->bankModel = new BankModel();
		$this->validation =  \Config\Services::validation();
	}

	public function index()
	{

		$data = [
			'menu'    		=> 'bank',
			'title'     	=> 'Bank'
		];

		return view('bank/daftar_bank', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->bankModel->select('id_bank, nama_bank, norek, atas_nama')->findAll();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info" onclick="edit(' . $value->id_bank . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" onclick="remove(' . $value->id_bank . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$value->id_bank,
				$value->nama_bank,
				$value->norek,
				$value->atas_nama,

				$ops,
			);
		}

		$data['token'] = csrf_hash();
		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('id_bank');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->bankModel->where('id_bank', $id)->first();

			$data->token = csrf_hash();
			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['id_bank'] = $this->request->getPost('idBank');
		$fields['nama_bank'] = $this->request->getPost('namaBank');
		$fields['norek'] = $this->request->getPost('norek');
		$fields['atas_nama'] = $this->request->getPost('atasNama');


		$this->validation->setRules([
			'nama_bank' => ['label' => 'Nama bank', 'rules' => 'required|max_length[50]'],
			'norek' => ['label' => 'No Rekening', 'rules' => 'required|max_length[50]'],
			'atas_nama' => ['label' => 'Atas nama', 'rules' => 'required|max_length[50]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->bankModel->insert($fields)) {

				$response['success'] = true;
				$response['messages'] = 'Data has been inserted successfully';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Insertion error!';
			}
		}

		$response['token'] = csrf_hash();
		return $this->response->setJSON($response);
	}

	public function edit()
	{

		$response = array();

		$fields['id_bank'] = $this->request->getPost('idBank');
		$fields['nama_bank'] = $this->request->getPost('namaBank');
		$fields['norek'] = $this->request->getPost('norek');
		$fields['atas_nama'] = $this->request->getPost('atasNama');


		$this->validation->setRules([
			'nama_bank' => ['label' => 'Nama bank', 'rules' => 'required|max_length[50]'],
			'norek' => ['label' => 'No Rekening', 'rules' => 'required|max_length[50]'],
			'atas_nama' => ['label' => 'Atas nama', 'rules' => 'required|max_length[50]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->bankModel->update($fields['id_bank'], $fields)) {

				$response['success'] = true;
				$response['messages'] = 'Successfully updated';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Update error!';
			}
		}

		$response['token'] = csrf_hash();
		return $this->response->setJSON($response);
	}

	public function remove()
	{
		$response = array();

		$id = $this->request->getPost('id_bank');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->bankModel->where('id_bank', $id)->delete()) {

				$response['success'] = true;
				$response['messages'] = 'Deletion succeeded';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Deletion error!';
			}
		}

		$response['token'] = csrf_hash();
		return $this->response->setJSON($response);
	}
}
