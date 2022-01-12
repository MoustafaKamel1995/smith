<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;

class CmsFaq extends Model
{
	protected $connection = 'common';

	protected $table = 'cms_faqs';
	protected $fillable = ['question', 'answer'];

}