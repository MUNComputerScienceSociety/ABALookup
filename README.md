ABA Lookup
==========

A project for the [Autism Society of NL] to help with the matching of ABA therapists with parents of children with Autism.

[![Build Status]][Travis]

How to help
-----------

To contribute to the project, you will need to have the requirements listed below available, as well as Git installed (and a GitHub account). Once you have the requirements, fork the project on GitHub, download your fork, make some changes, and open a pull request. You can learn more about [forking] and [pull requests] from GitHub Help.

Please also open [issues] for any possible enhancements or bugs you find.

For more details on contributing, please see the [the guidelines for contributing to this repository.](CONTRIBUTING.md)

Requirements
------------

- Apache HTTP Server (or almost any HTTP Server)
- PHP 5.4+ (with [PDO], [cURL], [SQLite], and [intl] at the least)
- Composer
- node.js/npm
- Ruby and RubyGems (for Sass)
- SQLite 3+

Downloading the project
-----------------------

If you already have a development environment ready to go with the above requirements available and are using *nix, you can issue the following commands:

    cd <where you keep your projects>
    git clone git@github.com:<your username>/<your fork name>.git
    cd <your fork name>
    make install dependencies database

If not, see [Preparing a development environment] in the Wiki.

Running tasks (via Grunt)
-------------------------

After downloading the project, you'll need to generate both the CSS files from the Sass files, and the single JavaScript file from the (possibly many) JavaScript files in the `public/js/src/` folder. To do so:

    gem install sass # This may need to be run as root
    grunt build

For more information on the organization of the Sass files in this project, see the [README file in the Sass folder](public/sass/README.md).

Unit tests
----------

Unit tests for the `AbaLookup` module reside in the `module/AbaLookup/test` folder and can be run with the following command:

    scripts/test-phpunit

Accessibility tests
-------------------

Accessibility tests for the site use the [pa11y](https://github.com/nature/pa11y) node.js module, and can be run with the following command:

    scripts/test-pa11y

Before running the accessibility tests, make sure you have the requirements installed for the pa11y module ([see the project's README](https://github.com/nature/pa11y#installing)). This test suite will check each of the routes against the W3C WCAG2.0 AAA standard.

All the tests
-------------

To run the complete test suite, both the unit tests and the accessibility tests, run the following:

    scripts/test

Coding standards
----------------

This project **tries** to adhere to the [Zend Framework Coding Standard for PHP] with the following modifications:

- Indentation should consist of **tab characters**, and spaces are to be used for formatting
- All files should end with a trailing newline (LF)
- PHP constants should be uppercased (including `TRUE`, `FALSE`, and `NULL`)

Browser support
---------------

The site supports the following browsers:

- Internet Explorer 8+
- The newest version of Google Chrome, Safari, and Firefox

Please [open an issue](CONTRIBUTING.md) if there are any incompatibilities in any of these browsers. If the site does not work in a browser not listed above, feel free to open an issue, but do know that the possibility of it being fixed is slightly smaller.

Contributing to the Wiki
------------------------

To help fill out the Wiki with information on setting up development environments, installing the application, usage, the matching algorithm, or anything else that you think will be useful to have documented:

    cd <where you keep your projects>
    git clone git@github.com:<your username>/<your fork name>.wiki.git
    cd <your fork name>
    # edit Markdown files
    git push
    # file an issue in the issue tracker requesting the changes be pulled in

See [this page](http://fusiongrokker.com/post/how-you-can-contribute-to-taffy-documentation) for more details on a similar workflow for forking gollum Wikis.

License
-------

[This software is licensed under the Simplified BSD License.](LICENSE.md)

  [Autism Society of NL]:http://www.autism.nf.net/
  [Build Status]:https://travis-ci.org/MUNComputerScienceSociety/ABALookup.png
  [Travis]:https://travis-ci.org/MUNComputerScienceSociety/ABALookup
  [forking]:https://help.github.com/articles/fork-a-repo
  [pull requests]:https://help.github.com/articles/using-pull-requests
  [issues]:https://help.github.com/articles/be-social#issues
  [PDO]:http://www.php.net/manual/en/book.pdo.php
  [cURL]:http://php.net/manual/en/book.curl.php
  [SQLite]:http://php.net/manual/en/book.sqlite.php
  [intl]:http://php.net/manual/en/book.intl.php
  [Preparing a development environment]:http://git.io/jAivwA
  [Zend Framework Coding Standard for PHP]:http://framework.zend.com/wiki/display/ZFDEV2/Coding+Standards
