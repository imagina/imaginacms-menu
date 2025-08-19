<?php

namespace Modules\Imenu\Models;

use Astrotomic\Translatable\Translatable;
use Imagina\Icore\Models\CoreModel;

class Menu extends CoreModel
{
  use Translatable;

  protected $table = 'imenu__menus';
  public string $transformer = 'Modules\Imenu\Transformers\MenuTransformer';
  public string $repository = 'Modules\Imenu\Repositories\MenuRepository';
  public array $requestValidation = [
    'create' => 'Modules\Imenu\Http\Requests\CreateMenuRequest',
    'update' => 'Modules\Imenu\Http\Requests\UpdateMenuRequest',
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
  public array $translatedAttributes = [
    'title',
    'status'
  ];
  protected $fillable = [
    'system_name',
    'primary',
  ];

  public function menuitems()
  {
    return $this->hasMany('Modules\Imenu\Models\MenuItem')->with('translations')->orderBy('position', 'asc');
  }
}
