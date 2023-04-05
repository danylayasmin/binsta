<?php

function displayTemplate($template, $data)
{
    global $twig;
    echo $twig->render($template, $data);
}