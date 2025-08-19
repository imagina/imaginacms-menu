<?php

namespace Modules\Imenu\Models;

use Astrotomic\Translatable\Translatable;
use Imagina\Icore\Models\CoreModel;
use Illuminate\Database\Eloquent\Casts\Attribute;

class MenuItem extends CoreModel
{
  use Translatable;

  protected $table = 'imenu__menu_items';
  public string $transformer = 'Modules\Imenu\Transformers\MenuItemTransformer';
  public string $repository = 'Modules\Imenu\Repositories\MenuItemRepository';
  public array $requestValidation = [
    'create' => 'Modules\Imenu\Http\Requests\CreateMenuItemRequest',
    'update' => 'Modules\Imenu\Http\Requests\UpdateMenuItemRequest',
  ];
  //Instance external/internal events to dispatch with extraData
  public array $dispatchesEventsWithBindings = [
    //eg. ['path' => 'path/module/event', 'extraData' => [/*...optional*/]]
    'created' => [],
    'creating' => [],
    'updated' => [],
    'updating' => [],
    'deleting' => [],
    'deleted' => []
  ];
  public array $translatedAttributes = ['title', 'uri', 'url', 'status', 'locale', 'description'];
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

  public function menu()
  {
    return $this->belongsTo(Menu::class);
  }

  /**
   * Check if page_id is empty and returning null instead empty string
   */
  public function pageId(): Attribute
  {
    return Attribute::make(
      get: fn(?int $value) => !empty($value) ? $value : null
    );
  }

  /**
   * Check if parent_id is empty and returning null instead empty string
   */
  public function parentId(): Attribute
  {
    return Attribute::make(
      get: fn(?int $value) => is_null($value) ? 0 : $value,
      set: fn(?int $value) => !empty($value) ? $value : null,
    );
  }

  public function systemName(): Attribute
  {
    return Attribute::make(
      get: fn(?string $value) => !empty($value) ? $value : \Str::slug($this->title, '-'),
    );
  }
}
