<?php

use RedBeanPHP\R as R;

class ProfileController extends BaseController
{
    // profile page
    public function me()
    {
        $this->authorizeUser();
        $user = $this->getBeanById('user', $_SESSION['loggedInUser']);

        $data = [
            'user' => $user,
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

        // check if username is set
        if (isset($_POST['username'])) {
            $user->username = $_POST['username'];
        }
        
        // check if display name is set
        if (isset($_POST['displayName'])) {
            $user->displayName = $_POST['displayName'];
        }
        
        // check if email is set
        if (isset($_POST['email'])) {
            $user->email = $_POST['email'];
        }
        
        // check if profile picture is set
        if (isset($_POST['profilePicture'])) {
            $user->profilePicture = $_POST['profilePicture'];
        }
        
        // check if bio is set
        if (isset($_POST['bio'])) {
            $user->bio = $_POST['bio'];
        }

        // store user
        R::store($user);

        // redirect to profile page
        header("Location: /profile/me");
    }
}
