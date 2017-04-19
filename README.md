# db

```php
$db = new DataBase($dsn, $username, $password, $options);

// SELECT * FROM users WHERE id = 1;
$user = $db['users'][1]; 

// SELECT * FROM users WHERE id IN (1,2,3);
$user = $db['users']->find([
    'WHERE' => ['id' => [1,2,3]]
]); 

// SELECT * FROM users WHERE login = 'foo';
$user = $db['users']->find(['WHERE' => ['login' => 'foo']]); 

// SELECT * FROM users WHERE login LIKE 'foo';
$user = $db['users']->find(['WHERE' => ['login[~]' => 'foo']]); 

// SELECT * FROM users LEFT JOIN attributes ON id = attributes.userId WHERE id = 1;
$user = $db['users']->find([
    'JOINS' => [
        '[>]attributes' => ['id' => 'userId']
    ],
    'WHERE' => ['id' => 1]
]);

// Сторонний преобразователь
class UsersMapper implements Mapper {}

$users = $db['users']->withMapper(new UsersMapper());


// Сторонняя сущность
class User extends Entity{}

$users = $db['users']->withEntity(User::class);

// INSERT INTO users (login, password) VALUES ('foo', 'bar')
$users[] = new User([
    'login' => 'foo',
    'password' => 'bar'
]);

$user = $db['users'][1]; 

// UPDATE users SET login = 'baz' WHERE id = 1
$users[1] = $user->withLogin('baz');

// DELETE FROM users WHERE id = 1
unset($users[1]);


$user1 = $db['users'][1]; 
$user2 = $db['users'][1]; 

$user1 === $user2; // true


$user1 = $db['users'][1]; 
$user2 = $user1->withLogin('baz');
 
$user1 === $user2; // false

```