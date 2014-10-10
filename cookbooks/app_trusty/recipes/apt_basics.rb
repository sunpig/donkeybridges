# =====================================================
# Basic packages, used throughout the cookbook

execute "update repositories" do
  command "apt-get update -y"
  action :run
end

apt_packages = [
  "autoconf",
  "automake",
  "bison",
  "build-essential",
  "curl",
  "gcc",
  "git-core",
  "libc6-dev",
  "libreadline6",
  "libreadline6-dev",
  "libssl-dev",
  "libsqlite3-dev",
  "libtool",
  "libxml2-dev",
  "libxslt-dev",
  "libyaml-dev",
  "ncurses-dev",
  "openssl",
  "pkg-config",
  "sqlite3",
  "zlib1g",
  "zlib1g-dev",
  "zip",

  # The script add-apt-repository script is provided by the
  # python-software-properties package
  # http://ubuntuforums.org/showthread.php?t=1320536
  # https://help.ubuntu.com/community/add-apt-repository
  "python-software-properties"
]
apt_packages.each do |pkg|
  apt_package pkg do
    action :upgrade # install or upgrade
  end
end
