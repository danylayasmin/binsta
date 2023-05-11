<?php

namespace Controllers;
use RedBeanPHP\R as R;

include "../public/api/code2img.php";

class TestController extends BaseController
{
    // test page
    public function welcome()
    {
        // Create a new instance of the Code2ImgAPI class
        $api = new \API\Code2ImgAPI();

        $posts = R::getAll('SELECT * from post');

        foreach ($posts as $post) {
            $code = $post['code'];
            $theme = $post['theme'];
            $language = $post['language'];
        

            // Call the get_image method to get the image bytes
            if (!isset($code) || !isset($theme) || !isset($language)) {
                error(404, 'Post not found', '/test/welcome');
                exit;
            } else {
                $image_bytes = $api->get_image($code, $theme, $language);
            }

            // image_bytes to base64
            $image_bytes = base64_encode($image_bytes);

            $user = R::load('user', $post['user_id']);
            $postData[] = [
                'post' => $post,
                'image_bytes' => $image_bytes,
                'id' => $post['id'],
                'user' => $user,
            ];
        }
        
        // get data for template
        $data = [
            'posts' => $postData,
            'project_name' => 'Binsta',
        ];
        
        displayTemplate('welcome.twig', $data);
    }
}