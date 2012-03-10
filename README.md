Robo47 Components
=================

[![Build Status](https://secure.travis-ci.org/robo47/Robo47-Components.png)](robo47/Robo47-Components)

Robo47 Components is a collection of classes I use in my Zend Framework 1 / 
Doctrine 1 based Applications.

Versions of other libraries I use in tests, development and production:

 * Zend Framework 1.11.6
 * Doctrine 1.2.4
 * HTMLPurifier 4.3.0
 * ezComponents 2009.2.1

Installation
------------

Create a composer.json in your projects root-directory

    {
        "require": {
            "robo47/robo47-components": "*"
        }
    }

and run

    curl -s http://getcomposer.org/installer | php
    php composer.phar install

Composer does not yet include dependencies to zend framewok, htmlpurifier, 
doctrine or ezComponents

License
-------

MIT

See file LICENSE.MIT

Known Issues
------------

Bugs in older Zend Framework versions which can make problems using 
Robo47 Components:

 * [ZF-8520](http://framework.zend.com/issues/browse/ZF-8520)
 * [ZF-9064](http://framework.zend.com/issues/browse/ZF-9064)
