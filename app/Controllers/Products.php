<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ProducsModel;
use App\Models\CategoriesModel;
use App\Models\SubCategoriesModel;
use App\Models\BrandsModel;
use App\Models\ProvidersModel;
use App\Models\ImagesModel;
use App\Models\ProductDetailModel;


// use CodeIgniter\Files\File;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

date_default_timezone_set('America/El_Salvador');
Configuration::instance([
    'cloud' => [
        'cloud_name' => 'devjson',
        'api_key' => '128981583667973',
        'api_secret' => 'GIxV8I5f_7wzDq-RYMKwLBbiQWc'
    ],
    'url' => [
        'secure' => true
    ]
]);

class Products extends ResourceController
{
    public function index()
    {
        //Get all products
        $productsModel = new ProducsModel();
        // $imagesModel = new ImagesModel();

        $data = array(
            'listProducts' => $productsModel->findAll(),
            // 'tes'=> $imagesModel->getImgOrderedByDate(22, 16)

        );

        return $this->respond($data);
    }

    public function getInformationToCreateProduct()
    {
        $category = new CategoriesModel();
        $subCategory = new SubCategoriesModel();
        $brands = new BrandsModel();
        $provider = new ProvidersModel();


        $data = array(
            "categories" => $category->getCategoriesByStatus(1),
            "subCategories" => $subCategory->getSubCategoriesByStatus(1),
            "brands" => $brands->getBrandsByStatus(1),
            "providers" => $provider->getProvidersByStatus(1),

        );

        return $this->respond($data);
    }

    public function create()
    {

        $productsModel = new ProducsModel();
        $imagesModel = new ImagesModel();
        $productDetailModel = new ProductDetailModel();



        $files = $this->request->getFiles();

        $informationProduct = json_decode($this->request->getPost('informationProduct'), true);
        $whoCreated = 0;
        $idProduct = false;
        if (count($informationProduct) > 0) {
            $whoCreated = $informationProduct['whoCreated'];
            $productCode = $productsModel->getProductCode();
            $productCode = $productCode != false ? $productCode['productCode'] + 1 : 0;
            $arrayProduct = array(
                "productCode" => $productCode,
                "productName" => $informationProduct['productName'],
                "description" => $informationProduct['description'],
                "stockProduct" => $informationProduct['stockProduct'],
                "stockLimit" => floatval($informationProduct['stockLimit']),
                "percentageProfit" => floatval($informationProduct['percentageProfit']),
                "idSubCategory" => intval($informationProduct['idSubCategory']),
                "whoCreated" => $whoCreated,
                "createDate" => date('Y-m-d H:i:s'),
                "barcode" => $informationProduct['barcode']
            );

            if (!$idProduct = $productsModel->insert($arrayProduct)) {
                return $this->failValidationErrors($productsModel->errors());
            }
        }


        // Detail product
        $productDetail = json_decode($this->request->getPost('detailProduct'), true);


        if (count($productDetail) > 0) {
            $arrayProductDetail = array(
                "idProvider" => intval($productDetail['idProvider']),
                "idBrand" => intval($productDetail['idBrand']),
                "quantity" => intval($productDetail['quantity']),
                "unitPurchasePrice" => floatval($productDetail['unitPurchasePrice']),
                "unitSalePrice" => floatval($productDetail['unitSalePrice']),
                "idBranchOffice" => intval($productDetail['idBranchOffice']),
                "idWineries" => $productDetail['idWineries'],
                "idFirstLevelLocation" => $productDetail['idFirstLevelLocation'],
                "idSecondLevelLocation" => $productDetail['idSecondLevelLocation'],
                "idThirdLevelLocation" => $productDetail['idThirdLevelLocation'],
                "whoCreated" => $whoCreated,
                "idProduct" => $idProduct,
                "createDate" => date('Y-m-d H:i:s')
            );

            if (!$idProductDetail = $productDetailModel->insert($arrayProductDetail)) {
                return $this->failValidationErrors($productDetailModel->errors());
            }
            $updateProduct = array(
                "stockProduct" => $productDetail['quantity'],
                "unitSalePriceAvg"=> $productDetail['unitSalePrice'],
                "unitPurchasePriceAvg"=> $productDetail['unitPurchasePrice'],
            );
            if (!$productsModel->update($idProduct, $updateProduct)) {
                return $this->failValidationErrors($productsModel->errors());
            }
        }

        $uploadPath = "../Uploads/";
        $Fecha = date("YmdHis");
        $contImg=0;
        foreach ($files as $img) {

            if ($img->isValid() && !$img->hasMoved()) {
                $realName = $img->getName();
                $nameCloudinary = explode('.', $realName)[0] . $Fecha.$contImg;
                $nameCloudinary = str_replace(" ", "", $nameCloudinary);
                $pathName = $img->getRealPath();
                $uploaded = (new UploadApi())->upload($pathName, [
                    'folder' => 'zowis/',
                    'public_id' => $nameCloudinary,
                ]);
                $contImg++;

                $arrayImages = array(
                    "link" => $uploaded['url'],
                    "whoCreated" => $whoCreated,
                    "idProduct" => $idProduct,
                    "createDate" => date('Y-m-d H:i:s'),
                    "priority"=>$contImg,
                    "publicIdCloudinary" => 'zowis/' . $nameCloudinary
                );

                if (!$saveImage = $imagesModel->insert($arrayImages)) {
                    return $this->failValidationErrors($imagesModel->errors());
                }

                //  echo $uploaded['url'];        

                // To upload files with codeigniter
                // $newName = $img->getRandomName();
                // $img->move($uploadPath, $newName);

            }
        }

        return $this->respondCreated(['message' => 'Create Successfully', 'data' => $idProduct]);
    }

