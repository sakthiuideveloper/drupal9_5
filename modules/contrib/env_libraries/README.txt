CONTENTS OF THIS FILE
---------------------

 * Introduction
 * Requirements
 * Installation
 * Configuration
 * Maintainers


INTRODUCTION
------------
The Env Libraries module allows developpers to declare libraries according to
several development environments.

When front developpers build libraries they may use different environments for
debug and prod. Most of the time they populate several file, one fore
development (readable) and one for production (minimised).
For example when you develop a javascript library with webpack (for example),
you build these files :
- Dev (for debug) : libraries/my-custom-library/dev/my.js
- Prod (minified) : libraries/my-custom-library/dist/my.js

In this context, this module allows you declare only one library that will
load the right file according to the current type of environment.
For this you have to declare a library defining the "dev" repertory in the path
like :
libraries/assets/<strong>\_\_ENV_LIB={DEV_PART}|{PROD_PART}__</strong>/js/my.js


REQUIREMENTS
------------

This module requires no modules outside of Drupal core.


INSTALLATION
------------

Install the Env Libraries module as you would normally install a contributed
Drupal module. Visit https://www.drupal.org/node/1897420 for further
information.


CONFIGURATION
-------------

You can declare a single library that will load the right file according to the
ENV_LIB settings.

For example, if you have this structure of files :
- libraries/my-custom-library/dev/my.js (for dev)
- libraries/my-custom-library/dist/my.js (for prod)

You just have to declare a single library :
my_custom_library:
  js:
    libraries/my-custom-library/\_\_ENV_LIB=dev|dist__/my.js: {}

By default, Env Libraries define the enviromnent as "prod". So
it is the right element of the "__ENV_LIB=dev|<strong>dist</strong>__" token
that is implemented.

To define your environment as "dev", you can add this line into your
settings.local.php :
`$settings['ENV_LIB'] = 'dev';`

or you can define this variable in your .env file :
`ENV_LIB = 'dev';`


MAINTAINERS
-----------

 * Thomas Sécher - https://www.drupal.org/u/tsecher

Supporting organization:

 * Lycanthrop - https://www.drupal.org/lycanthrop
