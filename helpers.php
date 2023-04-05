<?php

use RedBeanPHP\R as R;

// template rendering
function displayTemplate($template, $data = [])
{
    global $twig;
    // add session data to data array
    $data['session'] = $_SESSION;
    // add logged in user to data array
    $data['loggedInUser'] = $_SESSION['loggedInUser'] ?? null;
    // add username to data array
    $user = R::getAll('SELECT username FROM user WHERE id = ?', [$data['loggedInUser']]) ?? null;
    $data['username'] =  ($user[0]['username']) ?? null;
    // render template
    echo $twig->render($template, $data);
}

// error handling
function error($errorNumber, $errorMessage, $url)
{
    // error message
    $error = 'Error ' . $errorNumber . ': ' . $errorMessage;
    // log error
    http_response_code($errorNumber);
    // display error page
    displayTemplate('error.twig', ['errorMessage' => $errorMessage, 'errorNumber' => $errorNumber, 'url' => $url]);
    die();
}
