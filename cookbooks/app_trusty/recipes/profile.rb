# Throw a .profile into the vagrant user's homedir
cookbook_file "profile" do
  path "/home/vagrant/.profile"
  mode 0644
  owner "vagrant"
  group "vagrant"
end

