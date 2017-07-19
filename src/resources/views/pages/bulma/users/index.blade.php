@extends('SimpleMenu::pages.'.config('simpleMenu.framework').'.shared')
@section('title'){{ 'Users' }}@endsection

@section('sub')
    <h3 class="title">
        @lang('SimpleMenu::messages.users.title') "{{ count($users) }}"
        <a href="{{ route('admin.users.create') }}" class="button is-success">@lang('SimpleMenu::messages.app_add_new')</a>
    </h3>
    
    <table class="table is-bordered">
        <thead>
            <tr>
                <th>@lang('SimpleMenu::messages.users.fields.name')</th>
                <th>@lang('SimpleMenu::messages.users.fields.email')</th>
                <th>@lang('SimpleMenu::messages.users.fields.roles')</th>
                <th>@lang('SimpleMenu::messages.users.fields.permissions')</th>
                <th>@lang('SimpleMenu::messages.ops')</th>
            </tr>
        </thead>
        
        <tbody>
            @if (count($users) > 0)
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @foreach ($user->roles()->pluck('name') as $role)
                                <span class="tag is-medium is-info">{{ $role }}</span>
                            @endforeach
                        </td>
                        <td>
                            @foreach ($user->permissions()->pluck('name') as $perm)
                                <span class="tag is-medium is-info">{{ $perm }}</span>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('admin.users.edit',[$user->id]) }}" class="button is-info is-inline-block">@lang('SimpleMenu::messages.app_edit')</a>
                            <a class="is-inline-block">
                                {!! Form::open(['method' => 'DELETE', 'route' => ['admin.users.destroy', $user->id]]) !!}
                                    {!! Form::submit(trans('SimpleMenu::messages.app_delete'), ['class' => 'button is-danger']) !!}
                                {!! Form::close() !!}
                            </a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="9">@lang('SimpleMenu::messages.app_no_entries_in_table')</td>
                </tr>
            @endif
        </tbody>
    </table>
@stop