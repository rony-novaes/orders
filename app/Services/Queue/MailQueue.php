<?php
/**
 * ---------------------
 * Email Queuing Service 
 * ---------------------
 * 
 */
namespace App\Services\Queue;

use App\Models\Queue;
use Illuminate\Support\Facades\Validator;

class MailQueue extends Queue {

    protected $type = parent::TYPE_EMAIL;

    public static function valid(string $email) : bool
    {
        $validator = Validator::make(['email' => $email], ['email' => 'required|email']);
        return !$validator->fails();
    }
}
