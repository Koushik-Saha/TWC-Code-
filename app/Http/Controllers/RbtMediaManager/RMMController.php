<?php

namespace App\Http\Controllers\RbtMediaManager;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RbtMediaManager\Rmm\RmmManager;
use Illuminate\Http\Request;
use Validator;
use Image;
use Carbon\Carbon;

class RMMController extends Controller {

    protected $config; // configuration as object

    use RmmManager;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->initialMediaManager();
        $this->middleware($this->config->middleware);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDirectories() {
        $directories = [
            'main_directory'            => $this->defaultDirectoryName(),
            'sub_directories'           => $this->getAllDirectories(),
            'image_view_route'          => $this->config->image_cache_route
        ];

        return response()->json($directories, 200);
    }

    /**
     * @param Request $request
     * @param $directory
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeImage(Request $request, $directory = null) {
        $valid_mimes = ['image/gif', 'image/png', 'image/jpeg', 'image/bmp', 'image/webp'];

        $mime = explode(':', substr($request->image_data, 0, strpos($request->image_data, ';')))[1];

        if(!in_array($mime, $valid_mimes)) {
            return response()->json([
                'code'      => 422,
                'message'   => 'validation_error',
                'error'    => 'The file must be a valid Image type'
            ], 422);
        }

        $original_filename = str_replace(' ', '_', $request->original_name);
        $fileName = ($request->user_id) ? ($request->user_id . '_') : '';
        $fileName .= (Carbon::now()->timestamp . '_' . $original_filename);
        $path = ($directory) ? $this->defaultDirectoryPath() . '/' . $directory : $this->defaultDirectoryPath();

        if(!$this->isDirectory($path)) {
            return response()->json([
                'code'      => 400,
                'message'   => 'directory_not_found',
                'error'    => 'Directory "' . $directory . '" not found'
            ], 400);
        }
        $image = Image::make($request->image_data)
            ->save($path . '/' . $fileName);

        $fileUrl = '/';
        $fileUrl .= ($directory) ? $directory : $this->defaultDirectoryName();
        $fileUrl .= '/';
        $fileUrl .= $fileName;

       return response()->json([ 'image_url'  => $fileUrl ], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeDirectory(Request $request) {

        $validator = Validator::make($request->all(), [
            'directory_name' => 'required|regex:/^[a-zA-Z0-9 ]+([-._][a-zA-Z0-9 ]+)*$/'
        ], [
            'directory_name.required' => 'Directory Name can\'t be empty!',
            'directory_name.regex' => 'Directory Name can not contain any special character and must start and end with alphanumeric value!'
        ]);

        if($validator->fails()) {
            return response()->json([
                'code'      => 422,
                'message'   => 'validation_error',
                'error'    => $validator->getMessageBag()->first('directory_name')
            ], 422);
        }
        $path = $this->defaultDirectoryPath() . '/' . $request->post('directory_name');

        if($this->isDirectory($path)) {
            return response()->json([
                'code'      => 400,
                'message'   => 'directory_already_exists',
                'error'    => 'Directory "' . $request->post('directory_name') . '" is already exists..!'
            ], 400);
        }

        $this->makeDirectory($path);

        return response()->json($request->post('directory_name'), 200);

    }

    /**
     * @param null $directory
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFiles($directory = null) {
        if($directory) {
            $path = $this->defaultDirectoryPath() . '/' . $directory;
            if(!$this->isDirectory($path)) {
                return response()->json([
                    'code'      => 404,
                    'message'   => 'directory_not_exists',
                    'error'    => 'Directory "' . $directory . '" isn\'t exists..!'
                ], 404);
            }
            return response()->json($this->allFiles($directory), 200);
        }

        return response()->json($this->allFiles(), 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateDirectory(Request $request) {
        $validator = Validator::make($request->all(), [
            'rename_from' => 'required|regex:/^[a-zA-Z0-9 ]+([-._][a-zA-Z0-9 ]+)*$/',
            'rename_to' => 'required|regex:/^[a-zA-Z0-9 ]+([-._][a-zA-Z0-9 ]+)*$/',
        ], [
            'rename_from.required' => 'Old Directory Name can\'t be empty!',
            'rename_from.regex' => 'Old Directory Name can not contain any special character and must start and end with alphanumeric value!',
            'rename_to.required' => 'New Directory Name can\'t be empty!',
            'rename_to.regex' => 'New Directory Name can not contain any special character and must start and end with alphanumeric value!',
        ]);

        if($validator->fails()) {
            return response()->json([
                'code'      => 422,
                'message'   => 'validation_error',
                'error'    => $validator->getMessageBag()->first()
            ], 422);
        }
        $rename_from = $this->defaultDirectoryPath() . '/' . $request->rename_from;
        $rename_to = $this->defaultDirectoryPath() . '/' . $request->rename_to;

        if($this->isDirectory($rename_to)) {
            return response()->json([
                'code'      => 422,
                'message'   => 'already_exists',
                'error'    => 'Directory all ready exists!'
            ], 422);
        }

        $renamed = $this->renameDirectory($rename_from, $rename_to);
        if(!$renamed) {
            return response()->json([
                'code'      => 400,
                'message'   => 'server_error',
                'error'    => $validator->getMessageBag()->first()
            ], 400);
        }

        return response()->json('Renamed', 200);
    }

    public function removeDirectory($directory) {
        $path = $this->defaultDirectoryPath() . '/' . $directory;
        if(!$this->isDirectory($path)) {
            return response()->json([
                'code'      => 404,
                'message'   => 'not_exists',
                'error'    => 'Directory not exists!'
            ], 404);
        }

        if(!$this->deleteDirectory($path)) {
            return response()->json([
                'code'      => 400,
                'message'   => 'server_error',
                'error'    => 'Can not be deleted!'
            ], 400);
        }
        return response()->json('Deleted', 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeImage(Request $request) {
        $valid_mimes = ['image/gif', 'image/png', 'image/jpeg', 'image/bmp'];
        $path = $this->defaultDirectoryPath() . '/' . $request->post('image');
        $mime = $this->getMimeType($path);

        if(!$mime && !in_array($mime, $valid_mimes)) {
            return response()->json([
                'code'      => 422,
                'message'   => 'validation_error',
                'error'    => 'The file must be a valid Image type'
            ], 422);
        }
        if(!$this->deleteFile($path)) {
            return response()->json([
                'code'      => 500,
                'message'   => 'server_error',
                'error'    => 'Image can not be deleted..!'
            ], 500);
        }
        return response()->json('deleted', 200);
    }

    public function downloadImage(Request $request) {
        $path = $this->defaultDirectoryPath() . '/' . $request->post('image');

        if(!$this->fileExists($path)) {
            return response()->json([
                'code'      => 400,
                'message'   => 'not_exists',
                'error'    => 'Image not exists..!'
            ], 400);
        }
        $headers = [
            'Content-Type' => $this->getMimeType($path)
        ];
        return response()->download($path, 'image', $headers);
    }

}
