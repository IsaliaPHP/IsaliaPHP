<?php

/**
 * 
 * @author nelson rojas
 * class Model
 * Clase base para la gestion de modelos ORM
 * @property int id
 */
class Model
{
    protected $_model_name;
    protected $_table_name;
    protected $_attributes = [];

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

        if ((int) method_exists($this, "initialize")) {
            call_user_func(array($this, "initialize"));
        }

        $this->load($data);
    }

    public function setTableName(string $table_name)
    {
        $this->_table_name = $table_name;
    }

    public function findById(int $id)
    {
        $sql = "SELECT * FROM " . $this->_table_name .
            " WHERE id = $id";
        $result = Db::findFirst($sql);
        return $this->hydrate($result);
    }

    public function findAll(string $condition = '', array $parameters = null)
    {
        $sql = "SELECT * FROM " . $this->_table_name . " ";

        if (!empty($condition)) {
            $sql .= $condition;
        }

        $results = Db::findAll($sql, $parameters) ?? [];
        return $this->hydrateAll($results);
    }

    public function findFirst(string $condition, array $parameters = null)
    {
        $sql = "SELECT * FROM " . $this->_table_name . " ";

        if (!empty($condition)) {
            $sql .= $condition;
        }

        $result = Db::findFirst($sql, $parameters);
        return $this->hydrate($result);
    }

    public function findBySQL(string $sql, array $parameters = null)
    {
        $results = Db::findAll($sql, $parameters);
        return $this->hydrateAll($results);
    }


    public function create()
    {
        $this->beforeCreate();
        $result = Db::insert($this->_table_name, $this->_attributes);
        $this->afterCreate();
        return $result;
    }

    public function update(array $attributes=[], string $condition = '')
    {
        
        if (empty($condition)) {
            $condition = " WHERE id = " . $this->id;
        }
        

        if (count($attributes) == 0) {
            $attributes = [...$this->_attributes];
        } 

        $this->load($attributes);
        
        $this->beforeUpdate();
        $result = Db::update($this->_table_name, $this->_attributes, $condition);
        $this->afterUpdate();
        return $result;
    }

    public function deleteAll(string $condition, array $parameters = null)
    {
        $this->beforeDelete();
        $result = Db::delete($this->_table_name, $condition, $parameters);
        $this->afterDelete();
        return $result;
    }

    public function delete()
    {
        if (intval($this->id) > 0) {
            $condition = " WHERE id = " . intval($this->id);
            $this->beforeDelete();
            $result = Db::delete($this->_table_name, $condition);
            $this->afterDelete();
            return $result;
        } else {
            return false;
        }
    }

    public function load(array $attributes)
    {
        foreach ($attributes as $attribute => $value) {
            $this->$attribute = $value;
        }
    }

    public function save()
    {
        if (intval($this->id) > 0) {
            $this->beforeUpdate();
            return $this->update($this->_attributes, " WHERE id = " . $this->id) > 0;
            $this->afterUpdate();
        } else {
            $this->beforeCreate();
            $last_id = intval($this->create($this->_attributes));
            $this->afterCreate();
            $this->id = $last_id;
            return $last_id > 0;
        }
        return false;
    }



    public function __set($attribute, $value)
    {
        $this->_attributes[$attribute] = $value;
    }

    public function __get($attribute)
    {
        return $this->_attributes[$attribute] ?? null;
    }

    //metodos auxiliares
    public function initialize()
    {
    }
    public function beforeCreate()
    {
    }
    public function beforeUpdate()
    {
    }
    public function beforeDelete()
    {
    }
    public function afterCreate()
    {
    }
    public function afterUpdate()
    {
    }
    public function afterDelete()
    {
    }

    protected function hydrate($data)
    {
        if (!$data) {
            return null;
        }
        $model = new $this->_model_name();
        $model->load($data);
        return $model;
    }

    protected function hydrateAll(array $dataSet)
    {
        $models = [];
        foreach ($dataSet as $data) {
            $models[] = $this->hydrate($data);
        }
        return $models;
    }
}
