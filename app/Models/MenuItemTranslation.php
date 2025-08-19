<?php

namespace Modules\Imenu\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItemTranslation extends Model
{
  public $timestamps = false;
  protected $fillable = ['title', 'uri', 'url', 'status', 'locale', 'description'];
  protected $table = 'imenu__menu_item_translations';
}
