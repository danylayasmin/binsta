<?php

namespace Controllers;

use RedBeanPHP\R as R;

class HomeController extends BaseController
{
    public function welcome()
    {
        $posts = R::getAll('SELECT * from post');

        foreach ($posts as $post) {
            $code = $post['code'];
            $language = $post['language'];


            $user = R::load('user', $post['user_id']);

            $like_count = 0;

            if (isset($post['likes'])) {
                $like_count = count(json_decode($post['likes'], true));
            }


            $postData[] = [
                'post' => $post,
                'id' => $post['id'],
                'user' => $user,
                'code' => $code,
                'language' => $language,
                'likes' => $post['likes'],
                'like_count' => $like_count,
            ];
        }

        // get data for template
        $data = [
            'posts' => $postData,
            'project_name' => 'Binsta',

        ];

        displayTemplate('welcome.twig', $data);
    }

    public function searchPost()
    {
        $query = $_POST['query'];

        $users = R::find('user', 'username LIKE ? OR display_name LIKE ?', ["%$query%", "%$query%"]);

        $data = [
            'users' => $users,
        ];

        displayTemplate('search.twig', $data);
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
}
