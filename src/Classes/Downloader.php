<?php
namespace SimpleDownloader\Classes;

use SimpleDownloader\Exceptions\FileException;
use InvalidArgumentException;

class Downloader
{
    /**
     * Download file with given URL to given path and filename
     *
     * @param $url
     * @param $path
     * @param $destinationFileName
     * @param array $wget_params
     * @param bool|true $escapeShellCmd
     * @throws FileException
     */
    public function downloadFile($url, $path, $destinationFileName, $wget_params = [], $escapeShellCmd = true)
    {
        if (empty($path) || empty($url) || empty($destinationFileName)) {
            throw new InvalidArgumentException('Wrong arguments');
        }

        if ($this->pathShouldBeCreated($path)) {
            if ($this->pathCanBeCreated($path)) {
                mkdir($path, 0777, true);
            } else {
                throw new FileException('Path can not be created: '.$path);
            }
        }

        if (is_writable(dirname($path))) {
            $outputFile = $path . $destinationFileName;

            if ($escapeShellCmd) {
                $url = escapeshellcmd($url);
                $outputFile = escapeshellcmd($outputFile);
            }

            $strParams = '';
            if (is_array($wget_params) && !empty($wget_params)) {
                foreach ($wget_params as $value) {
                    $strParams .= $value . ' ';
                }
            }

            $outputCmd = "";
            $cmd = "wget $strParams {$url} -O $outputFile";
            exec($cmd, $outputCmd);

            
            //Windows and OS without wget
            if (!file_exists($outputFile)) {
                file_put_contents($outputFile, fopen($url, 'r'));
            }
            
            if (0 == filesize($outputFile)) {
                throw new FileException('Void file downloaded from: '.$url);
            }

        } else {
            throw new FileException('Path is not writable: '.$path);
        }
    }

    public function pathShouldBeCreated($path)
    {
        return !is_dir($path);
    }

    public function pathCanBeCreated($path)
    {
        if (is_dir(dirname($path)) && is_writable(dirname($path))) {
            return true;
        } else {
            if (!is_dir(dirname($path)) && $path != '/') {
                return $this->pathCanBeCreated(dirname($path));
            }
        }

        return false;
    }
}

