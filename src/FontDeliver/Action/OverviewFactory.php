<?php

namespace FontDeliver\Action;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use FontDeliver\Services\OverviewList;

class OverviewFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $list     = $container->get(OverviewList::class);
        $router   = $container->get(RouterInterface::class);
        $template = $container->has(TemplateRendererInterface::class)
            ? $container->get(TemplateRendererInterface::class)
            : null;

        return new OverviewAction($router, $template, $list);
    }
}
