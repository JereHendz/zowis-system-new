<?php

namespace App\Models;

use CodeIgniter\Model;

class SubCategoriesModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'sub_categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['idCategory','name', 'description','status','whodidit', 'whoCreated','createDate','updateDate'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'idCategory' => 'required',
        'name' => 'required'
    ];
    protected $validationMessages   = [
        'idCategory' => 'Debe seleccionar una categoría',
        'name' => 'El campo nombre es requerido'
    ];
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
}
