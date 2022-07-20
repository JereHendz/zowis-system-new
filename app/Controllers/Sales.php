<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\SalesModel;
use App\Models\SaleDetailModel;
use App\Models\ProducsModel;
use App\Models\ProductDetailModel;

class Sales extends ResourceController
{
    protected $format = 'json';

    public function index(){
        $salesModel = new SalesModel();
        $data = array(
            'sales' => $salesModel->findAll()
        );

        return $this->respond($data);
    }

    public function create(){
        // Instance of user model 
        $salesModel = new SalesModel();
        $saleDetailModel = new SaleDetailModel();
        $productDetailModel = new ProductDetailModel();
        $productsModel = new ProducsModel();

        // Get the form of provider 
        $data = $this->request->getJSON(true);
        $form = $data['cart'];
        $idBranchOffice = $data['idBranchOffice'];
        $idClient = $data['idClient'];
        $whoCreated = $data['whoCreated'];

        //calculo de datos para la orden de venta
        $gran_total = 0;
        $descuento_total = 0;
        $gran_subtotal = 0;
        foreach ($form as $item) {
            $precio = $item['price'];
            $quantity = $item['quantity'];
            $desc_decimal = $item['discount'] / 100;
            $subtotal = $quantity * $precio;
            $descuento = $quantity * $precio * $desc_decimal;
            $total = $subtotal - $descuento;

            $gran_subtotal += $subtotal;
            $descuento_total += $descuento;
            $gran_total += $total;
        }

        $data = [
            'idClient'          => $idClient,
            'idBranchOffice'    => $idBranchOffice,
            'subTotal'          => $gran_subtotal,
            'discount'          => $descuento_total,
            'total'             => $gran_total,
            'paymentType'       => 'Efectivo',
            'invoiced'          => 0,
            'status'            => 1,
            'whoCreated'        => $whoCreated,
            'createDate'        => date('Y-m-d H:m:s'),
        ];


        //Validating  information and save record on sale
        if (!$idSale = $salesModel->insert($data)) {
            return $this->failValidationErrors($salesModel->errors());
        }

        //insert into detail sale
        foreach ($form as $item){
            $idProduct = $item['idProduct'];
            $precio = $item['price'];
            $quantity = $item['quantity'];
            $aux_quantity = $quantity;
            $desc_decimal = $item['discount'] / 100;
            $subtotal = $quantity * $precio;
            $descuento = $quantity * $precio * $desc_decimal;
            $total = $subtotal - $descuento;

            $data = [
                'idSale'        => $idSale,
                'idProduct'     => $idProduct,
                'quantity'      => $quantity,
                'salePrice'      => $precio,
                'discount'      => $item['discount'],
                'total'         => $total,
                'whoCreated'    => $whoCreated,
                'createDate'    => date('Y-m-d H:m:s'),
            ];

            //Validating  information and save record on sale detail
            if (!$id = $saleDetailModel->insert($data)) {
                return $this->failValidationErrors($saleDetailModel->errors());
            }

            if($id){
                //validamos cantidades
                while($aux_quantity > 0){
                    //descuento del inventario
                    $detailProduct = $productDetailModel->getProductDetailByIdProduct($idProduct);
                    $idDetail = $detailProduct[0]['id'];
                    $stockDeatil = $detailProduct[0]['stock'];

                    if($stockDeatil > $aux_quantity){
                        //si las existencias son mayores o iguales a la cantidad vendida descontamos todo de una vez
                        $diference = $stockDeatil - $aux_quantity;
                        $aux_quantity = 0;
                        $dataUpdate = [
                            'stock'         => $diference,
                            'whodidit'      => $whoCreated,
                            'updateDate'    => date('Y-m-d H:m:s'),
                        ];
                        if(!$productDetailModel->update($idDetail, $dataUpdate)){
                            return $this->failValidationErrors($productDetailModel->errors());
                        }
                    }else if($stockDeatil < $aux_quantity){
                        //si las existencias de ese (lote o detalle de prod), no son suficientes, descontamos todo y dejamos pendiente la diferencia para sacarlo de otro locate
                        $diference = $aux_quantity - $stockDeatil;
                        $aux_quantity = $diference;
                        $dataUpdate = [
                            'stock'         => 0,
                            'whodidit'      => $whoCreated,
                            'updateDate'    => date('Y-m-d H:m:s'),
                        ];
                        if(!$productDetailModel->update($idDetail, $dataUpdate)){
                            return $this->failValidationErrors($productDetailModel->errors());
                        }
                    }else{
                        //si la cantidad es igual a las existencias entonces dejamos a 0 el(lote o detalle de prod)
                        $aux_quantity = 0;
                        $dataUpdate = [
                            'stock'         => 0,
                            'whodidit'      => $whoCreated,
                            'updateDate'    => date('Y-m-d H:m:s'),
                        ];
                        if(!$productDetailModel->update($idDetail, $dataUpdate)){
                            return $this->failValidationErrors($productDetailModel->errors());
                        }
                    }
                }
                //despues de haber descontado las unidades de los lotes, detalles o ubicaciones, actualizamos el saldo global del producto
                $data_prod = $productsModel->find($idProduct);
                $stockProduct = $data_prod['stockProduct'];
                $new_stock = $stockProduct - $quantity;
                $dataUpdate1 = [
                    'stockProduct'  => $new_stock,
                    'whodidit'      => $whoCreated,
                    'updateDate'    => date('Y-m-d H:m:s'),
                ];

                if(!$productsModel->update($idProduct, $dataUpdate1)){
                    return $this->failValidationErrors($productsModel->errors());
                }
            }
        }
        // Get the provider that has been saved
        $saleCreated = $salesModel->find($idSale);

        //Response using response trait of codeigniter 4
        return $this->respondCreated(['message' => 'Venta creada correctamente', 'data' => $saleCreated]);
    }

    public function update($id = null){
        $salesModel = new SalesModel();
        $form=$this->request->getJSON(true);

        if(empty($form)){
            return $this->failValidationErrors('Nothing to update');
        }

        if(!$salesModel->find($id)){
            return $this->failNotFound();
        }

        if(!$salesModel->update($id, $form)){
            return $this->failValidationErrors($salesModel->errors());
        }

        return $this->respondUpdated([
            'message'=>'Updated successfully',
            'data'=>$salesModel->find($id),
        ]);
    }

    public function show($id = null){
        $salesModel = new SalesModel();
        $data = array(
            'sale' => $salesModel->fin($id)
        );

        return $this->respond($data);
    }

}
