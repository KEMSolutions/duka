<?php namespace App\Models;

use Customers;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;


class Customer implements AuthenticatableContract, CanResetPasswordContract
{
	use Authenticatable, CanResetPassword;

    /**
     * @param string $email
     * @return mixed
     */
    public static function findByEmail($email) {
        return Customers::get($email);
    }
}

