<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines contain the default error messages used by
	| the validator class. Some of these rules have multiple versions such
	| as the size rules. Feel free to tweak each of these messages here.
	|
	*/

	"accepted"             => ":attribute doit être accepté.",
	"active_url"           => ":attribute n'est pas un URL valide.",
	"after"                => ":attribute doit être une date après le :date.",
	"alpha"                => ":attribute doit être composé que de lettres.",
	"alpha_dash"           => ":attribute doit être composé que de lettres, chiffres ou de tirets.",
	"alpha_num"            => ":attribute doit seulement être composé de lettres et de chiffres.",
	"array"                => ":attribute doit être un tableau.",
	"before"               => ":attribute doit être une date avant le :date.",
	"between"              => array(
		"numeric" => ":attribute doit se maintenir entre :min et :max.",
		"file"    => ":attribute doit se maintenir entre :min et :max kilobytes.",
		"string"  => ":attribute doit contenir entre :min et :max caractères.",
		"array"   => ":attribute doit contenir entre :min et :max éléments.",
	),
	"confirmed"            => "La confirmation de :attribute ne correspond pas.",
	"date"                 => ":attribute n'est pas une date valide.",
	"date_format"          => "Le format de :attribute ne correspond pas à :format.",
	"different"            => ":attribute et :other doivent être différents.",
	"digits"               => ":attribute doit contenir :digits chiffres.",
	"digits_between"       => ":attribute doit avoir de :min à :max chiffres.",
	"email"                => ":attribute doit être une adresse email valide.",
	"exists"               => ":attribute n'est pas valide.",
	"image"                => ":attribute doit être une image.",
	"in"                   => ":attribute n'est pas valide.",
	"integer"              => ":attribute doit être un chiffre entier.",
	"ip"                   => ":attribute doit être une adresse IP valide.",
	"max"                  => array(
		"numeric" => ":attribute ne peut être plus grand que :max.",
		"file"    => ":attribute ne peut être plus grand que :max kilobytes.",
		"string"  => ":attribute ne peut contenir plus de :max caractères.",
		"array"   => ":attribute ne peut contenir plus de :max éléments.",
	),
	"mimes"                => ":attribute doit être un fichier de type: :values.",
	"min"                  => array(
		"numeric" => ":attribute doit être de minimum :min.",
		"file"    => ":attribute doit être de minimum :min kilobytes.",
		"string"  => ":attribute doit contenir au moins :min caractères.",
		"array"   => ":attribute doit contenir au moins :min éléments.",
	),
	"not_in"               => ":attribute n'est pas valide.",
	"numeric"              => ":attribute doit être un chiffre.",
	"regex"                => "Le format de :attribute n'est pas valide.",
	"required"             => "Le champs de :attribute field est obligatoire.",
	"required_if"          => "Le champs :attribute est obligatoire lorsquw :other est :value.",
	"required_with"        => "Le champs :attribute est obligatoire lorsque :values est présent.",
	"required_with_all"    => "Le champs :attribute est obligatoire lorsque :values est présent.",
	"required_without"     => "Le champs :attribute est obligatoire lorsque :values n'est pas présent.",
	"required_without_all" => "Le champs :attribute est obligatoire lorsqu'aucun :values n'est présent.",
	"same"                 => "T:attribute et :other doivent correspondre.",
	"size"                 => array(
		"numeric" => ":attribute doit être de format :size.",
		"file"    => ":attribute doit être de :size kilobytes.",
		"string"  => ":attribute doit contenir :size caractères.",
		"array"   => ":attribute doit contenir :size éléments.",
	),
	"unique"               => ":attribute a déjà été sélectionné.",
	"url"                  => ":Le format de :attribute n'est pas valide.",

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| Here you may specify custom validation messages for attributes using the
	| convention "attribute.rule" to name the lines. This makes it quick to
	| specify a specific custom language line for a given attribute rule.
	|
	*/

	'custom' => array(
		'attribute-name' => array(
			'rule-name' => 'message personnalisé',
		),
	),

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Attributes
	|--------------------------------------------------------------------------
	|
	| The following language lines are used to swap attribute place-holders
	| with something more reader friendly such as E-Mail Address instead
	| of "email". This simply helps us make messages a little cleaner.
	|
	*/

	'attributes' => array(),

);
