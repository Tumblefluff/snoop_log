
# snoop_log

**snoop_log** is a simple web page designed to monitor and log incoming traffic to unspecified subdomains of your domain. This tool helps identify whether such traffic results from honest misspellings or potentially suspicious activities, especially when wildcard DNS entries (`*.your_domain.tld`) point to your server.

## Features

- **Traffic Logging**: Records visits to undefined subdomains, capturing details such as the subdomain accessed, timestamp, and visitor information.
- **Response Messages**: Provides tailored responses based on the nature of the subdomain accessed:
  - **Typo Response**: Indicates a likely misspelling of an existing subdomain.
  - **Suspicious Activity Response**: Flags potential probing or unauthorized access attempts.
- **Data Analysis**: Enables review of logged data to detect patterns, common misspellings, or malicious scanning attempts.

## Installation Instructions

### 1. Deploying with Nginx

**a. Prerequisites:**

- Ensure Nginx is installed on your server.

**b. Configuration Steps:**

1. **Create a Directory for Your Project:**

   ```bash
   sudo mkdir -p /var/www/snoop_log
   ```

2. **Upload Your Web Page:**

   Place `index.html`, `snoop.php` and any other necessary files into the `/var/www/snoop_log` directory.

3. **Set Permissions:**

   ```bash
   sudo chown -R www-data:www-data /var/www/snoop_log
   ```

4. **Configure Nginx:**

   Create a new configuration file:

   ```bash
   sudo nano /etc/nginx/sites-available/snoop_log
   ```

   Add the following configuration:

   ```nginx
   server {
       listen 80 default_server;
       server_name _;

       root /var/www/snoop_log;
       index index.html;

       access_log /var/log/nginx/snoop_log_access.log;

       location / {
           try_files $uri $uri/ =404;
       }
   }
   ```

   **Note:** This configuration is meant to replace the default server block, as having multiple `default_server` blocks may cause conflicts. Alternatively, you can disable or edit the default server block created during the Nginx installation process by commenting it out in its respective configuration file.

5. **Enable the Configuration:**

   ```bash
   sudo ln -s /etc/nginx/sites-available/snoop_log /etc/nginx/sites-enabled/
   ```

6. **Test and Reload Nginx:**

   ```bash
   sudo nginx -t
   sudo systemctl reload nginx
   ```

### 2. Deploying with Apache

**a. Prerequisites:**

- Ensure Apache is installed on your server.

**b. Configuration Steps:**

1. **Create a Directory for Your Project:**

   ```bash
   sudo mkdir -p /var/www/snoop_log
   ```

2. **Upload Your Web Page:**

   Place `index.html`, `snoop.log` and any other necessary files into the `/var/www/snoop_log` directory.

3. **Set Permissions:**

   ```bash
   sudo chown -R www-data:www-data /var/www/snoop_log
   ```

4. **Configure Apache:**

   Create a new configuration file:

   ```bash
   sudo nano /etc/apache2/sites-available/snoop_log.conf
   ```

   Add the following configuration:

   ```apache
   <VirtualHost *:80>
       ServerName your_domain.tld
       DocumentRoot /var/www/snoop_log

       ErrorLog ${APACHE_LOG_DIR}/snoop_log_error.log
       CustomLog ${APACHE_LOG_DIR}/snoop_log_access.log combined

       <Directory /var/www/snoop_log>
           Options -Indexes +FollowSymLinks
           AllowOverride None
           Require all granted
       </Directory>
   </VirtualHost>
   ```

   Replace `your_domain.tld` with your actual domain.

   **Note:** If this virtual host is meant to act as a catch-all, it may conflict with Apache's default configuration. To avoid this, either disable the default virtual host (`sudo a2dissite 000-default.conf`) or ensure this configuration is prioritized (e.g., by naming it with a prefix like `000-`).

5. **Enable the Site and Necessary Modules:**

   ```bash
   sudo a2ensite snoop_log.conf
   sudo a2enmod rewrite
   ```

6. **Test and Reload Apache:**

   ```bash
   sudo apachectl configtest
   sudo systemctl reload apache2
   ```

### 3. Deploying on Popular Hosting Platforms

For platforms like HostGator, Bluehost, or GoDaddy, the process generally involves:

1. **Accessing the Control Panel:**

   Log in to your hosting account and navigate to the file manager or FTP section.

2. **Uploading Files:**

   Upload `index.html`, `snoop.log` and any other necessary files to the `public_html` directory or the root directory designated for your domain.

3. **Managing Default Configurations:**

   Many hosting platforms have default settings for handling unmatched domains or subdomains. If possible, configure your hosting account to ensure this project is used as the default handler for wildcard DNS entries. Consult your provider's documentation or support for assistance with this setup.

4. **Setting Up Logging:**

   - **Custom Logs:** Some hosting providers allow you to set up custom logging through their control panel. Check their documentation or support for guidance.
   - **Server-Side Scripting:** If your logging mechanism relies on server-side scripts (e.g., PHP), ensure that these scripts are uploaded and have the correct permissions.

5. **Testing:**

   Access your domain in a web browser to ensure the page loads correctly and that logging functions as intended.

**Note:** Hosting platforms vary in their configurations and capabilities. If you encounter issues, consult your provider's support resources or contact their support team for assistance.

## License

This project is licensed under the [Unlicense](https://unlicense.org/), dedicating the work to the public domain for unrestricted use.
