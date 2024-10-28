<?php

/**
 * PostsController
 * @author nelson rojas
 * Clase base para la gestion operaciones sobre la tabla post
 * @property array posts
 * @property Post post
 */
class PostsController extends AdminController
{  
    public function index()
    {
        $this->posts = (new Post)->findAll();
    }

    public function show(int $id)
    {
        $this->post = (new Post)->findById($id);
    }

    public function create()
    {
        if (Request::hasPost("post") && $this->checkValid()) {
            $post = new Post(Request::post("post"));
            if ($post->save()) {
                Flash::valid("Post creado exitosamente.");
                $this->redirect("posts");
            }
        }
    }

    public function edit(int $id)
    {
        $post = (new Post)->findById($id);
        if (Request::hasPost("post") && $this->checkValid()) {
            if ($post->update(Request::post("post"))) {
                Flash::valid("Post actualizado exitosamente.");
                $this->redirect("posts");
            }
        }
        $this->post = $post;
    }

    public function delete(int $id)
    {
        //no view required
        $this->setView(null);
        $post = (new Post)->findById($id);
        if ($post && $post->delete()) {
            Flash::valid("Post eliminado exitosamente.");
        }
        $this->redirect("posts");
    }

    /**
     * permite revisar si las peticiones usando posts desde formularios 
     * usan la semilla de seguridad definida en el archivo de configuración
     * evitando que cualquiera pueda apuntar desde otros sitios a los métodos
     * de los controladores de la aplicación, ya sea a través de formularios o herramientas
     * como Postman, Insomnia, Bruno, o CURL directo
     */
    private function checkValid()
    {
        if (!Request::isSafe()) {
            Flash::error("Se proporcionaron datos no seguros.");
            return false;
        } else {
            return true;
        }
    }
}
