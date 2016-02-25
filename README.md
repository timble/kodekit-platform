# Nooku Platform

[ ![Codacy Badge](https://www.codacy.com/project/badge/f517d6945b4f463caf734b08bb43b0b9) ](https://www.codacy.com/app/timble/nooku-platform)

## What is Nooku Platform ?

Nooku Platform is an open-source web application platform. Developed and maintained by [Timble](http://timble.net) with
 the help of passionate developers from all over the world.

Nooku Platform uses a [component based architecture](http://en.wikipedia.org/wiki/Component-based_software_engineering)
and includes everything needed to create components according to the [Hierarchical Model-View-Contoller][HMVC] (HMVC) pattern.

By doing most of the repetitive work for you, Nooku Platform greatly reduces the time to develop all kinds of websites
and web applications, allowing you to focus on the things that matter : business logic and the user experience.

## Why Nooku Platform ?

Nooku Platform was [born at the end of 2010][nooku-birth] with the goal to create a [leaner][nooku-leaner] and [meaner][nooku-meaner]
multi-site and multi-lingual distro of [Joomla](http://www.joomla.org) 1.5. Originally named Nooku Server and renamed to
Nooku Platform in 2013.

Compatibility with Joomla was dropped soon after Joomla 1.5 reached end of life period in 2012. Since then, development continued in an effort to completely refactor the code base to a modern and lean architecture using well-established coding standards and best practices.

At the moment Nooku Platform is at version 0.9 and work is ongoing to remove the last bits of Joomla legacy code. Once
this work is completed an 1.0 alpha release will be made available.

## Experimental Only

The platform includes the newest of the new Nooku features. Be forewarned: it's designed for developers and early adopters,
and can sometimes break down completely. Not for the faint of heart!

The platform uses a rapid agile development cycle with almost daily changes. Upgrades between versions are not provided.
If you want to use the platform it's best to choose one of the 0.x releases and stick with it.

## Requirements

Nooku Platform is only supported on PHP 5.3.3 and up running MySQL v5.x, or similar and requires Nginx web server to run.


## Installation

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

* Run `vagrant up` in the repository directory. This will download the [nooku/box](http://github.com/nooku/nooku-server)
and setup your development environment.

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
* [Varnish](https://www.varnish-cache.org/) cache runs in front of the Nooku application but passes through all requests by default. To enable Varnish, SSH into the box and execute `varnish enable`. Purge the cache using `varnish purge`, and disable again with `varnish disable`.
* You can tweak the virtual machine configuration by copying the `box.config.yaml-dist` file to `box.config.yaml` and edit its contents. If you want to run multiple boxes for multiple projects, you should change the name of the box through this file to avoid naming conflicts in Virtualbox.

## Contributing

Nooku Platform is an open source, community-driven project. Contributions are welcome from everyone. 
We have [contributing guidelines](CONTRIBUTING.md) to help you get started.

## Contributors

See the list of [contributors](https://github.com/nooku/nooku-platform/contributors).

## License 

Nooku Platform is free and open-source software licensed under the [GPLv3 license](LICENSE.txt).

## Community

Keep track of development and community news.

* Follow [@joomlatoolsdev on Twitter](https://twitter.com/joomlatoolsdev)
* Join [joomlatools/dev on Gitter](http://gitter.im/joomlatools/dev)
* Read the [Joomlatools Developer Blog](https://www.joomlatools.com/developer/blog/)
* Subscribe to the [Joomlatools Developer Newsletter](https://www.joomlatools.com/developer/newsletter/)

[HMVC]: http://en.wikipedia.org/wiki/Hierarchical_model%E2%80%93view%E2%80%93controller
[boilerplate]: http://en.wikipedia.org/wiki/Boilerplate_code

[nooku-platform]: https://github.com/nooku/nooku-platform
[nooku-framework]: https://github.com/nooku/nooku-framework
[nooku-birth]: http://www.nooku.org/blog/2010/12/nooku-server-joomla-on-steroids/
[nooku-leaner]: http://www.nooku.org/blog/2011/01/creating-a-diet-for-nooku-server/
[nooku-meaner]: http://www.nooku.org/blog/2011/01/nooku-server-loses-40-weight/

[gitflow-model]: http://nvie.com/posts/a-successful-git-branching-model/
[gitflow-extensions]: https://github.com/nvie/gitflow
