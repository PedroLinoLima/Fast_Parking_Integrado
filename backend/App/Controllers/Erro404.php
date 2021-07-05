<?php

use App\Core\Controller;

class Erro404 extends Controller{

    public function index(){
        $this->view("erros/404");
    }
}