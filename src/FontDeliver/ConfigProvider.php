<?php

namespace FontDeliver;

/**
 * The configuration provider for the FontDeliver module
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
            'fontdeliver'  => [
                'fonttypes' => ['woff2', 'woff'],
                'fontweights' => [
                    100 => 'Thin',
                    200 => 'ExtraLight',
                    300 => 'Light',
                    400 => 'Regular',
                    600 => 'SemiBold',
                    700 => 'Bold',
                    800 => 'ExtraBold',
                    900 => 'Black',
                ],
                'paths' => [
                    'fonts' => 'data/fonts'
                ]
            ]
        ];
    }

    /**
     * Returns the container dependencies
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            'invokables' => [
                Action\FontAction::class => Action\FontAction::class,
            ],
            'factories'  => [
                Action\CssAction::class      => Action\CssFactory::class,
                Action\OverviewAction::class => Action\OverviewFactory::class,
                Services\FontList::class     => Services\FontListFactory::class,
                Services\OverviewList::class => Services\OverviewListFactory::class,
            ],
        ];
    }

    /**
     * Returns the templates configuration
     *
     * @return array
     */
    public function getTemplates()
    {
        return [
            'paths' => [
                'css'    => ['src/FontDeliver/templates/css'],
                'index'  => ['src/FontDeliver/templates/index'],
                'layout' => ['src/FontDeliver/templates/layout'],
            ],
        ];
    }
}
