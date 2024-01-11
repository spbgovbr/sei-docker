@php
    /** @var App\Http\View\DocsHelper\Directory $directory */
@endphp
{{-- todo tratar isso da mesma forma que o body (sem esses ifs) --}}

<li class="{{ $directory->shouldShowChildren() ? 'pl-2' : '' }}">
    <a class="showcase-navigator" href="#{{ $directory->getHtmlId() }}">{{ $directory->getTitle() }}</a>


    @if ($directory->testClasses and $directory->shouldShowChildren() )
        <ul class="doc-ul">
            @foreach ($directory->testClasses as $testClass)
                <li data-item="{{ $testClass->getHtmlId() }}">
                    <a class="showcase-navigator" href="#{{ $testClass->getHtmlId() }}">{!! $testClass->name !!}</a>
                </li>
            @endforeach
        </ul>
    @endif

    @if ($directory->childDirectories)
        <ul class="doc-ul">
            @each('docs.sidebar.partial', $directory->childDirectories, 'directory')
        </ul>
    @endif
</li>