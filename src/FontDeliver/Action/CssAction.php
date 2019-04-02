<?php

namespace FontDeliver\Action;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router;
use Zend\Expressive\Template;

use FontDeliver\Diactoros\Response\CssResponse;
use FontDeliver\Services\FontList;

class CssAction implements ServerMiddlewareInterface
{
    private $router;

    private $template;

    private $list;

    public function __construct(Router\RouterInterface $router, Template\TemplateRendererInterface $template = null, FontList $list)
    {
        $this->router   = $router;
        $this->template = $template;
        $this->list     = $list;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $family = urldecode($request->getAttribute('family'));

        return new CssResponse($this->template->render('css::index', ['list' => $this->list->__invoke($family)]));
    }
}
