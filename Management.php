<?php
// sgal/core/Management.php

require_once __DIR__ . '/../config/db.php';

class Management {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // -----------------------------------------------------------------
    // MÉTODOS CRUD PARA VEHÍCULOS
    // -----------------------------------------------------------------

    public function createVehiculo($placa, $marca, $modelo, $anio) {
        $query = "INSERT INTO vehiculos (placa, marca, modelo, anio) VALUES (:placa, :marca, :modelo, :anio)";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':placa', $placa);
            $stmt->bindParam(':marca', $marca);
            $stmt->bindParam(':modelo', $modelo);
            $stmt->bindParam(':anio', $anio, $anio ? PDO::PARAM_INT : PDO::PARAM_NULL);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en createVehiculo: ' . (string)$e->getMessage());
            return false;
        }
    }

    public function getVehiculoById($id) {
        $query = "SELECT * FROM vehiculos WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getVehiculoById: ' . (string)$e->getMessage());
            return false;
        }
    }

    public function updateVehiculo($id, $placa, $marca, $modelo, $anio) {
        $query = "UPDATE vehiculos SET placa = :placa, marca = :marca, modelo = :modelo, anio = :anio WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':placa', $placa);
            $stmt->bindParam(':marca', $marca);
            $stmt->bindParam(':modelo', $modelo);
            $stmt->bindParam(':anio', $anio, $anio ? PDO::PARAM_INT : PDO::PARAM_NULL);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en updateVehiculo: ' . (string)$e->getMessage());
            return false;
        }
    }

    public function deleteVehiculo($id) {
        $query = "DELETE FROM vehiculos WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en deleteVehiculo: ' . (string)$e->getMessage());
            return false;
        }
    }

    // -----------------------------------------------------------------
    // MÉTODOS CRUD PARA CLIENTES
    // -----------------------------------------------------------------

    public function createCliente($nombre, $direccion, $telefono) {
        $query = "INSERT INTO clientes (nombre_cliente, direccion_principal, telefono) VALUES (:nombre, :direccion, :telefono)";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':direccion', $direccion);
            $stmt->bindParam(':telefono', $telefono);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en createCliente: ' . (string)$e->getMessage());
            return false;
        }
    }

    public function getClienteById($id) {
        $query = "SELECT * FROM clientes WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getClienteById: ' . (string)$e->getMessage());
            return false;
        }
    }

    public function updateCliente($id, $nombre, $direccion, $telefono) {
        $query = "UPDATE clientes SET nombre_cliente = :nombre, direccion_principal = :direccion, telefono = :telefono WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':direccion', $direccion);
            $stmt->bindParam(':telefono', $telefono);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en updateCliente: ' . (string)$e->getMessage());
            return false;
        }
    }

    public function deleteCliente($id) {
        $query = "DELETE FROM clientes WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en deleteCliente: ' . (string)$e->getMessage());
            return false;
        }
    }
    
    // -----------------------------------------------------------------
    // MÉTODOS CRUD PARA TIPOS DE GASTO
    // -----------------------------------------------------------------

    public function createTipoGasto($nombre) {
        $query = "INSERT INTO tipos_gasto (nombre) VALUES (:nombre)";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en createTipoGasto: ' . (string)$e->getMessage());
            return false;
        }
    }
    
    public function getTipoGastoById($id) {
        $query = "SELECT * FROM tipos_gasto WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getTipoGastoById: ' . (string)$e->getMessage());
            return false;
        }
    }
    
    public function updateTipoGasto($id, $nombre) {
        $query = "UPDATE tipos_gasto SET nombre = :nombre WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $nombre);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en updateTipoGasto: ' . (string)$e->getMessage());
            return false;
        }
    }

    public function deleteTipoGasto($id) {
        $query = "DELETE FROM tipos_gasto WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en deleteTipoGasto: ' . (string)$e->getMessage());
            return false;
        }
    }
}