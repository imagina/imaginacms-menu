<?php

namespace Modules\Menu\Entities;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Icrud\Entities\CrudModel;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use TypiCMS\NestableTrait;

class Menuitem extends CrudModel
{
  use Translatable, NestableTrait, BelongsToTenant;

  protected $table = 'menu__menuitems';
  public $transformer = 'Modules\Menu\Transformers\MenuitemTransformer';
  public $repository = 'Modules\Menu\Repositories\MenuItemRepository';
  public $requestValidation = [
    'create' => 'Modules\Menu\Http\Requests\CreateMenuItemRequest',
    'update' => 'Modules\Menu\Http\Requests\UpdateMenuItemRequest',
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

  public $translatedAttributes = ['title', 'uri', 'url', 'status', 'locale', 'description'];

  protected $fillable = [
    'menu_id',
    'page_id',
    'system_name',
    'parent_id',
    'position',
    'target',
    'module_name',
    'is_root',
    'icon',
    'link_type',
    'class',
  ];

  /**
   * For nested collection
   *
   * @var array
   */
  public $children = [];

  public function menu()
  {
    return $this->belongsTo(Menu::class);
  }

  /**
   * Make the current menu item child of the given root item
   */
  public function makeChildOf(Menuitem $rootItem)
  {
    $this->parent_id = $rootItem->id;
    $this->save();
  }

  /**
   * Check if the current menu item is the root
   */
  public function isRoot()
  {
    return (bool)$this->is_root;
  }

  /**
   * Check if page_id is empty and returning null instead empty string
   */
  public function setPageIdAttribute($value)
  {
    $this->attributes['page_id'] = !empty($value) ? $value : null;
  }

  /**
   * Check if parent_id is empty and returning null instead empty string
   */
  public function setParentIdAttribute($value)
  {
    $this->attributes['parent_id'] = !empty($value) ? $value : null;
  }

  public function getParentIdAttribute($value)
  {
    return intval(is_null($value) ? 0 : $value);
  }

  public function setSystemNameAttribute($value)
  {
    $this->attributes['system_name'] = !empty($value) ? $value : \Str::slug($this->title, '-');
  }
}
