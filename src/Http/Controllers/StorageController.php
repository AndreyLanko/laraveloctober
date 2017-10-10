<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use File;
use ErrorException;
use App\Models\MediaContent;

class StorageController extends Controller
{
    public function media($filePath)
    {
        try {
            $folder=public_path('storage');
            $fullPath=$folder.'/'.$filePath;

            $fileName=pathinfo($filePath, PATHINFO_FILENAME);

            list($mediaId, $timestamp)=explode('-', $fileName);

            $media=MediaContent::find($mediaId);

            if ($media->filename!=$filePath) {
                throw new ErrorException("wrong filename", 1);
            }

            if(mb_substr($media->CONTENT_DATA, 0, 2)=='0x'){
                $file=hex2bin(mb_substr($media->CONTENT_DATA, 2));
            }else{
                $file=$media->CONTENT_DATA;
            }

            if(!file_exists($fullPath)){
                file_put_contents($fullPath, $file);
            }

            return response($file, 200)
                        ->header('Content-Type', 'image/jpg');

        } catch (ErrorException $e) {
            abort(404);
        }
    }

    public function get($filePath)
    {
        $fileInStorage=env('BACKEND_SERVER_URL').'/storage/'.$filePath;

        if(strpos($fileInStorage, 'storage/app') === false) {
            return $this->media($filePath);
        }

        $ch = curl_init($fileInStorage);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $raw=curl_exec($ch);

        if ($raw === false) {
            curl_close($ch);
            abort(404);
        } else {
            $curl_info = curl_getinfo($ch);
            curl_close($ch);

            $dir=public_path(dirname(parse_url($fileInStorage, PHP_URL_PATH)));

            File::makeDirectory($dir, 0755, true);

            $fp = fopen(public_path(parse_url($fileInStorage, PHP_URL_PATH)), 'x');

            fwrite($fp, $raw);
            fclose($fp);

            return response($raw, $curl_info['http_code'])
                        ->header('Content-Type', $curl_info['content_type'])
                        ->header('Content-Length', $curl_info['size_download']);
        }
    }
}
