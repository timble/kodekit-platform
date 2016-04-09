# Kodekit Platform

[ ![Codacy Badge](https://www.codacy.com/project/badge/f517d6945b4f463caf734b08bb43b0b9) ](https://www.codacy.com/app/timble/nooku-platform)

## What is Kodekit Platform ?

Kodekit Platform is an open-source web application platform. Developed and maintained by [Timble](http://timble.net) with
 the help of passionate developers from all over the world.

Kodekit Platform uses a [component based architecture](http://en.wikipedia.org/wiki/Component-based_software_engineering)
and includes everything needed to create components according to the [Hierarchical Model-View-Contoller][HMVC] (HMVC) pattern.

By doing most of the repetitive work for you, Kodekit Platform greatly reduces the time to develop all kinds of websites
and web applications, allowing you to focus on the things that matter : business logic and the user experience.

## Why Kodekit Platform ?

Kodekit Platform was born at the end of 2010 with the goal to create a leaner and meaner multi-site and multi-lingual 
distro of [Joomla](http://www.joomla.org) 1.5. Compatibility with Joomla was dropped soon after Joomla 1.5 reached end 
of life period in 2012. 

Since then, development continued in an effort to completely refactor the code base to a modern and lean architecture
 using well-established coding standards and best practices.

At the moment Kodekit Platform is at version 0.9 and work is ongoing to remove the last bits of Joomla legacy code. Once
this work is completed an 1.0 alpha release will be made available.

## Experimental Only

The platform includes the newest of the new features. Be forewarned: it's designed for developers and early adopters,
and can sometimes break down completely. Not for the faint of heart!

The platform uses a rapid agile development cycle with almost daily changes. Upgrades between versions are not provided.
If you want to use the platform it's best to choose one of the 0.x releases and stick with it.

## Requirements

Kodekit Platform is only supported on PHP 5.5 and up running MySQL v5.x, or similar and requires Nginx web server to run.


## Installation

* Clone this repository at [https://github.com/timble/kodekit-platform](https://github.com/timble/kodekit-platform)

```
    $ git clone https://github.com/timble/kodekit-platform
```

```
    $ git checkout develop
```

* Install [VirtualBox](http://www.virtualbox.org/)

* Install [Vagrant](http://downloads.vagrantup.com/)

* Run `vagrant up` in the repository directory. This will download the [kodekit/box](http://github.com/timble/kodebox)
and setup your development environment.

```
    $ vagrant up
```

* Add the following line into /etc/hosts

```
    33.33.33.63 kodekit.box webgrind.kodekit.box phpmyadmin.kodekit.box
```

* You can now reach the platform at [kodekit.box](http://kodekit.box/)
* You can login into the admin application at [kodekit.box/administrator](http://kodekit.box/) using the username and password `admin@localhost.home`/`admin`.
* You can access the APC dashboard at [kodekit.box/apc](http://kodekit.box/apc) and phpinfo() at [kodekit.box/phpinfo](http://kodekit.box/phpinfo).
* [Varnish](https://www.varnish-cache.org/) cache runs in front of the application but passes through all requests by default. 
To enable Varnish, SSH into the box and execute `varnish enable`. Purge the cache using `varnish purge`, and disable 
again with `varnish disable`.
* You can tweak the virtual machine configuration by copying the `box.config.yaml-dist` file to `box.config.yaml` and 
edit its contents. If you want to run multiple boxes for multiple projects, you should change the name of the box 
through this file to avoid naming conflicts in Virtualbox.

## Contributing

Kodekit Platform is an open source, community-driven project. Contributions are welcome from everyone. 
We have [contributing guidelines](CONTRIBUTING.md) to help you get started.

## Contributors

See the list of [contributors](https://github.com/timble/kodekit-platform/contributors).

## License 

Kodekit Platform is free and open-source software licensed under the [MPLv2 license](LICENSE.txt).

## Community

Keep track of development and community news.

* Follow [@timbleHQ on Twitter](https://twitter.com/timbleHQ)
* Join [timble/kodekit on Gitter](http://gitter.im/timble/kodekit)
* Read the [Timble Blog](https://www.timble.net/blog/)
* Subscribe to the [Timble Newsletter](https://www.timble.net/newsletter/)

[HMVC]: http://en.wikipedia.org/wiki/Hierarchical_model%E2%80%93view%E2%80%93controller
[boilerplate]: http://en.wikipedia.org/wiki/Boilerplate_code