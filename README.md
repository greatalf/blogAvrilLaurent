BLOG AVRIL LAURENT/PROJET 5

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/c10c2d9e7d7c4c48aa0d4794b33f6dfc)](https://www.codacy.com/app/greatalf/blogAvrilLaurent?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=greatalf/blogAvrilLaurent&amp;utm_campaign=Badge_Grade)

What you need before installation
You need Apache, MySQL and PHP: you can download a server (WAMP, MAMP, LAMP, XAMPP).

Installation
Download the project or clone it in the precise server file (www for Windows, htdocs for MAC ...).

Purpose
This is the 5th project of the "PHP / Symfony" Openclassrooms course. The main goal is to create my own blog with PHP. No frameworks such as Laravel or Symfony for this blog. Everything must be made entirely by hand.

Open model / Manager.php and verify this data (replace the password if necessary).

host = localhost
base = my_blog
'root' (this is the default user name).
'' (this is the default password with WAMP and MAC OS: if you are using MAMP and Apple, clear the password, put 'root').
Then create your database as follows:

First: http: // localhost: 8888 / phpmyadmin / (MAMP) or http: // localhost / phpmyadmin / (WAMP).
Click on "New Database".
enter "my_blog" and click "create".
Once your database is created, click on it and click on "Import".
Select a file and choose "my_blog.sql" in the project root file.

It is also necessary to config https://mailtrap.io in order to receive in local, all the mails sent since the application.
Here are the configs example : 

SMTP
Host:	smtp.mailtrap.io
Port:	25 or 465 or 2525
Username:	345fb3d7cb7ae0
Password:	e3a7c57c64ec5f
Auth:	PLAIN, LOGIN and CRAM-MD5
TLS:	Optional
