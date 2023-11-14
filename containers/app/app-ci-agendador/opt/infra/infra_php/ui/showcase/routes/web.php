<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\View\DocsHelper\FormTestClass;
use Illuminate\Http\Request;

Route::get('/', function () {
    return redirect('/docs');
});

Route::middleware('page-cache')->get('/docs/{renderer?}', [
        'uses' => 'Showcaser\ShowcaserController@show',
        'as' => 'ui'
    ]
);

Route::get('/outros', function (Request $request) {
    return view('/outros');
});

Route::post('/testeFile', function (Request $request) {
    return view('bootstrap4/form_file_teste');
});

Route::post('/outros', function (Request $request) {
    return view('/outros');
});

Route::post('/bootstrap4/deletefile', function (Request $request) {
    $data['status'] = 200;
    return json_encode($data);
});

Route::post('/testeFile', function (Request $request) {
    return view('bootstrap4/form_file_teste');
});

Route::get('/bootstrap4/form', function (Request $request) {
    return view('bootstrap4/form');
});

Route::get('/bootstrap4/form_short', function (Request $request) {
    return view('bootstrap4/form_short');
});

Route::get('/releases', function (Request $request) {
    $markdown = file_get_contents(__DIR__ . '/../../CHANGELOG.md');
    $html = \Tests\Showcaser::md2html($markdown);
    return view('releases', [
        'html' => $html
    ]);
});

Route::match(['get', 'post'], '/showcase-uiget', function (Request $request) {
    // instancia classe
    $class = $request->request->get('ui-class');

    /** @var \Tests\FormShowcaser $testClass */
    $formShowcaser = new $class();
    $method = 'retrieveValue' . $request->get('http_method');
    $ret = $formShowcaser->$method();
    $testClass = new FormTestClass($formShowcaser);

    return view('components.showcase.formcard.result', [
        'serverCode' => $testClass->getPhpServerCode($request->get('http_method')),
        'testClass' => $testClass,
        'request' => $request->all(),
        'result' => $ret
    ]);
});

Route::get('/states', function (Request $request) {
    $data = [
        'Brazil' => ['Rio Grande do Sul', 'Santa Catarina', 'Paraná'],
        'Argentina' => ['Córdoba', 'Chaco']
    ];
    $country = $request->get('country');
    usleep(array_rand([500, 1000]));
    return json_encode($data[$country]);
});

