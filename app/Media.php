<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Media extends Model
{
    public $table = "medias";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nickname', 'description', 'thumbnail', 'active', 'category_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'visitor'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function totalVotes()
    {
        return DB::table('media_vote')->where('media_id', '=', $this->id)->count();
    }

    public function voters()
    {
        return $this->belongsToMany('App\User', 'media_vote');
    }

    public function files()
    {
        return $this->hasMany('App\File');
    }
}