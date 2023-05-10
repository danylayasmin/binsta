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
}