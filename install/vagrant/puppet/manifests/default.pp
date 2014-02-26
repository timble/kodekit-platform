group { 'puppet': ensure => present }
Exec { path => [ '/bin/', '/sbin/', '/usr/bin/', '/usr/sbin/' ] }
File { owner => 0, group => 0, mode => 0644 }

class {'apt':
  always_apt_update => true,
}

Class['::apt::update'] -> Package <|
    title != 'python-software-properties'
and title != 'software-properties-common'
|>

apt::key { '4F4EA0AAE5267A6C': }

apt::ppa { 'ppa:ondrej/php5-oldstable':
  require => Apt::Key['4F4EA0AAE5267A6C']
}

apt::source { 'nginx':
  location => 'http://ppa.launchpad.net/sandyd/nginx-current-pagespeed/ubuntu',
  key      => '5626E4A6F2F46C93'
}

apt::source { 'mariadb':
  location => 'http://mariadb.cu.be//repo/5.5/ubuntu',
  key      => 'cbcb082a1bb943db',
}
    
class { 'puphpet::dotfiles': }

package { [
    'build-essential',
    'vim',
    'curl',
    'git-core',
    'unzip'
  ]:
  ensure  => 'installed',
}

file { '/var/cache/nginx':
  ensure => directory,
  mode   => '0644',
  group  => 'root',
  owner  => 'root'
}

file { '/var/cache/nginx/pagespeed':
  ensure => directory,
  mode   => '0644',
  group  => 'root',
  owner  => 'root',
  require => File['/var/cache/nginx']
}

class { 'nginx':
  require => File['/var/cache/nginx/pagespeed']
}

file { "${nginx::config::nx_temp_dir}/nginx.d/nooku-001":
  ensure  => file,
  content => template('nginx/vhost/nooku.erb'),
  notify  => Class['nginx::service'],
}

class { 'php':
  package             => 'php5-fpm',
  service             => 'php5-fpm',
  service_autorestart => false,
  config_file         => '/etc/php5/fpm/php.ini',
  module_prefix       => ''
}

php::module {
  [
    'php5-mysql',
    'php5-cli',
    'php5-curl',
    'php5-gd',
    'php5-intl',
    'php5-mcrypt',
    'php-apc',
  ]:
  service => 'php5-fpm',
}

service { 'php5-fpm':
  ensure     => running,
  enable     => true,
  hasrestart => true,
  hasstatus  => true,
  require    => Package['php5-fpm'],
}

class { 'php::devel':
  require => Class['php'],
}


class { 'xdebug':
  service => 'nginx',
}

# Install Xdebug but disable it by default since it's super slow
file { '/etc/php5/cli/conf.d/20-xdebug.ini':
  ensure => absent,
  notify  => Service['php5-fpm'],
  require => [Class['nginx'], Class['php']],
}

class { 'composer':
  require => Package['php5-fpm', 'curl'],
}

puphpet::ini { 'php':
  value   => [
    'date.timezone = "UTC"'
  ],
  ini     => '/etc/php5/conf.d/zzz_php.ini',
  notify  => Service['php5-fpm'],
  require => Class['php'],
}

puphpet::ini { 'custom':
  value   => [
    'sendmail_path = /usr/bin/env catchmail -fnoreply@example.com',
    'display_errors = On',
    'error_reporting = E_ALL & ~E_STRICT',
    'upload_max_filesize = "256M"',
    'post_max_size = "256M"',
    'memory_limit = "128M"'
  ],
  ini     => '/etc/php5/conf.d/zzz_custom.ini',
  notify  => Service['php5-fpm'],
  require => Class['php'],
}

