<?php

namespace Lang\Todo;

use DirectoryIterator;

class Storage
{
    public function directories(string $path): DirectoryIterator
    {
        return new DirectoryIterator(
            $this->realpath($path)
        );
    }

    public function getDecodedJson(string $filename)
    {
        return file_exists($filename)
            ? json_decode(file_get_contents($filename), true)
            : null;
    }

    public function load(string $path)
    {
        $include = $this->realpath($path);

        return file_exists($include)
            ? include $include
            : null;
    }

    public function store(string $path, string $content): void
    {
        file_put_contents($path, $content);
    }

    public function realpath(string $path): string
    {
        return realpath($path);
    }

    public function isExclusionList(string $language, $key): bool
    {
        if (is_string($key)) {
            $exclude = $this->getExclusionList($language) ?? [];

            if (in_array($key, $exclude, true)) {
                return true;
            }
        }

        return false;
    }

    private function getExclusionList(string $language, string $directory = __DIR__.'/../'): ?array
    {
        return $this->load(
            implode(DIRECTORY_SEPARATOR, [$directory, 'excludes', $language . '.php'])
        );
    }
}
