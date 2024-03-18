# Binsta 🧑‍💻🖼️

A social media platform where you can share code snippets with your friends and programmers around the world.

## Installation Instructions

    1. Clone the repository to your local machine or server.
    2. Navigate to the project directory.
    3. Run composer install to install the project dependencies.
    4. Run php seeder.php to seed the database with initial data.

#### Apache

For Apache, you need to set the document root to the `/public` directory in your virtual host configuration:

```conf
<VirtualHost *:80>
    DocumentRoot "/path/to/your/project/public"
    ServerName yourdomain.com

    <Directory "/path/to/your/project/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Nginx

For Nginx, you can use the following configuration as a starting point:

```conf
server {
    listen 80;
    server_name yourdomain.com;

    location / {
        root /path/to/your/project/public;
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
    }
}
```

#### Caddy

For Caddy, use the following configuration

```conf
yourdomain.com {
    root * /path/to/your/project/public
    file_server
}
```

Remember to replace /path/to/your/project with the actual path to your project and yourdomain.com with your actual domain name.

When you've followed these steps, you can now visit the website at your domain.

## Tech Stack

- PHP, JavaScript, HTML & CSS
- RedBeanPHP
- Twig
- TailwindCSS
- Alpine.js
