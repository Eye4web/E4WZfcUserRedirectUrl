<?php

namespace E4W\ZfcUser\RedirectUrl\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    /**
     * Array of urls which is allowed redirecting to.
     * @var array
     */
    protected $whitelist;

    /**
     * @param array $whitelist
     */
    public function setWhitelist($whitelist)
    {
        $this->whitelist = $whitelist;
    }

    /**
     * @return array
     */
    public function getWhitelist()
    {
        return $this->whitelist;
    }
    
}
