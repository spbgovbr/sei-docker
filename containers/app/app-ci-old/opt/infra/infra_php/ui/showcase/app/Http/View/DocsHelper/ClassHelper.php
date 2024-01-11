<?php


namespace App\Http\View\DocsHelper;


use Tests\AbstractDirectoryMetadata;
use Tests\DefaultDirectoryMetadata;
use Tests\FormShowcaser;
use Tests\Showcaser;

class ClassHelper
{

    /**
     * get the full name (name \ namespace) of a class from its file path
     * result example: (string) "I\Am\The\Namespace\Of\This\Class"
     *
     * @param $filePathName
     *
     * @return  string
     */
    public function getClassFullNameFromFile($filePathName)
    {
        return $this->getClassNamespaceFromFile($filePathName) . '\\' . $this->getClassNameFromFile($filePathName);
    }


    /**
     * build and return an object of a class from its file path
     *
     * @param $filePathName
     *
     * @return  mixed
     */
    public function getClassObjectFromFile($filePathName)
    {
        $classString = $this->getClassFullNameFromFile($filePathName);

        $object = new $classString;

        return $object;
    }


    /**
     * get the class namespace form file path using token
     *
     * @param $filePathName
     *
     * @return  null|string
     */
    public function getClassNamespaceFromFile($filePathName)
    {
        $src = file_get_contents($filePathName);

        $tokens = token_get_all($src);
        $count = count($tokens);
        $i = 0;
        $namespace = '';
        $namespace_ok = false;
        while ($i < $count) {
            $token = $tokens[$i];
            if (is_array($token) && $token[0] === T_NAMESPACE) {
                // Found namespace declaration
                while (++$i < $count) {
                    if ($tokens[$i] === ';') {
                        $namespace_ok = true;
                        $namespace = trim($namespace);
                        break;
                    }
                    $namespace .= is_array($tokens[$i]) ? $tokens[$i][1] : $tokens[$i];
                }
                break;
            }
            $i++;
        }
        if (!$namespace_ok) {
            return null;
        } else {
            return $namespace;
        }
    }

    /**
     * get the class name form file path using token
     *
     * @param $filePathName
     *
     * @return  mixed
     */
    public function getClassNameFromFile($filePathName)
    {
        $php_code = file_get_contents($filePathName);

        $classes = array();
        $tokens = token_get_all($php_code);
        $count = count($tokens);
        for ($i = 2; $i < $count; $i++) {
            if ($tokens[$i - 2][0] == T_CLASS
                && $tokens[$i - 1][0] == T_WHITESPACE
                && $tokens[$i][0] == T_STRING
            ) {

                $class_name = $tokens[$i][1];
                $classes[] = $class_name;
            }
        }

        return $classes[0];
    }

    /**
     * @param $folder
     * @param Directory|null $parentDirectory
     * @return Directory[]
     */
    public function getFilesTree($folder, ?Directory $parentDirectory = null): array
    {
        $directories = [];
        $files_list = scandir($folder);
        foreach ($files_list as $fileOrDir) {
            if (in_array($fileOrDir, ['.', '..'])) {
                continue;
            }

            $fileOrDir = $folder . DIRECTORY_SEPARATOR . $fileOrDir;

            if (is_dir($fileOrDir)) {
                $directories[] = $directory = $this->buildDirectory($parentDirectory, $fileOrDir);
                $this->getFilesTree($fileOrDir, $directory);
            } else {
                $this->buildTestClass($parentDirectory, $fileOrDir);
            }
        }

        return $directories;
    }


    private function buildTestClass(Directory $parentDirectory, string $filename): void
    {
        if (basename($filename) === 'Metadata.php') {
            return;
        }

        if (!str_contains($filename, '.php')) {
            return;
        }
        $class = $this->getClassFullNameFromFile($filename);
        /** @var Showcaser $component */
        $component = new $class();

        $testClass = $component instanceof FormShowcaser ?
            FormTestClass::class :
            TestClass::class;
        $testClass = new $testClass($component, $parentDirectory);
        $parentDirectory->addTestClass($testClass);
    }

    private function buildDirectory(?Directory $parentDirectory, $directoryName): Directory
    {
        $metadata = $this->getDirectoryMetadata($directoryName);

        $directoryBasename = basename($directoryName);
        $directory = new Directory($directoryBasename, $metadata);

        if ($parentDirectory) {
            $parentDirectory->addChildDirectory($directory);
        }

        return $directory;
    }

    private function getDirectoryMetadata(string $directoryName): AbstractDirectoryMetadata
    {
        $metadataFile = $directoryName . DIRECTORY_SEPARATOR . 'Metadata.php';

        if (file_exists($metadataFile)) {
            $class = $this->getClassFullNameFromFile($metadataFile);
            $metadataObject = new $class();
        } else {
            $metadataObject = new DefaultDirectoryMetadata();
        }
        return $metadataObject;
    }
}