<?php

namespace App\Controllers;

use App\Models\ServiceModel;

class Service extends BaseController
{
    public function index()
    {
        $model = new ServiceModel();
        $data['services'] = $model->findAll();

        return view('admin/services', $data);
    }

    public function create()
    {
        return view('admin/service_form');
    }

    public function store()
    {
        $model = new ServiceModel();

        $file = $this->request->getFile('photo');
        $filename = $file->getRandomName();
        $file->move('uploads/', $filename);

        $model->save([
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'duration' => $this->request->getPost('duration'),
            'photo' => $filename
        ]);

        return redirect()->to('/services');
    }

    public function delete($id)
    {
        $model = new ServiceModel();
        $model->delete($id);

        return redirect()->to('/services');
    }
}