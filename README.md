NewsPen
========

Painless newsletter creation and mailing list management.

https://netsyms.biz/apps/newspen

Features
--------

**Simple Editing**  
Just click and type.  Everything else, like design and layout, will be taken 
care of automatically.

**List Management**  
Import and manage email addresses, then send a newsletter with a couple clicks.

Installing
----------

0. Follow the installation directions for [AccountHub](https://source.netsyms.com/Business/AccountHub), then download this app somewhere.
1. Copy `settings.template.php` to `settings.php`
2. Import `database.sql` into your database server
3. Edit `settings.php` and fill in your DB info
4. Set the location of the AccountHub API in `settings.php` (see "PORTAL_API") and enter an API key ("PORTAL_KEY")
5. Set the location of the AccountHub home page ("PORTAL_URL")
6. Set the URL of this app ("URL")
7. Run `composer install` (or `composer.phar install`) to install dependency libraries.
