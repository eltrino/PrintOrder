Installation Guide:

There are two possible variants of installation
1.  Installation using composer
    1.1 Install composer as described on https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx
    1.2 Create composer.json file in your root project directory.
        For example file "/var/www/vhosts/magento/composer.json":

        For Magento 1.x version:

        {
            "require": {
                "magento-hackathon/magento-composer-installer": "dev-master",
                "eltrino/compatibility": "dev-master",
                "eltrino/po": "2.0"
            },
            "repositories": [
                {
                    "type": "vcs",
                    "url": "http://code.eltrino.com/ee.po"
                },
                {
                    "type": "vcs",
                    "url": "https://bitbucket.org/eltrino/compatibility.git"
                },
                {
                    "type": "vcs",
                    "url": "https://bitbucket.org/eltrino/magento-hackaton-installer.git"
                }
            ],
            "extra": {
                "magento-root-dir": "./"
            }
        }

        For Magento 2.x version:

        {
            "require": {
                "magento-hackathon/magento-composer-installer": "dev-master",
                "eltrino/po": "2.0"
            },
            "repositories": [
                {
                    "type": "vcs",
                    "url": "http://code.eltrino.com/ee.po"
                },
                {
                    "type": "vcs",
                    "url": "https://bitbucket.org/eltrino/magento-hackaton-installer.git"
                }
            ],
            "extra": {
                "magento-root-dir": "./"
            }
        }

    1.3 Run from command line inside of your project document root:
        composer install
    1.4 Flush the Magento caches

2. Installation using archive package.
    2.1 Unpack the archive to your temporary directory
    2.2

        For Magento 1.x:
            2.2.1 Copy the directory (from temporary) "app/code/Eltrino" to your "community app" project directory,
                  for example to "/var/www/vhosts/magento/app/code/comunity"
            2.2.2 Download the last compatibility module from
                      https://bitbucket.org/eltrino/compatibility/get/master.zip
                  and extract to your temporary directory
            2.2.3 Copy "app" directory from extracting arhive to your project root directory
                  for example to "/var/www/vhosts/magento"


        For Magento 2.x:
            2.2.1 Copy the directory "app" from archive to your project root directory,
                  for example to "/var/www/vhosts/magento"

    2.3 Flush the Magento caches.