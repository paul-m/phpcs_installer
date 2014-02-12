<?php

namespace Goatherd\Phpcs;

use Composer\Repository\InstalledRepositoryInterface;
use Composer\Installer\LibraryInstaller;

/**
 * Install within CodeSniffer.
 *
 * Name of the package standard defaults to the pretty name.
 * It may be replaced by the extra:phpcs-standard property of the composer.json.
 */
class Installer extends LibraryInstaller
{

  public function install(InstalledRepositoryInterface $repo, PackageInterface $package) {
    error_log(print_r($repo, TRUE));
    error_log(print_r($package, TRUE));
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
