<?php
// sgal/core/Config.php

require_once __DIR__ . '/../config/db.php';

class Config {
    private $conn;
    private $table = 'configuracion_global';
    
    // Un caché simple para no consultar la BBDD mil veces por la misma setting
    private $settingsCache = [];

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    /**
     * Obtiene TODOS los settings de la base de datos.
     * @return array
     */
    public function getAllSettings() {
        $query = "SELECT * FROM " . $this->table;
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $settings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Guardar en caché
            foreach ($settings as $setting) {
                $this->settingsCache[$setting['setting_key']] = $setting['setting_value'];
            }
            return $settings;
        } catch (PDOException $e) {
            error_log('Error en getAllSettings: ' . (string)$e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene el valor de un setting específico.
     * @return string|null El valor del setting o null si no se encuentra.
     */
    public function getSetting($key) {
        // 1. Revisar el caché
        if (isset($this->settingsCache[$key])) {
            return $this->settingsCache[$key];
        }

        // 2. Si no está en caché, buscar en BBDD
        $query = "SELECT setting_value FROM " . $this->table . " WHERE setting_key = :key LIMIT 1";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':key', $key);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                // Guardar en caché
                $this->settingsCache[$key] = $result['setting_value'];
                return $result['setting_value'];
            } else {
                return null; // El setting no existe
            }
        } catch (PDOException $e) {
            error_log('Error en getSetting: ' . (string)$e->getMessage());
            return null;
        }
    }

    /**
     * Actualiza el valor de un setting específico.
     * @return bool
     */
    public function updateSetting($key, $value) {
        $query = "UPDATE " . $this->table . " SET setting_value = :value WHERE setting_key = :key";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':value', $value);
            $stmt->bindParam(':key', $key);
            
            if ($stmt->execute()) {
                // Actualizar el caché
                $this->settingsCache[$key] = $value;
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log('Error en updateSetting: ' . (string)$e->getMessage());
            return false;
        }
    }
}