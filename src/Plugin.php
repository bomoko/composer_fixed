<?php

namespace ComposerFixed;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Plugin\PluginEvents;
use Composer\Plugin\PreFileDownloadEvent;

class Plugin implements PluginInterface, EventSubscriberInterface
{
  protected $composer;
  protected $io;

  public function activate(Composer $composer, IOInterface $io)
  {
    $this->composer = $composer;
    $this->io = $io;
    echo "IM IN HERE";
  }

  public static function getSubscribedEvents()
  {
    return [
      'post-update-cmd' => 'onPostUpdate'
    ];
  }

  public function onPostUpdate() {
    var_dump($this->composer->getPackage()->getRequires());
    var_dump($this->composer->getRepositoryManager()->getLocalRepository()->getPackages());
  }
}
