# prefix.cc Source Code

This is the source code to the [prefix.cc](http://prefix.cc/) web site
operated by Richard Cyganiak ([richard@cyganiak.de](mailto:richard@cyganiak.de), [@cygri](http://twitter.com/cygri)).


## Requirements

* Apache with `mod_rewrite` enabled
* PHP (recommended version: 7.2)
* MySQL


## Setting up a test site

1. Clone the repository into a local directory

2. Set up a virtual host `prefixcc.local` with that directory as document root

3. Create a new MySQL database:

        echo CREATE DATABASE prefixcc | mysql -u root 

4. Set up database tables:

        mysql -u root prefixcc < db_schema/schema.sql

5. Make a copy of the default configuration:

        cp default.config.php config.php

6. Edit database credentials in `config.php` if necessary

7. Import prefixes from the public prefix.cc site:

        php tools/csv-import.php http://prefix.cc/popular/all.file.csv | mysql -u root prefixcc

8. Go to [http://prefixcc.local/](http://prefixcc.local/) and you should have a functional site!
