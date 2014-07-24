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

* Clone this repository at [git://git.assembla.com/nooku-framework.git](git://git.assembla.com/nooku-framework.git)

```
    $ git clone git://git.assembla.com/nooku-framework.git
```

* Switch to the [develop](https://nooku.assembla.com/code/nooku-framework/git/nodes/develop) branch

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

