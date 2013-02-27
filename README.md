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

Checking out a working copy is necessary to install Nooku. To clone the git repository, use the following URL: [git://git.assembla.com/nooku-framework.git](git://git.assembla.com/nooku-framework.git).

For more information about Git, please see the official website: [http://www.git-scm.org](http://www.git-scm.org)

#### The 12.1 release

The 12.1 release can be found in the [release/12.1](https://nooku.assembla.com/code/nooku-framework/git/nodes/release/12.1) branch and can be installed using the web installer. To install, follow the following steps :

* Checkout the [release/12.1](https://nooku.assembla.com/code/nooku-framework/git/nodes/release/12.1) branch using Git.
* Point your browser to [http://mysite.com/code/installation](http://mysite.com/code/installation) to start the installation process.

You can either check out the repos root or you can checout the /code folder which contains the actual code.

#### The 13.1 release

The 13.1 release can befound in the the [develop](https://nooku.assembla.com/code/nooku-framework/git/nodes/develop) branch and for the moment can only be installed manually. To install follow the following steps :

*  Checkout the [develop](https://nooku.assembla.com/code/nooku-framework/git/nodes/develop) branch using Git.
*  Create a database, eg nooku-server.
* Execute the [schema.sql](develop/code/install/mysql/schema.sql), [data.sql](develop/code/install/mysql/data.sqll) and [sample.sql](develop/code/install/mysql/sample.sql) scripts in your nooku-server database. Make sure to replace the #__ suffix in each file with a database table name prefix of your choosing.
* Rename [config/config.php-dist](code/config/config.php-dist) to config/config.php and fill in your database details.

License
-------

The files in this archive are released under the GPLv3 license. You can find a copy of this license in [LICENSE](develop/LICENSE.md).

