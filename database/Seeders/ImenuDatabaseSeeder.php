<?php

namespace Modules\Imenu\Database\Seeders;

use Illuminate\Database\Seeder;

class ImenuDatabaseSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $this->call([InitialMenuSeeder::class]);
  }
}
