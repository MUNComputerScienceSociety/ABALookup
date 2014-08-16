ABA Lookup
==========

Finding a ABA therapist is a a very difficult task for parents of kids with autism. ABA Lookup aims to simplify the process by matching therapist based on an algorithm developed by the Memorial University of Newfoundland Computer Science Society that ranks therapist based on their compatibility.

Build status
------------

| Branch  | Build Status                        |
| ------- | ----------------------------------- |
| Master  | [![Build Status - Master]][Travis]  |
| Develop | [![Build Status - Develop]][Travis] |

  [Build Status - Master]:https://travis-ci.org/MUNComputerScienceSociety/ABALookup.svg?branch=master
  [Build Status - Develop]:https://travis-ci.org/MUNComputerScienceSociety/ABALookup.svg?branch=develop
  [Travis]:https://travis-ci.org/MUNComputerScienceSociety/ABALookup

How to help
-----------

First and foremost, you will need a GitHub account -- all of the development happens here. Please fork the project, make changes, and submit a pull request. To avoid doing work that won't get merged in here, open an issue and chat about it first. You can learn more about [forking] and [pull requests] from GitHub Help.

Please also open [issues] for any possible enhancements or bugs you find.

For more details on contributing, please see the [the guidelines for contributing to this repository](CONTRIBUTING.md).

  [forking]:https://help.github.com/articles/fork-a-repo
  [pull requests]:https://help.github.com/articles/using-pull-requests
  [issues]:https://help.github.com/articles/be-social#issues

Diving in
---------

You will need your development environment configured appropritately for the project. If you want to set things up yourself, awesome, take a look at the [list of requirements] below. If you'd prefer to use a preconfigured development environment, [we have one for the project][ABALookupBox].

  [list of requirements]:#development-requirements
  [ABALookupBox]:https://github.com/MUNComputerScienceSociety/ABALookupBox

Development requirements
------------------------

If you are setting up your development environment yourself, you will need:

- Apache HTTP Server (or almost any HTTP Server)
- PHP 5.4+ (with [PDO], [cURL], [SQLite], and [intl] at the least)
- [Composer]
- [PhantomJS]
- [node.js]/npm (Grunt and Bower)
- [Sass]
- SQLite 3+

**Remember**: we have [a development environment preconfigured for this project][ABALookupBox] that you can use to get up and running a lot quicker.

  [PDO]:http://www.php.net/manual/en/book.pdo.php
  [cURL]:http://php.net/manual/en/book.curl.php
  [SQLite]:http://php.net/manual/en/book.sqlite.php
  [intl]:http://php.net/manual/en/book.intl.php
  [Composer]:https://getcomposer.org/
  [PhantomJS]:http://phantomjs.org/
  [node.js]:http://nodejs.org/
  [Sass]:http://sass-lang.com/

Downloading the project
-----------------------

If you are using [the preconfigured development environment][ABALookupBox], it will automatically clone the repository for you.

If you are doing your own thing:

    cd <wherever you keep your projects>
    git clone https://github.com/<your username>/ABALookup.git
    cd ABALookup
    npm install -g grunt-cli
    npm install
    grunt install

Running tasks (via Grunt)
-------------------------

After downloading the project, you'll need to generate both the CSS files from the Sass files, and a single JavaScript file from the (possibly many) JavaScript files. To do so:

    grunt compile

See [the README file in the JS folder](public/js/README.md) and [the README file in the Sass folder](public/sass/README.md) for more information about how they're organised.

Unit tests
----------

Unit tests for the `AbaLookup` module reside in the `module/AbaLookup/test` folder and can be run with the following command:

    scripts/test-phpunit

Accessibility tests
-------------------

Accessibility tests for the site use the [pa11y](https://github.com/nature/pa11y) node.js module, and can be run with the following command:

    scripts/test-pa11y

This test suite will check a set of routes against the W3C WCAG2.0 AAA standard. As always, pa11y can't catch all accessibility errors. It'll detect many of them, but please [open an issue](CONTRIBUTING.md) for things that can be improved.

JavaScript linting
------------------

To run JSHint against the project's JavaScript files:

    grunt lint

All the tests
-------------

To run the complete test suite, which includes JSHint, PHPUnit, and the accessibility tests:

    grunt test

Coding standards
----------------

This project _tries_ to adhere to the [Zend Framework Coding Standard for PHP] with the following modifications:

- Indentation should consist of **tab characters**, and spaces are to be used for formatting/alignment
- All files should end with a trailing newline (LF)
- PHP constants should be uppercased (including `TRUE`, `FALSE`, and `NULL`)

Also, try to keep Git commit messages terse - if they are getting truncated by GitHub, that's not a good sign.

  [Zend Framework Coding Standard for PHP]:http://framework.zend.com/wiki/display/ZFDEV2/Coding+Standards

Browser support
---------------

We are aiming to support the following browsers:

- Internet Explorer 8+
- The newest version of Google Chrome/Opera, Safari, and Firefox

Please [open an issue](CONTRIBUTING.md) if there are any incompatibilities in any of these browsers.

If you run into issues with other browsers as well, feel free to open an issue, but do know that the possibility of it being fixed is slightly smaller.

License
-------

[This software is licensed under the Simplified BSD License.](LICENSE.md)
