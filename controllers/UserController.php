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

    // register page
    public function register()
    {
        // check if user is already logged in
        if (isset($_SESSION['loggedInUser'])) {
            error(303, 'You are already logged in', '/');
            exit;
        }

        displayTemplate('users/register.twig', []);
    }

    // register user
    public function registerPost() 
    {
        // check if both passwords are the same
        if ($_POST["password"] !== $_POST["confirmPassword"]) {
            error(401, 'Passwords do not match', '/user/register');
            die();
        }
        
        // check if username is already taken
        $user = R::findOne('user', 'username = ?', [$_POST['username']]);
        if (!is_null($user)) {
            error(401, 'Username already taken', '/user/register');
            die();
        }

        // create user, store in database
        $user = R::dispense('user');
        $user->username = $_POST['username'];
        $user->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        R::store($user);
        // start session, set variable
        $_SESSION['loggedInUser'] = $user['id'];
        header("Location: /");
    }
}
