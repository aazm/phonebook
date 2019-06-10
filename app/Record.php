<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Record
 * @package App
 * @property int $id
 * @property string $subscriber
 * @property string $phone
 */
class Record extends Model
{
    public $timestamps = false;
}
