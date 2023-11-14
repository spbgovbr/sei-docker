@extends($template)

@section('content')

    @foreach([
        'iconButton',
        'table',
        'requiredFields',
        'date',
        'datetime',
        'select',
        'selectWithSelectedValue',
        'multipleSelect',
        'multipleSelectWithSelectedValues',
        'inputText',
        'checkedCheckbox',
        'checkbox',
        'radioGroupArray',
        'radioGroupObject',
        'buttonPrimary',
        'buttonSecondary',
        'selectDependencyV4',
        'alert',
        ] as $page)
        @include('includer', ['page'=>'components.'.$page])
    @endforeach

@endsection
