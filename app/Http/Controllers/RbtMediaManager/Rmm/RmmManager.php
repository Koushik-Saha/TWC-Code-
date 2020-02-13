<?php

namespace App\Http\Controllers\RbtMediaManager\Rmm;

use File;

trait RmmManager {

    /**
     * Initialize the Media Manager
     */
    protected function initialMediaManager() {
        $this->makeConfigObject();
        $default_directory = $this->defaultDirectoryPath();
    }

    /**
     * make the configuration object from the config array
     */
    protected function makeConfigObject() {
        if(file_exists(config_path('rmm.php'))) {
            $config_array = require config_path('rmm.php');
        }
        else {
            $config_array = require app_path('Http/Controllers/RbtMediaManager/rmm.php');
        }
        if(property_exists($this, 'config')) {
            $this->config = new \stdClass();
            foreach($config_array as $key => $value) {
                $this->config->$key = $value;
            }
            return;
        }

        abort(500);
    }

    /**
     * check if default directory exists
     * otherwise make default directory
     * @return string
     */
    protected function defaultDirectoryPath() {
        $storage_path = ($this->config->storage_path) ? $this->config->storage_path : storage_path();
        $storage_folder = ($this->config->storage_folder) ? $this->config->storage_folder : 'uploads';
        $default_directory = $storage_path . '/' . $storage_folder;

        if(!$this->isDirectory($default_directory)) {
            $this->makeDirectory($default_directory);
        }
        return $default_directory;
    }

    /**
     * default directory name
     * @return string
     */
    protected function defaultDirectoryName() {
        $directory_name = ($this->config->storage_folder) ? $this->config->storage_folder : 'uploads';

        return $directory_name;
    }

    /**
     * @return array
     */
    protected function getAllDirectories() {
        $files = array_slice(scandir($this->defaultDirectoryPath()), 2);
        $directories = [];
        $sortedDirectories = [];
        $temporaryDirectories = [];

        foreach($files as $file) {
            if($this->isDirectory($this->defaultDirectoryPath() . '/' . $file)) {
                array_push($temporaryDirectories, $file);
                array_push($sortedDirectories, strtolower($file));
            }
        }
        asort($sortedDirectories);

        foreach($sortedDirectories as $directory) {
            foreach ($temporaryDirectories as $temporaryDirectory) {
                if($directory == strtolower($temporaryDirectory)) {
                    array_push($directories, $temporaryDirectory);
                    break;
                }
            }
        }
        return $directories;
    }

    protected function AllFiles($directory = null) {
        $path = ($directory) ? $this->defaultDirectoryPath() . '/' . $directory : $this->defaultDirectoryPath();
        if(!$this->isDirectory($path)) {
            return false;
        }
        $directories = array_slice(scandir($path), 2);
        $files = [];

        foreach($directories as $value) {
            if(!$this->isDirectory($this->defaultDirectoryPath() . '/' . $value)) {
                array_push($files, $value);
            }
        }
        return $files;
    }

    /**
     * @param $path
     */
    protected function makeDirectory($path) {
        if(!$this->isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
        return;
    }

    /**
     * @param $path
     * @return bool
     */
    protected function isDirectory($path) {
        return File::isDirectory($path);
    }

    /**
     * @param $oldPath
     * @param $newPath
     * @return bool
     */
    protected function renameDirectory($oldPath, $newPath) {
        if(!$this->isDirectory($newPath) && $this->isDirectory($oldPath)) {
            File::moveDirectory($oldPath, $newPath);
            return true;
        }
        return false;
    }

    /**
     * @param $path
     * @return bool
     */
    protected function deleteDirectory($path) {
        if($this->isDirectory($path)) {
            return File::deleteDirectory($path);
        }
        return false;
    }

    /**
     * @param $path
     * @return false|string
     */
    protected function getMimeType($path) {
        return File::mimeType($path);
    }

    /**
     * @param $path
     * @return bool
     */
    protected function deleteFile($path) {
        if(File::isFile($path) && $this->fileExists($path)) {
            return File::delete($path);
        }
        return false;
    }

    /**
     * @param $path
     * @return bool
     */
    protected function fileExists($path) {
        return File::exists($path);
    }


}