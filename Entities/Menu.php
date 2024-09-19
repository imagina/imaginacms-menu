<?php

namespace Modules\Menu\Entities;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Icrud\Entities\CrudModel;

use Modules\Isite\Entities\Module;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Menu extends CrudModel
{
  use Translatable, BelongsToTenant;

  protected $table = 'menu__menus';
  public $transformer = 'Modules\Menu\Transformers\MenuTransformer';
  public $repository = 'Modules\Menu\Repositories\MenuRepository';
  public $requestValidation = [
    'create' => 'Modules\Menu\Http\Requests\CreateMenuRequest',
    'update' => 'Modules\Menu\Http\Requests\UpdateMenuRequest',
  ];
  //Instance external/internal events to dispatch with extraData
  public $dispatchesEventsWithBindings = [
    //eg. ['path' => 'path/module/event', 'extraData' => [/*...optional*/]]
    'created' => [],
    'creating' => [],
    'updated' => [],
    'updating' => [],
    'deleting' => [],
    'deleted' => []
  ];
  protected $fillable = [
    'name',
    'title',
    'status',
    'primary',
  ];

  public $translatedAttributes = ['title', 'status'];


  public function menuitems()
  {
    return $this->hasMany('Modules\Menu\Entities\Menuitem')->with('translations')->orderBy('position', 'asc');
  }
}
