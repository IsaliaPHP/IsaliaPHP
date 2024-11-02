<?php

/**
 * Clase AttachmentManager
 * @author nelson rojas
 * @abstract
 * Clase que permite gestionar los archivos adjuntos
 */
class AttachmentManager {
    public $uploadDir;
    public $maxFileSize;
    public $allowedTypes;
    
    /**
     * Constructor de la clase AttachmentManager
     */
    public function __construct() {
        $this->uploadDir = Config::UPLOAD_DIR;
        $this->maxFileSize = Config::MAX_UPLOAD_FILE_SIZE; // 2MB by default
        $this->allowedTypes = Config::ALLOWED_FILE_TYPES;

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    /**
     * Método para subir un archivo
     * @param array $file Archivo a subir
     * @return string Ruta del archivo subido
     * @throws Exception Si ocurre un error durante la subida del archivo
     */
    public function upload($file) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Error durante la subida del archivo.');
        }

        if (!in_array($file['type'], $this->allowedTypes)) {
            throw new Exception('Tipo de archivo no permitido.');
        }

        if ($file['size'] > $this->maxFileSize) {
            throw new Exception('El tamaño del archivo excede el límite.');
        }

        $filename = $this->generateFilename($file['name']);
        $filePath = $this->uploadDir . '/' . $filename;

        if (!file_exists($file['tmp_name'])) {
            throw new Exception('El archivo temporal no existe. ' . $file['tmp_name']);
        }

        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return $filename;
        } else {
            throw new Exception('Error al mover el archivo subido. ' . $filePath . ' a ' . $this->uploadDir . ' desde ' . $file['tmp_name']);
        }
    }

    /**
     * Método para generar un nombre de archivo único
     * @param string $originalName Nombre original del archivo
     * @return string Nombre de archivo único
     */
    protected function generateFilename($originalName) {
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        if (is_array($ext)) {
            $ext = ''; // Valor predeterminado como cadena vacía si $ext es inesperadamente un array
        }
        return uniqid() . '.' . $ext;
    }
}
