<?php

use RedBeanPHP\R as R;

require_once "../public/api/code2img.php";

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
    
    // store new post in database
    public function createPost()
    {
        // Create a new instance of the Code2ImgAPI class
        $api = new Code2ImgAPI();

        $code = $_POST['code'];
        $theme = $_POST['theme'];
        $language = $_POST['language'];

        // Call the get_image method to get the image bytes
        try {
            $image_bytes = $api->get_image($code, $theme, $language);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            exit();
        }

        // Output the image to the browser
        header('Content-Type: image/png');
        echo $image_bytes;
    }
}
