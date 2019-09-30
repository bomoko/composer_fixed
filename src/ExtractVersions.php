<?php

namespace ComposerFixed;

use Localheinz\Composer\Json\Normalizer\ComposerJsonNormalizer;

use Localheinz\Json\Normalizer\Json;




class ExtractVersions
{

    public static function extract(array $composerJson, array $composerLock)
    {
        //go through each of the composer.json and grab the packages, get their actual versions from the lock file
        foreach ($composerJson['require'] as $item => $reqPackage) {
            $version = array_reduce($composerLock['packages'],
              function ($i, $v) use ($item) {
                  if ($v['name'] == $item) {
                      return ExtractVersions::packageToVersion($v);
                  }
                  return $i;
              }, $reqPackage);

            $composerJson['require'][$item] = $version;
        }
        return $composerJson;
    }

    public static function packageToVersion($lockVersion)
    {
        if (substr($lockVersion['version'], 0, 4) == 'dev-') {
            return $lockVersion['version'] . '#' . $lockVersion['source']['reference'];
        }
        return $lockVersion['version'];
    }

    public static function formatJson(array $json)
    {
        $encoded = json_encode($json);
        $normalizer = new ComposerJsonNormalizer();
        $json = Json::fromEncoded($encoded);
        $normalizedJson = $normalizer->normalize($json);

        echo $normalizedJson->encoded();
    }

}