<?php

namespace Goatherd\Phpcs;

use Composer\Installer\LibraryInstaller;
use Composer\Repository\InstalledRepositoryInterface;
use Composer\Package\PackageInterface;

/**
 * Install within CodeSniffer.
 *
 * Name of the package standard defaults to the pretty name.
 * It may be replaced by the extra:phpcs-standard property of the composer.json.
 */
class Installer extends LibraryInstaller
{

  public function install(InstalledRepositoryInterface $repo, PackageInterface $package) {
    print_r($repo);
    print_r($package);
    parent::install($repo, $package);
  }

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
