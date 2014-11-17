VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = "f500/debian-wheezy64"

  config.vm.network "private_network", ip: "192.168.33.10"

  config.vm.provision :shell, path: "provisioning/upgrade.sh"
  config.vm.provision :shell, path: "provisioning/php.sh"
  config.vm.provision :shell, path: "provisioning/mysql.sh"
  config.vm.provision :shell, path: "provisioning/mysql-database.sh"
end
