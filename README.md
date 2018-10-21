# Steps to install
------------------

1) install php on machine <br />
  (https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu-16-04)
2) clone repository
3) `cd` to automation_php_behat directory
4) update dependency `php composer.phar update`

## Behat command line
---------------------

1) run all feature with all scenarios from project <br />
\automation_php_behat\bin>behat -p default --config D:\Personal\Projects\automation_php_behat\behat.yml

2) you can run also a single scenario using a tag <br />
\automation_php_behat\bin>behat -p default --tags add --config D:\Personal\Projects\automation_php_behat\behat.yml <br />

--config   path to behat.yml config file <br />
--tags     scenario tag <br />
--p        profile

## Feature file
---------------

\automation_php_behat\features\api.feature

## Report file
---------------

\automation_php_behat\report\index.html
