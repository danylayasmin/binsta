<?php

include __DIR__ . '/vendor/autoload.php';

use RedBeanPHP\R as R;

//connect to database
R::setup('mysql:host=localhost;dbname=binsta', 'root', '');

//empty tables
R::nuke();

//create user
$gebruikers = [
    [
        'username' => 'john',
        'displayName' => 'John Doe',
        'email' => 'john@doe.nl',
        'password' => password_hash('doe123', PASSWORD_BCRYPT),
        'securityAnswer' => 'answer 1',
        'registeredAt' => new DateTime('now')
    ],
    [
        'username' => 'jane',
        'displayName' => 'Jane Doe',
        'email' => 'jane@doe.nl',
        'password' => password_hash('doe123', PASSWORD_BCRYPT),
        'securityAnswer' => 'answer 2',
        'registeredAt' => new DateTime('now')
    ]
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
    [
        'code' => '<?php echo "Hello World"; ?>',
        'caption' => 'First code post',
        'theme' => 'default',
        'language' => 'php',
        'user_id' => 1
    ],
    [
        'code' => 'console.log("Hello World");',
        'caption' => 'First code post',
        'theme' => 'shades-of-purple',
        'language' => 'javascript',
        'user_id' => 2
    ],
    [
        'code' => 'print("Hello World")',
        'caption' => 'Second code post',
        'theme' => 'duotone-sea',
        'language' => 'python',
        'user_id' => 1
    ],
    [
        'code' => 'echo "Hello World";',
        'caption' => 'Second code post',
        'theme' => 'material-oceanic',
        'language' => 'php',
        'user_id' => 2
    ]
];

//insert post
foreach ($berichten as $bericht) {
    $post = R::dispense('post');
    $post->code = $bericht['code'];
    $post->caption = $bericht['caption'];
    $post->theme = $bericht['theme'];
    $post->language = $bericht['language'];
    $post->user_id = $bericht['user_id'];
    R::store($post);
}
print(R::count('post') . " posts inserted" . PHP_EOL);
