<?php

use RedBeanPHP\R as R;

require_once "../public/api/code2img.php";

class PostController extends BaseController
{
    // show post with specified id
    public function show()
    {
        // Create a new instance of the Code2ImgAPI class
        $api = new Code2ImgAPI();

        // check if id is set
        if (!isset($_GET['id'])) {
            error(404, 'No ID provided', '/test/welcome');
            exit;
        }
        
        // check if id post exists
        $post = $this->getBeanById('post', $_GET['id']);
        if (!isset($post)) {
            error(404, 'Post not found with ID ' . $_GET['id'], '/test/welcome');
            exit;
        }

        $code = $post->code;
        $theme = $post->theme;
        $language = $post->language;

        // Call the get_image method to get the image bytes
        if (!isset($code) || !isset($theme) || !isset($language)) {
            error(404, 'Post not found', '/test/welcome');
            exit;
        } else {
            $image_bytes = $api->get_image($code, $theme, $language);
        }

        // Output the image to the browser
        header('Content-Type: image/png');
        echo $image_bytes;
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
        // store data in database
        $post = R::dispense('post');
        $post->code = $_POST['code'];
        $post->caption = $_POST['caption'];
        $post->theme = $_POST['theme'];
        $post->language = $_POST['language'];
        $user = R::load('user', $_SESSION['loggedInUser']);
        $post->user = $user;
        R::store($post);

        // redirect to post
        $id = $post->id;
        header("Location: /post/show?id=$id");
    }
}