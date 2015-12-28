Downloader Bundle
================

Simple Downloader
(forked from kodify/DownloaderBundle but with no dependency on Symfony framework)

What is this Downloader?
------------------------
It's a simple yet functional downloader that simply makes what it says in the tin, downloads the url
specified in the specified path.

Installation
------------
### Composer:

Add the following dependencies to your projects composer.json file:
      
    composer require lezhnev74/simple-downloader 
      
      
Usage
------------

    use Exception;
    use InvalidArgumentException;
    use SimpleDownloader\Classes\Downloader;
    use SimpleDownloader\Exceptions\FileException;            
    
    try {
      
      $downloader = new Downloader();
      $downloader->downloadFile("http://google.com/robots.txt", "/tmp", "robots.txt");
      echo "File was downloaded successfully!";
      
    } catch(FileException $e) {
      echo "We have a problem with file: ".$e->getMessage();
    } catch(InvalidArgumentException $e) {
      echo "Wrong arguments are passed: ".$e->getMessage();
    } catch(Exception $e) {
      echo "Something bad happened: ".$e->getMessage();
    }

      
    
