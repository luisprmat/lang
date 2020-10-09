<?php

require __DIR__.'/../vendor/autoload.php';

\Lang\Todo\TodoGenerator::make(__DIR__ . '/../src')
    ->save(__DIR__ . '/../todo.md');
