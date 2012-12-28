#
# Author:: Gergo Erdosi (<gergo@timble.net>)
# Cookbook Name:: php
# Recipe:: package
#
# Copyright 2012, Timble CVBA and Contributors.
#
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#
#     http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.
#

# Install base and additional PHP packages
pkgs = %w( php5-cgi php5 php5-dev php5-cli php-pear )
pkgs += %w( php-apc php5-curl php5-gd php5-imagick php5-mcrypt php5-mysql )

pkgs.each do |pkg|
  package pkg do
    action :install
  end
end

template "#{node['php']['conf_dir']}/php.ini" do
  source "php.ini.erb"
  owner "root"
  group "root"
  mode "0644"
end

# Install PHP-FPM
package "php5-fpm" do
  action :install
end

service "php5-fpm" do
  action [:enable, :start]
end

template "#{node['php']['fpm_conf_dir']}/php.ini" do
  source "php.ini.erb"
  owner "root"
  group "root"
  mode "0644"
end

service "php5-fpm" do
  action :restart
end