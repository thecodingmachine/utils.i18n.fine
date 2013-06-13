Fine installation guide
=========================

Dependencies
------------

Splash comes as a *Composer* package and requires the "Mouf" framework to run.
The first step is therefore to [install Mouf](http://www.mouf-php.com/).

Once Mouf is installed, you can process to the Splash installation.

Install Splash
--------------

Edit your *composer.json* file, and add a dependency on *mouf/mvc.splash*.

A minimal *composer.json* file might look like this:
```
	{
	    "require": {
	        "mouf/mouf": "~2.0",
	        "mouf/utils.i18n.fine": "3.0.*"
	    },
	    "autoload": {
	        "psr-0": {
	            "Test": "src/"
	        }
	    },
	    "minimum-stability": "dev"
	}
```
As explained above, Fine is a package of the Mouf framework. Mouf allows you (amoung other things) to visualy "build" your project's dependencies and instances.

To install the dependency, run
	php composer.phar install

This *composer.json* file assumes that you will put your code in the "src" directory, and that you will use the "Test" namespace and respect the PSR-0 naming scheme.
Be sure to create those directories (src/Test) before running the install process.
If you do not understand what "namespace" or "PSR-0" means, *stop right now*, and head over the [autoloading section of Composer](http://getcomposer.org/doc/01-basic-usage.md#autoloading) and the [PSR-0 documentation](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md).
	
At this point, the Fine packages should be downloaded and installed (and Mouf should be set up). Start the Mouf admin interface at http://localhost/{yourproject}/vendor/mouf/mouf
There is an install process to run, so just run it.

![Splash install screenshot](https://raw.github.com/thecodingmachine/utils.i18n.fine/3.0/doc/images/install_splash.png)
![Splash install screenshot](https://raw.github.com/thecodingmachine/utils.i18n.fine/3.0/doc/images/install_splash_2.png)

The Fine install process will:
 - Create a "fine" instance of the "Fine" class. The "fine" instance contains the global configuration for Fine (default resources path, language detector, etc...).


Configure your instance
----------------------------

In the edit interface you can change the language Detection and the file path where resources are saved.

![Splash menu](https://raw.github.com/thecodingmachine/utils.i18n.fine/3.0/doc/images/install_splash_3.png)

There are many possiblity to detect the language.
 - With the user browser
 - By domain: this is a list to set each language to domain name
 - Fixed: only one language fill
 
 
The second option is the file path where the translation will be stored. Be sure to create the folder before use the fine package.