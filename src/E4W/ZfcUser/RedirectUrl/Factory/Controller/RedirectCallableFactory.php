<?php

namespace E4W\ZfcUser\RedirectUrl\Factory\Controller;

use E4W\ZfcUser\RedirectUrl\Controller\RedirectCallback;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RedirectCallableFactory implements \Zend\ServiceManager\Factory\FactoryInterface
{

    public function __invoke(\Psr\Container\ContainerInterface $serviceLocator, $requestedName, array $options = null)
    {
        /* @var RouteInterface $router */
        $router = $serviceLocator->get('Router');

        /* @var Application $application */
        $application = $serviceLocator->get('Application');

        /* @var \ZfcUser\Options\ModuleOptions $zfcUserOtions */
        $zfcUserOtions = $serviceLocator->get('zfcuser_module_options');

        /* @var \E4W\ZfcUser\RedirectUrl\Options\ModuleOptions $options */
        $options = $serviceLocator->get('E4W\ZfcUser\RedirectUrl\ModuleOptions');

        return new RedirectCallback($application, $router, $zfcUserOtions, $options);
    }
}
