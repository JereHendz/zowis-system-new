<?php

namespace App\Models;

use CodeIgniter\Model;

class ImagesModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'images_products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = 
    [
        'id',
        'link',
        'status',
        'description',
        'whoCreated',
        'whodidit',
        'createDate',
        'updateDate',
        'idProduct',
        'priority',
        'visibleCustomer',
        'publicIdCloudinary'
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

    public function getImagesByProduct($idProduct)
    {
        $this->select('images_products.*, products.productName',false);
        $this->table("images_products");
        $this->join("products","images_products.idProduct=products.id");
        $this->where("products.id =", $idProduct);
        $this->orderBy("images_products.priority", "asc");

        $query = $this->get();
        return ($query->getNumRows() > 0) ? $query->getResultArray() : array();
    }

    public function getImgOrderedByDate($idProduct, $id)
    {
        $this->select('*');
        $this->table("images_products");
        $this->where("id <>", $id);
        $this->where("idProduct =", $idProduct);
        $this->orderBy("priority", "asc");
        $this->orderBy("updateDate", "desc");
        $query = $this->get();
        return ($query->getNumRows() > 0) ? $query->getResultArray() : array();
    }
}
