Vagrant.configure("2") do |config|
  config.vm.box = "nooku/box"
  config.vm.box_version = "~> 3.1.0"

  config.vm.network :private_network, ip: "33.33.33.63"
  config.ssh.forward_agent = true
end
