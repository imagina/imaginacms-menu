<?php

namespace Modules\Imenu\Repositories\Cache;

use Modules\Imenu\Repositories\MenuItemRepository;
use Imagina\Icore\Repositories\Cache\CoreCacheDecorator;

class CacheMenuItemDecorator extends CoreCacheDecorator implements MenuItemRepository
{
    public function __construct(MenuItemRepository $menuitem)
    {
        parent::__construct();
        $this->entityName = 'imenu.menuitems';
        $this->repository = $menuitem;
    }
}
