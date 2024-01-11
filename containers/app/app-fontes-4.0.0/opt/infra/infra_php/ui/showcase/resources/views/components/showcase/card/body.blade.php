@php
    /** @var \App\Http\View\DocsHelper\Directory $directory */
@endphp

@foreach ($directory->testClasses as $testClass)

    @php
        /** @var \App\Http\View\DocsHelper\Directory $directory */
        /** @var \App\Http\View\DocsHelper\TestClass $testClass */

        $page = $directory->getNameHtml() . $testClass->getHtmlId();
        $htmlTabId = str_replace('.','-',"$page-html");
        $phpTabId = str_replace('.','-',"$page-php");
    @endphp

    <x-card-body-content :testClass="$testClass">
        @include('comparatorCard', [
            'phpTabId' => $phpTabId,
            'htmlTabId' => $htmlTabId,
            'php' => $testClass->php,
            'html' => $testClass->getHtml(),
        ])
    </x-card-body-content>

@endforeach
