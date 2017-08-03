@php
    $locales = SimpleMenu::AppLocales();
@endphp

{{-- title --}}
<div class="field">
    {{ Form::label('title', 'Title', ['class' => 'label']) }}
    <div class="control input-box">
        <div class="select toggle-locale">
            <select v-model="title">
                @foreach ($locales as $code)
                    <option value="{{ $code }}">{{ $code }}</option>
                @endforeach
            </select>
        </div>
        @foreach ($locales as $code)
            <input type="text" name="title[{{ $code }}]" class="input" v-show="showTitle('{{ $code }}')" value="{{ old('title.'.$code) }}" placeholder="Some Title">
        @endforeach
    </div>
    @if($errors->has('title'))
        <p class="help is-danger">
            {{ $errors->first('title') }}
        </p>
    @endif
</div>

{{-- body --}}
<div class="field">
    {{ Form::label('body', 'Body', ['class' => 'label']) }}
    <div class="control input-box">
        <div class="select toggle-locale">
            <select v-model="body">
                @foreach ($locales as $code)
                    <option value="{{ $code }}">{{ $code }}</option>
                @endforeach
            </select>
        </div>
        @foreach ($locales as $code)
            <textarea id="body-{{ $code }}" name="body[{{ $code }}]" class="textarea" v-show="showBody('{{ $code }}')">{{ old('body.'.$code) }}</textarea>
        @endforeach
    </div>
    @if($errors->has('body'))
        <p class="help is-danger">
            {{ $errors->first('body') }}
        </p>
    @endif
</div>

{{-- desc --}}
<div class="field">
    {{ Form::label('desc', 'Description', ['class' => 'label']) }}
    <div class="control input-box">
        <div class="select toggle-locale">
            <select v-model="desc">
                @foreach ($locales as $code)
                    <option value="{{ $code }}">{{ $code }}</option>
                @endforeach
            </select>
        </div>
        @foreach ($locales as $code)
            <textarea id="desc-{{ $code }}" name="desc[{{ $code }}]" class="textarea" v-show="showDesc('{{ $code }}')">{{ old('desc.'.$code) }}</textarea>
        @endforeach
    </div>
    @if($errors->has('desc'))
        <p class="help is-danger">
            {{ $errors->first('desc') }}
        </p>
    @endif
</div>

{{-- prefix --}}
<div class="field">
    {{ Form::label('prefix', 'Url Prefix', ['class' => 'label']) }}
    <div class="control input-box">
        <div class="select toggle-locale">
            <select v-model="prefix">
                @foreach ($locales as $code)
                    <option value="{{ $code }}">{{ $code }}</option>
                @endforeach
            </select>
        </div>
        @foreach ($locales as $code)
            <input type="text" name="prefix[{{ $code }}]" class="input" v-show="showPrefix('{{ $code }}')" value="{{ old('prefix.'.$code) }}" placeholder="abc">
        @endforeach
    </div>
    @if($errors->has('prefix'))
        <p class="help is-danger">
            {{ $errors->first('prefix') }}
        </p>
    @endif
</div>

{{-- url --}}
<div class="field">
    {{ Form::label('url', 'Url', ['class' => 'label']) }}
    <div class="control input-box">
        <div class="select toggle-locale">
            <select v-model="url">
                @foreach ($locales as $code)
                    <option value="{{ $code }}">{{ $code }}</option>
                @endforeach
            </select>
        </div>
        @foreach ($locales as $code)
            <input type="text" name="url[{{ $code }}]" class="input" v-show="showUrl('{{ $code }}')" value="{{ old('url.'.$code) }}" placeholder="xyz/{someParam}">
        @endforeach
    </div>
    @if($errors->has('url'))
        <p class="help is-danger">
            {{ $errors->first('url') }}
        </p>
    @endif
</div>