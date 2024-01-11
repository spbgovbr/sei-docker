@php
    /** @var App\Http\View\DocsHelper\Directory $directories */
@endphp


<div class="doc-ul">
    @each('docs.sidebar.partial', $directories, 'directory')
</div>