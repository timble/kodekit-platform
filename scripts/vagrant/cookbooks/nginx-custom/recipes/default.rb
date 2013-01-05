#
# Author:: Gergo Erdosi (<gergo@timble.net>)
# Cookbook Name:: nginx-custom
# Recipe:: default
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

node['nginx']['site']['list'].each do |site|
  bash "create_symlink_#{site.gsub('-', '_')}" do
    user 'root'
    group 'root'
    cwd "#{node['nginx']['site']['dir']}/#{site}"
    code <<-EOH
      [ -L public ] || ln -s source/code public
    EOH
  end

  template "#{node['nginx']['dir']}/sites-available/#{site}" do
    source "sites/#{site}.erb"
    owner 'root'
    group 'root'
    mode 00644
    notifies :reload, 'service[nginx]'
  end

  nginx_site site do
    enable true
  end
end