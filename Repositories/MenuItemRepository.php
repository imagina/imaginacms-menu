<?php

namespace Modules\Menu\Repositories;

use Modules\Core\Icrud\Repositories\BaseCrudRepository;

interface MenuItemRepository extends BaseCrudRepository
{
  /**
   * Get online root elements
   *
   * @param int $menuId
   * @return object
   */
  public function rootsForMenu(int $menuId);

  /**
   * Get all root elements
   *
   * @param int $menuId
   * @return object
   */
  public function allRootsForMenu($menuId);

  /**
   * Get the menu items ready for routes
   *
   * @return mixed
   */
  public function getForRoutes();

  /**
   * Get the root menu item for the given menu id
   *
   * @param int $menuId
   * @return object
   */
  public function getRootForMenu($menuId);

  /**
   * Return a complete tree for the given menu id
   *
   * @param int $menuId
   * @return object
   */
  public function getTreeForMenu($menuId);

  /**
   * @param string $uri
   * @param string $locale
   * @return object
   */
  public function findByUriInLanguage($uri, $locale);

  /**
   * Update the Menu Items for the given ids
   *
   * @param array $criterias
   * @param array $data
   * @return bool
   */
  public function updateItems($criterias, $data);

  /**
   * Delete the Menu Items for the given ids
   *
   * @param array $criterias
   * @return bool
   */
  public function deleteItems($criterias);

  /**
   * Update Orders the Menu Items
   *
   * @param array $data
   * @return bool
   */
  public function updateOrders($data);
}