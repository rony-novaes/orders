<?php

namespace App\Models;

use App\Services\Queue\Types\EmailGodbyeCustomer;
use App\Services\Queue\Types\EmailWelcomeCustomer;
use Code\MongoDb\Eloquent\Model;
use Code\MongoDb\Eloquent\SoftDeletes;

class Queue extends Model
{
    use SoftDeletes;
    protected $connection = "queue";
    protected $collection = "queues";

    protected $fillable = ["enqueue_id", "type", "data"];

    const TYPE_EMAIL = 1;
    const TYPE_EMAIL_WELCOME_CUSTOMER = 1;
    const TYPE_EMAIL_GOODBYE_CUSTOMER = 11;

    const TYPES = [
        self::TYPE_EMAIL_WELCOME_CUSTOMER => EmailWelcomeCustomer::class ,
        self::TYPE_EMAIL_GOODBYE_CUSTOMER => EmailGodbyeCustomer::class ,
    ];
}