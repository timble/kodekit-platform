README
======

What is Nooku?
--------------

Nooku is an open-source web application framework. It uses a component based architecture. Written in PHP 5.3, HTML5, CSS3 and Javascript, and made by passionate developers from all over the world. 

By doing most of the repetitive work for you, Nooku greatly reduces the time to develop all kinds of websites and web applications, allowing you to focus on the things that matter : features.


Requirements
------------

Nooku is only supported on PHP 5.3.3 and up running MySQL v5.x, or similar. 


Installation
------------

* Clone the git repository at [git://git.assembla.com/nooku-framework.git](git://git.assembla.com/nooku-framework.git)
* Switch to the [develop](https://nooku.assembla.com/code/nooku-framework/git/nodes/develop) branch
* Run [Composer](http://getcomposer.org/) to install dependencies `composer install` (see Install Composer below)
* Create a database, eg nooku-server
* Execute the [schema.sql](develop/code/install/sql/schema.sql), [data.sql](develop/code/install/sql/data.sqll) and [sample.sql](develop/code/install/sql/sample.sql) scripts in your nooku-server database
* Rename [config/config.php-dist](code/config/config.php-dist) to config/config.php and fill in your database details

For more information about Git, please see the official website: [http://www.git-scm.org](http://www.git-scm.org)

### Install Composer

Install [Composer](http://getcomposer.org/) and move it to a global location so you can use it in other projects to.

    $ curl -sS https://getcomposer.org/installer | php
    $ mv composer.phar /usr/local/bin/composer

Make sure to restart your Terminal before running `composer install` from within your cloned nooku-framework directory.

License
-------

The files in this archive are released under the GPLv3 license. You can find a copy of this license in [LICENSE](develop/LICENSE.md).

