<?php

namespace App\Controller\Web;

use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Respect\Validation\Validator as V;
use Slim\Http\Request;
use Slim\Http\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    public function index( )
    {
        $results=[];
        //return $this->redirect("/apidoc");

        return $this->render('apidoc/home.html', ['item'=>3]);

    }


}
