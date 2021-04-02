<?php

namespace E4W\ZfcUser\RedirectUrl\Factory\Options;

use E4W\ZfcUser\RedirectUrl\Options\ModuleOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ModuleOptionsFactory implements \Zend\ServiceManager\Factory\FactoryInterface
{
    /**
     * Create options
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return SocialService
     */
    public function __invoke(\Psr\Container\ContainerInterface $serviceLocator, $requestedName, array $options = null)
    {
        $config = $serviceLocator->get('Config');

        $service = new ModuleOptions($config['e4wzfcuserredirecturl']);
        return $service;
    }
}
