<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use App\Models\EmployeeModel;
use App\Models\ProcessStateModel;

date_default_timezone_set('America/El_Salvador');

class Users extends ResourceController
{
    protected $format = 'json';

    public function index(){
        //Get all users
        $userModel = new UserModel();
        $employeeModel = new EmployeeModel();
        $processState = new ProcessStateModel();

        $data = array(
            'users' => $userModel->findAll(),
            'employees' => $employeeModel->findAll(),
            'status' => $processState->getStateByType(1)
        );

        return $this->respond($data);
    }

    public function show($id = null){
        //Get all users
        $userModel = new UserModel();

        $data = array(
            'user' => $userModel->find($id),
        );

        return $this->respond($data);
    }

    public function create(){
        $encrypter = \Config\Services::encrypter();
        // Instance of user model 
        $userModel = new UserModel();

        // Get the form of login 
        $form = $this->request->getJSON(true);

        //encrypt password
        $password=base64_encode($encrypter->encrypt($form['password']));

        // para desencriptar usar lo siguiente
        // $pass1=base64_decode($password);
        // $passDecrypted = $encrypter->decrypt($pass1);

        // Create user array, set up default information
        $data = [
            'userName' => $form['userName'],
            'email' => $form['email'],
            'password' => $password,
            'idEmployee' => $form['idEmployee'],
            'temporaryKey' => 1,
            'whoCreated' => $form['whoCreated'],
            'createDate' => date('Y-m-d H:m:s'),
        ];  
                
        // Validating  information and save record
        if(!$id = $userModel->insert($data)){
            return $this->failValidationErrors($userModel->errors());
        }
        
        // Get the user that has been saved
        // $userCreated = $userModel->find($id);

        // Response using response trait of codeigniter 4
        return $this->respondCreated(['message'=>'Usuario creado correctamente','data'=>$data]);
    }

    public function update($id = null){
        $form=$this->request->getJSON(true);
        $userModel = new UserModel();
        
        if(empty($form)){
            return $this->failValidationErrors('Nothing to update');
        }

        if(!$userModel->find($id)){
            return $this->failNotFound();
        }

        if(!$userModel->update($id, $form)){
            return $this->failValidationErrors($userModel->errors());
        }

        return $this->respondUpdated([
            'message'=>'Updated successfully',
            'data'=>$userModel->find($id),
        ]);
    }
    
    
    // public function delete($id=null){
    //     if (!$this->model->find($id)) {
    //         return $this->failNotFound();
    //     }

    //     $this->model->where('id',$id)->delete();

    //     return $this->respondDeleted([
    //         'message'=>"Registro {$id} fue eliminado"
    //     ]);
    // }

    public function login(){
        $encrypter = \Config\Services::encrypter();
        $userModel = new UserModel();
        
        // Get the credential user and password
        $form =$this->request->getJSON(true);

        // Validating  the credential
        $password = $form['password'];
        $res = $userModel->credential($form['email']);
        // If there is a user
        if (count($res) <= 0) {
            // We did not found the user test
             return $this->failValidationErrors("User not found");
        }

        $passBd = $res[0]['password'];
        $pass1=base64_decode($passBd);
        $passDecrypted = $encrypter->decrypt($pass1);
        if($password == $passDecrypted){
            $data = $res;
        }else{
            return $this->failValidationErrors("Invalid pass");
        }

         return $this->respondCreated(['message'=>'Logeado correctamente','response'=>$data]);
    }

}
