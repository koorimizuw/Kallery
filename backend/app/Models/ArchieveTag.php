<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchieveTag extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'archieve_tags';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'archieve_id',
        'tag_id',
        'uid',
    ];

    public static function checkTag($archieve_id, $tag_id)
    {
        return self::where('archieve_id', $archieve_id)
            ->where('tag_id', $tag_id)
            ->first();
    }

    public static function createArchieveTag($archieve_id, $tag_id, $uid)
    {
        $tag = self::checkTag($archieve_id, $tag_id);
        if (!$tag) {
            return self::create(array(
                "archieve_id" => $archieve_id,
                "tag_id" => $tag_id,
                "uid" => $uid,
            ));
        }

        return $tag;
    }
}
