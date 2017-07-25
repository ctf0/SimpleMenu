@extends('SimpleMenu::admin.'.config('simpleMenu.framework').'.shared')
@section('title'){{ 'Roles' }}@endsection

@section('sub')
    <h3 class="title">
        @lang('SimpleMenu::messages.roles.title') "{{ count($roles) }}"
        <a href="{{ route('admin.roles.create') }}" class="button is-success">@lang('SimpleMenu::messages.app_add_new')</a>
    </h3>

    <table class="table is-bordered">
        <thead>
            <tr>
                <th>@lang('SimpleMenu::messages.roles.fields.name')</th>
                <th>@lang('SimpleMenu::messages.roles.fields.permission')</th>
                <th>@lang('SimpleMenu::messages.ops')</th>
            </tr>
        </thead>

        <tbody>
            @if (count($roles) > 0)
                @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>
                            @foreach ($role->permissions()->pluck('name') as $permission)
                                <span class="tag is-medium is-medium is-info">{{ $permission }}</span>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('admin.roles.edit',[$role->id]) }}" class="button is-info is-inline-block">@lang('SimpleMenu::messages.app_edit')</a>
                            <a class="is-inline-block">
                                {{ Form::open(['method' => 'DELETE','route' => ['admin.roles.destroy', $role->id]]) }}
                                    {{ Form::submit(trans('SimpleMenu::messages.app_delete'), ['class' => 'button is-danger']) }}
                                {{ Form::close() }}
                            </a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6">@lang('SimpleMenu::messages.app_no_entries_in_table')</td>
                </tr>
            @endif
        </tbody>
    </table>
@stop