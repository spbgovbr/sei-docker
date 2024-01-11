@extends($template)

@section('content')
    <style>
        .releases h3 {
            font-size: 20px;
        }

        .releases li {
            list-style-type: square;
        }
    </style>

    <div class="container releases">
        {!!$html !!}
    </div>

@endsection
