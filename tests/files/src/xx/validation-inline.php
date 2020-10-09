<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'first_key'            => 'This key must be accepted.',
    'level_two'            => [
        'subkey'  => 'This field must be between :min and :max.',
        'file'    => 'This file must be between :min and :max kilobytes.',
    ],
    'second_key'           => 'This field must be true or false.',
    'with_subkeys'         => [
        // 'subkey'  => 'This value must be a subkey',
        'other'   => 'Other translation for this key',
    ],
    // 'third_key'   => 'Text for the third key',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

];
