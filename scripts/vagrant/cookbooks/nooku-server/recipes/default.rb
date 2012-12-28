#
# Author:: Gergo Erdosi (<gergo@timble.net>)
# Cookbook Name:: nooku-server
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

require "mysql"

# Create configuration file
template "#{node['nooku-server']['dir']}/configuration.php" do
  source "configuration.erb"
  mode 00644
  action :create_if_missing
end

# Create database
ruby_block "db_create" do
  block do
    begin
      dbh = Mysql.new("localhost", "root", node['nooku-server']['db']['password'])
      dbh.query("CREATE DATABASE `#{node['nooku-server']['db']['database']}`")
    ensure
      dbh.close if dbh
    end
  end
  action :create
end

# Run database installation files
ruby_block "db_install" do
  block do
    begin
      dbh = Mysql.new("localhost", "root", node['nooku-server']['db']['password'], node['nooku-server']['db']['database'])
      dbh.set_server_option Mysql::OPTION_MULTI_STATEMENTS_ON

      %w( install_schema install_data sample_data ).each do |file|
        content = File.read("#{node['nooku-server']['dir']}/installation/sql/#{file}.sql")
        content = content.gsub("#__", node['nooku-server']['db']['prefix'])

        dbh.query(content)
        while dbh.next_result
          dbh.store_result rescue nil
        end
      end
    ensure
      dbh.close if dbh
    end
  end
  action :create
end