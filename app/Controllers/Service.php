<?php

namespace App\Controllers;

use App\Models\ServiceModel;

class Service extends BaseController
{
    protected $serviceModel;

    public function __construct()
    {
        // Memasukkan model ke constructor agar tidak perlu memanggil 'new ServiceModel()' di setiap fungsi
        $this->serviceModel = new ServiceModel();
    }

public function index()
    {
        $data = [
            'title'    => 'Layanan Makeup',
            'menu'     => 'layanan', // Tambahkan ini
            'services' => $this->serviceModel->findAll()
        ];

        return view('admin/services', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Layanan',
            'menu'  => 'layanan' // Tambahkan ini
        ];
        // Pastikan nama file view sesuai (tadi kita buat services_create.php)
        return view('admin/services_create', $data);
    }

    public function save()
    {
        $file = $this->request->getFile('photo');
        
        // Cek apakah ada file yang diunggah
        if ($file->isValid() && !$file->hasMoved()) {
            $filename = $file->getRandomName();
            // Pindahkan ke folder public/assets/img/services agar mudah dipanggil di view
            $file->move('assets/img/services', $filename);
        } else {
            $filename = 'default.jpg'; // Opsi jika tidak upload foto
        }

        $this->serviceModel->save([
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price'       => $this->request->getPost('price'),
            'duration'    => $this->request->getPost('duration'),
            'photo'       => $filename
        ]);

        return redirect()->to('/services')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data = [
            'title'   => 'Edit Layanan',
            'menu'    => 'layanan',
            'service' => $this->serviceModel->find($id)
        ];

        return view('admin/services_edit', $data);
    }

    public function update($id)
    {
        $file = $this->request->getFile('photo');
        $oldPhoto = $this->request->getPost('oldPhoto');

        if ($file->isValid() && !$file->hasMoved()) {
            $filename = $file->getRandomName();
            $file->move('assets/img/services', $filename);
            
            // Hapus foto lama jika bukan default
            if ($oldPhoto != 'default.jpg' && file_exists('assets/img/services/' . $oldPhoto)) {
                unlink('assets/img/services/' . $oldPhoto);
            }
        } else {
            $filename = $oldPhoto;
        }

        $this->serviceModel->update($id, [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price'       => $this->request->getPost('price'),
            'duration'    => $this->request->getPost('duration'),
            'photo'       => $filename
        ]);

        return redirect()->to('/services')->with('success', 'Layanan berhasil diupdate.');
    }

    public function delete($id)
    {
        $service = $this->serviceModel->find($id);

        // Hapus file fisik foto dari folder jika ada
        if ($service['photo'] != 'default.jpg' && file_exists('assets/img/services/' . $service['photo'])) {
            unlink('assets/img/services/' . $service['photo']);
        }

        $this->serviceModel->delete($id);

        return redirect()->to('/services')->with('success', 'Layanan berhasil dihapus.');
    }
}