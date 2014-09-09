<?php

namespace BiberLtd\Bundle\ContentManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BiberLtdContentManagementBundle:Default:index.html.twig', array('name' => $name));
    }
}
