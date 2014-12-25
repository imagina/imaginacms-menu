<?php namespace Modules\Menu\Http\Controllers\Admin;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Redirector;
use Laracasts\Flash\Flash;
use Modules\Menu\Entities\Menu;
use Modules\Menu\Entities\Menuitem;
use Modules\Menu\Http\Requests\CreateMenuItemRequest;
use Modules\Menu\Http\Requests\UpdateMenuItemRequest;
use Modules\Menu\Repositories\MenuItemRepository;
use Modules\Page\Repositories\PageRepository;

class MenuItemController
{
    /**
     * @var MenuItemRepository
     */
    private $menuItem;
    /**
     * @var Redirector
     */
    private $redirector;
    /**
     * @var PageRepository
     */
    private $page;
    /**
     * @var Repository
     */
    private $cache;

    public function __construct(MenuItemRepository $menuItem, Redirector $redirector, PageRepository $page, Repository $cache)
    {
        $this->menuItem = $menuItem;
        $this->redirector = $redirector;
        $this->page = $page;
        $this->cache = $cache;
    }

    public function create(Menu $menu)
    {
        $pages = $this->page->all();

        return view('menu::admin.menuitems.create', compact('menu', 'pages'));
    }

    public function store(Menu $menu, CreateMenuItemRequest $request)
    {
        $menuItem = $this->menuItem->create($this->addMenuId($menu, $request));

        $rootItem = $this->cache->rememberForever("root.item.for.menu-{$menuItem->id}", function() use($menuItem) {
            return $this->menuItem->getRootForMenu($menuItem->menu_id);
        });

        $menuItem->makeChildOf($rootItem);

        Flash::success(trans('menu::messages.menuitem created'));
        return $this->redirector->route('dashboard.menu.edit', [$menu->id]);
    }

    public function edit(Menu $menu, Menuitem $menuItem)
    {
        $pages = $this->page->all();

        return view('menu::admin.menuitems.edit', compact('menu', 'menuItem', 'pages'));
    }

    public function update(Menu $menu, Menuitem $menuItem, UpdateMenuItemRequest $request)
    {
        $this->menuItem->update($menuItem, $this->addMenuId($menu, $request));

        Flash::success(trans('menu::messages.menuitem updated'));
        return $this->redirector->route('dashboard.menu.edit', [$menu->id]);
    }

    /**
     * @param Menu $menu
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @return array
     */
    private function addMenuId(Menu $menu, FormRequest $request)
    {
        return array_merge($request->all(), ['menu_id' => $menu->id]);
    }
}