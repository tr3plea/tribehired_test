<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'post_id', 'message'
    ];
	
	public function post()
    {
        return $this->belongsTo('App\Post', 'post_id', 'id');
    }
	
	public function scopeKeyword($query, $keyword)
    {
        if ($keyword) {
            $key = "%" . strtolower($keyword) . "%";
			
			return $query->whereRaw("LOWER(comments.message) LIKE ?", [$key])
						->orWhereHas('post', function($q) use ($key){
								return $q->whereRaw("LOWER(posts.title) LIKE ?", [$key])->orWhereRaw("LOWER(posts.body) LIKE ?", [$key]);
							});
        }
    }
	
	public function scopeCreatedDate($query, $date)
    {
        if ($date) {
			return $query->whereDate('created_at',$date);
        }
    }
}
