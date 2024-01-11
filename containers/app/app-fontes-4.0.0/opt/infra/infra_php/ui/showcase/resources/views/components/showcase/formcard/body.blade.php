@php
    /** @var \App\Http\View\DocsHelper\Directory $directory */
    /** @var \TRF4\UI\UI $ui */
@endphp

@foreach ($directory->testClasses as $testClass)

    @php
        /** @var \App\Http\View\DocsHelper\Directory $directory */
        /** @var \App\Http\View\DocsHelper\FormTestClass $testClass */

        $baseID = $testClass->getHtmlId();
        $page = $directory->getNameHtml() . $baseID;
        $htmlTabId = $baseID . '-html';
        $phpTabId = $baseID . '-php';
        $formId = $baseID . '-form';
        $class = get_class($testClass->showcaser);
        $resultArea = $formId . '-result';

        $html = $testClass->getHtml();
        $php = $testClass->php;

        $phpTab = $testClass->php;

        $tabs = [
            [
                'id' => $phpTabId,
                'name' => 'PHP (View)',
                'content' => $php,
                'preClass' => 'php'
            ],[
                'id' => $htmlTabId,
                'name' => 'HTML',
                'content' =>  $html,
                'preClass' => 'html'
            ]
        ];
    @endphp

    <x-card-body-content :testClass="$testClass">
        <x-comparator :tabs="$tabs">
            <form data-result-area="{{$resultArea}}" id="{{ $formId }}" class='showcaser-form m-0' method="post">
                @csrf
                <input type="hidden" name="ui-class" value="{{ $class }}">
                {!! $html !!}
                <div>
                    {!! $ui->select('', 'http_method', ['GET'=>'GET', 'POST'=>'POST'])
                           ->_wrapper('class', 'd-inline')
                           ->randomId()
                           ->dataWidth('70px') !!}

                    {!! $ui->button('Submit')
                           ->class('submit-button')
                           ->primary()
                           ->type('submit') !!}
                </div>
            </form>
        </x-comparator>

        <div id="{{$resultArea}}" class="formsubmit-result"></div>

    </x-card-body-content>
@endforeach