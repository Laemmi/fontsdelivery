<?php

namespace FontDeliver\Action;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router;
use Zend\Expressive\Template;

use FontDeliver\Diactoros\Response\CssResponse;
use FontDeliver\FontList;

class CssAction implements ServerMiddlewareInterface
{
    private $router;

    private $template;

    public function __construct(Router\RouterInterface $router, Template\TemplateRendererInterface $template = null)
    {
        $this->router   = $router;
        $this->template = $template;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $family = urldecode($request->getAttribute('family'));

        $list = FontList::factory($family);

        return new CssResponse($this->template->render('css::index', ['list' => $list]));
    }
}
