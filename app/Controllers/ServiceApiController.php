<?php

namespace App\Controllers;

use App\Models\ServiceModel;

class ServiceApiController extends BaseController
{
    protected $serviceModel;

    public function __construct()
    {
        $this->serviceModel = new ServiceModel();
    }

    public function getServices()
    {
        $apiKey = $this->request->getHeaderLine('X-API-KEY');

        if ($apiKey !== 'MUA_SECRET_KEY_2026') {
            return $this->response->setJSON([
                'status'=>401,
                'error'=>true,
                'message'=>'Unauthorized'
            ])->setStatusCode(401);
        }

        return $this->response->setJSON([
            'status'=>200,
            'error'=>false,
            'total'=>$this->serviceModel->countAllResults(false),
            'data'=>$this->serviceModel->findAll()
        ]);
    }
}