<?php

/**
 * PostsController
 * @author nelson rojas
 * Clase base para la gestion operaciones sobre la tabla post
 * @property array posts
 * @property Post post
 */
class PostsController extends Controller
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
                Flash::error("Non safe data provided");
                return;
            }
            $post = new Post(Request::post("post"));
            if ($post->save()) {
                Flash::valid("Post created successfully");
                $this->redirect("posts");
            }
        }
    }

    public function edit(int $id)
    {
        $post = (new Post)->findById($id);
        if (Request::hasPost("post")) {
            if (!Request::isSafe()) {
                Flash::error("Non safe data provided");
                return;
            }
            if ($post->update(Request::post("post"))) {
                Flash::valid("Post updated successfully");
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
            Flash::valid("Post deleted successfully");
        }
        $this->redirect("posts");
    }

}
