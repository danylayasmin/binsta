<?php

use RedBeanPHP\R as R;

class PostController extends BaseController
{
    public function index()
    {
    }
    
    // create new post
    public function create()
    {
        // check if user is logged in
        $this->authorizeUser();
        
        // get data for template
        $const = array(
            'themes' => ['a11y-dark', 'atom-dark', 'base16-ateliersulphurpool.light', 'cb', 'darcula', 'default',
            'dracula', 'duotone-dark', 'duotone-earth', 'duotone-forest', 'duotone-light', 'duotone-sea', 'duotone-space',
            'ghcolors', 'hopscotch', 'material-dark', 'material-light', 'material-oceanic', 'nord', 'pojoaque', 'shades-of-purple',
            'synthwave84', 'vs', 'vsc-dark-plus', 'xonokai'],
            'languages' => ['c', 'css', 'cpp', 'go', 'html', 'java', 'javascript', 'jsx', 'php', 'python', 'rust', 'typescript']
        );
        
        displayTemplate('post/create.twig', $const);
    }
}
