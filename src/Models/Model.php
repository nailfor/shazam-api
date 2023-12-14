<?php

namespace nailfor\shazam\API\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    use JsonizeTrait;
    use JoinTrait;

    public const TABLE_NAME = '';

    protected static $unguarded = false;

    protected $table = '';

    public function __construct($attributes = [])
    {
        $this->table = static::TABLE_NAME;
        parent::__construct($attributes);
    }

    /**
     * @inheritdoc
     */
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }
}
