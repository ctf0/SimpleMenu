@extends('SimpleMenu::admin.'.config('simpleMenu.framework').'.shared')
@section('title'){{ 'Menus' }}@endsection

@section('sub')
    <h3 class="title">
        @lang('SimpleMenu::messages.menus.title') "{{ count($menus) }}"
        <a href="{{ route('admin.menus.create') }}" class="button is-success">@lang('SimpleMenu::messages.app_add_new')</a>
    </h3>

    <table class="table is-narrow is-fullwidth is-bordered">
        <thead>
            <tr>
                <th>@lang('SimpleMenu::messages.menus.fields.name')</th>
                <th>@lang('SimpleMenu::messages.ops')</th>
            </tr>
        </thead>
        <tbody>
            @if (count($menus) > 0)
                @foreach ($menus as $menu)
                    <tr>
                        <td>{{ $menu->name }}</td>
                        <td>
                            <a href="{{ route('admin.menus.edit',[$menu->id]) }}" class="button is-info is-inline-block">
                                @lang('SimpleMenu::messages.app_edit')
                            </a>
                            <a class="is-inline-block">
                                {{ Form::open(['method' => 'DELETE', 'route' => ['admin.menus.destroy', $menu->id]]) }}
                                    {{ Form::submit(trans('SimpleMenu::messages.app_delete'), ['class' => 'button is-danger']) }}
                                {{ Form::close() }}
                            </a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="2">@lang('SimpleMenu::messages.app_no_entries_in_table')</td>
                </tr>
            @endif
        </tbody>
    </table>
@stop