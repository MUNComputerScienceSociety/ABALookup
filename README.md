ABA Lookup
==========

A project for the [Autism Society of NL] to help with the matching of ABA therapists with parents.

How to help
-----------

Fork the project on GitHub, make some changes, and open a pull request. You can learn more about [forking] and [pull requests] from GitHub Help. As well, please open [issues] for any possible enhancements or bugs you find.

Requirements
------------

- Composer
- Apache HTTP Server
- PHP 5.3+
- SQLite 3+

Downloading the project
-----------------------

To contribute back to the project, you will need to have the above requirements available. If you already have a development environment ready to go and are using *nix, you can issue the following commands in a terminal:

    cd <where you keep your projects>
    git clone <your fork>
    cd <clone location>
    composer update
    composer install
    # run the following to create the database
    mkdir database
    vendor/bin/doctrine-module orm:schema-tool:create

If not, see *Preparing a development environment* in the Wiki.

Contributing to the Wiki
------------------------

To help fill out the Wiki with information on setting up development environments, installing the app, the app in general, usage, the matching algorithm, or anything else you think will be useful to have documented throughly:

    cd <where you keep your projects>
    git clone git@github.com:<your username>/<your fork name>.wiki.git
    cd <clone location>
    # edit Markdown files
    git push
    # open an issue requesting the changes be pulled in

License
-------

Copyright (C) 2013

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but without any warranty; without even the implied warranty of merchantability or fitness for a particular purpose. See the GNU General Public License for more details.

  [forking]:https://help.github.com/articles/fork-a-repo
  [pull requests]:https://help.github.com/articles/using-pull-requests
  [issues]:https://help.github.com/articles/be-social#issues
  [Autism Society of NL]:http://www.autism.nf.net/
