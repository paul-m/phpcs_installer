<?php

namespace Goatherd\Phpcs;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class Plugin implements PluginInterface
{
    public function activate(Composer $composer, IOInterface $io)
    {
      echo('activating.....');
        //$installer = new Installer($io, $composer);
        //$composer->getInstallationManager()->addInstaller($installer);
    }
}
