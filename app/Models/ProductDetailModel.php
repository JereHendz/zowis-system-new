<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductDetailModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'product_details';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'descriptionDetail',
        'unitPurchasePrice',
        'quantity',
        'whodidit',
        'whoCreated',
        'createDate',
        'updateDate',
        'idProduct',
        'idBrand',
        'idBranchOffice',
        'idWineries',
        'idFirstLevelLocation',
        'idSecondLevelLocation',
        'idThirdLevelLocation',
        'idProvider',
        'stock',
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

    public function getProductDetailByIdProduct($idProduct)
    {
        $sql = "SELECT *
                FROM product_details
                WHERE idProduct = '$idProduct' AND stock > 0
                ORDER BY id LIMIT 1";
        $query = $this->db->query($sql);     
        return ($query->getNumRows()>0) ? $query->getResultArray():array();
    }
}
