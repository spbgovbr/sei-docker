<?php

namespace App\Http\Controllers\Showcaser;

use App\Http\Controllers\Controller;
use App\Http\View\DocsHelper\ClassHelper;
use App\Http\View\DocsHelper\Directory;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use ReflectionException;
use TRF4\UI\Util\CacheHelper;

class ShowcaserController extends Controller
{

    /**
     * @var ClassHelper
     */
    public $classHelper;

    public function __construct(ClassHelper $classHelper)
    {
        $this->classHelper = $classHelper;
        parent::__construct();
    }

    /**
     * @param null $renderer
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws ReflectionException
     */
    public function show($renderer = null)
    {
        session(['renderer' => $renderer]);

        $main_folder = realpath('../../../../tests/Unit/Showcaseable/');

        if (!env("PRODUCTION")) {

            $cacheHelper = new CacheHelper($main_folder);

            if ($cacheHelper->shouldReload()) {
                Artisan::call('cache:clear');
                Artisan::call('view:clear');
                $cache = null;
            } else {
                $cache = Cache::get('directories');
            }

            if ($cache) {
                $directories = $cache;
            } else {
                $directories = $this->getDirectoriesAndTestClassTree($main_folder);
                Cache::put('directories', $directories);
            }

        } else {
            $directories = $this->getDirectoriesAndTestClassTree($main_folder);
        }
        return view('docs.main', ['directories' => $directories]);
    }

    /**
     * @param string $folder
     * @return Directory[]
     * @throws ReflectionException
     */
    protected function getDirectoriesAndTestClassTree(string $folder): array
    {
        $directoryAndTestClassTree = $this->classHelper->getFilesTree($folder);
        return $directoryAndTestClassTree;
    }

}
