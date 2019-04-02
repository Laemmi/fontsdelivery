<?php

namespace FontDeliver\Action;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

use FontDeliver\Services\FontList;

class CssFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $list     = $container->get(FontList::class);
        $router   = $container->get(RouterInterface::class);
        $template = $container->has(TemplateRendererInterface::class)
            ? $container->get(TemplateRendererInterface::class)
            : null;

        return new CssAction($router, $template, $list);
    }
}
