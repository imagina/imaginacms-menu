<?php

namespace Modules\Menu\Blade;

use Illuminate\Support\Arr;

final class MenuDirective
{
    private $name;

    private $presenter;

    private $bindings;

    public function show($arguments)
    {
        $this->extractArguments($arguments);

        return $this->returnMenu();
    }

    /**
     * Extract the possible arguments as class properties
     */
    private function extractArguments(array $arguments)
    {
        $this->name = Arr::get($arguments, 0);
        $this->presenter = Arr::get($arguments, 1);
        $this->bindings = Arr::get($arguments, 2, []);
    }

    /**
     * Prepare arguments and return menu
     *
     * @return string|null
     */
    private function returnMenu()
    {
        $customPresenter = config('asgard.menu.config.default_menu_presenter');
        if ($this->presenter === null && $customPresenter !== null) {
            $this->presenter = $customPresenter;
        }

        return app('menus')->get($this->name, $this->presenter, $this->bindings);
    }

    public function __toString()
    {
        return $this->returnMenu();
    }
}
