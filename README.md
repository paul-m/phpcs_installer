phpcs installer
===============

[Composer-installer](http://getcomposer.org/doc/articles/custom-installers.md) for [PHP_Codesniffer](https://github.com/squizlabs/PHP_CodeSniffer) standards.

Features
--------

* installs PHP_CodeSniffer (1.4+) through composer
* installs `phpcs-standard` type repository with php_codesniffer

Usage
-----

### In your standard repository

Add these lines to `composer.json`
```json
{
  "name": "your-vendor/your-standard",
  "type": "phpcs-standard",
  "require": {
    "goatherd/phpcs_installer": "*"
  }
}
```

You may optionally want to set the standards name or use an other php_codesniffer provider
```json
  "extra": {
    "phpcs-standard": "my-standard",
    "phpcs-path": "my-vendor/php_codesniffer/CodeSniffer/Standards"
  }
```

### Consumer

```json
{
  "require-dev": {
    "your-vendor/your-standard": "*"
  }
}
```

```sh
./vendor/bin/phpcs --standard=your-standard ./src
```

Generic git install
-------------------

Install your standard within a pre-/ pear-installed PHP_CodeSniffer package using pear and git:
```
cd `pear config-get php_dir`/PHP/CodeSniffer/Standards
git clone YOUR_REPO_LINK [STANDARD_NAME]
```

Restrictions
------------

* your standard must define `./ruleset.xml` and `./Sniffs/` at exactly those paths
* the installer requires PHP 5.3+ (as does composer)
