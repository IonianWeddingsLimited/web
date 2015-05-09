# ionian

This is the website for ionianweddings. It includes the PHP code required to get it working on all environments.


# Configuration

The database configuration can be found in _includes/settings.php
Make sure the following folders are writable:
  
  * chmod 777 oos/font/unifont

# Issues

Listing here known issues with the source code

### Short Open Tags

They are used everywhere in the code already it is not a recommended practice. Changed in a couple of places but not all places were updated.


### File encoding

Not all files are encoded as UTF-8!!! Currently identified at least two different encoding WINDOWS-1252 and UTF-8 as being
in use.

Run the following command for all the filess (see fixiconv.sh script):

    iconv -f WINDOWS-1252 -t utf-8 invoice.php > invoice.new.php


### PHP/MySQL driver

The old mysql driver is used, rewrite is recommended so that the code is updated