<?php

use RedBeanPHP\R as R;

class BaseController
{
    // easy way to get a bean by id
    public function getBeanById($typeOfBean, $queryStringKey)
    {        
        $bean = R::findOne($typeOfBean, 'id = ?', [$queryStringKey]);
        return $bean;
    }
}
