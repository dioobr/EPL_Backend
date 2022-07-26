# EPL BACKEND
##### EPL (England Premier League)

This backend project was developed by [Diogo Braga](mailto:the@dioobr.com "Diogo Braga") for test purposes.

### Online Demo

If you just want to see it working, you can check in the address https://api.epl.dioobr.com.
The frontend is also available in the address https://epl.dioobr.com.

### Requirements

This code was tested with PHP 8.1, PHP-FPM and NGINX 1.23. The only PHP extensions that is required, are CURL and OpenSSL;

### Installation

You don't need to run composer. Clone or copy the code in a directory and configure your web server to talk with PHP and set your vhost to redirect all requests to index.php file.

Give PHP permissions to read+write inside the "cache" directory.

### NGINX Configuration

Create and enable a virtual host with the following configuration:

```javascript
server {
    listen      127.0.0.1:80;
    listen      [::1]:80;

    listen      127.0.0.1:443 ssl;
    listen      [::1]:443 ssl;

    ssl_protocols TLSv1.3 TLSv1.2;
    ssl_certificate /my-backend-directory/ssl/api.epl.dioobr.com.local.crt;
    ssl_certificate_key /my-backend-directory/ssl/api.epl.dioobr.com.local.key;

    ssl_ciphers 'EECDH+AESGCM:EDH+AESGCM:AES256+EECDH:AES256+EDH';
    ssl_prefer_server_ciphers on;
    ssl_session_cache shared:SSL:10m;

    server_name api.epl.dioobr.com.local;

    root /my-backend-directory/public;
    index   index.php;

    error_log /my-backend-directory/log/error.log;
    access_log /my-backend-directory/log/access.log combined;

    client_max_body_size 4M;

    location ~ \.php$ {
         try_files     $uri =404;
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME   $document_root$fastcgi_script_name;
        include        fastcgi_params;
        fastcgi_intercept_errors off;
    }

    location / {
        try_files $uri /index.php;
    }
}
```

On this configuration, SSL is being used. You need to create a directory called "ssl" in the root directory of the project, and run the following commands to generate a self generated SSL certificate:

    openssl genrsa -out api.epl.dioobr.com.local.key 2048
    openssl req -new -sha256 -key api.epl.dioobr.com.local.key -out api.epl.dioobr.com.local.csr
    openssl x509 -in api.epl.dioobr.com.local.csr -out api.epl.dioobr.com.local.crt -req -signkey api.epl.dioobr.com.local.key -days 1825

In this case, I'm starting the files names with "api.epl.dioobr.com.local", feel free to change, but don't forget to also change in your NGINX configuration.

If you are going to use log files generated by NGINX, don't forget to create the "log" directory, also, in the root directory of the project.

### PHP-FPM

The NGINX configuration above talk with PHP using PHP-FPM that is available over the default TCP port 9000.

## Features

This API Service consumes some data from an API provided by the website TheSportsDB.com.
Here you have the following endpoints:

1. **Latest Events and Results**:
Endpoint URL: https://api.epl.dioobr.com/events/past

2. **EPL Teams Badges**:
Endpoint URL: https://api.epl.dioobr.com/teams/badge[/tiny]/{teamID}.png
Example 1: https://api.epl.dioobr.com/teams/badge/133599.png
Example 2: https://api.epl.dioobr.com/teams/badge/tiny/133599.png

I'm working with the URL **api.epl.dioobr.com**, but you can replace it according to your local installation.
