<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ Str::limit($desc, 50) }}">
    <meta name="keywords" content="{{ $meta }}">
    <title>{{ $title }}</title>

    {{-- styles --}}
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bulma/0.7.1/css/bulma.min.css">
</head>
<body>
    <div class="columns">
        <nav class="column is-3">
            <aside class="menu">
                @include('SimpleMenu::menu.example')
            </aside>
        </nav>

        <div class="column">
            <section class="hero">
                <div class="hero-body">
                    <h1 class="title">{!! $title !!}</h1>
                    <h3 class="subtitle">{!! $desc !!}</h3>
                    <div class="content">{!! $body !!}</div>
                </div>
            </section>
        </div>
    </div>
</body>
</html>
