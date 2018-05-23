<?php

namespace FontDeliver\Action;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router;
use Zend\Expressive\Template;

use FontDeliver\Diactoros\Response\CssResponse;


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
        $params = $request->getQueryParams();

        $list = \FontDeliver\FontList::factory($params['family']);

//        echo "<pre>";
//        print_r($list);
//        echo "</pre>";

        return new CssResponse($this->template->render('css::index', ['list' => $list]));
    }
}
