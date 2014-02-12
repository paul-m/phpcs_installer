<?php

namespace Goatherd\Phpcs;

use Composer\Installer\LibraryInstaller;
use Composer\Repository\InstalledRepositoryInterface;
use Composer\Package\PackageInterface;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Util\Filesystem;

/**
 * Install within CodeSniffer.
 *
 * Name of the package standard defaults to the pretty name.
 * It may be replaced by the extra:phpcs-standard property of the composer.json.
 */
class Installer extends LibraryInstaller
{

//  public function __construct(IOInterface $io, Composer $composer, $type = 'phpcs-standard', Filesystem $filesystem = null) {
//    parent::__construct($io, $composer, $type, $filesystem);
//  }

  protected function getStandards(PackageInterface $package) {
    $standards = array();
    $extra = $package->getExtra();
    if (isset($extra)) {
      $standards = isset($extra['phpcs-standards'])
        ? $extra['phpcs-standards']
        : array();
    }
    return $standards;
  }

  protected function getPhpcsStandardsPath() {
    return 'squizlabs/php_codesniffer/CodeSniffer/Standards';
  }

//  public function isInstalled(InstalledRepositoryInterface $repo, PackageInterface $package) {
//    // Check for our symlinks.
//    $standards = $this->getStandards($package);
//    foreach($standards as $name=>$path) {
//      if 
//    }
//
//    if(
//    ) {
//      return parent::isInstalled($repo, $package);
//    }
//    return FALSE;
//  }


  public function install(InstalledRepositoryInterface $repo, PackageInterface $package) {
    print_r($repo);
    print_r($package);
    parent::install($repo, $package);
  }
//
//  public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target) {
//    print_r($repo);
//    print_r($initial);
//    parent::update($repo, $initial, $target);
//  }

    /** {@inheritDoc} */
    public function getInstallPath(PackageInterface $package)
    {
        $extra = $package->getExtra();
        $name =
            isset($extra['phpcs-standard'])
            ? $extra['phpcs-standard']
            : $package->getPrettyName();
        $path =
            isset($extra['phpcs-path'])
            ? $extra['phpcs-path']
            : 'squizlabs/php_codesniffer/CodeSniffer/Standards';
        // package name must denote a single directoy only
        $name = str_replace(array('/', '\\'), '-', $name);
        return $this->vendorDir . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $name;
    }

    /** {@inheritDoc} */
    public function supports($packageType)
    {
        return 'phpcs-standard' === $packageType;
    }
}
