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

  protected function getPhpcsStandardsPath(PackageInterface $package) {
    return $this->vendorDir . '/squizlabs/php_codesniffer/CodeSniffer/Standards';
  }

  public function install(InstalledRepositoryInterface $repo, PackageInterface $package) {
    // Let LibraryInstaller add the package as normal.
    parent::install($repo, $package);
    // Do our symlinking.
    $standards = $this->getPackageStandards($package);
    $phpcsStandardsPath = $this->getPhpcsStandardsPath($package);
    foreach($standards as $standardName=>$standardPath) {
      $newName = implode('/',
        array(
          $phpcsStandardsPath,
          $standardName,
        )
      );
//      $linkPath = $phpcsStandardsPath . '/' . $standardName;
      $oldName = implode('/',
        array(
          $this->vendorDir,
          $package->getName(),
          $standardPath
        )
      );
//      $targetPath = $this->vendorDir . '/' . $package->getName()  . $standardPath;
      print("Old: $oldName New: $newName\n");
      rename($oldName, $newName);
    }
  }

    /** {@inheritDoc} */
/*    public function getInstallPath(PackageInterface $package)
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
    }*/

    /** {@inheritDoc} */
    public function supports($packageType)
    {
        return 'phpcs-standard' === $packageType;
    }
}
