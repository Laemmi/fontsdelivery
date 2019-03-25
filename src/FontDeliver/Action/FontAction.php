<?php

namespace FontDeliver\Action;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;

use FontDeliver\Diactoros\Response\FontResponse;


class FontAction implements ServerMiddlewareInterface
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $font = $request->getAttribute('font');
        $dir  = substr($font, 0, strpos($font, '-'));

        $datapath = realpath(__DIR__ . '/../../../../data/fonts');

        $font = $datapath . '/' . $dir . '/' . $font;

        return new FontResponse($font);
    }
}
