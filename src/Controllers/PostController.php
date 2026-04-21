<?php

namespace Choco\Controllers;
use Choco\Core\Attributes\Database\Column;
use Choco\Core\Controller;
use Choco\Core\Services\MetadataService;
use Choco\Entities\Post;
use Choco\Repositories\PostRepository;

class PostController extends Controller
{
    protected PostRepository $postRepository;

    public function __construct(){
        $this->postRepository = new PostRepository();
    }

    public function index()
    {
        //get all the posts
        $posts = $this->postRepository->all();

        echo $this->view("post/index",[
            "title" => "All the posts",
            "posts" => $posts
        ]);
    }
}