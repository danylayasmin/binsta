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
    $data['username'] = ($user[0]['username']) ?? null;
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


function getHighlightJSData(): array
{
    $highlightJSData = array(
        'themes' => [
            'a11y-dark',
            'atom-one-dark',
            'base16-apathy',
            'base16-atelier-cave',
            'devibeans',
            'far',
            'felipec',
            'github-dark',
            'gradient-dark',
            'gradient-light',
            'ir-black',
            'night-owl',
            'nord',
            'rainbow',
            'shades-of-purple',
            'srcery',
            'tokyo-night-dark',
            'base16-decaf',
            'base16-outrun-dark'
        ],
        'languages' => [
            "bash",
            "c",
            "cpp",
            "csharp",
            "css",
            "diff",
            "go",
            "graphql",
            "ini",
            "java",
            "javascript",
            "json",
            "kotlin",
            "less",
            "lua",
            "makefile",
            "markdown",
            "objectivec",
            "perl",
            "php",
            "php-template",
            "plaintext",
            "python",
            "python-repl",
            "r",
            "ruby",
            "rust",
            "scss",
            "shell",
            "sql",
            "swift",
            "typescript",
            "vbnet",
            "wasm",
            "xml",
            "yaml"
        ]
    );

    return $highlightJSData;
}

function retrieveThemeCookie(): string
{
    if (!isset($_COOKIE['theme'])) {
        setcookie('theme', 'github-dark', path: '/');
        return 'github-dark';
    }

    return $_COOKIE['theme'];
}

function setThemeCookie(string $themeName): bool
{
    return setcookie('theme', $themeName);
}
