<?php
class Bd 
{
	private $_conexion = null;
    private $_ultimoId;
    private $_filasAfectadas;

    public final function conectar() {
        try {
            $this->_conexion = new PDO(
            	Configuracion::CADENA_CONEXION, 
            	Configuracion::USUARIO_BD, 
            	Configuracion::CLAVE_BD, 
            	Configuracion::PARAMETROS_EXTRAS
            	);
            return TRUE;
        } catch (Exception $e) {
            return FALSE;
        }
    }
    
    public final function buscar(string $sql, array $parametros = null) {
        $this->conectar();

        $sth = $this->_conexion->prepare($sql);
        if (is_array($parametros)) {
            $sth->execute($parametros);
        } else {
            $sth->execute();
        }

        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        $sth->closeCursor();
        $this->cerrarConexion();

        return $result;
    }

    public final function buscarPrimero(string $sql, array $parametros = null) {
        $this->conectar();
        $sth = $this->_conexion->prepare($sql);
        if (is_array($parametros)) {
            $sth->execute($parametros);
        } else {
            $sth->execute();
        }
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        $sth->closeCursor();
        $this->cerrarConexion();

        return $result;
    }

    public final function obtenerValor(string $sql)
    {
        $this->conectar();
        $sth = $this->_conexion->prepare($sql);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_NUM);
        $sth->closeCursor();
        $this->cerrarConexion();

        return !empty($result) ? $result[0] : null;
    }    


    public final function ejecutar(string $sql, array $parametros = null) {
        $this->conectar();
        $sth = $this->_conexion->prepare($sql);
        
        if (is_array($parametros)) {
            $sth->execute($parametros);
        } else {
            $sth->execute();
        }

        $this->_ultimoId = null;
        $this->_filasAfectadas = null;
        
        $this->_filasAfectadas = $sth->rowCount();
        $result = intval($this->_filasAfectadas) > 0;
        
        if (strpos(strtolower($sql), 'insert into') !== false) {
            $this->_ultimoId = $this->_conexion->lastInsertId();
            
            if ($this->_ultimoId > 0) {
            	$result = $this->_ultimoId;
            }
            
        }
        
        Informacion::escribirLog($sql);
        Informacion::escribirLog(count($parametros));
        
        $this->cerrarConexion();
        
        return $result ?? null;
    }

    public final function obtenerUltimoId()
    {
        return $this->_ultimoId;
    }

    public final function cerrarConexion() {
        $this->_conexion = null;
    }

    public final function iniciarTransaccion() {
        $this->_conexion->beginTransaction();
    }

    public final function aceptarTransaccion() {
        $this->_conexion->commit();
    }

    public final function reversarTransaccion() {
        $this->_conexion->rollback();
    }
}
