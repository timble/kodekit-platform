#
# Author:: Gergo Erdosi (<gergo@timble.net>)
# Cookbook Name:: nginx
# Recipe:: nooku-server
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

template "#{node['nginx']['dir']}/sites-available/#{node['nginx']['nooku-server']['site_name']}" do
  source "sites/nooku-server.erb"
  owner "root"
  group "root"
  mode 00644
  notifies :reload, "service[nginx]"
end

nginx_site node['nginx']['nooku-server']['site_name'] do
  enable true
end