<?php

namespace App\Core;

class Router
{

    private $controller;

    private $method;

    private $controllerMethod;

    private $params = [];

    function __construct() {

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header("Access-Control-Allow-Headers: Content-Type");

        $url = $this->parseURL();

        header("Content-type: application/json");

        if (file_exists("../App/Controllers/" . $url[1] . ".php")) {

            $this->controller = $url[1];
            unset($url[1]);
        } elseif (empty($url[1])) {
            $this->controller = "clientes";
        } else {
            $this->controller = "erro404";
        }

        require_once "../App/Controllers/" . $this->controller . ".php";

        $this->controller = new $this->controller;

        $this->method = $_SERVER["REQUEST_METHOD"];

        switch ($this->method) {
            case "GET":
               
                if (isset($url[2])) {
                    $this->controllerMethod = "find";
                    $this->params = [$url[2]];
                } else {
                    $this->controllerMethod = "index";
                }
                break;

            case "POST":
                $this->controllerMethod = "store";
                break;

            case "PUT":
                $this->controllerMethod = "update";
                if (isset($url[2]) && is_numeric($url[2])) {
                    $this->params = [$url[2]];
                } else {
                    http_response_code(400);
                    echo json_encode(["erro" => "É necessário informar um id"]);
                    exit;
                }
                break;

            case "DELETE":
                $this->controllerMethod = "delete";
                if (isset($url[2]) && is_numeric($url[2])) {
                    $this->params = [$url[2]];
                } else {
                    http_response_code(400);
                    echo json_encode(["erro" => "É necessário informar um id"]);
                    exit;
                }
                break;

            default:
                echo "Método não suportado";
                exit;
                break;
        }

        call_user_func_array([$this->controller, $this->controllerMethod], $this->params);
    }

    private function parseURL()
    {
        return explode("/", $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
    }
}
