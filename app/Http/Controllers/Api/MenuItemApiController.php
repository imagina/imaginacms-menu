<?php

namespace Modules\Imenu\Http\Controllers\Api;

use Imagina\Icore\Http\Controllers\CoreApiController;
//Model
use Modules\Imenu\Models\MenuItem;
use Modules\Imenu\Repositories\MenuItemRepository;

class MenuItemApiController extends CoreApiController
{
  public function __construct(MenuItem $model, MenuItemRepository $modelRepository)
  {
    parent::__construct($model, $modelRepository);
  }
}
