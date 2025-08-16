<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'otp',
        'remember_token',
        'mail_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public static function isPermitted($page = null) 
    {
        $is_permitted = false;
        
        if (!$page) {
            $page = request()->module;
        }

        if (auth()->check()) {

            $permissions = UserRole::permissions();

            if (in_array($page, $permissions)) {
                $is_permitted = true;
            }
        }

        $current_path = request()->path();

        $accessible_paths = [];

        foreach ($accessible_paths as $path) {
            if (str_contains($current_path, $path)) {
                $is_permitted = true;
                break;
            }
        }

        return $is_permitted;
    }
}
