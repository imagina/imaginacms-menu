<?php

namespace Modules\Imenu\Models;

use Illuminate\Database\Eloquent\Model;

class MenuTranslation extends Model
{
  public $timestamps = false;
  protected $fillable = ['title', 'status'];
  protected $table = 'imenu__menu_translations';
}
