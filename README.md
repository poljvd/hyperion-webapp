hyperion-webapp
===============

Colorpicker webapp for Hyperion (https://github.com/tvdzwan/hyperion/wiki/)

The color picker is based on the Boblight version created by Nadnerb (http://blog.nadnerb.co.uk/)

## Installation
Install dependencies:
```
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

Download the contents of this repository to /home/pi/www and restart the webserver:
```
sudo service nginx restart
sudo service php5-fpm restart
```

The site should now be available on http://ip-of-host:8888

## License
This software is released un the GPL license
