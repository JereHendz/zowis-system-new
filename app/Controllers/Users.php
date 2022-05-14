<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;

date_default_timezone_set('America/El_Salvador');

class Users extends ResourceController
{
    protected $format    = 'json';


    public function index(){
        // $email =$this->input->get("email");
        // $password =$this->input->raw_input_stream;
         // Instance of user model 
         $userModel = new UserModel();

        return $this->respond($userModel->findAll());
    }

    public function create(){

        // Instance of user model 
        $userModel = new UserModel();

        // Get the form of login 
        $form =$this->request->getJSON(true);
        
        // Create user array, set up default information
        $data = [
            'userName' => $form['userName'],
            'email'    => $form['email'],
            'password'    => $form['password'],
            'passConfirm'  => $form['passConfirm'],
            'createDate' =>date('Y-m-d H:m:s'),
            'whoCreated'=>1,
            'whodidit'=>1,
            'temporaryKey'=>1,
            'idEmployee'=>$form['idEmployee'],
        ];          
        
        // Validating  information and save record
        if(!$id = $userModel->insert($data)){

            return $this->failValidationErrors($userModel->errors());
        }
        
        // Get the user that has been saved
         $userCreated = $userModel->find($id);

        //  Response using response trait of codeigniter 4
         return $this->respondCreated(['message'=>'Usuario creado correctamente','data'=>$data]);
  
    }

    public function update($id = null){

        $form=$this->request->getJSON(true);
        if(empty($form)){
            return $this->failValidationErrors('Nothing to update');
        }

        if(!$this->model->find($id)){
            return $this->failNotFound();
        }

        if(!$this->model->update($id, $form)){
            return $this->failValidationErrors($this->model->errors());
        }

        return $this->respondUpdated([
            'message'=>'Updated successfully',
            'data'=>$this->model->find($id),
        ]);
    }
    
    public function delete($id=null){
        if (!$this->model->find($id)) {
            return $this->failNotFound();
        }

        $this->model->where('id',$id)->delete();

        return $this->respondDeleted([
            'message'=>"Registro {$id} fue eliminado"
        ]);
    }

    public function login(){
        $userModel = new UserModel();
        
        // Get the credential user and password
        $form =$this->request->getJSON(true);
        
        // Validating  the credential
        $data=$userModel->credential($form['email'], $form['password']);

        // If there is a user
        if (count($data)<=0) {
            // We did not found the user
             return $this->failValidationErrors("User not found");
         } 

         return $this->respondCreated(['message'=>'Logeado correctamente','response'=>true]);
    }

}
