@php
    $file = str_replace('.','/',$page). '.php';
    $basePath = realpath(__DIR__.'/../../../resources/views/');
    $file = $basePath.'/'.$file;
    $content = file_get_contents($file);
    $htmlTabId = str_replace('.','-',"$page-html");
    $phpTabId = str_replace('.','-',"$page-php");
@endphp

@include('comparatorCard', [
    'phpTabId' => $phpTabId,
    'htmlTabId' => $htmlTabId,
    'php' => $content,
    'html' =>  (new \Gajus\Dindent\Indenter())->indent(trim(html_entity_decode(View::make($page)))),
])
