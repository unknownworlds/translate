<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BaseString
 *
 * @property int $id
 * @property int $project_id
 * @property string $key
 * @property string $text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $quality_controlled
 * @property int $alternative_or_empty
 * @property int $locked
 * @method static \Illuminate\Database\Eloquent\Builder|BaseString newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseString newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseString query()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseString whereAlternativeOrEmpty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseString whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseString whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseString whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseString whereLocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseString whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseString whereQualityControlled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseString whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseString whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BaseString extends Model
{

    protected $fillable = ['project_id', 'key', 'text', 'quality_controlled', 'alternative_or_empty'];

}
