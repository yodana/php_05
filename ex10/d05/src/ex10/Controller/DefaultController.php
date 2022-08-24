<?php

namespace ex10\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/e10/")
     */
    public function indexAction()
    {
        return $this->render('ex10:Default:index.html.twig');
    }
}
