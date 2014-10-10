# ==========
# Install and configure nginx to serve our app/site


nginx_site_name = 'app'

# Install nginx
execute "add nginx repository" do
  command "add-apt-repository ppa:nginx/development"
  action :run
end

execute "update repositories" do
  command "apt-get update -y"
  action :run
end

package "nginx" do
  action :upgrade # upgrade or install
end


service "nginx" do
  supports :start => true, :stop => true, :restart => true
  action :nothing
end

# Disable the default site
execute "disable nginx default site" do
  command "rm /etc/nginx/sites-enabled/default"
  action :run
  only_if "test -f /etc/nginx/sites-enabled/default"
  notifies :restart, "service[nginx]"
end

# Define the project site
cookbook_file "nginx-app-site" do
  path "/etc/nginx/sites-available/app"
  owner  'root'
  group  'root'
  mode 0644 # -rw-r--r--
  action :create
  notifies :reload, 'service[nginx]'
end

# Enable the project site
execute "enable nginx project site" do
  command "ln -s /etc/nginx/sites-available/#{nginx_site_name} /etc/nginx/sites-enabled/#{nginx_site_name}"
  action :run
  not_if "test -f /etc/nginx/sites-enabled/#{nginx_site_name}"
  notifies :restart, "service[nginx]"
end
