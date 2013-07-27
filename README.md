ABA Lookup
==========

A project for the [Autism Society of NL] to help with the matching of ABA therapists with parents of children with Autism.

[![Build Status](https://travis-ci.org/MUNComputerScienceSociety/ABALookup.png)][Travis]

How to help
-----------

To contribute to the project, you will need to have the requirements listed below available, as well as Git installed (and a GitHub account). Once you have the requirements, fork the project on GitHub, make some changes, and open a pull request. You can learn more about [forking] and [pull requests] from GitHub Help. As well, please open [issues] for any possible enhancements or bugs you find.

For more details on contributing, please see the [the guidelines for contributing to this repository.](CONTRIBUTING.md)

Requirements
------------

- Apache HTTP Server
- PHP 5.4+ (with PDO, cURL, and the SQLite driver)
- Composer
- PHPUnit
- SQLite 3+

(Dependencies, such as ZF2, Doctrine ORM, the Doctrine ORM Module, and PHPUnit are installed via Composer - by running `composer install`.)

Downloading the project
-----------------------

If you already have a development environment ready to go with the above requirements available and are using *nix, you can issue the following commands at a shell:

```bash
cd <where you keep your projects>
git clone git@github.com:<your username>/<your fork name>.git
cd <clone location>
# composer self-update
composer install
# run the following to create the database
scripts/database
```

If not, see [Preparing a development environment] in the Wiki.

Coding standards
----------------

This project **tries** to adhere to the [Zend Framework Coding Standard for PHP] with the following modifications:

- Indentation should consist of tab characters. Spaces are to be used for formatting
- All files should end with a trailing newline (LF)
- PHP constants should be uppercased (including `TRUE`, `FALSE`, and `NULL`)

Browser support
---------------

The site supports the following browsers:

- Internet Explorer 8+
- The newest version of Google Chrome, Safari, and Firefox

Please [open an issue](CONTRIBUTING.md) if there are any incompatibilities in any of these browsers. If the site does not work in a browser not listed above, feel free to still open an issue, but do know that the possibility of it being fixed is slightly smaller.

Unit tests
----------

Unit tests for the `AbaLookup` module reside in the `module/AbaLookup/test` folder and can be run with the following command:

```bash
# from the top level directory
scripts/test
```

Contributing to the Wiki
------------------------

To help fill out the Wiki with information on setting up development environments, installing the app, app usage, the matching algorithm, or anything else that you think will be useful to have documented:

```bash
cd <where you keep your projects>
git clone git@github.com:<your username>/<your fork name>.wiki.git
cd <clone location>
# edit Markdown files
git push
# file an issue in the issue tracker requesting the changes be pulled in
```

See [this page](http://fusiongrokker.com/post/how-you-can-contribute-to-taffy-documentation) for more details on a similar workflow for forking gollum Wikis.

License
-------

This software is licensed under the Simplified BSD License. (See LICENSE file.)

  [Travis]:https://travis-ci.org/MUNComputerScienceSociety/ABALookup
  [forking]:https://help.github.com/articles/fork-a-repo
  [pull requests]:https://help.github.com/articles/using-pull-requests
  [issues]:https://help.github.com/articles/be-social#issues
  [Autism Society of NL]:http://www.autism.nf.net/
  [Preparing a development environment]:http://git.io/jAivwA
  [Zend Framework Coding Standard for PHP]:http://framework.zend.com/wiki/display/ZFDEV2/Coding+Standards