    public function getAllProductsWithImages()
    {
        $productModel = new ProducsModel();
        $pr = $productModel->getProductWithImage();
        $products = array();
        foreach ($pr as $key => $value) {
            $products[] = array(
                "id" => $value['id'],
                "img" => !empty($value['link']) ? $value['link'] : "ecommerce/01.jpg",
                "name" => $value['productName'],
                "note" => "test not",
                "note" => $value['barcode'],
                "discription" => $value['description'],
                "discountPrice" => $value['productDiscount'],
                "status" => "none",
                "price" => floatval($value['unitSalePrice']),
                "stock" => "In stock",
                "review" => "(250 review)",
                "category" => "Man",
                "colors" => array(
                    "White",
                    "gray"
                ),
                "size" => array(
                    "M",
                    "L",
                    "XL"
                ),
                "tags" => array(
                    "Diesel",
                    "Hudson",
                    "Lee"
                ),
                "variants" => array(
                    "color" => array(
                        "color" => "White",
                        "images" => "ecommerce/01.jpg"
                    ),
                    "color" => array(
                        "color" => "gray",
                        "images" => "ecommerce/04.jpg"
                    ),
                    "color" => array(
                        "color" => "black",
                        "images" => "ecommerce/02.jpg"
                    ),
                    "color" => array(
                        "color" => "pink",
                        "images" => "ecommerce/03.jpg"
                    )
                )
            );
        }

        return json_encode($products);
    }

    public function getImagesByProduct($id = null)
    {
        $imagesModel = new ImagesModel();
        $imagesByProduct = $imagesModel->getImagesByProduct($id);
        $data = array(
            "imagesByProduct" => $imagesByProduct
        );
        return json_encode($data);
    }

