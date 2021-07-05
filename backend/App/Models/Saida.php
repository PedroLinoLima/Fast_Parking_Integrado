<?php

use App\Core\Model;


class Saida
{
    public $id;
    public $valorPago;

    public function getDadosValorApagar($id)
    {
        $sql = " SELECT datediff(dataEntrada, dataSaida) as totalDiasEstacionado, timediff(horaSaida, horaEntrada) as totalHorasEstacionado from tblClientes WHERE idCliente = ? ";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $resultado = $stmt->fetchAll(\PDO::FETCH_OBJ);
            return $resultado;
        } else {
            return "Cliente não encontrado";
        }
    }

    public function saidaCliente()
    {
        $sql = " UPDATE tblClientes SET dataSaida = current_date(), horaSaida = curtime(), status = 1, valorPago = ? WHERE idCliente = ? ";
        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindValue(1, $this->valorPago);
        $stmt->bindValue(2, $this->id);
        return $stmt->execute();
    }
}
