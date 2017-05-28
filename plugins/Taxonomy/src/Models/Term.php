<?php

namespace Taxonomy\Models;

use Mini\Database\ORM\Model as BaseModel;
use Mini\Database\ORM\ModelNotFoundException;


class Term extends BaseModel
{
	protected $table = 'terms';

	protected $primaryKey = 'id';

	protected $fillable = array(
		'name', 'description', 'vocabulary_id', 'parent', 'weight',
	);

	protected $hidden = array(
		'created_at','updated_at'
	);


	public function termRelations()
	{
		return $this->morphMany('Taxonomy\Models\TermRelation', 'relationable');
	}

	public function vocabulary()
	{
		return $this->belongsTo('Taxonomy\Models\Vocabulary');
	}

	public function children()
	{
		return $this->hasMany('Taxonomy\Models\Term', 'parent_id', 'id')->orderBy('weight', 'ASC');
	}

	public function parent()
	{
		return $this->belongsTo('Taxonomy\Models\Term', 'parent_id', 'id');
	}
}