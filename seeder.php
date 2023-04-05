<?php

include __DIR__ . '/vendor/autoload.php';

use RedBeanPHP\R as R;

//connect to database
R::setup('mysql:host=localhost;dbname=binsta', 'bit_academy', 'bit_academy');

//empty tables
R::nuke();

//create user
$gebruikers = [
    ['username' => 'john',
    'password' => password_hash('doe123', PASSWORD_BCRYPT),],
];

//insert user
foreach ($gebruikers as $gebruiker) {
    $user = R::dispense('user');
    $user->username = $gebruiker['username'];
    $user->password = $gebruiker['password'];
    R::store($user);
}
print(R::count('user') . " users inserted" . PHP_EOL);
