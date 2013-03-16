phpcs installer
===============

[Composer-installer](http://getcomposer.org/doc/articles/custom-installers.md) for
[PHP_Codesniffer](https://github.com/squizlabs/PHP_CodeSniffer) standards.

Features
--------

* installs PHP_CodeSniffer dependency (1.4+) through composer
* installs a `phpcs-standard` type composer repository within the `CodeSniffer/Standards/` directory

Thus your standard will be registered with the CodeSniffer provided at `vendor/bin/phpcs` by default.

Usage
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

You may optionally want to set the standards name as in
```json
  "extra": {
    "phpcs-standard": "my-standard"
  }
```

Now you may either add the standard to the `require-dev` or `require` section or `git clone STANDARD_REPO` and `composer install` for a standalone version.

### Example

For some project add *phpcs-standards* to the `require-dev` section of the projects `composer.json`.

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

Generic git install
-------------------

Of course you may always install your standard within a pear-installed phpcs using pear and git:
```
cd `pear config-get php_dir`/PHP/CodeSniffer/Standards
git clone YOUR_REPO_LINK [STANDARD_NAME]
```

The installer is not needed (or used) for direct installation.

Restrictions
------------

* your standard must define `./ruleset.xml` and `./Sniff/` at exactly those paths
* the installer requires PHP 5.3+ (as any composer installer does)
