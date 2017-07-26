@extends('SimpleMenu::admin.'.config('simpleMenu.framework').'.shared')
@section('title'){{ 'Permissions' }}@endsection

@section('sub')
    <h3 class="title">
        @lang('SimpleMenu::messages.permissions.title') "{{ count($permissions) }}"
        <a href="{{ route('admin.permissions.create') }}" class="button is-success">@lang('SimpleMenu::messages.app_add_new')</a>
    </h3>

    <table class="table is-bordered">
        <thead>
            <tr>
                <th>@lang('SimpleMenu::messages.permissions.fields.name')</th>
                <th>@lang('SimpleMenu::messages.ops')</th>
            </tr>
        </thead>

        <tbody>
            @if (count($permissions) > 0)
                @foreach ($permissions as $permission)
                    <tr>
                        <td>{{ $permission->name }}</td>
                        <td>
                            <a href="{{ route('admin.permissions.edit',[$permission->id]) }}" class="button is-info is-inline-block">
                                @lang('SimpleMenu::messages.app_edit')
                            </a>
                            <a class="is-inline-block">
                                {{ Form::open(['method' => 'DELETE', 'route' => ['admin.permissions.destroy', $permission->id]]) }}
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