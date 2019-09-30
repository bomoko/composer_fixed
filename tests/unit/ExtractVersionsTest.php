<?php
use PHPUnit\Framework\TestCase;

use ComposerFixed\ExtractVersions;


class ExtractVersionsTest extends TestCase
{

    public function testExtract()
    {
        $composer = json_decode(file_get_contents(__DIR__ . "/fixtures/composer.json"),
          true);
        $lock = json_decode(file_get_contents(__DIR__ . "/fixtures/composer.lock"),
          true);

        $lockPackageMap = $this->getMappedLockDataFromFileArray($lock);

        $this->assertNotEquals($composer['require']['drupal/core'], $lockPackageMap['drupal/core']);
        $transformedComposerJson = ExtractVersions::extract($composer, $lock);
        $this->assertEquals($transformedComposerJson['require']['drupal/core'], $lockPackageMap['drupal/core']['version']);
        ExtractVersions::formatJson($transformedComposerJson);
    }

    protected function getMappedLockDataFromFileArray(array $lockFile)
    {
        $returnMap = [];
        foreach($lockFile['packages'] as $package) {
            $returnMap[$package['name']] = $package;
        }

        return $returnMap;
    }

}
