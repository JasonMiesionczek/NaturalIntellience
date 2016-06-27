<?php
/**
 * Created by PhpStorm.
 * User: jason
 * Date: 4/22/16
 * Time: 3:10 PM
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    protected function getCommonParameters(array $otherParams = array())
    {
        $common = array(
            'user' => $this->getUser()
        );

        return array_merge($common, $otherParams);
    }
}