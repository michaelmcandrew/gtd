<?php

namespace Tsd\GtdBundle\Services;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class TwigDateRequestListener
{
    protected $twig;

    function __construct(\Twig_Environment $twig) {
        $this->twig = $twig;
    }

    public function onKernelRequest(GetResponseEvent $event) {
        $this->twig->getExtension('core')->setDateFormat('d/m/y', '%d days');
    }
}
