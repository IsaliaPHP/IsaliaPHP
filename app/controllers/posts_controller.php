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
        if (Request::hasPost("post")) {
            if (!Request::isSafe()) {
                Flash::error("Se proporcionaron datos no seguros.");
                return;
            }
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
        if (Request::hasPost("post")) {
            if (!Request::isSafe()) {
                Flash::error("Se proporcionaron datos no seguros.");
                return;
            }
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

}
