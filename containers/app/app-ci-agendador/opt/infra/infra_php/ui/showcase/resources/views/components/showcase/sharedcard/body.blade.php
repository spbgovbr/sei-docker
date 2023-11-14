@foreach ($directory->testClasses as $testClass)
    @php
        /** @var \App\Http\View\DocsHelper\Directory $directory */
        /** @var \App\Http\View\DocsHelper\TestClass $testClass */

        $page = $directory->getNameHtml() . $testClass->getHtmlId();
        $htmlTabId = str_replace('.','-',"$page-html");
        $phpTabId = str_replace('.','-',"$page-php");
    @endphp

    <div>
        @if ($testClass->showcaser->isPrototype())
            <div class="is-prototype-container d-flex">
                <div class="prototype-text align-self-center">
                    PROTÓTIPO
                </div>
            </div>
        @endif

        @include('comparator', [
           'leftSideContent' => $testClass->getHtml(),
           'tabs' => [[
               'id' => $phpTabId,
               'name' => 'PHP',
               'content' => $testClass->php,
               'preClass' => 'php'
           ],[
               'id' => $htmlTabId,
               'name' => 'HTML',
               'content' =>  $testClass->getHtml(),
               'preClass' => 'html'
           ]]
       ])
    </div>
@endforeach
