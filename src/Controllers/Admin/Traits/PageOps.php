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
            'action.regex'              => trans('SimpleMenu::messages.regex', ['attribute' => trans('SimpleMenu::messages.action')]),
            'template.required_without' => trans('validation.required_without', [
                'attribute' => trans('SimpleMenu::messages.template'),
                'values'    => trans('SimpleMenu::messages.action'),
            ]),
            'route_name.required' => trans('validation.required', ['attribute' => trans('SimpleMenu::messages.route_name')]),
            'url.required'        => trans('validation.required', ['attribute' => trans('SimpleMenu::messages.url')]),
            'url.unique'          => trans('validation.unique', ['attribute' => trans('SimpleMenu::messages.url')]),
            'title.required'      => trans('validation.required', ['attribute' => trans('SimpleMenu::messages.title')]),
            'title.unique'        => trans('validation.unique', ['attribute' => trans('SimpleMenu::messages.title')]),
        ];

        // main
        $validator = Validator::make($request->all(), [
            'action'     => 'nullable|regex:/@/ius',
            'template'   => 'required_without:action',
            'route_name' => 'required|unique:pages,route_name,' . $id ?: '',
            'url.*'      => 'unique_translation:pages,url,' . $id ?: '',
            'title.*'    => 'unique_translation:pages,title,' . $id ?: '',
        ], $customMessages);

        // extra
        $validator->after(function ($validator) use ($request, $customMessages) {
            // url
            if (!array_filter($request->url)) {
                $validator->errors()->add('url', $customMessages['url.required']);
            }

            // title
            if (!array_filter($request->title)) {
                $validator->errors()->add('title', $customMessages['title.required']);
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
        $result = $request->except(['roles', 'permissions', 'menus', 'cover', 'controllerFile']);

        foreach ($result as $k => $v) {
            if (is_array($v)) {
                $result[$k] = array_filter($v) ?: null;
            }
        }

        return $result;
    }

    /**
     * save or get controller content.
     *
     * @param [type] $action [description]
     * @param [type] $type   [description]
     * @param [type] $data   [description]
     *
     * @return [type] [description]
     */
    protected function actionFileContent($action, $type, $data = null)
    {
        $class = substr($action, 0, strpos($action, '@'));
        $file  = (new \ReflectionClass($class))->getFileName();

        if ($type == 'get') {
            return file_get_contents($file);
        }

        return file_put_contents($file, $data);
    }
}
