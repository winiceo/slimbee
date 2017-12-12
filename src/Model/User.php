<?php

namespace App\Model;

use Cartalyst\Sentinel\Users\EloquentUser;
use Symfony\Component\Validator\Constraints as Assert;

class User extends EloquentUser
{
    protected $table = 'user';

    protected $primaryKey = 'id';


    /**
     * @Assert\NotBlank()
     */
    public $username;

    protected $fillable = [
        'username',
        'email',
        'password',
        'last_name',
        'first_name',
        'permissions',
    ];

    protected $loginNames = ['username', 'email'];


}
