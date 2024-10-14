<?php

/**
 * Post
 * @author nelson rojas
 * Clase base para la gestion operaciones sobre la tabla post
 * @property datetime created_at
 * @property datetime updated_at
 */
class Post extends Model {
    public function beforeCreate()
    {
        $this->created_at = date("Y-m-d H:i:s");
    }

    public function beforeUpdate()
    {
        $this->updated_at = date("Y-m-d H:i:s");
    }
}