    public function updateImage()
    {
        $informationProduct = json_decode($this->request->getPost('infoUpdate'), true);
        $id = $this->request->getPost('id');


        $imagesModel = new ImagesModel();

        if (count($informationProduct) > 0) {
            $informationProduct['updateDate'] = date('Y-m-d H:m:s');
        }

        if (empty($informationProduct)) {
            return $this->failValidationErrors('Nothing to update');
        }

        if (!$imagesModel->find($id)) {
            return $this->failNotFound();
        }
        $infoImg = $imagesModel->find($id);



        if ($idUpt = !$imagesModel->update($id, $informationProduct)) {
            return $this->failValidationErrors($imagesModel->errors());
        }


        if (count($infoImg) > 0) {
            if ($infoImg['priority'] != $informationProduct['priority']) {

                $dataImgOrdered = $imagesModel->getImgOrderedByDate($infoImg['idProduct'], $id);
                $contPriority = 0;
                foreach ($dataImgOrdered as $key => $value) {
                    $contPriority++;
                    if ($informationProduct['priority'] == $contPriority) {
                        $contPriority++;
                    }
                    $arrayUpdateImg = array(
                        "priority" => $contPriority
                    );
                    $v = $imagesModel->update($value['id'], $arrayUpdateImg);
                }
            }
        }

        $files = $this->request->getFiles();
        if (!empty($infoImg['publicIdCloudinary'])) {
            $responseDelete = (new UploadApi())->destroy($infoImg['publicIdCloudinary']);
        }
        // $responseDelete validate if the result is ok
        $Fecha = date("YmdHis");
        foreach ($files as $img) {
            if ($img->isValid() && !$img->hasMoved()) {
                $realName = $img->getName();
                $nameCloudinary = explode('.', $realName)[0] . $Fecha;
                $nameCloudinary = str_replace(" ", "", $nameCloudinary);
                $pathName = $img->getRealPath();
                $uploaded = (new UploadApi())->upload($pathName, [
                    'folder' => 'zowis/',
                    'public_id' => $nameCloudinary,
                ]);
                $arrayImagesUpt = array(
                    "link" => $uploaded['url'],
                    "whodidit" => $informationProduct['whodidit'],
                    "updateDate" => date('Y-m-d H:i:s'),
                    "publicIdCloudinary" => 'zowis/' . $nameCloudinary
                );

                if ($idUpt = !$imagesModel->update($id, $arrayImagesUpt)) {
                    return $this->failValidationErrors($imagesModel->errors());
                }
            }
        }

        return $this->respondUpdated([
            'message' => 'Updated successfully',
            'data' => $imagesModel->find($idUpt),
            'id' => $idUpt
        ]);
    }

    public function createImagesByProduct()
    {
        $idProduct = $this->request->getPost('id');
        $whoCreated = $this->request->getPost('whodidit');

        $productModel = new ProducsModel();
        $imagesModel = new ImagesModel();

        if (!$productModel->find($idProduct)) {
            return $this->failNotFound();
        }

        $infoImg = $imagesModel->getImagesByProduct($idProduct);


        $contImg = count($infoImg);
       
        $files = $this->request->getFiles();
       
        // $responseDelete validate if the result is ok
        $Fecha = date("YmdHis");
        foreach ($files as $img) {
            $contImg++;
            if ($img->isValid() && !$img->hasMoved()) {
                $realName = $img->getName();
                $nameCloudinary = explode('.', $realName)[0] . $Fecha.$contImg;
                $nameCloudinary = str_replace(" ", "", $nameCloudinary);
                $pathName = $img->getRealPath();
                $uploaded = (new UploadApi())->upload($pathName, [
                    'folder' => 'zowis/',
                    'public_id' => $nameCloudinary,
                ]);
                $arrayImages = array(
                    "link" => $uploaded['url'],
                    "whoCreated" => $whoCreated,
                    "idProduct" => $idProduct,
                    "createDate" => date('Y-m-d H:i:s'),
                    "priority"=>$contImg,
                    "publicIdCloudinary" => 'zowis/' . $nameCloudinary
                );

                echo $nameCloudinary;

                if (!$saveImage = $imagesModel->insert($arrayImages)) {
                    return $this->failValidationErrors($imagesModel->errors());
                }
            }
        }

        return $this->respondCreated(['message' => 'Create Successfully', 'data' => $imagesModel->getImagesByProduct($idProduct)]);

    }

    public function test()
    {

        $idPublic = "zowis/WhatsAppImage2022-06-25at1120220630205044";
        // $nameCloudinary = str_replace(" ", "", $idPublic); 
        $responseDelete = (new UploadApi())->destroy($idPublic);
        // echo $responseDelete;
        // echo $nameCloudinary;
        // echo "jere";

    }
}
