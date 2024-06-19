<?php

namespace Modules\Menu\Repositories\Cache;

use Modules\Core\Icrud\Repositories\Cache\BaseCacheCrudDecorator;
use Modules\Menu\Repositories\MenuItemRepository;

class CacheMenuItemDecorator extends BaseCacheCrudDecorator implements MenuItemRepository
{
  /**
   * @var MenuItemRepository
   */
  protected $repository;

  public function __construct(MenuItemRepository $menuItem)
  {
    parent::__construct();
    $this->entityName = 'menusItems';
    $this->tags = 'menus';
    $this->repository = $menuItem;
  }

  /**
   * Get all root elements
   *
   * @return mixed
   */
  public function rootsForMenu(int $menuId)
  {
    return $this->remember(function () use ($menuId) {
      return $this->repository->rootsForMenu($menuId);
    });
  }

  /**
   * Get the menu items ready for routes
   *
   * @return mixed
   */
  public function getForRoutes()
  {
    return $this->remember(function () {
      return $this->repository->getForRoutes();
    });
  }

  /**
   * Get the root menu item for the given menu id
   */
  public function getRootForMenu($menuId)
  {
    return $this->remember(function () use ($menuId) {
      return $this->repository->getRootForMenu($menuId);
    });
  }

  /**
   * Return a complete tree for the given menu id
   */
  public function getTreeForMenu($menuId)
  {
    return $this->remember(function () use ($menuId) {
      return $this->repository->getTreeForMenu($menuId);
    });
  }

  /**
   * Get all root elements
   */
  public function allRootsForMenu($menuId)
  {
    return $this->remember(function () use ($menuId) {
      return $this->repository->allRootsForMenu($menuId);
    });
  }

  public function findByUriInLanguage($uri, $locale)
  {
    return $this->remember(function () use ($uri, $locale) {
      return $this->repository->findByUriInLanguage($uri, $locale);
    });
  }

  /**
   * Update the Menu Items for the given ids
   */
  public function updateItems($criterias, $data)
  {
    $this->cache->tags($this->getTags())->flush();

    return $this->repository->updateItems($criterias, $data);
  }

  /**
   * Delete the Menu Items for the given ids
   */
  public function deleteItems($criterias)
  {
    $this->cache->tags($this->getTags())->flush();

    return $this->repository->deleteItems($criterias);
  }

  /**
   * Update Orders the Menu Items
   */
  public function updateOrders($data)
  {

    $this->cache->tags($this->getTags())->flush();

    return $this->repository->updateOrders($data);
  }
}
