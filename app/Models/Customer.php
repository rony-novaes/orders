<?php

namespace App\Models;

use App\Services\Customer\CustomerDeleteService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\Address\setAddressTrait;
use App\Traits\Phone\SetPhoneTrait;
use App\Traits\Model\AlterModelAttributeTrait;
use \Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, SoftDeletes, Notifiable, SetPhoneTrait, setAddressTrait, AlterModelAttributeTrait;
    /**
     * The fillable.
     *
     * @var array<string>
     */
    protected $fillable = ["name", "email", "birth_date", "password"];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [        
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birth_date' => 'date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function delete() : CustomerDeleteService
    {
        return (new CustomerDeleteService($this))->delete();
    }

    public function deleteForce() {
        return parent::delete();
    }

    public function setPassword(string $password)
    {
        $this->password = Hash::make($password);
    }
}
