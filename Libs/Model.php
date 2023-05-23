<?php

/**
 * 
 * @author nelson rojas
 * class Model
 * @property int id
 */
class Model
{
    protected $_model_name;
    protected $_table_name;
    protected $_attributes = [];

    public function __construct($class_name = '')
    {
        if (empty($class_name)) {
            $this->_model_name = get_class($this);
        } else {
            $this->_model_name = $class_name;
        }

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
    }

    public function setTableName(string $table_name)
    {
        $this->_table_name = $table_name;
    }

    public function findById(int $id)
    {
        $sql = "SELECT * FROM " . $this->_table_name .
            " WHERE id = $id";
        return Db::findFirst($sql);
    }

    public function findAll(string $condition = '', array $parameters = null)
    {
        $sql = "SELECT * FROM " . $this->_table_name . " ";

        if (!empty($condition)) {
            $sql .= $condition;
        }

        return Db::findAll($sql, $parameters);
    }

    public function findFirst(string $condition, array $parameters = null)
    {
        $sql = "SELECT * FROM " . $this->_table_name . " ";

        if (!empty($condition)) {
            $sql .= $condition;
        }

        return Db::findFirst($sql, $parameters);
    }


    public function create(array $attributes)
    {
        $this->beforeCreate();
        return Db::insert($this->_table_name, $attributes);
        $this->afterCreate();
    }

    public function update(array $attributes, string $condition = null)
    {
        $this->beforeUpdate();
        return Db::update($this->_table_name, $attributes, $condition);
        $this->afterUpdate();
    }

    public function deleteAll(string $condition, array $parameters = null)
    {
        $this->beforeDelete();
        return Db::delete($this->_table_name, $condition, $parameters);
        $this->afterDelete();
    }

    public function delete()
    {
        if (intval($this->id) > 0) {
            $condition = " WHERE id = " . intval($this->id);
            $this->beforeDelete();
            return Db::delete($this->_table_name, $condition);
            $this->afterDelete();
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
}
