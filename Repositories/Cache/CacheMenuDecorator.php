<?php

namespace Modules\Menu\Repositories\Cache;

use Modules\Core\Icrud\Repositories\Cache\BaseCacheCrudDecorator;
use Modules\Menu\Repositories\MenuRepository;

class CacheMenuDecorator extends BaseCacheCrudDecorator implements MenuRepository
{
  /**
   *
   * @var MenuRepository
   */
  protected $repository;

  public function __construct(MenuRepository $menu)
  {
    parent::__construct();
    $this->entityName = 'menus';
    $this->repository = $menu;
  }

  /**
   * Get all online menus
   */
  public function allOnline()
  {
    return $this->remember(function () {
      return $this->repository->allOnline();
    });
  }
}