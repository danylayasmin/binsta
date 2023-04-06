<?php

use RedBeanPHP\R as R;

class UserController extends BaseController
{
    // login page
    public function login()
    {
        // check if user is already logged in
        if (isset($_SESSION['loggedInUser'])) {
            error(303, 'You are already logged in', '/');
            exit;
        }
        
        displayTemplate('users/login.twig', []);
    }

    // login user
    public function loginPost()
    {
        // check if username is set
        $user = R::findOne('user', 'username = ?', [$_POST['username']]);
        if (!isset($user)) {
            error(401, 'User not found with username ' . $_POST['username'], '/user/login');
        }
        
        // check if password is correct
        if (!password_verify($_POST['password'], $user->password)) {
            error(401, 'Username and password do not match', '/user/login');
        }

        // start session, set variable
        $_SESSION['loggedInUser'] = $user['id'];
        header("Location: /");
    }

    // logout user
    public function logout()
    {
        // end session
        session_destroy();
        header("Location: /user/login");
        die();
    }

    // sign up page
    public function signup()
    {
        // check if user is already logged in
        if (isset($_SESSION['loggedInUser'])) {
            error(303, 'You are already logged in', '/');
            exit;
        }

        $const = array(
            'securityQuestions' => ['What was your childhood nickname?', 'What was the name of your favorite (stuffed) pet?', 'What city were you born in?']
        );

        displayTemplate('users/signup.twig', $const);
    }

    // sign up user
    public function signupPost() 
    {
        // check if both passwords are the same
        if ($_POST["password"] !== $_POST["confirmPassword"]) {
            error(401, 'Passwords do not match', '/user/signup');
            die();
        }
        
        // check if username is already taken
        $user = R::findOne('user', 'username = ?', [$_POST['username']]);
        if (!is_null($user)) {
            error(401, 'Username already taken', '/user/signup');
            die();
        }

        // check if e-mail is already taken
        $email = R::findOne('user', 'email = ?', [$_POST['email']]);
        if (!is_null($email)) {
            error(401, 'E-mail already taken', '/user/signup');
            die();
        }

        // create user, store in database
        $user = R::dispense('user');
        $user->username = $_POST['username'];
        $user->displayName = $_POST['displayName'];
        $user->email = $_POST['email'];
        $user->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $user->securityAnswer = $_POST['securityAnswer'];
        $user->bio = null;
        $user->avatar = 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png';
        R::store($user);
        // start session, set variable
        $_SESSION['loggedInUser'] = $user['id'];
        header("Location: /");
    }

    // forgot password page
    public function forgot()
    {
        $const = array(
            'securityQuestions' => ['What was your childhood nickname?', 'What was the name of your favorite (stuffed) pet?', 'What city were you born in?']
        );

        displayTemplate('users/forgot.twig', $const);
    }
}
