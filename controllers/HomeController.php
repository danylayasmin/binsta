<?php

namespace Controllers;

use RedBeanPHP\R as R;

class HomeController extends BaseController
{
    public function index()
    {
        if (isset($_SESSION['loggedInUser'])) {
            header('Location: home/feed');
        } else {
            displayTemplate('index.twig');
        }
    }

    public function feed()
    {
        $posts = R::getAll('SELECT * from post');

        foreach ($posts as $post) {
            $code = $post['code'];
            $language = $post['language'];


            $user = R::load('user', $post['user_id']);

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

            // get all comments for this post
            $comments = R::getAll('SELECT * from comment WHERE post_id =?', [$post['id']]);
            $commentData = [];

            foreach ($comments as $comment) {
                $commentUser = R::load('user', $comment['user_id']);
                $commentData[] = [
                    'comment' => $comment,
                    'user' => $commentUser,
                ];
            }


            $postData[] = [
                'post' => $post,
                'id' => $post['id'],
                'user' => $user,
                'code' => $code,
                'language' => $language,
                'likes' => $post['likes'],
                'like_count' => $like_count,
                'likeData' => $likeData,
                'comments' => $commentData,
            ];
        }

        // get data for template
        $data = [
            'posts' => $postData,
            'project_name' => 'Binsta',
        ];

        displayTemplate('feed.twig', $data);
    }

    public function searchPost()
    {
        $query = $_POST['query'];

        $users = R::find('user', 'username LIKE ? OR display_name LIKE ?', ["%$query%", "%$query%"]);

        $posts = R::getAll('SELECT * from post');

        foreach ($posts as $post) {
            $code = $post['code'];
            $language = $post['language'];


            $user = R::load('user', $post['user_id']);

            $like_count = 0;

            if (isset($post['likes'])) {
                $like_count = count(json_decode($post['likes'], true));
            }

            // get all comments for this post
            $comments = R::getAll('SELECT * from comment WHERE post_id =?', [$post['id']]);
            $commentData = [];

            foreach ($comments as $comment) {
                $commentUser = R::load('user', $comment['user_id']);
                $commentData[] = [
                    'comment' => $comment,
                    'user' => $commentUser,
                ];
            }


            $postData[] = [
                'post' => $post,
                'id' => $post['id'],
                'user' => $user,
                'code' => $code,
                'language' => $language,
                'likes' => $post['likes'],
                'like_count' => $like_count,
                'comments' => $commentData,
            ];
        }

        $data = [
            'users' => $users,
            'posts' => $postData,
            'project_name' => 'Binsta',
        ];

        displayTemplate('feed.twig', $data);
    }

    public function likePost()
    {
        $postId = $_POST['postId'];
        $userId = $_SESSION['loggedInUser']; // dit kunnen we dan opslaan in een array in db?
        // en dan iets van ophalen wie heeft geliked, als t logged in user is dan ingekleurd ofzo

        $action = 'unknown';

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

    public function commentPost()
    {
        $postId = $_POST['post_id'];
        $userId = $_SESSION['loggedInUser'];
        $commentInput = $_POST['comment'];

        $comment = R::dispense('comment');
        $comment->post_id = $postId;
        $comment->user_id = $userId;
        $comment->comment = $commentInput;

        R::store($comment);

        header('Location: /home/feed');
    }
}
