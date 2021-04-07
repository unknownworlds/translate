<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
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
 */
	class AdminWhiteboard extends \Eloquent {}
}

namespace App\Models{
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
 */
	class BaseString extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Language
 *
 * @property int $id
 * @property string $name
 * @property string $locale
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool $is_rtl
 * @property bool $skip_in_output
 * @property string|null $steam_api_name
 * @method static \Illuminate\Database\Eloquent\Builder|Language newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Language newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Language query()
 * @method static \Illuminate\Database\Eloquent\Builder|Language sortedByName()
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereIsRtl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereSkipInOutput($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereSteamApiName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereUpdatedAt($value)
 */
	class Language extends \Eloquent {}
}

namespace App\Models{
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
 */
	class Log extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Page
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page query()
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereUpdatedAt($value)
 */
	class Page extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Project
 *
 * @property int $id
 * @property string $name
 * @property string $api_key
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string $data_input_handler
 * @property string $data_output_handler
 * @property string|null $output_template
 * @method static \Illuminate\Database\Eloquent\Builder|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newQuery()
 * @method static \Illuminate\Database\Query\Builder|Project onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereApiKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDataInputHandler($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDataOutputHandler($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereOutputTemplate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Project withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Project withoutTrashed()
 */
	class Project extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Role
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
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
 */
	class TranslatedString extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $oauth_provider
 * @property string|null $oauth_id
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $theme
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TranslatedString[] $translatedStrings
 * @property-read int|null $translated_strings_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOauthId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOauthProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Vote
 *
 * @property int $id
 * @property int $string_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Vote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vote query()
 * @method static \Illuminate\Database\Eloquent\Builder|Vote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vote whereStringId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vote whereUserId($value)
 */
	class Vote extends \Eloquent {}
}

