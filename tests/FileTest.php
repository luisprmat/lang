<?php

namespace Tests;

use Lang\Todo\Output;
use Lang\Todo\Storage;
use Lang\Todo\TodoGenerator;

class FileTest extends TestCase
{
    /** @test */
    function a_json_file_is_loaded()
    {
        $storage = new Storage;
        $output = new Output;

        // $gen = new TodoGenerator(__DIR__.'/temp', $storage, $output);

        // $str = $gen->getTranslations('xx');

        // var_dump($str);

        // $todo = new TodoGenerator(__DIR__.'/temp', $file, $output);

        // $loadFile = $file->getDecodedJson('./temp/xx.json');

        // var_dump(get_class($loadFile));

        // $this->assertFileExists(__DIR__.'/temp/xx.json');

        TodoGenerator::make(__DIR__ . '/files/src')
            ->save(__DIR__ . '/temp/todo.md');
    }
}
