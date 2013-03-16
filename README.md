phpcs installer
===============

[Composer-installer](http://getcomposer.org/doc/articles/custom-installers.md) for
[PHP_Codesniffer](https://github.com/squizlabs/PHP_CodeSniffer) standards.

features
--------

* installs PHP_CodeSniffer dependency (1.4+) through composer
* installs a `phpcs-standard` type composer repository within the `CodeSniffer/Standards/ directory
* registers your standard with the CodeSniffer provided under `vendor/bin/phpcs`
  (if you run `composer install` from within your repository)

As you see it is about getting phpcs ready and working without need for those really long full paths.

usage
-----

In your standards repository add
```json
{
  "type": "phpcs-standard",
  "require": {
    "goatherd/phpcs_installer": "*"
  }
}
```

Now either `git clone YOUR_REPO` and `composer install`.
Or require your standard with composer.


intention
---------

For your PHP projects add `phpcs-standard`s to the `require-dev` section of the projects `composer.json`.
The installer will take care to fetch anything needed for you to utilise them.

```json
{
  "name": "you/your-project-x",
  "require-dev": {
    "any-vendor/some-standard": "*"
  }
}
```

```sh
./vendor/bin/phpcs --standard=some-standard ./src
```

generic git install
-------------------

You can alternately install your standard within a pear-installed phpcs using pear and git:
```
cd `pear config-get php_dir`/PHP/CodeSniffer/Standards
git clone YOUR_REPO_LINK [STANDARD_NAME]
```

The installer is not needed (or used) for direct installation.

limitations
-----------

* your standard must define `./ruleset.xml` and `./Sniff/` at exactly those positions
* the installer does not link tests as phpcs does not ship those with composer installations
