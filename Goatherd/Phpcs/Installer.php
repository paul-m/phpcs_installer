<?php

namespace Goatherd\Phpcs;

use Composer\Installer\LibraryInstaller;
use Composer\Repository\InstalledRepositoryInterface;
use Composer\Package\PackageInterface;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Util\Filesystem;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Install within CodeSniffer.
 *
 * Name of the package standard defaults to the pretty name.
 * It may be replaced by the extra:phpcs-standard property of the composer.json.
 */
class Installer extends LibraryInstaller
{

  protected function copyDirectory($source, $dest) {
/*    if (!file_exists($source)) {
      throw new \InvalidArgumentException('Source file does not exist: ' . $source);
    }
    if (file_exists($dest)) {
      if(!is_dir($dest)) {
        throw new \InvalidArgumentException('Destination file exists and is not a directory: ' . $dest);
      }
      removeDirectory($dest);
    }
    mkdir($dest);

    foreach (
      $iterator = new \RecursiveIteratorIterator(
        new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
        \RecursiveIteratorIterator::SELF_FIRST) as $item
    ) {
      if ($item->isDir()) {
        mkdir($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
      } else {
        copy($item, $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
      }
    }*/
    $fs = new Filesystem();
    $fs->mirror($source, $dest);
  }

  protected function removeDirectory($dir) {
    if (!file_exists($dir)) {
      throw new \InvalidArgumentException('Source file does not exist: ' . $source);
    }
    if(!is_dir($dir)) {
      throw new \InvalidArgumentException('Destination file exists and is not a directory: ' . $dir);
    }

    $directories = [$dir];
    foreach (
      $iterator = new \RecursiveIteratorIterator(
        new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
        \RecursiveIteratorIterator::SELF_FIRST)
      as $item
    ) {
      if ($item->isDir()) {
        array_unshift($directories, $item);
      }
      else {
        unlink($item);
      }
    }
    foreach($directories as $directory) {
      rmdir($directory);
    }
  }

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
      $this->copyDirectory($sourcePath, $destPath);
    }
  }

  public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package) {
    $this->io->write("..hey! uninstalling: " . $package->getName());
    $standards = $this->getPackageStandards($package);
    $phpcsStandardsPath = $this->getPhpcsStandardsPath();
    foreach($standards as $standardName=>$standardPath) {
      $installedStandardPath = realpath(implode('/',
        array(
          $phpcsStandardsPath,
          $standardName,
        )
      ));
      unlink($installedStandardPath);
    }
    parent::uninstall($repo, $package);
  }

  /** {@inheritDoc} */
    public function supports($packageType)
    {
        return 'phpcs-standard' === $packageType;
    }
}
