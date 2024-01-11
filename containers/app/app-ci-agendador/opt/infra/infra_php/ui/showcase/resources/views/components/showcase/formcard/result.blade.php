@php
    /** @var \App\Http\View\DocsHelper\FormTestClass $testClass */
    $tabs = [[
        'id' => rand() . 'php-server',
        'name' => 'Php (Server)',
        'content' => $serverCode,
        'preClass' => 'php'
    ], [
        'id' => rand() . 'php-server-request',
        'name' => 'Request',
        'content' => var_export($request, true),
        'preClass' => 'php'
    ]];
@endphp

<x-comparator :tabs="$tabs">
    Result:
    <pre>@php (var_export($result))</pre>
</x-comparator>