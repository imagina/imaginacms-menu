<?php

namespace Modules\Menu\Entities;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Menu extends Model
{
    use Translatable, BelongsToTenant;

    protected $fillable = [
        'name',
        'title',
        'status',
        'primary',
    ];
    public $translatedAttributes = ['title', 'status'];
    protected $table = 'menu__menus';

    public function menuitems()
    {
        return $this->hasMany('Modules\Menu\Entities\Menuitem')->with("translations")->orderBy('position', 'asc');
    }
}
