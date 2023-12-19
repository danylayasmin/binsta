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


function getHighlightJSData(): array
{
    $highlightJSData = array(
        'themes' => [
            'a11y-dark', 'atom-one-dark', 'base16-apathy', 'base16-atelier-cave', 'devibeans', 'far', 'felipec', 'github-dark', 'gradient-dark', 'gradient-light',
            'ir-black', 'night-owl', 'nord', 'rainbow', 'shades-of-purple', 'srcery', 'tokyo-night-dark', 'base16-decaf', 'base16-outrun-dark'
        ],
        'languages' => [
            'arduino', 'apache', 'autohotkey', 'bash', 'sh', 'zsh', 'brainfuck', 'csharp', 'c', 'cpp', 'hpp', 'cmake', 'css', 'crystal', 'elixir', 'erlang',
            'go', 'gradle', 'graphql', 'groovy', 'html', 'xhtml', 'http', 'https', 'handlebars', 'haskell', 'haxe', 'hlsl', 'ini', 'toml', 'json', 'java', 'javascript', 'jsx',
            'julia', 'less', 'lisp', 'lua', 'makefile', 'markdown', 'moonscript', 'nginx', 'nim', 'nix', 'ocaml', 'monkey', 'php', 'perl', 'postgresql', 'powershell', 'python',
            'ruby', 'rust', 'scss', 'sql', 'scala', 'shell', 'swift', 'twig', 'craftcms', 'typescript', 'tsx', 'vbnet', 'vbs', 'vim', 'x86asm', 'yaml', 'yml', 'zephir'
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
