<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;

class CmsHowItWork extends Model
{
    protected $connection = 'common';

	protected $table = 'cms_how_it_works';
	protected $fillable = ['step', 'heading', 'content'];
}
