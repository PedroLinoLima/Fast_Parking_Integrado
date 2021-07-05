<?php

use App\Core\Model;

class Cliente
{

    public $id;
    public $nome;
    public $placa;
    public $dataEntrada;
    public $dataSaida;
    public $horaEntrada;
    public $horaSaida;
    public $status;
    public $valorPago;
    public $idPreco;

    public function listarTodos()
    {

        $sql = " SELECT * FROM tblClientes ";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $resultado = $stmt->fetchAll(\PDO::FETCH_OBJ);
            return $resultado;
        } else {
            return [];
        }
    }

    public function buscarPorId($id)
    {

        $sql = " SELECT * FROM tblClientes WHERE idCliente = ? ";
        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindValue(1, $id);

        if ($stmt->execute()) {

            $cliente = $stmt->fetch(PDO::FETCH_OBJ);
            if (!$cliente) {
                return false;
            }

            $this->id = $cliente->idCliente;
            $this->nome = $cliente->nome;
            $this->placa = $cliente->placa;
            $this->dataEntrada = $cliente->dataEntrada;
            $this->dataSaida = $cliente->dataSaida;
            $this->horaEntrada = $cliente->horaEntrada;
            $this->horaSaida = $cliente->horaSaida;
            $this->status = $cliente->status;
            $this->valorPago = $cliente->valorPago;
            return $this;
        } else {
            return false;
        }
    }

    public function inserir()
    {

        $sql = " INSERT INTO tblClientes 
                (nome, placa, dataEntrada, horaEntrada, status) 
                VALUES (?, ucase(?), current_date(), curtime(), ? ) ";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindValue(1, $this->nome);
        $stmt->bindValue(2, $this->placa);
        $stmt->bindValue(3, 0);

        if ($stmt->execute()) {
            $this->id = Model::getConn()->lastInsertId();
            return $this;
        } else {
            return false;
        }
    }

    public function deletar()
    {
        $sql = " DELETE FROM tblClientes WHERE idCliente = ? ";
        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindValue(1, $this->id);
        return $stmt->execute();
    }

    public function atualizar()
    {
        $sql = " UPDATE tblClientes SET 
            nome = ?, placa = ? WHERE idCliente = ? ";
        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindValue(1, $this->nome);
        $stmt->bindValue(2, $this->placa);
        $stmt->bindValue(3, $this->id);

        return $stmt->execute();
    }
}
