<?php

namespace Modules\Menu\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Modules\Core\Icrud\Repositories\Eloquent\EloquentCrudRepository;
use Modules\Menu\Events\MenuItemIsCreating;
use Modules\Menu\Events\MenuItemIsUpdating;
use Modules\Menu\Events\MenuItemWasCreated;
use Modules\Menu\Events\MenuItemWasUpdated;
use Modules\Menu\Repositories\MenuItemRepository;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class EloquentMenuItemRepository extends EloquentCrudRepository implements MenuItemRepository
{
  /**
   * Filter names to replace
   * @var array
   */
  protected $replaceFilters = [];

  /**
   * Relation names to replace
   * @var array
   */
  protected $replaceSyncModelRelations = [];

  /**
   * Filter query
   *
   * @param $query
   * @param $filter
   * @param $params
   * @return mixed
   */
  public function filterQuery($query, $filter, $params)
  {

    /**
     * Note: Add filter name to replaceFilters attribute before replace it
     *
     * Example filter Query
     * if (isset($filter->status)) $query->where('status', $filter->status);
     *
     */

    // Filter By Menu
    if (isset($filter->menu)) {
      $query->where('menu_id', $filter->menu);
    }

    //add filter by search
    if (isset($filter->search)) {
      //find search in columns
      $query->where(function ($query) use ($filter) {
        $query->whereHas('translations', function ($query) use ($filter) {
          $query->where('locale', $filter->locale)
            ->where('title', 'like', '%' . $filter->search . '%');
        })->orWhere('id', 'like', '%' . $filter->search . '%')
          ->orWhere('updated_at', 'like', '%' . $filter->search . '%')
          ->orWhere('created_at', 'like', '%' . $filter->search . '%');
      });
    }

    $this->validateTenantWithCentralData($query);

    //Response
    return $query;
  }

  /**
   * Method to sync Model Relations
   *
   * @param $model ,$data
   * @return $model
   */
  public function syncModelRelations($model, $data)
  {
    //Get model relations data from attribute of model
    $modelRelationsData = ($model->modelRelations ?? []);

    /**
     * Note: Add relation name to replaceSyncModelRelations attribute before replace it
     *
     * Example to sync relations
     * if (array_key_exists(<relationName>, $data)){
     *    $model->setRelation(<relationName>, $model-><relationName>()->sync($data[<relationName>]));
     * }
     *
     */

    //Response
    return $model;
  }

  public function validateTenantWithCentralData($query)
  {
    $entitiesWithCentralData = json_decode(setting('isite::tenantWithCentralData', null, '[]', true));
    $tenantWithCentralData = in_array('menuitem', $entitiesWithCentralData);

    if ($tenantWithCentralData && isset(tenant()->id)) {
      $model = $this->model;

      $query->withoutTenancy();
      $query->where(function ($query) use ($model) {
        $query->where($model->qualifyColumn(BelongsToTenant::$tenantIdColumn), tenant()->getTenantKey())
          ->orWhereNull($model->qualifyColumn(BelongsToTenant::$tenantIdColumn));
      });
    } else {
      // Validation like DEEV
      // When user is going to pay the plan in central checkout
      if (config("tenancy.mode") != NULL && config("tenancy.mode") == "singleDatabase" && is_null(tenant()))
        $query->where("organization_id", null);
    }

  }

  public function create($data)
  {
    event($event = new MenuItemIsCreating($data));

    $data = $event->getAttributes();

    //force it into the system name setter
    $data['system_name'] = $data['system_name'] ?? '';

    $model = parent::create($data); // TODO: Change the autogenerated stub

    event(new MenuItemWasCreated($model));

    return $model;
  }

  public function update($menuItem, $data)
  {
    event($event = new MenuItemIsUpdating($menuItem, $data));

    $model = parent::update($event->getAttributes(), $data); // TODO: Change the autogenerated stub

    event(new MenuItemWasUpdated($model));

    return $model;
  }

  /**
   * Get online root elements
   */
  public function rootsForMenu($menuId)
  {
    return $this->model->whereHas('translations', function (Builder $q) {
      $q->where('status', 1);
      $q->where('locale', App::getLocale());
    })->with('translations')->whereMenuId($menuId)->orderBy('position')->get();
  }

  /**
   * Get all root elements
   */
  public function allRootsForMenu($menuId)
  {
    return $this->model->with('translations')->whereMenuId($menuId)->orderBy('parent_id')->orderBy('position')->get();
  }

  /**
   * Get Items to build routes
   */
  public function getForRoutes()
  {
    $menuitems = DB::table('menu__menus')
      ->select(
        'primary',
        'menu__menuitems.id',
        'menu__menuitems.parent_id',
        'menu__menuitems.module_name',
        'menu__menuitem_translations.uri',
        'menu__menuitem_translations.locale'
      )
      ->join('menu__menuitems', 'menu__menus.id', '=', 'menu__menuitems.menu_id')
      ->join('menu__menuitem_translations', 'menu__menuitems.id', '=', 'menu__menuitem_translations.menuitem_id')
      ->where('uri', '!=', '')
      ->where('module_name', '!=', '')
      ->where('status', '=', 1)
      ->where('primary', '=', 1)
      ->orderBy('module_name')
      ->get();

    $menuitemsArray = [];
    foreach ($menuitems as $menuitem) {
      $menuitemsArray[$menuitem->module_name][$menuitem->locale] = $menuitem->uri;
    }

    return $menuitemsArray;
  }

  /**
   * Get the root menu item for the given menu id
   */
  public function getRootForMenu($menuId)
  {
    return $this->model->with('translations')->where(['menu_id' => $menuId, 'is_root' => true])->firstOrFail();
  }

  /**
   * Return a complete tree for the given menu id
   */
  public function getTreeForMenu($menuId)
  {
    $items = $this->rootsForMenu($menuId);

    return $items->noCleaning()->nest();
  }

  /**
   * @param string $uri
   * @param string $locale
   * @return object
   */
  public function findByUriInLanguage($uri, $locale)
  {
    return $this->model->whereHas('translations', function (Builder $q) use ($locale, $uri) {
      $q->where('status', 1);
      $q->where('locale', $locale);
      $q->where('uri', $uri);
    })->with('translations')->first();
  }

  public function updateBy($criteria, $data, $params = false)
  {
    $model = parent::getitem($criteria, $params); // TODO: Change the autogenerated stub

    //Update menu item
    event($event = new MenuItemIsUpdating($model, $data));

    $model = parent::update($event->getAttributes(), $data); // TODO: Change the autogenerated stub

    event(new MenuItemWasUpdated($model));

    return $model;
  }

  public function updateItems($criterias, $data)
  {
    $query = $this->model->query();
    $query->whereIn('id', $criterias)->update($data);

    return $query;
  }

  public function deleteItems($criterias)
  {
    $query = $this->model->query();

    $query->whereIn('id', $criterias)->delete();

    return $query;
  }

  public function updateOrders($data)
  {
    $menuitems = [];
    foreach ($data['menuitems'] as $menuitem) {
      $menuitems[] = $this->model->find($menuitem['id'])
        ->update([
          'position' => $menuitem['position'],
          'parent_id' => $menuitem['parent_id'],
        ]);
    }

    return $menuitems;
  }
}