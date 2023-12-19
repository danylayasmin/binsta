<?php

namespace Controllers;

use RedBeanPHP\R as R;

class TestController extends BaseController
{
    // test page
    public function welcome()
    {
        $posts = R::getAll('SELECT * from post');

        foreach ($posts as $post) {
            $code = $post['code'];
            $theme = $post['theme'];
            $language = $post['language'];


            $user = R::load('user', $post['user_id']);
            $postData[] = [
                'post' => $post,
                'id' => $post['id'],
                'user' => $user,
                'code' => $code,
                'theme' => $theme,
                'language' => $language,
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

        $response = ['success' => true];
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
