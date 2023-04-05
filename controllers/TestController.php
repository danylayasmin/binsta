<?php

use RedBeanPHP\R as R;

class TestController extends BaseController
{
    // test page
    public function welcome()
    {
        $data = [
            'project_name' => 'Binsta',
        ];
        
        displayTemplate('welcome.twig', $data);
    }
}
