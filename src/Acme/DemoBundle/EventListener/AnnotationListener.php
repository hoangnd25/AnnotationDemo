<?php

namespace Acme\DemoBundle\EventListener;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Util\ClassUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class AnnotationListener
{
    protected $reader;

    public function __construct($reader)
    {
        /** @var AnnotationReader $reader */
        $this->reader = $reader;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        /*
         * $controller passed can be either a class or a Closure. This is not usual in Symfony2 but it may happen.
         * If it is a class, it comes in array format
         *
         */
        if (!is_array($controller)) {
            return;
        }

        list($controllerObject, $methodName) = $controller;

        $demoAnnotation = 'Acme\DemoBundle\Annotation\Demo';

        $message = '';

        // Get class annotation, using ClassUtils::getClass in case the controller is an proxy
        $classAnnotation = $this->reader->getClassAnnotation(
            new \ReflectionClass(ClassUtils::getClass($controllerObject)), $demoAnnotation
        );
        if($classAnnotation)
            $message .=  $classAnnotation->message.'<br>';

        // Get method annotation
        $controllerReflectionObject = new \ReflectionObject($controllerObject);// get reflection controller
        $reflectionMethod = $controllerReflectionObject->getMethod($methodName);// get reflection method
        $methodAnnotation = $this->reader->getMethodAnnotation($reflectionMethod,$demoAnnotation);
        if($methodAnnotation)
            $message .=  $methodAnnotation->message.'<br>';

        // Override the response
        $event->setController(
            function() use ($message) {
                return new Response($message);
            }
        );

    }
}
