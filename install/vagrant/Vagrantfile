Vagrant.configure("2") do |config|
  config.vm.box = "precise64"
  config.vm.box_url = "http://files.vagrantup.com/precise64.box"
  config.vm.hostname = "nooku.dev"

  config.vm.network :private_network, ip: "33.33.33.63"
  config.ssh.forward_agent = true

  config.vm.usable_port_range = (2200..2250)
  config.vm.provider :virtualbox do |v|
    v.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
    v.customize ["modifyvm", :id, "--memory", 1024]
    v.customize ["modifyvm", :id, "--name", "nooku-box-build"]
  end

  config.vm.provision :shell, :inline =>
     "if [[ ! -f /apt-get-run ]]; then sudo apt-get update && sudo touch /apt-get-run; fi"

  config.vm.provision :shell, :inline => 'echo -e "mysql_root_password=root
controluser_password=awesome" > /etc/phpmyadmin.facts;'

  config.vm.provision :puppet do |puppet|
    puppet.manifests_path = "puppet/manifests"
    puppet.module_path = "puppet/modules"
    puppet.options = ['--verbose']#, '--debug']
  end
end
