E4WZfcUserRedirectUrl
=======

Introduction
------------

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

3. Enable it in your `application.config.php`file.

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
