<?php

/**
 * 
 * @author nelson rojas
 * class Model
 * @abstract
 * Clase base para la gestion de modelos ORM
 * @property int id
 */
class Model
{
    protected $_model_name;
    protected $_table_name;
    protected $_attributes = [];
    protected $_query_builder;

    /**
     * Constructor de la clase Model.
     * @param array $data Un array asociativo con los datos iniciales del modelo.
     */
    public function __construct(array $data = [])
    {
        $this->_model_name = get_class($this);
        
        $this->_table_name = strtolower(
            preg_replace(
                '/(?<!^)[A-Z]/',
                '_$0',
                $this->_model_name
            )
        );

        $this->_query_builder = new SqlBuilder($this->_table_name);

        if ((int) method_exists($this, "initialize")) {
            call_user_func(array($this, "initialize"));
        }

        $this->load($data);
    }

    /**
     * Método para establecer el nombre de la tabla manualmente.
     * @param string $table_name El nombre de la tabla.
     */
    public function setTableName(string $table_name)
    {
        $this->_table_name = $table_name;
        $this->_query_builder->setTableName($table_name);
    }

    /**
     * Método para obtener el nombre de la tabla.
     * @return string El nombre de la tabla.
     */
    public function getTableName()
    {
        return $this->_table_name;
    }

    /**
     * Método para encontrar un registro por su ID.
     * @param int $id El ID del registro.
     * @return Model|null El modelo hidratado o null si no hay resultados.
     */
    public function findById(int $id)
    {
        $result = $this->_query_builder
            ->reset()
            ->where("id = :id")
            ->limit(1);
        
        return $this->hydrate(Db::findFirst($result->toSql(), [':id' => $id]));
    }

    /**
     * Método para encontrar todos los registros que coincidan con la condición.
     * @param string $condition La condición de búsqueda.
     * @param array $parameters Los parámetros de la condición.
     * @return array Un array de modelos hidratados.
     */
    public function findAll(string $condition = '', array $parameters = null)
    {
        $query = $this->_query_builder->reset();
        
        if (!empty($condition)) {
            $query->where($condition);
        }
        
        $results = Db::findAll($query->toSql(), $parameters) ?? [];
        return $this->hydrateAll($results);
    }

    /**
     * Método para encontrar el primer registro que coincida con la condición.
     * @param string $condition La condición de búsqueda.
     * @param array $parameters Los parámetros de la condición.
     * @return Model|null El modelo hidratado o null si no hay resultados.
     */
    public function findFirst(string $condition = '', array $parameters = null)
    {
        $query = $this->_query_builder
            ->reset()
            ->limit(1);
        
        if (!empty($condition)) {
            $query->where($condition);
        }
        
        $result = Db::findFirst($query->toSql(), $parameters);
        return $this->hydrate($result);
    }

    /**
     * Método para encontrar un registro por una consulta SQL.
     * @param string $sql La consulta SQL.
     * @param array $parameters Los parámetros de la consulta.
     * @return array Un array de modelos hidratados.
     */
    public function findBySQL(string $sql, array $parameters = null)
    {
        $results = Db::findAll($sql, $parameters);
        return $this->hydrateAll($results);
    }

    /**
     * Método para crear un registro.
     * @return bool True si la operación se realizó correctamente, false en caso contrario.
     */
    public function create()
    {
        $this->beforeCreate();
        $result = Db::insert($this->_table_name, $this->_attributes);
        $this->afterCreate();
        return !empty($result);
    }

    /**
     * Método para actualizar un registro.
     * @param array $attributes Los atributos a actualizar.
     * @param string $condition La condición de actualización.
     * @return bool True si la operación se realizó correctamente, false en caso contrario.
     */
    public function update(array $attributes = null, string $condition = '')
    {
        if (empty($condition)) {
            $condition = "id = " . $this->id;
        } 
        $condition = " WHERE " . $condition;

        // Si $attributes es null, usamos todos los atributos del modelo
        if (isset($attributes) && count($attributes) > 0) {
            $this->load($attributes);
        } else {
            return false;
        }
        
        $this->beforeUpdate();
        $result = Db::update($this->_table_name, $this->_attributes, $condition);
        $this->afterUpdate();
        return ($result > 0);
    }

