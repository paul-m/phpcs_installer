<?php

/*
 * TODO add file headers and class description
 */

namespace Goahterd\Phpcs;

use Composer\Composer;

use Composer\Script\Event;

class InstallScript
{
    public static function install(Event $event)
    {
        self::addSymlinks($event->getComposer());
    }

    public static function update(Event $event)
    {
        self::addSymlinks($event->getComposer());
    }

    public static function addSymlinks(Composer $composer)
    {
       $vendorDir = rtrim($composer->getConfig()->get('vendor-dir'), '/');
    
       // TODO use schema and loop to apply symlinks
       // TODO mark feature as alpha and document limitations/ assumptions
       // link 'ruleset.xml' and 'Sniff'
       exec('ln -s %s/ruleset.xml %s/ruleset.xml');
       exec('ln -s %s/Sniff %s/Sniff');

       // TODO link any .php-file in base dir
    }
}
