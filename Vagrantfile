# -*- mode: ruby -*-
# vi: set ft=ruby :

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = "ubuntu/trusty64"

  # Base box gives 384MB memory - give it a bit more than that.
  config.vm.provider "virtualbox" do |v|
    v.customize ["modifyvm", :id, "--memory", "768"]
  end

  # Forward localhost:8081 on host to port 80 on guest
  config.vm.network "forwarded_port", guest: 80, host: 8081
  
  config.vm.provision :chef_solo do |chef|
    chef.cookbooks_path = "cookbooks"

    chef.add_recipe "app_trusty::apt_basics"
    chef.add_recipe "app_trusty::php_fpm"
    chef.add_recipe "app_trusty::nginx"
    chef.add_recipe "app_trusty::profile"

  end
end
