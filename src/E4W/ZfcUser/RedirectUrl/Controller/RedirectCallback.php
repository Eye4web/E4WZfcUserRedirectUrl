<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace E4W\ZfcUser\RedirectUrl\Controller;

use Zend\Mvc\Application;
use Zend\Mvc\Router\RouteInterface;
use Zend\Mvc\Router\Exception;
use Zend\Http\PhpEnvironment\Response;
use ZfcUser\Options\ModuleOptions as ZfcUserOptions;
use E4W\ZfcUser\RedirectUrl\Options\ModuleOptions;

/**
 * Buils a redirect response based on the current routing and parameters
 */
class RedirectCallback
{

    /** @var RouteInterface  */
    private $router;

    /** @var Application */
    private $application;

    /** @var ModuleOptions */
    private $options;

    /** @var ZfcUserOptions */
    private $zfcUserOptions;

    /**
     * @param Application $application
     * @param RouteInterface $router
     * @param ModuleOptions $options
     */
    public function __construct(Application $application, RouteInterface $router, ZfcUserOptions $zfcUserOptions, ModuleOptions $options)
    {
        $this->router = $router;
        $this->application = $application;
        $this->options = $options;
        $this->zfcUserOptions = $zfcUserOptions;
    }

    /**
     * @return Response
     */
    public function __invoke()
    {
        $routeMatch = $this->application->getMvcEvent()->getRouteMatch();
        $redirect = $this->getRedirect($routeMatch->getMatchedRouteName(), $this->getRedirectUrlFromRequest());

        $response = $this->application->getResponse();
        $response->getHeaders()->addHeaderLine('Location', $redirect);
        $response->setStatusCode(302);
        return $response;
    }

    /**
     * Return the redirect from param.
     * First checks GET then POST
     * @return string
     */
    private function getRedirectUrlFromRequest()
    {
        $request  = $this->application->getRequest();
        $redirect = $request->getQuery('redirect');
        if ($redirect && $this->urlWhitelisted($redirect)) {
            return $redirect;
        }

        $redirect = $request->getPost('redirect');
        if ($redirect && $this->urlWhitelisted($redirect)) {
            return $redirect;
        }

        return false;
    }

    /**
     * Checks if a $url is in whitelist
     * / and localhost are always allowed
     *
     * partly snatched from https://gist.github.com/mjangda/1623788
     *
     * @param $url
     * @return bool
     */
    private function urlWhitelisted($url)
    {
        $always_allowed = array('localhost');
        $whitelisted_domains = array_merge($this->options->getWhitelist(), $always_allowed);

        // Add http if missing(to satisfy parse_url())
        if (strpos($url, "/") !== 0 && strpos($url, "http") !== 0) {
            $url = 'http://' . $url;
        }
        $domain = parse_url($url, PHP_URL_HOST);

        if (strpos($url, "/") === 0 || in_array($domain, $whitelisted_domains)) {
            return true;
        }

        foreach ($whitelisted_domains as $whitelisted_domain) {
            $whitelisted_domain = '.' . $whitelisted_domain;
            if (strpos($domain, $whitelisted_domain) === (strlen($domain) - strlen($whitelisted_domain))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns the url to redirect to based on current url.
     * If $redirect is set and the option to use redirect is set to true, it will return the $redirect url
     * after verifying that the url is in the whitelist.
     *
     * @param string $currentRoute
     * @param bool $redirect
     * @return mixed
     */
    private function getRedirect($currentRoute, $redirect = false)
    {
        $useRedirect = $this->zfcUserOptions->getUseRedirectParameterIfPresent();
        $urlAllowed = ($redirect && $this->urlWhitelisted($redirect));
        if ($useRedirect && $urlAllowed) {
            return $redirect;
        }

        switch ($currentRoute) {
            case 'zfcuser/register':
            case 'zfcuser/login':
                $route = $this->zfcUserOptions->getLoginRedirectRoute();
                return $this->router->assemble(array(), array('name' => $route));
                break;
            case 'zfcuser/logout':
                $route = $this->zfcUserOptions->getLogoutRedirectRoute();
                return $this->router->assemble(array(), array('name' => $route));
                break;
            default:
                return $this->router->assemble(array(), array('name' => 'zfcuser'));
        }
    }
}
