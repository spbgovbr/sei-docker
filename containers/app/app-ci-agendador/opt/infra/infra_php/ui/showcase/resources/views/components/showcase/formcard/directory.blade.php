@php
    /** @var App\Http\View\DocsHelper\Directory $directory */
@endphp

<div class="card doc-card showcase-directory " data-item="{{ $directory->name }}">
    <div class="card-header sticky-top">
        <h3 id="{{ $directory->getHtmlId() }}">{{$directory->getTitle()}}<span class="subitem-title"></span>
            @include('macros.card-header-link', ['id' => $directory->getHtmlId()])
        </h3>
        <div class="card-subtitle">{!! $directory->getDescription() !!}</div>
    </div>
    <div class="card-body mb-0">

        @if ($directory->testClasses)
            <div class="cards">
                {!! $directory->renderFragments() !!}
            </div>
        @endif

        @if ($directory->childDirectories)
            <div class="cards">
                @foreach ($directory->childDirectories as $directory)
                    {!! $directory->render() !!}
                @endforeach
            </div>
        @endif
    </div>
</div>