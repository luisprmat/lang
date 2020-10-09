<?php

namespace Lang\Todo;

class Output
{
    protected $items = [];

    protected $eol = PHP_EOL;

    protected $line = PHP_EOL . PHP_EOL;

    protected $columns = 10;

    public function add(string $language, string $value = null): void
    {
        if (! array_key_exists($language, $this->items)) {
            $this->items[$language] = [];
        }

        if ($value) {
            array_push($this->items[$language], $value);
        }
    }

    public function get(): string
    {
        $result = $this->header();
        $result .= $this->table();

        foreach ($this->items as $language => $values) {
            $result .= $this->summary($language);
            $result .= $this->content($values);
        }

        return $result;
    }

    protected function header(): string
    {
        return '# Todo list' . $this->eol;
    }

    protected function summary(string $language): string
    {
        return "{$this->line}## {$language}{$this->line}";
    }

    protected function content(array $values): string
    {
        if ($this->isEmpty($values)) {
            return $this->eol . 'All lines are translated 😊' . $this->eol;
        }

        $content       = implode($this->eol, $values);
        $sumMissing    = count($values);
        $sumNotPresent = $this->getSumNotPresent($values);

        return <<<HTML
<details>
<summary>show<small> (all missing: $sumMissing, including not present: $sumNotPresent)</small></summary>

{$content}

[ [to top](#todo-list) ]
</details>
HTML;
    }

    protected function getSumNotPresent(array $data): int
    {
        $sum = 0;

        foreach ($data as $value) {
            if (strpos($value, ' : not present') !== false) {
                $sum++;
            }
        }

        return $sum;
    }

    protected function table(): string
    {
        $result = '';

        $captions = implode('|', array_fill(0, $this->columns, ' '));
        $divider  = implode('|', array_fill(0, $this->columns, ':---:'));

        $result .= "|{$captions}|{$this->eol}";
        $result .= "|{$divider}|{$this->eol}";

        $keys = array_keys($this->items);
        $rows = array_chunk($keys, $this->columns);

        array_map(function ($row) use (&$result) {
            $row    = $this->tableHeaderValue($row);
            $result .= $row . $this->eol;
        }, $rows);

        return $result . $this->eol . $this->eol;
    }

    protected function tableHeaderValue(array $languages): string
    {
        $languages = array_map(function ($language) {
            $icon = $this->icon($this->items[$language]);

            return "[{$language} {$icon}](#$language)";
        }, $languages);

        return implode('|', $languages);
    }

    protected function icon($values): string
    {
        $is_empty = is_array($values) ? $this->isEmpty($values) : (bool) $values;

        return $is_empty ? '✔' : '❗';
    }

    protected function isEmpty(array $values): bool
    {
        return empty($values);
    }
}
