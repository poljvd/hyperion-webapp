hyperion-webapp
===============

Colorpicker webapp for Hyperion (https://github.com/tvdzwan/hyperion/wiki/)

The color picker is based on the Boblight version created by Nadnerb (http://blog.nadnerb.co.uk/)
The color picker was re-factored by Bradley Cornford (http://bradleycornford.co.uk/)

## Installation
Install dependencies:
```
sudo apt-get update
sudo apt-get install nginx php5-fpm
```

Configure the webserver:
```
sudo nano /etc/nginx/sites-enabled/default
```

Replace the contents of the file with:
```
server {
        listen 8888;
        root /home/pi/www;
        index index.php index.html index.htm;

        location ~ \.php$ {
                fastcgi_pass unix:/var/run/php5-fpm.sock;
                fastcgi_index index.php;
                include fastcgi_params;
        }
}
```

```
sudo nano /etc/nginx/fastcgi_params
```

Add the following contents to the file:
```
fastcgi_param    SCRIPT_FILENAME    $document_root$fastcgi_script_name;
fastcgi_param    PATH_INFO          $fastcgi_path_info;
fastcgi_param    PATH_TRANSLATED    $document_root$fastcgi_path_info;

```

Download the contents of this repository to /home/pi/www. You can either download a zip from github or clone the repository using (you need to have git installed for this):
```
git clone https://github.com/poljvd/hyperion-webapp.git /home/pi/www
```


Finally restart the webserver:
```
sudo service nginx restart
sudo service php5-fpm restart
```

The site should now be available on http://ip-of-host:8888

*Required if you are running Raspbian / Debian:*

Update the visudo list:
```
pkexec visudo
```

In the section "# Cmnd alias specification" add:
```
Cmnd_Alias WEBDAEMONS = /etc/init.d/hyperion
```

In the section "#includedir /etc/sudoers.d"  add:
```
www-data ALL = (root) NOPASSWD: WEBDAEMONS
```

Open the configuration file:
```
sudo nano /home/pi/www/conf/Configuration.php
```

Update the configuration for "serverController"
```
'serverController' => 'sudo /etc/init.d',
```

## FAQ
- I got a 403 error when loading the webpage.  
_Check if index.php exists in the /home/pi/www folder and check the permissions on the file (change with chmod -R 775 /home/pi/www)_
- I got an error when clicking a button.
_Check the owner of the index.php file in /home/pi/www (change with chown -R pi:www-data /home/pi/www)_

## License
This software is released under the GPL license
