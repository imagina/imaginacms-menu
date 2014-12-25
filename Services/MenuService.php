<?php namespace Modules\Menu\Services;

use Illuminate\Contracts\Cache\Repository;
use Modules\Menu\Repositories\MenuItemRepository;

class MenuService
{
    /**
     * Current Menu Item being looped over
     *
     * @var
     */
    protected $menuItem;
    /**
     * @var MenuItemRepository
     */
    private $menuItemRepository;
    /**
     * @var Repository
     */
    private $cache;

    /**
     * @param MenuItemRepository $menuItem
     * @param Repository $cache
     */
    public function __construct(MenuItemRepository $menuItem, Repository $cache)
    {
        $this->menuItemRepository = $menuItem;
        $this->cache = $cache;
    }

    /**
     * Perform needed operations on given menu item and set its position
     *
     * @param $item
     * @param int $position
     */
    public function handle($item, $position)
    {
        $this->menuItem = $this->menuItemRepository->find($item['id']);

        $rootItem = $this->cache->rememberForever("root.item.for.menu-{$this->menuItem->id}", function() {
            return $this->menuItemRepository->getRootForMenu($this->menuItem->menu_id);
        });
        $this->savePosition($this->menuItem, $position);

        if ( ! $this->menuItem->isRoot() && $this->menuItem->parent_id != $rootItem->parent_id) {
            $this->menuItem->makeChildOf($rootItem);
        }

        if ($this->hasChildren($item)) {
            $this->setChildrenRecursively($item, $this->menuItem);
        }
    }

    /**
     * Sets the children of the given item
     *
     * @param $item
     * @param $parent
     */
    private function setChildrenRecursively($item, $parent)
    {
        foreach ($item['children'] as $childPosition => $childItem) {
            $childMenuItem = $this->menuItemRepository->find($childItem['id']);
            $this->savePosition($childMenuItem, $childPosition);
            $childMenuItem->makeChildOf($parent);
            if ($this->hasChildren($childItem)) {
                $this->setChildrenRecursively($childItem, $childMenuItem);
            }
        }
    }

    /**
     * Check if the item has children
     *
     * @param $item
     * @return bool
     */
    private function hasChildren($item)
    {
        return isset($item['children']);
    }

    /**
     * Save the position of the given item
     *
     * @param $item
     * @param $position
     */
    private function savePosition($item, $position)
    {
        $item->position = $position;
        $item->save();
    }
}