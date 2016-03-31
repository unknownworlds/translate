<?php
/**
 * Copyright (c) Unknown Worlds Entertainment, 2016. 
 * Created by Lukas Nowaczek <lukas@unknownworlds.com> <@lnowaczek>
 * Visit http://unknownworlds.com/
 * This file is a part of proprietary software. 
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model {

	protected $fillable = [ 'name', 'file_handler', 'api_key' ];

}
