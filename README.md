E4WZfcUserRedirectUrl
=======

Introduction
------------
This module changes the redirect behavior of ZfcUser to use url's instead of routes.
The redirect URL is matched against a whitelist.
Localhost and current domain are whitelisted by default.

Installation
------------
#### With composer

1. Add this project composer.json:

    ```json
    "require": {
        "eye4web/e4w-zfc-user-redirect-url": "dev-master"
    }
    ```

2. Now tell composer to download the module by running the command:

    ```bash
    $ php composer.phar update
    ```

3. Enable it in your `application.config.php` file.

    ```php
    <?php
    return array(
        'modules' => array(
            // ...
            'E4W\ZfcUser\RedirectUrl'
        ),
        // ...
    );
    ```

4. Copy config/e4w.zfcuser.redirecturl.global.php.dist to config/autoload/e4w.zfcuser.redirecturl.global.php and add
whitelisted domains.