puphpet::ini { 'xdebug':
  value   => [
  'xdebug.remote_autostart = 0',
  'xdebug.remote_connect_back = 1',
  'xdebug.remote_enable = 1',
  'xdebug.remote_handler = "dbgp"',
  'xdebug.remote_port = 9000',
  'xdebug.remote_host = "33.33.33.1"',
  'xdebug.show_local_vars = 0',
  'xdebug.profiler_enable = 0',
  'xdebug.profiler_enable_trigger = 1',
  'xdebug.max_nesting_level = 1000'
  ],
  ini     => '/etc/php5/conf.d/zzz_xdebug.ini',
  notify  => Service['php5-fpm'],
  require => Class['php'],
}

class { 'mysql::server':
  config_hash   => {
    'root_password' => 'root',
    'bind_address' => false,
  },
  package_name => 'mariadb-server'
}

exec { 'grant-all-to-root':
  command     => "mysql --user='root' --password='root' --execute=\"GRANT ALL ON *.* TO 'root'@'%' IDENTIFIED BY 'root' WITH GRANT OPTION;\"",
  require => Class['phpmyadmin']
}

class { 'phpmyadmin':
  require => [Class['mysql::server'], Class['mysql::config'], Class['php']],
}

nginx::resource::vhost { 'phpmyadmin.nooku.dev':
  ensure      => present,
  server_name => ['phpmyadmin.nooku.dev'],
  listen_port => 80,
  index_files => ['index.php'],
  www_root    => '/usr/share/phpmyadmin',
  try_files   => ['$uri', '$uri/', '/index.php?$args'],
  require     => Class['phpmyadmin'],
}

nginx::resource::location { "phpmyadmin-php":
  ensure              => 'present',
  vhost               => 'phpmyadmin.nooku.dev',
  location            => '~ \.php$',
  proxy               => undef,
  try_files           => ['$uri', '$uri/', '/index.php?$args'],
  www_root            => '/usr/share/phpmyadmin',
  location_cfg_append => {
    'fastcgi_split_path_info' => '^(.+\.php)(/.+)$',
    'fastcgi_param'           => 'PATH_INFO $fastcgi_path_info',
    'fastcgi_param '          => 'PATH_TRANSLATED $document_root$fastcgi_path_info',
    'fastcgi_param  '         => 'SCRIPT_FILENAME $document_root$fastcgi_script_name',
    'fastcgi_pass'            => 'unix:/var/run/php5-fpm.sock',
    'fastcgi_index'           => 'index.php',
    'include'                 => 'fastcgi_params'
  },
  notify              => Class['nginx::service'],
  require             => Nginx::Resource::Vhost['phpmyadmin.nooku.dev'],
}

class { 'mailcatcher': }

class { 'less': }

class { 'uglifyjs': }

class { 'scripts': }

class { 'webgrind':
  require => Package['unzip'],
}

nginx::resource::vhost { 'webgrind.nooku.dev':
  ensure      => present,
  server_name => ['webgrind.nooku.dev'],
  listen_port => 80,
  index_files => ['index.php'],
  www_root    => '/usr/share/webgrind',
  try_files   => ['$uri', '$uri/', '/index.php?$args'],
  require     => Class['webgrind'],
}

nginx::resource::location { "webgrind-php":
  ensure              => 'present',
  vhost               => 'webgrind.nooku.dev',
  location            => '~ \.php$',
  proxy               => undef,
  try_files           => ['$uri', '$uri/', '/index.php?$args'],
  www_root            => '/usr/share/webgrind',
  location_cfg_append => {
  'fastcgi_split_path_info' => '^(.+\.php)(/.+)$',
  'fastcgi_param'           => 'PATH_INFO $fastcgi_path_info',
  'fastcgi_param '          => 'PATH_TRANSLATED $document_root$fastcgi_path_info',
  'fastcgi_param  '         => 'SCRIPT_FILENAME $document_root$fastcgi_script_name',
  'fastcgi_pass'            => 'unix:/var/run/php5-fpm.sock',
  'fastcgi_index'           => 'index.php',
  'include'                 => 'fastcgi_params'
  },
  notify              => Class['nginx::service'],
  require             => Nginx::Resource::Vhost['webgrind.nooku.dev'],
}
