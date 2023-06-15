<?php

namespace Controllers;
use RedBeanPHP\R as R;

class ProfileController extends BaseController
{
    // profile page
    public function me()
    {
        $this->authorizeUser();
        $user = $this->getBeanById('user', $_SESSION['loggedInUser']);

        // Get all posts for the logged-in user
        $posts = R::getAll('SELECT * FROM post WHERE user_id = ?', [$_SESSION['loggedInUser']]);

        // Get data for the template
        $data = [
            'posts' => $posts,
            'user' => $user
        ];

        displayTemplate('profile/me.twig', $data);
    }

    // edit profile page
    public function edit()
    {
        $this->authorizeUser();
        $user = $this->getBeanById('user', $_SESSION['loggedInUser']);

        $data = [
            'user' => $user,
        ];
        displayTemplate('profile/edit.twig', $data);
    }

    // edit profile
    public function editPost()
    {
        // retrieve user
        $user = $this->getBeanById('user', $_SESSION['loggedInUser']);

        $user->username = $_POST['username'];
        $user->displayName = $_POST['displayName'];
        $user->email = $_POST['email'];
        $user->profilePicture = $_POST['profilePicture'];
        $user->bio = $_POST['bio'];
        $user->updatedAt = new \DateTime('now');
        
        // store user
        R::store($user);

        // redirect to profile page
        header("Location: /profile/me");
    }

    // show profile with specified id
    public function show()
    {
        // check if id is set
        if (!isset($_GET['id'])) {
            error(404, 'No user ID provided', '/test/welcome');
            exit;
        }

        // check if id post exists
        $user = $this->getBeanById('user', $_GET['id']);
        if (!isset($user)) {
            error(404, 'User not found with ID ' . $_GET['id'], '/test/welcome');
            exit;
        }

        // Get all posts for the user
        $posts = R::getAll('SELECT * FROM post WHERE user_id = ?', [$_GET['id']]);

        // Get data for the template
        $data = [
            'posts' => $posts,
            'user' => $user
        ];

        displayTemplate('profile/show.twig', $data);
    }
}
