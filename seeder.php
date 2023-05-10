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
    'displayName' => 'John Doe',
    'email' => 'john@doe.nl',
    'password' => password_hash('doe123', PASSWORD_BCRYPT),
    'securityAnswer' => 'answer 1',
    'registeredAt' => new DateTime('now')]
];

//insert user
foreach ($gebruikers as $gebruiker) {
    $user = R::dispense('user');
    $user->username = $gebruiker['username'];
    $user->displayName = $gebruiker['displayName'];
    $user->email = $gebruiker['email'];
    $user->password = $gebruiker['password'];
    $user->securityAnswer = $gebruiker['securityAnswer'];
    $user->bio = null;
    $user->profilePicture = 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png';
    $user->registeredAt = $gebruiker['registeredAt'];
    $user->updatedAt = null;
    R::store($user);
}
print(R::count('user') . " users inserted" . PHP_EOL);

//create post
$berichten = [
    ['code' => '<?php echo "Hello World"; ?>',
    'caption' => 'First code post',
    'theme' => 'default',
    'language' => 'php']
];

//insert post
foreach ($berichten as $bericht) {
    $post = R::dispense('post');
    $post->code = $bericht['code'];
    $post->caption = $bericht['caption'];
    $post->theme = $bericht['theme'];
    $post->language = $bericht['language'];
    R::store($post);
}
print(R::count('post') . " posts inserted" . PHP_EOL);