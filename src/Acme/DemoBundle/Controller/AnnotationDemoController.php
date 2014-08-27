<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Acme\DemoBundle\Annotation\Demo;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Demo(message="This is class annotation")
 */
class AnnotationDemoController extends Controller
{
    /**
     * @Route("/annotation", name="annotation_demo")
     * @Template()
     * @Demo(message="This is method annotation")
     */
    public function indexAction()
    {
        return new Response('This message won\'t appear');
    }

}
