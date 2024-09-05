<?php

namespace Modules\Menu\Transformers;

use Illuminate\Support\Facades\Cache;
use Modules\Core\Icrud\Transformers\CrudResource;

class MenuTransformer extends CrudResource
{
  /**
   * Method to merge values with response
   *
   * @return array
   */
  public function modelAttributes($request)
  {
    return [];
  }
}
