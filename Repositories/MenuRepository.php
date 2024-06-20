<?php

namespace Modules\Menu\Repositories;

use Modules\Core\Icrud\Repositories\BaseCrudRepository;

interface MenuRepository extends BaseCrudRepository
{
  public function allOnline();
}