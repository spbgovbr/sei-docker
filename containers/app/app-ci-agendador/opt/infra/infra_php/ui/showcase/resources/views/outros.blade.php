@extends($template)

@section('content')

    @foreach([
        'table',
        'requiredFields',
        'fileUpload',
        'fileUploadSubmit',
        'multipleSelect',
        ] as $page)
        @include('includer', ['page'=>'components.'.$page])
    @endforeach

@endsection
