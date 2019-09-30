<?php

namespace ComposerFixed;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Plugin\PluginEvents;
use Composer\Plugin\PreFileDownloadEvent;
use Composer\Package\Version\VersionSelector;
use Composer\DependencyResolver\Pool;
use Composer\Repository\CompositeRepository;

class Plugin implements PluginInterface, EventSubscriberInterface
{
  protected $composer;
  protected $io;

  public function activate(Composer $composer, IOInterface $io)
  {
    $this->composer = $composer;
    $this->io = $io;
  }

  public static function getSubscribedEvents()
  {
    return [
      'post-update-cmd' => 'onPostUpdate'
    ];
  }

  public function onPostUpdate() {
    $this->generateAbsolutePackages();
  }

  public function outputFile($packagesArray) {
    $output = [
      "name" => "somemeta/package",
      "type" => "metapackage",
      "description" => "locked dependencies",
      "require" => $packagesArray
    ];
    print json_encode($output);
  }

  protected function generateAbsolutePackages() {
    $rootPackage = $this->composer->getPackage();
    $requires = $rootPackage->getRequires();

    $lockPackages = [];

    foreach ($this->composer->getRepositoryManager()
               ->getLocalRepository()
               ->getPackages() as $package) {
      $lockPackages[$package->getName()] = $package->getVersion();
    }
    $composer = $this->composer;
    //new Pool($composer->getPackage()->getMinimumStability(), $composer->getPackage()->getStabilityFlags());
    $pool = new Pool($composer->getPackage()->getMinimumStability(), $composer->getPackage()->getStabilityFlags());
//    $pool->addRepository(new CompositeRepository($composer->getRepositoryManager()->getRepositories()));
        $pool->addRepository($composer->getRepositoryManager()->getLocalRepository());
    $vs = new VersionSelector($pool);


    $directAbsolutePackages = [];
    var_dump(get_class($this->composer->getPackage()));
    foreach ($this->composer->getPackage()
               ->getRequires() as $requireName => $require) {
//                $directAbsolutePackages[$requi]
      var_dump($requireName);

      var_dump($vs->findBestCandidate($requireName)->getPrettyVersion());//$require->getPrettyVersion());
//      $directAbsolutePackages[$requireName] = $lockPackages[$requireName];
    }

//    $this->outputFile($directAbsolutePackages);
  }


}
