Nooku Platform
==============

What is Nooku Platform ?
-----------------------

Nooku Platform is an open-source web application platform. Developed and maintained by [Timble](http://timble.net) with the help of passionate developers from all over the world. 

Nooku Platform uses a [component based architecture](http://en.wikipedia.org/wiki/Component-based_software_engineering) and includes everything needed to create components according to the [Hierarchical Model-View-Contoller](http://en.wikipedia.org/wiki/Hierarchical_model%E2%80%93view%E2%80%93controller) (HMVC) pattern.

By doing most of the repetitive work for you, Nooku Platform greatly reduces the time to develop all kinds of websites and web applications, allowing you to focus on the things that matter : business logic and the user experience.

Development
-----------

The platform uses a rapid agile development cycle with almost daily changes. Until the platform hits a 1.0 stable release upgrades between versions are not provided. If you want to use the platform it's best to choose one of the 0.x releases and stick with it. 

Requirements
------------

Nooku Platform is only supported on PHP 5.3.3 and up running MySQL v5.x, or similar and requires Nginx web server to run.


Installation
------------

* Clone this repository at [https://github.com/nooku/nooku-platform](https://github.com/nooku/nooku-platform)

```
    $ git clone https://github.com/nooku/nooku-platform
```

* Switch to the [develop](https://github.com/nooku/nooku-platform/tree/develop) branch

```
    $ git checkout develop
```

* Install [VirtualBox](http://www.virtualbox.org/)

* Install [Vagrant](http://downloads.vagrantup.com/)

* Run `vagrant up` in the repository directory. This will download the [nooku/box](http://github.com/nooku/nooku-server) and setup your development environment.

```
    $ vagrant up
```

* Add the following line into /etc/hosts

```
    33.33.33.63 nooku.dev webgrind.nooku.dev phpmyadmin.nooku.dev
```

* You can now reach Nooku at [nooku.dev](http://nooku.dev/)
* You can login into the admin application at [nooku.dev/administrator](http://nooku.dev/) using the username and password `admin@localhost.home`/`admin`.
* You can access the APC dashboard at [nooku.dev/apc](http://nooku.dev/apc) and phpinfo() at [nooku.dev/phpinfo](http://nooku.dev/phpinfo).

License
-------

The files in this archive are released under the GPLv3 license. You can find a copy of this license in [LICENSE](develop/LICENSE.md).

