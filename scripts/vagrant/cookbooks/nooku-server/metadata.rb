name             "nooku-server"
maintainer       "Timble CVBA and Contributors"
maintainer_email "info@timble.net"
license          "Apache 2.0"
description      "Installs/Configures Nooku Server"
long_description IO.read(File.join(File.dirname(__FILE__), "README.md"))
version          "0.1.0"

depends "mysql"
depends "nginx"
depends "php"