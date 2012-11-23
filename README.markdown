# Project Mobile Uni-App
a fork of the Kurogo Mobile Web project.

The project Mobile Uni-App is a Swiss Fork of the Kurogo-Mobile-Web Project. We adapt the existing project to fit the needs of Swiss higher education institutes and develop new modules based on the Kurogo framework. The latest progress of our project is available on GitHub.

Mobile Uni-App is based on Kurogo, which is a PHP framework for delivering high quality, data driven customizable content to a wide
range of mobile devices. Our project currently includes the following modules:

* People directory (HTML parser)
* News/RSS feeds
* Event Calendar (HTML parser and .Net webserivce access)
* Maps
* Emergency
* Transportation (time schedule)
* Library access (based on EDS / EbscoHost and ALEPH)


## Online Guide

We strongly recommend developers read the developer's guide provided by Kurogo Mobile Web:

* [HTML](http://kurogo.org/docs/mw/)

## Quick Setup and Requirements

Kurogo is a PHP application. It is currently qualified for use with

* Apache 2.x
    * mod_rewrite, and .htaccess support (AllowOverride)
* IIS 7.5
   * URL Rewrite Module 2.0
* PHP 5.2 (5.3 recommended) or higher with the following extensions
    * zlib, xml, dom, json, pdo, mbstring, LDAP, curl

To install, simply copy the files to your webserver, and set the document root to the www
folder. For more detailed setup information, please see the Developer's guide on kurogo.org/docs