<?php

namespace ctf0\SimpleMenu\Controllers\Admin\Traits;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait PageOps
{
    /**
     * validation.
     *
     * @param [type] $request [description]
     * @param [type] $id      [description]
     *
     * @return [type] [description]
     */
    protected function sT_uP_Validaiton($request, $id = null)
    {
        $customMessages = [
            'template.required_without' => __('validation.required_without', [
                'attribute' => __('SimpleMenu::validation.template'),
                'values'    => __('SimpleMenu::validation.action'),
            ]),
            'route_name.required' => __('validation.required', ['attribute' => __('SimpleMenu::validation.route_name')]),
            'url.required'        => __('validation.required', ['attribute' => __('SimpleMenu::validation.url')]),
            'url.unique'          => __('validation.unique', ['attribute' => __('SimpleMenu::validation.url')]),
            'title.required'      => __('validation.required', ['attribute' => __('SimpleMenu::validation.title')]),
            'title.unique'        => __('validation.unique', ['attribute' => __('SimpleMenu::validation.title')]),
        ];

        // main
        $validator = Validator::make($request->all(), [
            'template'   => 'required_without:action',
            'route_name' => 'required|unique:pages,route_name,' . $id ?: '',
        ], $customMessages);

        // extra
        $validator->after(function ($validator) use ($request, $customMessages, $id) {
            // url
            if (!array_filter($request->url)) {
                $validator->errors()->add('url', $customMessages['url.required']);
            }

            foreach (app('simplemenu')->AppLocales() as $code) {
                $v = Validator::make($request->all(), [
                    "url.$code" => "unique:pages,url->$code," . $id ?: '',
                ], $customMessages);

                if ($v->fails()) {
                    $validator->errors()->add('url', $customMessages['url.unique']);
                }
            }

            // title
            if (!array_filter($request->title)) {
                $validator->errors()->add('title', $customMessages['title.required']);
            }

            foreach (app('simplemenu')->AppLocales() as $code) {
                $v = Validator::make($request->all(), [
                    "title.$code" => "unique:pages,title->$code," . $id ?: '',
                ], $customMessages);

                if ($v->fails()) {
                    $validator->errors()->add('title', $customMessages['title.unique']);
                }
            }
        });

        if ($validator->fails()) {
            throw (new ValidationException($validator))
                        ->errorBag('default')
                        ->redirectTo(url()->previous());
        }
    }

    /**
     * make sure we only have fields with values.
     *
     * @param [type] $request [description]
     *
     * @return [type] [description]
     */
    protected function cleanEmptyTranslations($request)
    {
        $result = $request->except(['roles', 'permissions', 'menus']);

        foreach ($result as $k => $v) {
            if (is_array($v)) {
                if (empty(array_filter($v))) {
                    $result[$k] = '';
                } else {
                    $result[$k] = array_filter($v);
                }
            }
        }

        return $result;
    }
}
