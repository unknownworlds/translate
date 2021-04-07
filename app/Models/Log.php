<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Log
 *
 * @property int $id
 * @property int $project_id
 * @property int $user_id
 * @property int $log_type
 * @property string $text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $language_id
 * @property-read \App\Models\Language|null $language
 * @property-read \App\Models\Project $project
 * @method static \Illuminate\Database\Eloquent\Builder|Log newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Log newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Log query()
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereLogType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereUserId($value)
 * @mixin \Eloquent
 */
class Log extends Model
{

    protected $table = 'log';

    protected $fillable = ['project_id', 'user_id', 'language_id', 'log_type', 'text'];

    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language');
    }

}
