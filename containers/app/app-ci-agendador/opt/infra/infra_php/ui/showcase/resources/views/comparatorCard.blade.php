@include('comparator', [
    'leftSideContent' => $html,
    'tabs' => [[
        'id' => $phpTabId,
        'name' => 'PHP',
        'content' => $php,
        'preClass' => 'php'
    ],[
        'id' => $htmlTabId,
        'name' => 'HTML',
        'content' =>  $html,
        'preClass' => 'html'
    ]]
])
