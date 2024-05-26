<?php

class Controlador
{
    private $lista;

    public function __construct()
    {
        $this->lista = [];
    }

    public function getAll()
    {
        $con = new Conexion();
        $connection = $con->getConnection();
        $sql = "SELECT id, nombre, activo FROM `info-contacto`;";
        $stmt = mysqli_prepare($connection, $sql);

        if ($stmt) {
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            while ($tupla = mysqli_fetch_assoc($result)) {
                $tupla['activo'] = $tupla['activo'] == 1 ? true : false;
                array_push($this->lista, $tupla);
            }
            mysqli_stmt_free_result($stmt);
        }

        $con->closeConnection();
        return $this->lista;
    }

    public function postNuevo($_newObject)
    {
        $con = new Conexion();
        $connection = $con->getConnection();
        $id = count($this->getAll()) + 1;
        $sql = "INSERT INTO `info-contacto` (id, nombre, activo) VALUES (?, ?, true);";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, 'is', $id, $_newObject->nombre);
        $rs = mysqli_stmt_execute($stmt);

        $con->closeConnection();
        return $rs;
    }

    public function patchEncenderApagar($_id, $_accion)
    {
        $con = new Conexion();
        $connection = $con->getConnection();
        $sql = "UPDATE `info-contacto` SET activo = ? WHERE id = ?";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $_accion, $_id);
        $rs = mysqli_stmt_execute($stmt);

        $con->closeConnection();
        return $rs;
    }

    public function putNombreById($_nombre, $_id)
    {
        $con = new Conexion();
        $connection = $con->getConnection();
        $sql = "UPDATE `info-contacto` SET nombre = ? WHERE id = ?";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, 'si', $_nombre, $_id);
        $rs = mysqli_stmt_execute($stmt);

        $con->closeConnection();
        return $rs;
    }

    public function deleteById($_id)
    {
        $con = new Conexion();
        $connection = $con->getConnection();
        $sql = "DELETE FROM `info-contacto` WHERE id = ?";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $_id);
        $rs = mysqli_stmt_execute($stmt);

        $con->closeConnection();
        return $rs;
    }
}
