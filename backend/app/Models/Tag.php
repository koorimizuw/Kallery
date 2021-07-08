<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tags';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    protected static function createTag($name)
    {
        return self::create(array(
            "name" => $name,
        ));
    }

    public static function getTagId($name)
    {
        $res = self::where('name', $name)->first();
        if (!$res) {
            return self::createTag($name)->id;
        }

        return $res->id;
    }
}
