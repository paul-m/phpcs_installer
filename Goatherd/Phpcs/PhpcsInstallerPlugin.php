<?php

namespace Goatherd\Phpcs;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class PhpcsInstallerPlugin implements PluginInterface
{
    public function activate(Composer $composer, IOInterface $io)
    {
        $installer = new PhpcsInstaller($io, $composer);
        $composer->getInstallationManager()->addInstaller($installer);
    }
}