    /**
     * Método para eliminar todos los registros que coincidan con la condición.
     * @param string $condition La condición de eliminación.
     * @param array $parameters Los parámetros de la condición.
     * @return bool True si la operación se realizó correctamente, false en caso contrario.
     */
    public function deleteAll(string $condition, array $parameters = null)
    {
        $condition = " WHERE " . $condition;
        $this->beforeDelete();
        $result = Db::delete($this->_table_name, $condition, $parameters);
        $this->afterDelete();
        return ($result > 0);
    }

    /**
     * Método para eliminar un registro.
     * @return bool True si la operación se realizó correctamente, false en caso contrario.
     */
    public function delete()
    {
        if (intval($this->id) > 0) {
            $condition = " WHERE id = " . intval($this->id);
            $this->beforeDelete();
            $result = Db::delete($this->_table_name, $condition);
            $this->afterDelete();
            return ($result > 0);
        } else {
            return false;
        }
    }

    /**
     * Método para cargar atributos en el modelo.
     * @param array $attributes Los atributos a cargar.
     */
    public function load(array $attributes)
    {
        foreach ($attributes as $attribute => $value) {
            $this->$attribute = $value;
        }
    }

    /**
     * Método para guardar un registro.
     * @return bool True si la operación se realizó correctamente, false en caso contrario.
     */
    public function save()
    {
        $result = false;
        if (intval($this->id) > 0) {
            $this->beforeUpdate();
            $result = $this->update() > 0;
            $this->afterUpdate();
        } else {
            $this->beforeCreate();
            $last_id = intval($this->create());
            $this->afterCreate();
            $this->id = $last_id;
            $result = $last_id > 0;
        }
        return $result;
    }
    
    /**
     * Métodos auxiliares
     */

    /**
     * Método para inicializar el modelo.
     */
    public function initialize()
    {
    }

    /**
     * Método para ejecutar antes de crear un registro.
     */
    public function beforeCreate()
    {
    }

    /**
     * Método para ejecutar antes de actualizar un registro.
     */
    public function beforeUpdate()
    {
    }

    /**
     * Método para ejecutar antes de eliminar un registro.
     */
    public function beforeDelete()
    {
    
    }

    /**
     * Método para ejecutar después de crear un registro.
     */
    public function afterCreate()
    {
    
    }

    /**
     * Método para ejecutar después de actualizar un registro.
     */
    public function afterUpdate()
    {
    
    }

    /**
     * Método para ejecutar después de eliminar un registro.
     */
    public function afterDelete()
    {
    
    }


    /**
     * Método para hidratar un conjunto de datos en un modelo.
     * @param array $data El conjunto de datos a hidratar.
     * @return Model|null El modelo hidratado o null si no hay datos.
     */
    protected function hydrate($data)
    {
        if (empty($data)) {
            return null;
        }
        $model = new $this->_model_name();
        $model->load($data);
        return $model;
    }

    /**
     * Método para hidratar un conjunto de datos en un modelo.
     * @param array $dataSet El conjunto de datos a hidratar.
     * @return array Un array de modelos hidratados.
     */
    protected function hydrateAll(array $dataSet)
    {
        $models = [];
        foreach ($dataSet as $data) {
            $models[] = $this->hydrate($data);
        }
        return $models;
    }

    /**
     * Método mágico para establecer un atributo del modelo.
     * @param string $attribute El nombre del atributo.
     * @param mixed $value El valor del atributo.
     */
    public function __set($attribute, $value)
    {
        $this->_attributes[$attribute] = $value;
    }

    /**
     * Método mágico para obtener un atributo del modelo.
     * @param string $attribute El nombre del atributo.
     * @return mixed|null El valor del atributo o null si no existe.
     */
    public function __get($attribute)
    {
        return $this->_attributes[$attribute] ?? null;
    }

    /**
     * Delega mágicamente los métodos no definidos al SqlBuilder
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        if (method_exists($this->_query_builder, $method)) {
            $result = call_user_func_array([$this->_query_builder, $method], $arguments);
            return ($result === $this->_query_builder) ? $this : $result;
        }
        throw new \BadMethodCallException("Method {$method} does not exist");
    }

    /**
     * Ejecuta la consulta y retorna los resultados hidratados
     * @return array
     */
    public function execute()
    {
        $results = Db::findAll(
            $this->_query_builder->toSql(), 
            $this->_query_builder->getParameters()
        );
        return $this->hydrateAll($results);
    }

}
