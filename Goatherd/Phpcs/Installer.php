<?php

namespace Goatherd\Phpcs;

use Composer\Installer\LibraryInstaller;
use Composer\Repository\InstalledRepositoryInterface;
use Composer\Package\PackageInterface;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Install within CodeSniffer.
 *
 * Name of the package standard defaults to the pretty name.
 * It may be replaced by the extra:phpcs-standard property of the composer.json.
 */
class Installer extends LibraryInstaller
{

  protected function getPackageStandards(PackageInterface $package) {
    $standards = array();
    $extra = $package->getExtra();
    if (isset($extra)) {
      // Handle old-style extras.
      if (isset($extra['phpcs-standard'])) {
        // Move old-style name to new 'phpcs-standards' style.
        $extra['phpcs-standards'][$extra['phpcs-standard']] = '.';
      }
      // Handle new-style extras.
      if (isset($extra['phpcs-standards'])) {
        foreach($extra['phpcs-standards'] as $standardName => $standardPath) {
          $standards[$standardName] = $standardPath;
        }
      }
    }
    // Did we get any standards at all?
    if (count($standards) < 1) {
      // Default to pretty name and . path.
      $standards[$package->getPrettyName()] = '.';
    }
    return $standards;
  }

  protected function getPhpcsStandardsPath() {
    return $this->vendorDir . '/squizlabs/php_codesniffer/CodeSniffer/Standards';
  }

  public function install(InstalledRepositoryInterface $repo, PackageInterface $package) {
    // Let LibraryInstaller add the package as normal.
    parent::install($repo, $package);
    // Do our file manipulations.
    $standards = $this->getPackageStandards($package);
    $phpcsStandardsPath = $this->getPhpcsStandardsPath();
    foreach($standards as $standardName=>$standardPath) {
      $destPath = implode('/',
        array(
          $phpcsStandardsPath,
          $standardName,
        )
      );
      $sourcePath = realpath(implode('/',
        array(
          $this->vendorDir,
          $package->getName(),
          $standardPath
        )
      ));
      $this->io->write("Adding phpcs standard: $standardName");
      $fs = new Filesystem();
      $fs->mirror($sourcePath, $destPath);
    }
  }

  public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package) {
    $standards = $this->getPackageStandards($package);
    $this->io->write("Removing phpcs standards: " . implode(', ', $standards));    
    $fs = new Filesystem();
    $fs->remove($standards);
    parent::uninstall($repo, $package);
  }

  /** {@inheritDoc} */
    public function supports($packageType)
    {
        return 'phpcs-standard' === $packageType;
    }
}
