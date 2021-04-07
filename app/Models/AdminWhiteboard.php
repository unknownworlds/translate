<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AdminWhiteboard
 *
 * @property int $id
 * @property int $project_id
 * @property int $language_id
 * @property int $user_id
 * @property string $text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|AdminWhiteboard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminWhiteboard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminWhiteboard query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminWhiteboard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminWhiteboard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminWhiteboard whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminWhiteboard whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminWhiteboard whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminWhiteboard whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminWhiteboard whereUserId($value)
 * @mixin \Eloquent
 */
class AdminWhiteboard extends Model
{
    protected $fillable = ['project_id', 'language_id', 'user_id', 'text'];

    public function user()
    {
        return $this->belongsTo('App\Models\User')->select(['id', 'name']);
    }
}
