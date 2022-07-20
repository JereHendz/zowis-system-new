<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\SaleDetailModel;


class SaleDetail extends ResourceController
{
    protected $format = 'json';

    public function showSaleDetail($idVenta = null){
        $saleDetailModel = new SaleDetailModel();
        $data = array(
            'saleDetail' => $saleDetailModel->showSaleDetail($idVenta)
        );

        return $this->respond($data);
    }

}
