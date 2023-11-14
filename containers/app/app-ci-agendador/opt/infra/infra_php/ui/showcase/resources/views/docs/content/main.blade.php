@php
    /** @var App\Http\View\DocsHelper\Directory $directories */
@endphp

@foreach ($directories as $directory)
    {!! $directory->render()!!}
@endforeach