<?php

namespace Modules\Imenu\Repositories\Cache;

use Modules\Imenu\Repositories\MenuRepository;
use Imagina\Icore\Repositories\Cache\CoreCacheDecorator;

class CacheMenuDecorator extends CoreCacheDecorator implements MenuRepository
{
    public function __construct(MenuRepository $menu)
    {
        parent::__construct();
        $this->entityName = 'imenu.menus';
        $this->repository = $menu;
    }
}
