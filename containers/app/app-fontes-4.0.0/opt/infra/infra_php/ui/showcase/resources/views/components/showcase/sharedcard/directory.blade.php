@php
    /** @var App\Http\View\DocsHelper\Directory $directory */
@endphp

<div class="card doc-card">
    <div class="card-header">
        <h4 id="{{ $directory->getHtmlId() }}">{{$directory->getTitle()}}
            @include('macros.card-header-link', ['id' => $directory->getHtmlId()])
        </h4>
        <div class="card-subtitle">{!! $directory->getDescription() !!}</div>
    </div>
    <div class="card-body mb-0 p-0">

        @if ($directory->testClasses)
            <div class="cards shared-card">
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