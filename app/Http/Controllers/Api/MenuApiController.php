<?php

namespace Modules\Imenu\Http\Controllers\Api;

use Imagina\Icore\Http\Controllers\CoreApiController;
//Model
use Modules\Imenu\Models\Menu;
use Modules\Imenu\Repositories\MenuRepository;

class MenuApiController extends CoreApiController
{
  public function __construct(Menu $model, MenuRepository $modelRepository)
  {
    parent::__construct($model, $modelRepository);
  }
}
