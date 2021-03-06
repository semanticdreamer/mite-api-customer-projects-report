# mite-api-customer-projects-report

Summarized report of time tracked per customer on [mite.][mite] projects.

## Setup

Install [Composer][composer], a Dependency Manager for PHP (execute in project root):

    cd app/ && curl -s http://getcomposer.org/installer | php

Install PHP Dependencies, managed by [Composer][composer] (execute in project root).

    php composer.phar install

(Optional, recommended for development environment though, e.g. to have documentation at hand) Install Dependencies as [Git][git] `submodules` into `dependencies/` (execute in project root):

    git submodule update --init --recursive

Rename `config.yml.dist` to `config.yml` and adjust configuration to your needs, in particular add mite. customer account information in section `accounts`.

## File System Layout

`public_html/`

Contains the application's `.htaccess` and `index.php` files.

The `index.php` file is where the Slim Framework application is instantiated and run.

Public assets, e.g. CSS stylesheets (`*.css`), images, and scripts (e.g. JavaScripts, `*.js`), are also in this directory.

`app/`

Contains the application’s code that should not be available in the public document root.

`app/composer.json`

Contains project dependencies, managed by [Composer][composer].

`app/vendor/`

Contains third-party libraries. Directory managed by [Composer][composer].

`app/ext`

Contains third-party classes and libraries shipped with the app (i.e. **NOT** managed by [Composer][composer]).

`app/lib/`

Contains custom libraries used by the application.

`app/routes/`

Separate route files, required by `public_html/index.php`.

`dependencies/`

(Optional) [Git][git] `submodules` of third party libraries .

## Dependencies

Project's PHP dependencies, managed by [Composer][composer].

- [Slim][slimframework] - A RESTful micro framework for PHP 5 inspired by Sinatra.
- [Slim Framework Extras][slimextras] - Custom Views (e.g. `MustacheView`).
- [Twig][twig] - The flexible, fast, and secure template language for PHP.
- [Symfony YAML Component][symfonyyaml] - A Component that loads and dumps YAML files.
- [Bootstrap, from Twitter][twitterbootstrap] - Simple and flexible HTML, CSS, and Javascript for popular user interface components and interactions."

## Dev Notes (to-be-deleted)

- [Slim Framework Documentation](http://www.slimframework.com/learn)

- [How to organize a large Slim Framework application](http://www.slimframework.com/read/how-to-organize-a-large-slim-framework-application)

Path to [MAMP's][mamp] PHP 5.3 binary on OS X:

    /Applications/MAMP/bin/php/php5.3.6/bin/php

Install [Composer][composer]:

    cd app/ && curl -s http://getcomposer.org/installer | /Applications/MAMP/bin/php/php5.3.6/bin/php

Install Dependencies via [Composer][composer]:

    cd app/ && /Applications/MAMP/bin/php/php5.3.6/bin/php composer.phar install

Update Dependencies via [Composer][composer]:

    cd app/ && /Applications/MAMP/bin/php/php5.3.6/bin/php composer.phar update

[mite]: http://mite.yo.lk/ "mite."
[slimframework]:http://www.slimframework.com/ "Slim Framework - A RESTful micro framework for PHP 5 inspired by Sinatra."
[slimextras]: https://github.com/codeguy/Slim-Extras "Slim Framework Extras (e.g. Custom Views)"
[twig]: http://twig.sensiolabs.org/ "Twig, the flexible, fast, and secure template language for PHP."
[composer]: http://getcomposer.org/ "Composer - Dependency Manager for PHP"
[packagist]: http://packagist.org/ "Packagist - The PHP package archivist"
[mamp]: http://www.mamp.info/ "MAMP - stands for: Macintosh, Apache, Mysql and PHP."
[git]: http://git-scm.com/ "Git is a free and open source distributed version control system."
[symfonyyaml]:https://github.com/symfony/Yaml "Symfony YAML Component"
[twitterbootstrap]:http://twitter.github.com/bootstrap/ "Bootstrap, from Twitter"