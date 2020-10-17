<?php

return [

    // token for admin APIs
    'admin_jwt' =>[
        'key' => 'x%vk@kxy&h!m7m@n7j*z8bon!$&762!eujz3d',
        'password' => 'aiq64^frt5pved5^e#%9e3'
    ],

    // token for admin APIs
    'massive_jwt' =>[
        'key' => 'rTiqowY*bMGvghD9BF8MfhYwKj5g#D*JtC',
        'password' => 'XMPtdiN6m8xkrFCkqfn7'
    ],

    // token for client APIs
    'client_jwt' =>[
        'key' => 'ujbnjeccb#*apzbxkwj&t%786!&b3hw7q%',
        'expiration' => (3600 * 24) * 1
    ],

    'user_management_jwt' =>[
        'key' => 'ujbnjeccsaaswj&t%786!&b3hw7q%',
    ],

    // type of string for generate a code
    'generatorString' =>[
        'number' => '12345689',
        'alphabetic' => 'ABCDEFGHJKMNPQRSTUWXYZ',
        'bothCharacter' => '12345689ABCDEFGHJKMNPQRSTUWXYZ'
    ],
    // length of code
    'automateCodeLength' => 6

];