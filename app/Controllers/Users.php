<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;

class Users extends ResourceController
{
    public  $userModel;


    protected $format    = 'json';


    public function index(){
        // $email =$this->input->get("email");
        // $password =$this->input->raw_input_stream;

        // 
        // echo "jere";
        // echo $password;
        // return $form;
        return $this->respond($this->model->findAll());
    }

    public function create(){
        $form =$this->request->getJSON(true);

        if (!$id=$this->model->insert($form)) {
           // Data did not validate
            return $this->failValidationErrors($this->model->errors());
        }

        $user=$this->model->find($id);
        return $this->respondCreated(['message'=>'Registro creado correctamente','data'=>$user]);
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
        
        $form =$this->request->getJSON(true);
        $email=$form['email'];
        $password=$form['password'];
        $data=$userModel->credential($email, $password);
        if (count($data)<=0) {
            // Data did not validate
             return $this->failValidationErrors("User not found");
         } 
         return $this->respondCreated(['message'=>'Logeado correctamente','response'=>true]);
    }

}
