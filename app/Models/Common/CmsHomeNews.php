<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;

class CmsHomeNews extends Model
{
    protected $connection = 'common';

	protected $table = 'cms_home_news';
	protected $fillable = ['heading', 'content', 'photo'];
}
