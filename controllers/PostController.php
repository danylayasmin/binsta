<?php

namespace Controllers;

use RedBeanPHP\R as R;

class PostController extends BaseController
{
    // show post with specified id
    public function show()
    {
        $posts = R::getAll('SELECT * from post');

        // check if id is set
        if (!isset($_GET['id'])) {
            error(404, 'No ID provided', '/home/feed');
            exit;
        }

        foreach ($posts as $post) {
            $code = $post['code'];
            $theme = $_COOKIE['theme'] ?? 'github-dark';
            $language = $post['language'];

            // check if id post exists
            $post = $this->getBeanById('post', $_GET['id']);
            if (!isset($post)) {
                error(404, 'Post not found with ID ' . $_GET['id'], '/home/feed');
                exit;
            }

            $like_count = 0;

            if (isset($post['likes'])) {
                $like_count = count(json_decode($post['likes'], true));

                $likes = json_decode($post['likes'], true);
                $likeData = [];

                foreach ($likes as $like) {
                    $likeUser = R::load('user', $like);
                    $likeData[] = $likeUser;
                }
            }

            $comments = R::getAll('SELECT * from comment WHERE post_id =?', [$post['id']]);
            $commentData = [];

            foreach ($comments as $comment) {
                $commentUser = R::load('user', $comment['user_id']);
                $commentData[] = [
                    'comment' => $comment,
                    'user' => $commentUser,
                ];
            }
        }
        // get data for template
        $data = [
            'post' => $post,
            'id' => $_GET['id'],
            'user' => $post->user,
            'code' => $code,
            'theme' => $theme,
            'language' => $language,
            'likes' => $post['likes'],
            'like_count' => $like_count,
            'likeData' => $likeData,
            'comments' => $commentData,
        ];

        displayTemplate('post/show.twig', $data);
    }

    public function likePost()
    {
        $postId = $_POST['postId'];
        $userId = $_SESSION['loggedInUser'];

        $post = R::load('post', $postId);
        $postLikes = json_decode($post->likes, true); // Decode the JSON string to an array

        if (!is_array($postLikes)) {
            $postLikes = [];
        }

        if (!in_array($userId, $postLikes)) {
            $postLikes[] = $userId;
            $action = 'liked';
        } else {
            $postLikes = array_diff($postLikes, [$userId]);
            $action = 'unliked';
        }

        $post->likes = json_encode($postLikes);
        R::store($post);



        $response = ['success' => true, 'action' => $action];
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    // create new post
    public function create()
    {
        // check if user is logged in
        $this->authorizeUser();

        // get data for template
        $data = getHighlightJSData();

        displayTemplate('post/create.twig', $data);
    }

    // store new post in database
    public function createPost()
    {
        validateLength('caption', 255, 'Caption is too long', '/post/create');
        validateLength('code', 5000, 'Code snippet is too long', '/post/create');
        validateLength('language', 50, 'Language is too long', '/post/create');


        // store data in database
        $post = R::dispense('post');
        $post->code = $_POST['code'];
        $post->caption = $_POST['caption'];
        $post->language = $_POST['language'];
        $post->likes = json_encode([]);
        $user = R::load('user', $_SESSION['loggedInUser']);
        $post->user = $user;
        R::store($post);

        // redirect to post
        $id = $post->id;
        header("Location: /post/show?id=$id");
    }

    public function edit()
    {
        $this->authorizeUser();

        if (!isset($_GET['id'])) {
            error(
                404,
                'No ID provided',
                '/home/feed'
            );
            exit;
        }

        // get post with specified id
        $post = $this->getBeanById('post', $_GET['id']);

        // check if post exists
        if (!isset($post)) {
            error(404, 'Post not found with ID ' . $_GET['id'], '/home/feed');
            exit;
        }

        // check if user is authorized to edit post
        if ($post->user->id !== $_SESSION['loggedInUser']) {
            error(403, 'You are not authorized to edit this post', '/home/feed');
            exit;
        }

        // get data for template
        $data = getHighlightJSData();
        $data['post'] = $post;

        displayTemplate(
            'post/edit.twig',
            $data
        );
    }

    public function update()
    {
        $this->authorizeUser();

        if (!isset($_GET['id'])) {
            error(
                404,
                'No ID provided',
                '/home/feed'
            );
            exit;
        }

        $post = $this->getBeanById('post', $_GET['id']);

        if (!isset($post)) {
            error(404, 'Post not found with ID ' . $_GET['id'], '/home/feed');
            exit;
        }

        if (
            $post->user->id !== $_SESSION['loggedInUser']
        ) {
            error(403, 'You are not authorized to edit this post', '/home/feed');
            exit;
        }

        validateLength(
            'caption',
            255,
            'Caption is too long',
            '/post/edit?id=' . $post->id
        );
        validateLength('code', 5000, 'Code snippet is too long', '/post/edit?id=' . $post->id);
        validateLength('language', 50, 'Language is too long', '/post/edit?id=' . $post->id);

        $post->code = $_POST['code'];
        $post->caption = $_POST['caption'];
        $post->language = $_POST['language'];
        R::store($post);

        // redirect to post
        header("Location: /post/show?id=" . $post->id);
    }

    // delete post with specified id if authorized. always POST cause we dont have a frontend page for this (its a button)
    public function deletePost()
    {
        $this->authorizeUser();

        if (!isset($_POST['id'])) {
            error(
                404,
                'No ID provided',
                '/home/feed'
            );
            exit;
        }

        $post = $this->getBeanById('post', $_POST['id']);

        if (!isset($post)) {
            error(404, 'Post not found with ID ' . $_POST['id'], '/home/feed');
            exit;
        }

        if (
            $post->user->id !== $_SESSION['loggedInUser']
        ) {
            error(403, 'You are not authorized to delete this post', '/home/feed');
            exit;
        }

        R::trash($post);
        header('Location: /home/feed');
    }
}
