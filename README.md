# Donkeybridges

Prerequisites:

* [Vagrant](http://www.vagrantup.com/)
* [Virtualbox](https://www.virtualbox.org/)

First, ensure that you have the "ubuntu/trusty64" base box available locally:

```
vagrant box list
```

If "ubuntu/trusty64" is not listed, do:

```
vagrant box add ubuntu/trusty64
```

Then:

```
vagrant up
```

After vagrant is done, the site should be available at
[http://localhost:8081/](http://localhost:8081/)

