<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\TranslatedString
 *
 * @property int $id
 * @property int $project_id
 * @property int $language_id
 * @property int $base_string_id
 * @property int $user_id
 * @property string $text
 * @property int $up_votes
 * @property int $down_votes
 * @property int $is_accepted
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $accepted_by
 * @property int|null $deleted_by
 * @property int $alternative_or_empty
 * @property-read \App\Models\Language $language
 * @property-read \App\Models\Project $project
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|TranslatedString newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TranslatedString newQuery()
 * @method static \Illuminate\Database\Query\Builder|TranslatedString onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TranslatedString query()
 * @method static \Illuminate\Database\Eloquent\Builder|TranslatedString whereAcceptedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TranslatedString whereAlternativeOrEmpty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TranslatedString whereBaseStringId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TranslatedString whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TranslatedString whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TranslatedString whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TranslatedString whereDownVotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TranslatedString whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TranslatedString whereIsAccepted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TranslatedString whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TranslatedString whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TranslatedString whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TranslatedString whereUpVotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TranslatedString whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TranslatedString whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|TranslatedString withTrashed()
 * @method static \Illuminate\Database\Query\Builder|TranslatedString withoutTrashed()
 * @mixin \Eloquent
 */
class TranslatedString extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'language_id',
        'base_string_id',
        'user_id',
        'text',
        'up_votes',
        'down_votes',
        'is_accepted',
        'deleted_at',
        'accepted_by',
        'deleted_by',
        'alternative_or_empty'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language');
    }

    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }
}
