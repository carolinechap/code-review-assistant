// test.php
<?php
class UserController
{
    public function index()
    {
        $users = [];
        foreach ($users as $user) {
            echo $user['name';
        }
        return new Response('OK');
    }
}
