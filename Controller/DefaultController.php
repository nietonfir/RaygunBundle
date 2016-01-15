<?php

namespace Nietonfir\RaygunBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('NietonfirRaygunBundle:Default:index.html.twig');
    }
}
