# PHP GD Extension Installation Guide

The PHP GD extension is required for PDF generation with images (like company logos). Here's how to install it:

## Windows (XAMPP/WAMP)

1. **For XAMPP:**
   - Open `php.ini` file (usually in `C:\xampp\php\php.ini`)
   - Find the line `;extension=gd`
   - Remove the semicolon (`;`) to uncomment it: `extension=gd`
   - Save the file and restart Apache

2. **For WAMP:**
   - In WAMP system tray icon, click: PHP → PHP Extensions → gd2
   - Restart WAMP services

## Windows (Standalone PHP)

1. Open your `php.ini` file
2. Find and uncomment (remove `;`): `extension=gd`
3. Restart your web server

## Linux (Ubuntu/Debian)

```bash
# For PHP 7.4
sudo apt-get install php7.4-gd

# For PHP 8.0
sudo apt-get install php8.0-gd

# For PHP 8.1
sudo apt-get install php8.1-gd

# For PHP 8.2
sudo apt-get install php8.2-gd

# Restart web server
sudo service apache2 restart
# OR for Nginx
sudo service nginx restart
sudo service php8.2-fpm restart
```

## CentOS/RHEL

```bash
# For PHP 7.4
sudo yum install php74-gd

# For PHP 8.0+
sudo dnf install php-gd

# Restart web server
sudo systemctl restart httpd
```

## macOS (Homebrew)

```bash
# If using Homebrew PHP
brew install php
# GD is usually included by default

# If using MacPorts
sudo port install php82-gd
```

## Verify Installation

Create a PHP file with this content to check if GD is installed:

```php
<?php
if (extension_loaded('gd')) {
    echo "GD Extension is installed!";
    print_r(gd_info());
} else {
    echo "GD Extension is NOT installed";
}
?>
```

## Current System Status

Your current PHP configuration:
- **PHP Version:** Check with `php -v`
- **GD Status:** Currently NOT installed
- **PDF Generation:** Using fallback text-only template

## Benefits of Installing GD

With GD extension installed, you will get:
- ✅ Company logo in PDF invoices
- ✅ Better formatted invoices
- ✅ Professional appearance
- ✅ Image processing capabilities

## Alternative Solution

If you cannot install GD extension immediately, the system now automatically:
- Detects if GD is available
- Uses logo-enabled template when GD is present
- Falls back to text-only template when GD is missing
- Still generates fully functional PDF invoices

Both templates produce professional invoices - the text-only version simply replaces the logo with company text.