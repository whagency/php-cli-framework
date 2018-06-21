<p align="center">
    <a href="https://webheads.agency/" target="_blank">
        <img src="https://webheads.agency/files/images/LogoWebHeads.png" width="181" alt="WebHeads - Creative Web Agency">
    </a>
</p>

# php-cli-framework

PHP CLI framework - good console solution for developing and performing modules for regular tasks with CRON and casual one time tasks like operating with data of the remote databases, export and import popular datatypes, load and optimize images etc. 


Directory structure
-------------------

~~~php
files/		contains files of all modules
tmp/		contains tempory files and logs
vendor/		contains dependent 3rd-party packages
webheads/	contains core classes of the framework and custom classes and libs
~~~

Included libs
-------------

* [PhpSpreadsheet](https://phpspreadsheet.readthedocs.io/en/develop/) - PHP library for reading and writing spreadsheet files
* [ActiveRecord](http://www.phpactiverecord.org) - access data in a database
* [ImageWorkshop](http://phpimageworkshop.com) - library that helps you to manage images
* [TinyPNG](https://tinypng.com) - optimization images
* [CLImate](https://climate.thephpleague.com) - library for output colored text, special formats, and other in terminal
* [mPDF](https://mpdf.github.io) - generate PDF files from HTML with Unicode/UTF-8 and CJK support
* [cli-progress-bar](https://github.com/dariuszp/cli-progress-bar) - progress bar for cli apps

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

1. Install framework to you directory, for example `php-cli-framework`

```
php composer.phar create-project --prefer-dist whagency/php-cli-framework php-cli-framework
```

2. Go to the installation directory

```
cd php-cli-framework
```

3. Run framework via console

```
php core
// base starting

php core info
// display all modules and action list

php core add
// add new module and/or action

php core module/action
// run my custom module/action
```

4. Also, you can run framework in WEB mode with your web server

```
http://localhost/php-cli-framework/module/action
```