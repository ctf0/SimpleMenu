<?php

namespace ctf0\SimpleMenu\Models;

use Baum\Node;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Translatable\HasTranslations;

class Page extends Node
{
    use HasRoles, HasTranslations;

    protected $guard_name = 'web';
    protected $appends    = ['nests'];
    protected $hidden     = ['children'];
    public $translatable  = ['title', 'body', 'desc', 'prefix', 'url'];

    public function menuNames()
    {
        return $this->belongsToMany(Menu::class);
    }

    public function getAttributeValue($key)
    {
        if (!$this->isTranslatableAttribute($key)) {
            return parent::getAttributeValue($key);
        }

        return $this->getTranslationWithoutFallback($key, LaravelLocalization::getCurrentLocale());
    }

    /**
     * attach to a menu.
     *
     * @param [type] $menus [description]
     *
     * @return [type] [description]
     */
    public function assignToMenus($menus)
    {
        return $this->menuNames()->attach($menus);
    }

    /**
     * update attached menus.
     *
     * @param [type] $menus [description]
     *
     * @return [type] [description]
     */
    public function syncMenus($menus)
    {
        return $this->menuNames()->sync($menus);
    }

    /**
     * eager load page Descendants.
     *
     * @return [type] [description]
     */
    public function getNestsAttribute()
    {
        $childs = array_flatten(current($this->getDescendants()->toHierarchy()));

        return count($childs) ? $childs : null;
    }

    /**
     * helpers.
     *
     * @return [type] [description]
     */
    public function clearDescendants()
    {
        $this->clearNests();
    }

    public function clearSelfAndDescendants()
    {
        // self
        $this->makeRoot();

        // childs
        $this->clearNests();

        // fire events
        $this->touch();
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
    }
}
