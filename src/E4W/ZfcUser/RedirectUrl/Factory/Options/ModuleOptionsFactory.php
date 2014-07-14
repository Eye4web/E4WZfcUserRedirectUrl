<?php

namespace E4W\ZfcUser\RedirectUrl\Factory\Options;

use E4W\ZfcUser\RedirectUrl\Options\ModuleOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ModuleOptionsFactory implements FactoryInterface
{
    /**
     * Create options
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return SocialService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        $service = new ModuleOptions($config['e4wzfcuserredirecturl']);
        return $service;
    }
}
