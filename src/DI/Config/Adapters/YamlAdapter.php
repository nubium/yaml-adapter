<?php

declare (strict_types=1);

namespace Nubium\DI\Config\Adapters;

use Nette;
use Nette\DI\Config\Helpers;
use Nette\DI\Config\IAdapter;
use Nette\Utils\FileSystem;
use Nubium\DI\Config\Adapters\Exception\NotImplementedException;
use Symfony\Component\Yaml\Yaml;

class YamlAdapter implements IAdapter
{
    /** @internal */
    const
        INHERITING_SEPARATOR = '<', // child < parent
        PREVENT_MERGING = '!';

    public function load($file)
    {
        return $this->process(Yaml::parse(FileSystem::read($file)));
    }

    private function process(array $arr)
    {
        $res = [];
        foreach ($arr as $key => $val) {
            if (is_string($key) && substr($key, -1) === self::PREVENT_MERGING) {
                if (!is_array($val) && $val !== null) {
                    throw new Nette\InvalidStateException("Replacing operator is available only for arrays, item '$key' is not array.");
                }
                $key = substr($key, 0, -1);
                $val[Helpers::EXTENDS_KEY] = Helpers::OVERWRITE;
            } elseif (is_string($key) && preg_match('#^(\S+)\s+'.self::INHERITING_SEPARATOR.'\s+(\S+)\z#', $key, $matches)) {
                if (!is_array($val) && $val !== null) {
                    throw new Nette\InvalidStateException("Inheritance operator is available only for arrays, item '$key' is not array.");
                }
                list(, $key, $val[Helpers::EXTENDS_KEY]) = $matches;
                if (isset($res[$key])) {
                    throw new Nette\InvalidStateException("Duplicated key '$key'.");
                }
            }

            if (is_array($val)) {
                $val = $this->process($val);
            }
            $res[$key] = $val;
        }

        return $res;
    }

    public function dump(array $data)
    {
        throw new NotImplementedException('It is not possible to dump configuration to yaml yet.');
    }
}
