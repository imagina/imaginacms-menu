<?php

namespace Modules\Imenu\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Imenu\Models\Menu;
use Modules\Imenu\Repositories\MenuItemRepository;
use Modules\Imenu\Repositories\MenuRepository;

class InitialMenuSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    if (Menu::count() === 0) {
      $menuRepo = app(MenuRepository::class);
      $itemRepo = app(MenuItemRepository::class);

      $menu = $menuRepo->create([
        'system_name' => 'main-menu',
        'primary' => true,
        'en' => [
          'title' => 'Main Menu',
          'status' => 1,
        ],
        'es' => [
          'title' => 'Menú Principal',
          'status' => 1,
        ]
      ]);
      $menuItems = [
        [
          'menu_id' => $menu->id,
          'system_name' => 'home',
          'position' => 1,
          'target' => '_self',
          'link_type' => 'internal',
          'en' => [
            'title' => 'Home',
            'uri' => 'home',
            'url' => '/home',
            'status' => 1,
            'locale' => 'en',
            'description' => 'Go to homepage',
          ],
          'es' => [
            'title' => 'Inicio',
            'uri' => 'inicio',
            'url' => '/inicio',
            'status' => 1,
            'locale' => 'es',
            'description' => 'Ir a inicio',
          ]
        ],
        [
          'menu_id' => $menu->id,
          'system_name' => 'about-us',
          'position' => 2,
          'target' => '_self',
          'link_type' => 'internal',
          'en' => [
            'title' => 'About Us',
            'uri' => 'about-us',
            'url' => '/about-us',
            'status' => 1,
            'locale' => 'en',
            'description' => 'Learn more about us',
          ],
          'es' => [
            'title' => 'Nosotros',
            'uri' => 'nosotros',
            'url' => '/nosotros',
            'status' => 1,
            'locale' => 'es',
            'description' => 'Conócenos',
          ]
        ],
        [
          'menu_id' => $menu->id,
          'system_name' => 'contact',
          'position' => 3,
          'target' => '_self',
          'link_type' => 'internal',
          'en' => [
            'title' => 'Contact',
            'uri' => 'contact',
            'url' => '/contact',
            'status' => 1,
            'locale' => 'en',
            'description' => 'Contact us',
          ],
          'es' => [
            'title' => 'Contacto',
            'uri' => 'contacto',
            'url' => '/contacto',
            'status' => 1,
            'locale' => 'es',
            'description' => 'Contáctanos',
          ]
        ],
      ];
      foreach ($menuItems as $item) {
        $itemRepo->create($item);
      }
    }
  }
}
