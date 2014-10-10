# ==========
# Install and configure php-fpm
#


# Install php-fpm
apt_packages = [
  "php5-cli",
  "php5-mysql",
  "php5-memcache",
  "php5-fpm"
]
apt_packages.each do |pkg|
  apt_package pkg do
    action :upgrade # install or upgrade
  end
end

service "php5-fpm" do
  supports :start => true, :stop => true, :restart => true
  action :nothing
end

# Remove original www.conf
file "/etc/php5/fpm/pool.d/www.conf" do
  action :delete
  only_if "test -f /etc/php5/fpm/pool.d/www.conf"
end

# Create new configuration file
cookbook_file "php-fpm-www-pool.conf" do
  path "/etc/php5/fpm/pool.d/www.conf"
  mode 0644 # -rw-r--r--
  action :create
  notifies :restart, "service[php5-fpm]"
end

