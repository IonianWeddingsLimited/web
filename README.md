# ionian

This is the website for ionianweddings. It includes the PHP code required to get it working on all environments.


# Configuration

The database configuration can be found in _includes/settings.php


# Issues

Listing here known issues with the source code

### Short Open Tags

They are used everywhere in the code already it is not a recomended practice. Changed in a couple of places but not all places were updated.


### File encoding

Files are encoded not UTF-8, this generates issues with current PHP configurations. Started to convert those files to UTF-8 using the following:

    iconv -f WINDOWS-1252 -t utf-8 invoice.php > invoice.new.php

### PHP/MySQL driver

The old mysql driver is used, rewrite is recomended so that the code is updated