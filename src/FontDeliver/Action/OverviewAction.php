<?php

namespace FontDeliver\Action;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template;

use FontDeliver\Services\OverviewList;

class OverviewAction implements ServerMiddlewareInterface
{
    private $router;

    private $template;

    private $list;

    public function __construct(Router\RouterInterface $router, Template\TemplateRendererInterface $template = null, OverviewList $list)
    {
        $this->router   = $router;
        $this->template = $template;
        $this->list     = $list;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        return new HtmlResponse($this->template->render('index::index', ['list' => $this->list]));
    }
}
