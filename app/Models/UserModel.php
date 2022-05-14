<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['idEmployee','userName','password', 'temporaryKey','status','whodidit', 'whoCreated','createDate','updateDate', 'email'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'userName'     => 'required|is_unique[users.userName]',
        'email'        => 'required|valid_email|is_unique[users.email]',
        'idEmployee'     => 'required',
        'password'     => 'required',
        'passConfirm' => 'required_with[password]|matches[password]',
    ];

    protected $validationMessages   = [
        'userName'=>[
            'required'=>'El campo nombre de usuario es requerido.',
            'is_unique' => 'Lo sentimos mucho el usuario ya ha sido registrado, por favor ingrese otro usuario.',
        ],
        'email'=>[
            'required'=>'El campo email es requerido.',
            'valid_email'=>'El email ingresado no tiene un formato válido, intente de nuevo.',
            'is_unique' => 'El email ya está siendo utilizado por otro usuario, por favor ingrese otro email.',
        ],
        'idEmployee'=>[
            'required'=>'Debe asignar un empleado al usuario.',
        ],
        'password'=>[
            'required'=>'El campo contraseña es requerido.'
        ],
        'passConfirm'=>[
            'required'=>'Confirme la contraseña por favor.',
            'matches'=>'Las contraseñas no coinciden. Verifique por favor'
        ],

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

    public function credential($userName, $password)
    {
        # code...
        $sql="SELECT * FROM  users WHERE (userName='$userName' OR email='$userName') AND password='$password'";
        $query = $this->db->query($sql);     
        return ($query->getNumRows()>0) ? $query->getResultArray():array();
    }

    // public function getTypesReturnOnSale()
    // {
    //     $this->db->where("status ", 1);
    //     $query=$this->db->get('returnOnSaleType');
    //     return ($query->num_rows() > 0) ? $query->result_array():array();
    // }

}
