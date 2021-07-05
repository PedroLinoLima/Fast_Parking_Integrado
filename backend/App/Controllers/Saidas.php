<?php
session_start();

use App\Core\Controller;

class Saidas extends Controller {

    public function calculaPreco($id) {

        $clienteSaida = $this->model("Saida");
        $dados = $clienteSaida->getDadosValorApagar($id);

        $precoModel = $this->model("Preco");
        $dadosPreco = $precoModel->listarTodos();

        $totalDias = $dados[0]->totalDiasEstacionado;
        $totalHoras = $dados[0]->totalHorasEstacionado;

        $precoUmaHora = $dadosPreco[0]->umaHora;
        $precoDemaisHoras = $dadosPreco[0]->demaisHoras;


        var_dump($totalHoras);
        if ($totalDias == 0) {
            if (strtotime($totalHoras)  <= strtotime("1:00:00")) {
                return $precoUmaHora;
            } else {
                $valorPagar = $precoDemaisHoras * (idate('H' , strtotime($totalHoras)) - 1) + $precoUmaHora;
                return $valorPagar;
            }
        }
    }

    public function find($id) {
        $this->calculaPreco($id);
    }

    public function update($id) {
        $json = file_get_contents("php://input");

        $clienteSaida = json_decode($json);

        $clienteSaida = $this->model("Saida");

        if (!$clienteSaida) {
            http_response_code(404);
            echo json_encode(["erro" => "Cliente não encontrado"]);
            exit;
        }
              
        $clienteSaida->id = $id;
        $clienteSaida->valorPago = $this->calculaPreco($id);

        if ($clienteSaida->saidaCliente()) {
            http_response_code(204);

        } else {
            http_response_code(500);
            echo json_encode(["erro" => "Problemas ao fazer a saída do cliete"]);
        }
    }

    

}
