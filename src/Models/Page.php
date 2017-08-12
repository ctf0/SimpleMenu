<?php

namespace ctf0\SimpleMenu\Models;

use Baum\Node;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Translatable\HasTranslations;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class Page extends Node
{
    use HasRoles, HasTranslations, ClearCacheTrait;

    protected $with       = ['roles', 'permissions', 'menus'];
    protected $appends    = ['nests'];
    public $translatable  = ['title', 'body', 'desc', 'prefix', 'url'];
    protected $guard_name = 'web';
    protected $hidden     = [
        'children', 'roles', 'permissions',
        'menus', 'pivot', 'parent_id',
        'lft', 'rgt', 'depth',
    ];

    public function menus()
    {
        return $this->belongsToMany(Menu::class);
    }

    protected function getCrntLocale()
    {
        return LaravelLocalization::getCurrentLocale();
    }

    /**
     * override HasTranslations\getAttributeValue().
     *
     * @param [type] $key [description]
     *
     * @return [type] [description]
     */
    public function getAttributeValue($key)
    {
        if (!$this->isTranslatableAttribute($key)) {
            return parent::getAttributeValue($key);
        }

        return $this->getTranslationWithoutFallback($key, $this->getCrntLocale());
    }

    /**
     * Menus.
     *
     * @param [type] $menus [description]
     *
     * @return [type] [description]
     */
    public function assignToMenus($menus)
    {
        return $this->menus()->attach($menus);
    }

    public function syncMenus($menus)
    {
        return $this->menus()->sync($menus);
    }

    /**
     * Nesting.
     *
     * @param mixed $columns
     *
     * @return [type] [description]
     */
    public function getAncestors($columns = ['*'])
    {
        return Cache::rememberForever($this->getCrntLocale() . "-{$this->route_name}_ancestors", function () use ($columns) {
            return $this->ancestors()->get($columns);
        });
    }

    public function getNestsAttribute()
    {
        return Cache::rememberForever($this->getCrntLocale() . "-{$this->route_name}_nests", function () {
            $childs = array_flatten(current($this->getDescendants()->toHierarchy()));

            return count($childs) ? $childs : null;
        });
    }

    public function destroyDescendants()
    {
        $this->clearNests();
    }

    public function clearSelfAndDescendants()
    {
        // self
        $this->makeRoot();

        // childs
        $this->clearNests();
    }

    protected function clearNests()
    {
        $lft = $this->getLeft();
        $rgt = $this->getRight();

        if (is_null($lft) || is_null($rgt)) {
            return;
        }

        $lftCol = $this->getLeftColumnName();
        $rgtCol = $this->getRightColumnName();

        $this->where($lftCol, '>', $lft)->where($rgtCol, '<', $rgt)->each(function ($one) {
            $one->makeRoot();
        });

        $this->cleanData();
    }

    /**
     * clear cacheing and stuff.
     *
     * @param [type] $page [description]
     *
     * @return [type] [description]
     */
    public function cleanData()
    {
        $route_name = $this->route_name;

        // clear page session
        session()->forget($route_name);

        // remove the route file
        File::delete(config('simpleMenu.routeListPath'));

        // clear page cache
        $this->clearCache($route_name);
        $this->clearCache('_ancestors');
        $this->clearCache('_nests');

        // clear menu cache
        $this->clearCache('Menu');
    }
}
