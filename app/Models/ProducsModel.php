<?php

namespace App\Models;

use CodeIgniter\Model;

class ProducsModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    =
    [
        'id',
        'productCode',
        'productName',
        'description',
        'stockProduct',
        'stockLimit',
        'percentageProfit',
        'status',
        'whodidit',
        'whoCreated',
        'createDate',
        'updateDate',
        'idSubCategory',
        'productDiscount'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getProductCode()
    {
        $this->select('count(*) productCode');
        $this->table("products");
        $query = $this->get();
        return ($query->getNumRows() > 0) ? $query->getResultArray() : array();
    }

    public function getProductWithImage()
    {
        $this->select('products.id, products.productName, products.description, images_products.link,
         product_details.barcode, product_details.unitSalePrice, products.productDiscount',false);
        $this->table("products");
        $this->join("product_details","product_details.idProduct=products.id");
        $this->join("images_products","images_products.idProduct=products.id");
        $this->groupBy("products.id");
        $query = $this->get();
        return ($query->getNumRows() > 0) ? $query->getResultArray() : array();
    }

    // public function saveDetailProduct($data)
    // {
    //     $this->table('product_details');
    //     $this->save('product_details',$data);
    //     return ($this->affected_rows() != 1) ? false : true;
    // }
}
