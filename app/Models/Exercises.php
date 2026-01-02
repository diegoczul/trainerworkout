<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
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

    protected $fillable = ['name', 'description', 'nameEngine', 'used'];
    public $translatedAttributes = ['name', 'description', 'nameEngine'];
    public $useTranslationFallback = true;
    public $translationForeignKey = 'exercises_id';

    protected $dates = ['deleted_at'];

    public static $rules = [
        'youtube' => ['sometimes', 'nullable', 'url', 'regex:/^(https?:\/\/)?(www\.)?(youtube\.com\/(watch\?v=|shorts\/)|youtu\.be\/)([\w-]{11})(\?.*)?$/'],
        "name" => "required|min:2|max:500",
        "description" => "max:500",
        //        "equipment" => "required|max:500",
        "bodygroupId" => "required",
        "image1" => 'sometimes|mimes:jpg,png,jpeg,gif',
        "image2" => 'sometimes|mimes:jpg,png,jpeg,gif',
        'video' => 'nullable|file|mimes:mp4,mpeg4,mpeg-4,x-m4v,m4v,mov',
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

    public function getVideoUrlAttribute($value)
    {
        return $value ? asset($value) : "";
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

    public static function searchExercises_($search, $limit = 15, $filters = null, $restrictToUser = false, $lang = "en")
    {

        $bodygroups = array();
        $types = array();
        $equipments = array();
        $custom = false;

        $subqueryEquipments = "";
        $subqueryTypes = "";
        $subqueryBodygroups = "";

        $search = str_replace("\\", "", $search);
        $search = str_replace("'", "", $search);

        $search = trim($search);

        $words = explode(" ", $search);



        $subqueryWords = "1 = 0 ";

        // if (count($words) >= 1 and $words[0] != "") {
        // 	foreach ($words as $word) {
        // 		$subqueryWords .= " or exercises_translations.name like '%" . rtrim($word, "s") . "%' or exercises_translations.description like '%" . rtrim($word, "s") . "%' or exercises_translations.nameEngine like '%" . rtrim($word, "s") . "%'";
        // 	}

        // 	$subEquipments = EquipmentsTranslation::where("name", 'like', '%' . rtrim($word) . '%')->get();
        // 	foreach ($subEquipments as $sub) {
        // 		array_push($equipments, $sub->equipments_id);
        // 	}
        // 	$subTypes = ExercisestypesTranslation::where("name", 'like', '%' . rtrim($word) . '%')->get();
        // 	foreach ($subTypes as $sub) {
        // 		array_push($types, $sub->exercisestypes_id);
        // 	}
        // }



        if ($filters and count($filters) > 0) {
            foreach ($filters as $filter) {
                if ($filter["type"] == "type") {
                    array_push($types, $filter["id"]);
                }
                if ($filter["type"] == "bodygroup") {
                    array_push($bodygroups, $filter["id"]);
                }
                if ($filter["type"] == "equipment") {
                    array_push($equipments, $filter["id"]);
                }
                if ($filter["type"] == "custom") {
                    $custom = true;
                }
            }
        }

        if (count($equipments) > 0) $subqueryEquipments = " equipmentId in(" . implode(",", $equipments) . ")";

        if (count($types) > 0) $subqueryTypes = " exercisestypesId in(" . implode(",", $types) . ")";

        //if(count($bodygroups) > 0) $subqueryBodygroups = " and (bodygroups.id in(".implode(",",$bodygroups).") or exercises_bodygroups.bodygroupId in(".implode(",",$bodygroups)."))";

        if (count($bodygroups) > 0) $subqueryBodygroups = " and (bodygroups.id in(" . implode(",", $bodygroups) . "))";

        if (count($bodygroups) > 0) $subqueryBodygroups2 = " exercises.bodygroupId in(" . implode(",", $bodygroups) . ") or exercises_bodygroups.bodygroupId in(" . implode(",", $bodygroups) . ")";


        $exercises = ExercisesTranslation::whereRaw(DB::raw($subqueryWords))->select("exercises_id")->distinct()->get();


        $preFilterExercsies = array();
        $preFilterExercsiesTypes = array();
        $preFilterExercsiesEquipments = array();
        $preFilterExercsiesBodygroup = array();
        $singleFilter = 0;

        if (is_array($exercises) and count($exercises) > 0) {
            $singleFilter++;
            foreach ($exercises as $ex) {
                if (!in_array($ex->exercises_id, $preFilterExercsies)) array_push($preFilterExercsies, $ex->exercises_id);
            }
        }

        if (is_array($types) and count($types) > 0) {
            Log::error("Types");
            Log::error($types);
            $singleFilter++;
            $exercises = ExercisesExercisestypes::whereRaw($subqueryTypes)->select("exerciseId")->distinct()->get();
            foreach ($exercises as $ex) {
                if (!in_array($ex->exerciseId, $preFilterExercsiesTypes)) array_push($preFilterExercsiesTypes, $ex->exerciseId);
            }
            if ($singleFilter < 2 or count($filters) == 0) {
                $preFilterExercsies    = array_unique(array_merge($preFilterExercsies, $preFilterExercsiesTypes), SORT_REGULAR);
            } else {
                $preFilterExercsies = array_intersect($preFilterExercsies, $preFilterExercsiesTypes);
            }
        }


        if (is_array($equipments) and count($equipments) > 0) {
            Log::error("equipments");
            Log::error($equipments);
            $singleFilter++;
            $exercises = ExercisesEquipments::whereRaw($subqueryEquipments)->select("exerciseId")->distinct()->get();
            foreach ($exercises as $ex) {
                if (!in_array($ex->exerciseId, $preFilterExercsiesEquipments)) array_push($preFilterExercsiesEquipments, $ex->exerciseId);
            }
            if ($singleFilter < 2 or count($filters) == 0) {
                $preFilterExercsies    = array_unique(array_merge($preFilterExercsies, $preFilterExercsiesEquipments), SORT_REGULAR);
            } else {
                $preFilterExercsies = array_intersect($preFilterExercsies, $preFilterExercsiesEquipments);
            }
        }

        if (is_array($bodygroups) and count($bodygroups) > 0) {
            Log::error("bodygroups");
            Log::error($bodygroups);
            $singleFilter++;
            $exercises = Exercises::leftJoin("exercises_bodygroups", "exercises.id", "=", "exercises_bodygroups.exerciseId")->whereRaw($subqueryBodygroups2)->select("exercises.id")->distinct()->get();
            foreach ($exercises as $ex) {
                if (!in_array($ex->exerciseId, $preFilterExercsiesBodygroup)) array_push($preFilterExercsiesBodygroup, $ex->id);
            }
            if ($singleFilter < 2  or count($filters) == 0) {
                $preFilterExercsies    = array_unique(array_merge($preFilterExercsies, $preFilterExercsiesBodygroup), SORT_REGULAR);
            } else {
                $preFilterExercsies = array_intersect($preFilterExercsies, $preFilterExercsiesBodygroup);
            }
        }








        $result = "select * ";
        $result .= " from (";
        $result .= "(";
        $result .= "select exercises_translations.name, exercises.id, exercises.bodygroupId, exercises.thumb, exercises.image, exercises.thumb2, exercises.image2, exercises.used ";
        $result .= " , CHAR_LENGTH(exercises_translations.name) as length, 0 as equipmentId, exercises_translations.nameEngine, exercises.video, exercises.youtube, exercises.exercisestypesId ";
        $result .= " ,exercises.type, authorId, exercises_users.favorite ";
        if ($search != "") {
            $result .= " ,MATCH(exercises_translations.name ) AGAINST('" . $search . "') as scoreName ";
            $result .= " ,MATCH(exercises_translations.nameEngine ) AGAINST('" . $search . "') as scoreNameEngine ";
        }
        $result .= " from exercises_translations ";
        $result .= " left join exercises on exercises_translations.exercises_id = exercises.id";
        $result .= " left join bodygroups on bodygroups.id = exercises.bodygroupId";
        // if(count($bodygroups) > 0){
        // $result .= " left join exercises_bodygroups on exercises.id = exercises_bodygroups.exerciseId and exercises_bodygroups.bodygroupId in(".implode(",",$bodygroups).")";
        // } else {
        // $result .= " left join exercises_bodygroups on exercises.id = exercises_bodygroups.exerciseId and exercises_bodygroups.bodygroupId in(0)";
        // }
        $result .= " left join exercises_users on exercises_users.exerciseId = exercises.id and exercises_users.locale = '" . App::getLocale() . "' and exercises_users.userId = " . Auth::user()->id;
        $result .= " where exercises_translations.locale = '" . App::getLocale() . "'";
        $result .= " and exercises.deleted_at is null and exercises_translations.deleted_at is null";
        $result .= " and exercises.equipmentRequired = 0 ";
        if ($custom) {
            $result .= " and ( exercises_users.favorite = 1  or (exercises.type = 'private' and exercises.authorId = " . Auth::user()->id . ") or (exercises.type is null and exercises.authorId = " . Auth::user()->id . ") )";
        } else {
            $result .= " and ( (exercises.type = 'public') or (exercises.type = 'private' and exercises.authorId = " . Auth::user()->id . ") or (exercises.type is null and exercises.authorId = " . Auth::user()->id . ") )";
        }
        if ($restrictToUser) $result .= " and authorId = " . Auth::user()->id;
        if (count($preFilterExercsies) > 0) $result .= " and exercises.id in (" . implode(",", $preFilterExercsies) . ")";
        $result .= ")";

        $result .= " union all";

        $result .= "(";
        $result .= "select concat(exercises_translations.name,' " . Lang::get("content.with") . " ',equipments_translations.name) as name, exercises.id, exercises.bodygroupId, exercises.thumb, exercises.image, exercises.thumb2, exercises.image2, exercises.used  ";
        $result .= " , CHAR_LENGTH(concat(exercises_translations.name,' " . Lang::get("content.with") . " ',equipments_translations.name)) as length, exercises_equipments.equipmentId, exercises_translations.nameEngine, exercises.video, exercises.youtube, exercises.exercisestypesId ";
        $result .= " ,exercises.type, authorId, exercises_users.favorite ";
        if ($search != "") {
            $result .= " ,MATCH(exercises_translations.name ) AGAINST('" . $search . "') as scoreName ";
            $result .= " ,MATCH(exercises_translations.nameEngine ) AGAINST('" . $search . "') as scoreNameEngine ";
        }
        $result .= " from exercises_equipments ";
        $result .= " left join exercises on exercises.id = exercises_equipments.exerciseId";
        $result .= " left join exercises_translations on exercises_translations.exercises_id = exercises.id";
        $result .= " left join bodygroups on bodygroups.id = exercises.bodygroupId";
        // if(count($bodygroups) > 0){
        // $result .= " left join exercises_bodygroups on exercises.id = exercises_bodygroups.exerciseId and exercises_bodygroups.bodygroupId in(".implode(",",$bodygroups).")";
        // } else {
        // $result .= " left join exercises_bodygroups on exercises.id = exercises_bodygroups.exerciseId and exercises_bodygroups.bodygroupId in(0)";
        // }
        $result .= " left join equipments on equipments.id = exercises_equipments.equipmentId";
        $result .= " left join equipments_translations on equipments.id = equipments_translations.equipments_id";
        $result .= " left join exercises_users on exercises_users.exerciseId = exercises.id and exercises_users.locale = '" . App::getLocale() . "' and exercises_users.userId = " . Auth::user()->id . " and exercises_users.equipmentId = exercises_equipments.id";
        $result .= " where exercises_translations.locale = '" . App::getLocale() . "' and equipments_translations.locale = '" . App::getLocale() . "'";
        $result .= " and exercises_equipments.type != 'hidden' and exercises.deleted_at is null and exercises_translations.deleted_at is null and exercises_equipments.deleted_at is null";
        if ($custom) {
            $result .= " and ( exercises_users.favorite = 1  or (exercises.type = 'private' and exercises.authorId = " . Auth::user()->id . ") or (exercises.type is null and exercises.authorId = " . Auth::user()->id . ") )";
        } else {
            $result .= " and ( (exercises.type = 'public') or (exercises.type = 'private' and exercises.authorId = " . Auth::user()->id . ") or (exercises.type is null and exercises.authorId = " . Auth::user()->id . ") )";
        }

        if ($restrictToUser) $result .= " and authorId = " . Auth::user()->id;
        if (count($preFilterExercsies) > 0) $result .= " and exercises.id in (" . implode(",", $preFilterExercsies) . ")";

        $result .= ")";

        $result .= ") master ";



        if (is_array($words) and count($words) >= 1 and $words[0] != "") {
            if (count($equipments) > 0) {
                $result .= " order by favorite DESC, type ASC, scoreName DESC,scoreNameEngine DESC, length ASC, FIELD(equipmentId, " . implode(",", $equipments) . ") DESC, used ASC ";
            } else if (is_array($types) and count($types) > 0) {
                $result .= " order by favorite DESC, type ASC, scoreName DESC,scoreNameEngine DESC, length ASC, FIELD(exercisestypesId, " . implode(",", $types) . ") DESC, used ASC";
            } else if (is_array($bodygroups) and count($bodygroups) > 0) {
                $result .= " order by favorite DESC, type ASC, scoreName DESC,scoreNameEngine DESC, length ASC, FIELD(bodygroupId, " . implode(",", $bodygroups) . ") DESC, used ASC ";
            } else {
                $result .= " order by favorite DESC, type ASC, scoreName DESC,scoreNameEngine DESC, length ASC ";
            }
        } else {
            if (is_array($equipments) and count($equipments) > 0) {
                $result .= " order by  favorite DESC, type ASC, used DESC,FIELD(equipmentId, " . implode(",", $equipments) . ") DESC, length ASC  ";
            } else if (is_array($types) and count($types) > 0) {
                $result .= "order by  favorite DESC, type ASC, used DESC, FIELD(exercisestypesId, " . implode(",", $types) . ") DESC, length ASC ";
            } else if (is_array($bodygroups) and count($bodygroups) > 0) {
                $result .= "order by  favorite DESC, type ASC, used DESC, FIELD(bodygroupId, " . implode(",", $bodygroups) . ") DESC, length ASC";
            } else {
                $result .= " order by favorite DESC, type ASC, used DESC ";
            }
        }


        $result .= " limit " . $limit;

        Log::error($result);


        $result = DB::select($result);


        return $result;
    }

    public static function searchExercises($search, $limit = 15, $filters = null, $restrictToUser = false, $lang = "en")
    {
        $userId = auth()->id();
        $search = trim(str_replace(["\\", "'"], '', $search));
        $parsedFilters = ['bodygroups' => [], 'types' => [], 'equipments' => []];

        if (is_array($filters)) {
            foreach ($filters as $filter) {
                if ($filter['type'] === 'bodygroup') $parsedFilters['bodygroups'][] = (int) $filter['id'];
                elseif ($filter['type'] === 'type') $parsedFilters['types'][] = (int) $filter['id'];
                elseif ($filter['type'] === 'equipment') $parsedFilters['equipments'][] = (int) $filter['id'];
            }
        }

        // ---------------- Base Query ----------------
        $baseQuery = self::query()
            ->join('exercises_translations', function ($join) use ($lang) {
                $join->on('exercises.id', '=', 'exercises_translations.exercises_id')
                    ->where('exercises_translations.locale', '=', $lang);
            })
            ->leftJoin('exercises_users', function ($join) use ($userId, $lang) {
                $join->on('exercises.id', '=', 'exercises_users.exerciseId')
                    ->where('exercises_users.locale', '=', $lang)
                    ->where('exercises_users.userId', '=', $userId);
            })
            ->whereNull('exercises.deleted_at')
            ->where('exercises.equipmentRequired', 0)
            ->where(function ($q) use ($restrictToUser, $userId) {
                if ($restrictToUser == true) {
                    $q->where('exercises.authorId', $userId)
                        ->orWhere('exercises_users.favorite', 1);
                } else {
                    $q->where('exercises.type', 'public')
                        ->orWhere(function ($subQ) use ($userId) {
                            $subQ->whereIn('exercises.type', ['private', null])
                                ->where('exercises.authorId', $userId);
                        });
                }
            })
            ->select(
                'exercises.id',
                'exercises.thumb',
                'exercises.thumb2',
                'exercises.image',
                'exercises.image2',
                'exercises.bodygroupId',
                'exercises.youtube',
                'exercises.video',
                'exercises.exercisesTypesId',
                'exercises.type',
                'exercises.authorId',
                'exercises_users.favorite',
                'exercises_translations.name',
                'exercises_translations.nameEngine',
                DB::raw("CHAR_LENGTH(exercises_translations.name) as length")
            );

        if (!empty($search)) {
            $baseQuery->addSelect(
                DB::raw("MATCH(exercises_translations.name) AGAINST('$search') as scoreName"),
                DB::raw("MATCH(exercises_translations.nameEngine) AGAINST('$search') as scoreNameEngine")
            )->whereRaw("MATCH(exercises_translations.name, exercises_translations.nameEngine) AGAINST(? IN BOOLEAN MODE)", [$search]);
        }

        // --- Apply filters to base query ---
        if (!empty($parsedFilters['bodygroups'])) {
            $baseQuery->where(function ($q) use ($parsedFilters) {
                $q->whereIn('exercises.bodygroupId', $parsedFilters['bodygroups'])
                    ->orWhereIn('exercises.id', function ($sub) use ($parsedFilters) {
                        $sub->select('exerciseId')->from('exercises_bodygroups')
                            ->whereNull('deleted_at')
                            ->whereIn('bodygroupId', $parsedFilters['bodygroups']);
                    });
            });
        }

        if (!empty($parsedFilters['types'])) {
            $baseQuery->whereIn('exercisesTypesId', $parsedFilters['types']);
        }

        if (!empty($parsedFilters['equipments'])) {
            $baseQuery->whereIn('exercises.id', function ($sub) use ($parsedFilters) {
                $sub->select('exerciseId')->from('exercises_equipments')
                    ->whereNull('deleted_at')
                    ->whereIn('equipmentId', $parsedFilters['equipments']);
            });
        }

        $baseResults = $baseQuery->limit($limit)->get();

        // ---------------- With Equipment Query ----------------
        $withQuery = DB::table('exercises_equipments')
            ->join('exercises', 'exercises.id', '=', 'exercises_equipments.exerciseId')
            ->join('exercises_translations', function ($join) use ($lang) {
                $join->on('exercises.id', '=', 'exercises_translations.exercises_id')
                    ->where('exercises_translations.locale', '=', $lang);
            })
            ->join('equipments', 'equipments.id', '=', 'exercises_equipments.equipmentId')
            ->join('equipments_translations', function ($join) use ($lang) {
                $join->on('equipments.id', '=', 'equipments_translations.equipments_id')
                    ->where('equipments_translations.locale', '=', $lang);
            })
            ->leftJoin('exercises_users', function ($join) use ($userId, $lang) {
                $join->on('exercises.id', '=', 'exercises_users.exerciseId')
                    ->where('exercises_users.locale', '=', $lang)
                    ->where('exercises_users.userId', '=', $userId);
            })
            ->where('exercises_equipments.type', '!=', 'hidden')
            ->whereNull('exercises.deleted_at')
            ->whereNull('exercises_translations.deleted_at')
            ->whereNull('exercises_equipments.deleted_at')
            ->where(function ($q) use ($restrictToUser, $userId) {
                if ($restrictToUser == true) {
                    $q->where('exercises.authorId', $userId)
                        ->orWhere('exercises_users.favorite', 1);
                } else {
                    $q->where('exercises.type', 'public')
                        ->orWhere(function ($subQ) use ($userId) {
                            $subQ->whereIn('exercises.type', ['private', null])
                                ->where('exercises.authorId', $userId);
                        });
                }
            })
            ->select(
                DB::raw("CONCAT(exercises_translations.name, ' " . Lang::get('content.with') . " ', equipments_translations.name) as name"),
                'exercises.id',
                'exercises.thumb',
                'exercises.thumb2',
                'exercises.image',
                'exercises.image2',
                'exercises.bodygroupId',
                'exercises.youtube',
                'exercises.video',
                'exercises.exercisesTypesId',
                'exercises.type',
                'exercises.authorId',
                'exercises_users.favorite',
                'exercises_translations.nameEngine',
                'exercises_equipments.equipmentId',
                DB::raw("CHAR_LENGTH(CONCAT(exercises_translations.name, ' " . Lang::get('content.with') . " ', equipments_translations.name)) as length")
            );

        if (!empty($search)) {
            $withQuery->addSelect(
                DB::raw("MATCH(exercises_translations.name) AGAINST('$search') as scoreName"),
                DB::raw("MATCH(exercises_translations.nameEngine) AGAINST('$search') as scoreNameEngine")
            )->whereRaw("MATCH(exercises_translations.name, exercises_translations.nameEngine) AGAINST(? IN BOOLEAN MODE)", [$search]);
        }

        // --- Apply filters to with-query ---
        if (!empty($parsedFilters['bodygroups'])) {
            $withQuery->where(function ($q) use ($parsedFilters) {
                $q->whereIn('exercises.bodygroupId', $parsedFilters['bodygroups'])
                    ->orWhereIn('exercises.id', function ($sub) use ($parsedFilters) {
                        $sub->select('exerciseId')->from('exercises_bodygroups')
                            ->whereNull('deleted_at')
                            ->whereIn('bodygroupId', $parsedFilters['bodygroups']);
                    });
            });
        }

        if (!empty($parsedFilters['types'])) {
            $withQuery->whereIn('exercisesTypesId', $parsedFilters['types']);
        }

        if (!empty($parsedFilters['equipments'])) {
            $withQuery->whereIn('exercises_equipments.equipmentId', $parsedFilters['equipments']);
        }

        $withResults = collect($withQuery->limit($limit)->get());

        // ---------------- Merge & Sort ----------------
        $baseResults = $baseResults->filter(fn($e) => $e->equipmentRequired == 0);

        $merged = collect($baseResults)
            ->merge($withResults)
            ->unique(fn($item) => $item->id . '-' . ($item->equipmentId ?? '0'));

        $merged = $merged->sortBy([
            ['favorite', 'desc'],
            ['type', 'asc'],
            ['scoreName', 'desc'],
            ['scoreNameEngine', 'desc'],
            ['length', 'asc'],
        ])->values();

        return $merged->take($limit);
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
            $result = DB::select($query, array(Lang::get("content.with"), App::getLocale(), App::getLocale(), $userId, $userId, App::getLocale(), $userId, $userId));

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
            $result = DB::select($query, array(Lang::get("content.with"), "*" . $search . "*", "*" . $search . "*", "*" . $search . "*", "*" . $search . "*", "*" . $search . "*", "*" . $search . "*", "*" . $search . "*", "*" . $search . "*", "*" . $search . "*", Lang::get("content.with"), App::getLocale(), App::getLocale(), $userId, $userId, "*" . $search . "*", "*" . $search . "*", "*" . $search . "*", "*" . $search . "*", "*" . $search . "*", App::getLocale(), $userId, $userId));

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
        return $this->hasMany(ExercisesBodyGroups::class, "exerciseId", "id")->with("bodygroup");
    }

    public function bodyGroups()
    {
        return $this->belongsToMany(BodyGroups::class, 'exercises_bodygroups', 'exerciseId', 'bodygroupId');
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
