<?php

namespace Lang\Todo;

class TodoGenerator
{
    /** @var Storage */
    protected $storage;

    /** @var Output */
    protected $output;

    protected $basePath;

    public function __construct(string $basePath, Storage $storage, Output $output)
    {
        $this->storage  = $storage;
        $this->output   = $output;
        $this->basePath = $basePath;

        $this->load();
    }

    /**
     * Returns object.
     *
     * @param  string  $basePath base path
     *
     * @return TodoGenerator
     */
    public static function make(string $basePath): self
    {
        $storage = new Storage();
        $output  = new Output();

        return new self($basePath, $storage, $output);
    }

    /**
     * Save todo list.
     *
     * @param  string  $path
     */
    public function save(string $path): void
    {
        $this->storage->store($path, $this->output->get());
    }

    /**
     * Compare translations and generate file.
     */
    private function load(): void
    {
        // Get English version
        $english   = $this->getTranslations('en');
        $languages = $this->getLanguages();

        $this->compareTranslations($languages, $english);
    }

    /**
     * Returns array of translations by language.
     *
     * @param  string  $language language code
     * @param  string  $directory directory
     *
     * @return array
     */
    private function getTranslations(string $language, string $directory = __DIR__.'/../'): array
    {
        return [
            'json'              => $this->getJsonContent($language, $directory),
            'auth'              => $this->getContent($language, $directory, 'auth.php'),
            'pagination'        => $this->getContent($language, $directory, 'pagination.php'),
            'passwords'         => $this->getContent($language, $directory, 'passwords.php'),
            'validation'        => $this->getContent($language, $directory, 'validation.php'),
            'validation-inline' => $this->getContent($language, $directory, 'validation-inline.php'),
        ];
    }

    private function getJsonPath(string $language, string $directory): string
    {
        $directoryJson = ('en' === $language) ? '/en/' : '/../json/';

        return $directory . $directoryJson . $language . '.json';
    }

    private function getJsonContent(string $language, string $directory): ?array
    {
        $path = $this->getJsonPath($language, $directory);

        return $this->storage->getDecodedJson($path);
    }

    private function getContent(string $language, string $directory, string $filename)
    {
        return $this->storage->load(
            implode(DIRECTORY_SEPARATOR, [$directory, $language, $filename])
        );
    }

    /**
     * Returns list of languages.
     *
     * @return array
     */
    private function getLanguages(): array
    {
        $directories = $this->storage->directories($this->basePath);
        $result      = [];

        foreach ($directories as $directory) {
            if (! $directory->isDot()) {
                array_push($result, $directory->getFilename());
            }
        }

        sort($result);

        return array_filter($result);
    }

    /**
     * Compare translations.
     *
     * @param  array  $default language by default
     * @param  array  $languages others languages
     */
    private function compareTranslations(array $languages, array $default)
    {
        array_map(function ($language) use ($default) {
            $this->output->add($language);

            $current = $this->getTranslations($language, $this->basePath);

            $this->generatingInfoList($default, $current, $language);
        }, $languages);
    }

    private function generatingInfoList($default, $current, $language)
    {
        $arrayDefault = $this->align($default, ['custom', 'attributes']);
        $arrayCurrent = $this->align($current);

        foreach ($arrayDefault as $key => $values) {
            if (! isset($arrayCurrent[$key])) {
                $this->output->add(
                    $language,
                    " * {$key} : not present"
                );
            } elseif ($arrayCurrent[$key] === $values) {
                if (! $this->storage->isExclusionList($language, $arrayCurrent[$key])) {
                    $this->output->add(
                        $language,
                        " * {$key}"
                    );
                }
            }
        }
    }

    private function align($items = [], $ignore = [], $prefix = '', $connector = ' : ')
    {
        $newArray = [];

        if ( ! is_array($ignore)) {
            $ignore = (array) $ignore;
        }

        foreach ($items as $key => $value) {
            if( in_array($key, $ignore)) {
                continue;
            }

            if (is_array($value) && ! empty($value)) {
                $newArray = array_merge(
                    $newArray,
                    $this->align($value, $ignore, $prefix . $key . $connector, '.')
                );
            } else {
                $newArray[$prefix.$key] = $value;
            }
        }

        return $newArray;
    }
}
