<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Exercises extends Model implements TranslatableContract
{
    use SoftDeletes, Translatable;

    protected $fillable = ['used'];
    public $translatedAttributes = ['name', 'description', 'nameEngine'];
    public $useTranslationFallback = true;

    protected $dates = ['deleted_at'];

    public static $rules = [
        "name" => "required|min:2|max:500",
        "description" => "max:500",
        "equipment" => "max:500",
    ];


    public function getImageUrlAttribute($value)
    {
        return $value ? asset($value) : null;
    }

    public function getImage2UrlAttribute($value)
    {
        return $value ? asset($value) : null;
    }
    public function getThumbUrlAttribute($value)
    {
        return $value ? asset($value) : null;
    }

    public function getThumb2UrlAttribute($value)
    {
        return $value ? asset($value) : null;
    }

    public function editPermissions($user = null)
    {
        if (!$user) $user = Auth::user();
        return ($this->authorId == $user->id || strpos($user->email, Config::get("constants.accountDomain")) !== false);
    }

    public function removeFile($attribute)
    {
        if ($attribute == "image") {
            File::delete($this->image);
            File::delete($this->thumb);
            $this->image = null;
            $this->thumb = null;
            $this->save();
        }

        if ($attribute == "image2") {
            File::delete($this->image2);
            File::delete($this->thumb2);
            $this->image2 = null;
            $this->thumb2 = null;
            $this->save();
        }

        if ($attribute == "video") {
            File::delete($this->video);
            $this->video = null;
            $this->save();
        }
    }

    public static function searchExercises($search, $limit = 15, $filters = null, $restrictToUser = false)
    {
        $bodygroups = $types = $equipments = [];
        $custom = false;

        $search = str_replace(["\\", "'"], "", trim($search));

        $query = DB::table('exercises_translations')
            ->join('exercises', 'exercises_translations.exercises_id', '=', 'exercises.id')
            ->leftJoin('bodygroups', 'bodygroups.id', '=', 'exercises.bodygroupId')
            ->leftJoin('exercises_users', function ($join) {
                $join->on('exercises_users.exerciseId', '=', 'exercises.id')
                    ->where('exercises_users.locale', app()->getLocale())
                    ->where('exercises_users.userId', Auth::user()->id);
            })
            ->select(
                'exercises_translations.name',
                'exercises.id',
                'exercises.bodygroupId',
                'exercises.thumb',
                'exercises.image',
                'exercises.thumb2',
                'exercises.image2',
                'exercises.used',
                DB::raw('CHAR_LENGTH(exercises_translations.name) as length'),
                DB::raw('0 as equipmentId'),
                'exercises_translations.nameEngine',
                'exercises.video',
                'exercises.youtube',
                'exercises.exercisestypesId',
                'exercises.type',
                'authorId',
                'exercises_users.favorite'
            )
            ->where('exercises_translations.locale', app()->getLocale())
            ->whereNull('exercises.deleted_at')
            ->whereNull('exercises_translations.deleted_at')
            ->where('exercises.equipmentRequired', 0);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw("MATCH(exercises_translations.name) AGAINST(?)", [$search])
                    ->orWhereRaw("MATCH(exercises_translations.nameEngine) AGAINST(?)", [$search]);
            });
        }

        if ($custom) {
            $query->where(function ($q) {
                $q->where('exercises_users.favorite', 1)
                    ->orWhere(function ($q) {
                        $q->where('exercises.type', 'private')
                            ->where('exercises.authorId', Auth::user()->id);
                    })
                    ->orWhere(function ($q) {
                        $q->whereNull('exercises.type')
                            ->where('exercises.authorId', Auth::user()->id);
                    });
            });
        } else {
            $query->where(function ($q) {
                $q->where('exercises.type', 'public')
                    ->orWhere(function ($q) {
                        $q->where('exercises.type', 'private')
                            ->where('exercises.authorId', Auth::user()->id);
                    })
                    ->orWhere(function ($q) {
                        $q->whereNull('exercises.type')
                            ->where('exercises.authorId', Auth::user()->id);
                    });
            });
        }

        if ($restrictToUser) {
            $query->where('authorId', Auth::user()->id);
        }

        if (!empty($filters)) {
            foreach ($filters as $filter) {
                switch ($filter["type"]) {
                    case "type":
                        $query->whereIn('exercises.exercisestypesId', [$filter["id"]]);
                        break;
                    case "bodygroup":
                        $query->whereIn('bodygroups.id', [$filter["id"]]);
                        break;
                    case "equipment":
                        $query->whereIn('equipmentId', [$filter["id"]]);
                        break;
                }
            }
        }

        $result = $query->distinct()->limit($limit)->get();

        Log::error($query->toSql(), $query->getBindings());

        return $result;
    }

    public static function searchExercisesCount($search, $count = null, $filters = null)
    {
        $bodygroups = array();
        $types = array();
        $equipments = array();
        $custom = false;

        $subqueryEquipments = "";
        $subqueryTypes = "";
        $subqueryBodygroups = "";

        if ($filters && count($filters) > 0) {
            foreach ($filters as $filter) {
                if ($filter["type"] == "type") {
                    array_push($bodygroups, $filter["id"]);
                }
                if ($filter["type"] == "bodygroup") {
                    array_push($types, $filter["id"]);
                }
                if ($filter["type"] == "equipment") {
                    array_push($equipments, $filter["id"]);
                }
                if ($filter["type"] == "custom") {
                    $custom = true;
                }
            }
        }

        if (count($equipments) > 0) $subqueryEquipments = " and equipments.id in(" . implode($equipments) . ")";
        if (count($types) > 0) $subqueryTypes = " and exercisestypes.id in(" . implode($types) . ")";
        if (count($bodygroups) > 0) $subqueryBodygroups = " and bodygroups.id in(" . implode($bodygroups) . ")";

        $userId = 0;
        if (Auth::check()) $userId = Auth::user()->id;

        if ($search == "") {
            $query = "
        select count(id) as total from (
        select exercises.id as id
        from exercises_equipments
        left join exercises on exerciseId = exercises.id
        left join bodygroups on bodygroups.id = exercises.bodygroupId
        left join exercisestypes on exercisestypes.id = exercises.exercisesTypesId
        left join exercises_translations on exercises_translations.exercises_id = exercises.id and exercises_translations.locale = ?
        left join equipments on equipmentId = equipments.id and equipments.deleted_at is null
        left join equipments_translations on equipments_translations.equipments_id = equipments.id and equipments_translations.locale = ?
        where exercises.deleted_at is null and exercises_equipments.deleted_at is null and ( (exercises.type = 'public') or (exercises.type = 'private' and exercises.authorId = ?) or (exercises.type is null and exercises.authorId = ?) )
        " . $subqueryBodygroups . $subqueryTypes . $subqueryEquipments . "
        Union
        select exercises.id as id
        from exercises
        left join exercises_translations on exercises_translations.exercises_id = exercises.id and exercises_translations.locale = ?
        left join bodygroups on bodygroups.id = exercises.bodygroupId
        left join exercisestypes on exercisestypes.id = exercises.exercisesTypesId
        where exercises.equipmentRequired = 0 and exercises.deleted_at is null and ( (exercises.type = 'public') or (exercises.type = 'private' and exercises.authorId = ?) or (exercises.type is null and exercises.authorId = ?) )
        " . $subqueryBodygroups . $subqueryTypes . $subqueryEquipments . "
        ) as complete
        ";
            $result = DB::select(DB::raw($query), array(Lang::get("content.with"), App::getLocale(), App::getLocale(), $userId, $userId, App::getLocale(), $userId, $userId));

            return $result;
        } else {
            $query = "select count(id) as total from (
                    select exercises.id as id
                    from exercises_equipments
                    left join exercises on exerciseId = exercises.id
                    left join equipments on equipmentId = equipments.id and equipments.deleted_at is null
                    left join bodygroups on bodygroups.id = exercises.bodygroupId
                    left join exercisestypes on exercisestypes.id = exercises.exercisesTypesId
                    left join equipments_translations on equipments_translations.equipments_id = equipments.id and equipments_translations.locale = ?
                    left join exercises_translations on exercises_translations.exercises_id = exercises.id and exercises_translations.locale = ?
                    where exercises.deleted_at is null and exercises_equipments.deleted_at is null and ( (exercises.type = 'public') or (exercises.type = 'private' and exercises.authorId = ?) or (exercises.type is null and exercises.authorId = ?) )
                    " . $subqueryBodygroups . $subqueryTypes . $subqueryEquipments . "
                    Union
                    select exercises.id as id
                    from exercises
                    left join exercises_translations on exercises_translations.exercises_id = exercises.id and exercises_translations.locale = ?
                    left join bodygroups on bodygroups.id = exercises.bodygroupId
                    left join exercisestypes on exercisestypes.id = exercises.exercisesTypesId
                    where exercises.equipmentRequired = 0 and exercises.deleted_at is null and ( (exercises.type = 'public') or (exercises.type = 'private' and exercises.authorId = ?) or (exercises.type is null and exercises.authorId = ?) )
                    " . $subqueryBodygroups . $subqueryTypes . $subqueryEquipments . "
                    ) as complete";
            $result = DB::select(DB::raw($query), array(Lang::get("content.with"), "*" . $search . "*", "*" . $search . "*", "*" . $search . "*", "*" . $search . "*", "*" . $search . "*", "*" . $search . "*", "*" . $search . "*", "*" . $search . "*", "*" . $search . "*", Lang::get("content.with"), App::getLocale(), App::getLocale(), $userId, $userId, "*" . $search . "*", "*" . $search . "*", "*" . $search . "*", "*" . $search . "*", "*" . $search . "*", App::getLocale(), $userId, $userId));

            return $result;
        }
    }

    public function scopeSearch($query, $search)
    {
        $query->whereRaw("Match (exercises.name) Against (?) as name ", array($search));
        $query->whereRaw("Match (exercises.name,exercises.description) Against (?) as nameDescription ", array($search));
        $query->whereRaw("Match (exercises.description) Against (?) as description ", array($search));
        return $query;
    }

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    public static function getBodyGroupsList()
    {
        return BodyGroups::orderBy("name")->pluck("name", "id");
    }

    public function bodygroup()
    {
        return $this->belongsTo(BodyGroups::class, "bodygroupId", "id");
    }

    public function exercisesTypes()
    {
        return $this->hasMany(ExercisesExercisesTypes::class, "exerciseId", "id")->with("exercisestypes");
    }

    public function bodygroupsOptional()
    {
        return $this->hasMany(ExercisesBodygroups::class, "exerciseId", "id")->with("bodygroup");
    }

    public static function getExercisesTypesList()
    {
        return DB::table("exercisestypes")->orderBy("name")->Lists("name", "id");
    }

    public function user()
    {
        return $this->belongsTo(Users::class, "userId", "id");
    }

    public function equipments()
    {
        return $this->hasMany(ExercisesEquipments::class, "exerciseId", "id")->with("equipments")->where("type", "required");
    }

    public function equipmentsOptional()
    {
        return $this->hasMany(ExercisesEquipments::class, "exerciseId", "id")->with("equipments")->where("type", "optional");
    }

    public function equipmentsHidden()
    {
        return $this->hasMany(ExercisesEquipments::class, "exerciseId", "id")->with("equipments")->where("type", "hidden");
    }

    public function author()
    {
        return $this->belongsTo(Users::class, "authorId", "id");
    }

    public function exercisesImages()
    {
        return $this->hasMany(ExercisesImages::class, "exercisesId", "id");
    }

    public function incrementUsage()
    {
        $this->used = $this->used + 1;
        $this->save();
    }
}
