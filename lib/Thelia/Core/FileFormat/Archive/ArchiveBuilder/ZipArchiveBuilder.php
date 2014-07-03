<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace Thelia\Core\FileFormat\Archive\ArchiveBuilder;
use Thelia\Core\FileFormat\Archive\AbstractArchiveBuilder;
<<<<<<< HEAD
<<<<<<< HEAD
use Thelia\Core\FileFormat\Archive\ArchiveBuilder\Exception\ZipArchiveException;
use Thelia\Core\HttpFoundation\Response;
use Thelia\Core\Thelia;
use Thelia\Exception\FileNotReadableException;
=======
=======
use Thelia\Core\FileFormat\Archive\ArchiveBuilder\Exception\ZipArchiveException;
>>>>>>> Finish implementing and testing zip
use Thelia\Core\HttpFoundation\Response;
use Thelia\Core\Thelia;
use Thelia\Exception\FileNotReadableException;
<<<<<<< HEAD
use Thelia\Log\Tlog;
>>>>>>> Define archive builders and formatters
=======
>>>>>>> Finish implementing and testing zip
use Thelia\Tools\FileDownload\FileDownloaderInterface;

/**
 * Class ZipArchiveBuilder
 * @package Thelia\Core\FileFormat\Archive\ArchiveBuilder
 * @author Benjamin Perche <bperche@openstudio.fr>
 *
 * This class is a driver defined by AbstractArchiveBuilder,
 * it's goal is to manage Zip archives.
 *
 * You can create a new archive by creating a new instance,
 * or load an existing zip with the static method loadArchive.
 */
class ZipArchiveBuilder extends AbstractArchiveBuilder
{
<<<<<<< HEAD
<<<<<<< HEAD
=======
    const TEMP_DIRECTORY_NAME = "archive_builder";

>>>>>>> Define archive builders and formatters
=======
>>>>>>> Begin tar, tar.bz2 and tar.gz formatter, fix zip test resources
    /**
     * @var \ZipArchive
     */
    protected $zip;

<<<<<<< HEAD
<<<<<<< HEAD
    public function __construct()
    {
        parent::__construct();

        $this->zip = new \ZipArchive();
=======
    /**
     * @var string This is the absolute path to the zip file in cache
     */
    protected $zipCacheFile;

    /**
     * @var string This is the path of the cache
     */
    protected $cacheDir;

=======
>>>>>>> Finish implementing and testing zip
    public function __construct()
    {
        parent::__construct();

<<<<<<< HEAD
        $this->translator = Translator::getInstance();
>>>>>>> Define archive builders and formatters
=======
        $this->zip = new \ZipArchive();
>>>>>>> Begin tar, tar.bz2 and tar.gz formatter, fix zip test resources
    }

    /**
     * On the destruction of the class,
     * remove the temporary file.
     */
<<<<<<< HEAD
    public function __destruct()
=======
    function __destruct()
>>>>>>> Define archive builders and formatters
    {
        if ($this->zip instanceof \ZipArchive) {
            @$this->zip->close();

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
            if (file_exists($this->cacheFile)) {
                unlink($this->cacheFile);
=======
            if (file_exists($this->zip_cache_file)) {
                unlink($this->zip_cache_file);
>>>>>>> Define archive builders and formatters
=======
            if (file_exists($this->zipCacheFile)) {
                unlink($this->zipCacheFile);
>>>>>>> Begin tar, tar.bz2 and tar.gz formatter, fix zip test resources
=======
            if (file_exists($this->cacheFile)) {
                unlink($this->cacheFile);
>>>>>>> Finish implementing and testing zip
            }
        }
    }

    /**
<<<<<<< HEAD
     * @param  string                                     $filePath           It is the path to access the file.
     * @param  string                                     $directoryInArchive This is the directory where it will be stored in the archive
     * @param  null|string                                $name               The name of the file in the archive. if it null or empty, it keeps the same name
     * @param  bool                                       $isOnline
     * @return $this
     * @throws \Thelia\Exception\FileNotFoundException
     * @throws \Thelia\Exception\FileNotReadableException
     * @throws \ErrorException
=======
     * @param string $filePath It is the path to access the file.
     * @param string $directoryInArchive This is the directory where it will be stored in the archive
     * @param null|string $name The name of the file in the archive. if it null or empty, it keeps the same name
     * @param bool $isOnline
     * @return $this
     * @throws \Thelia\Exception\FileNotFoundException
     * @throws \Thelia\Exception\FileNotReadableException
<<<<<<< HEAD
>>>>>>> Define archive builders and formatters
=======
     * @throws \ErrorException
>>>>>>> Finish implementing and testing zip
     *
     * This methods adds a file in the archive.
     * If the file is local, $isOnline must be false,
     * If the file online, $filePath must be an URL.
     */
    public function addFile($filePath, $directoryInArchive = null, $name = null, $isOnline = false)
    {
<<<<<<< HEAD
<<<<<<< HEAD
        $directoryInArchive = $this->formatDirectoryPath($directoryInArchive);

        /**
         * Add empty directory if it doesn't exist
         */

        if (!empty($directoryInArchive)) {
            $this->addDirectory($directoryInArchive);
        }

        if (empty($name) || !is_scalar($name)) {
            $name = basename($filePath);
        }

        /**
         * Download the file if it is online
         * If it's local check if the file exists and if it is redable
         */
        $fileDownloadCache = $this->cacheDir . DS . md5(uniqid()) . ".tmp";
        $this->copyFile($filePath, $fileDownloadCache, $isOnline);

        /**
         * Then write the file in the archive and commit the changes
         */
        $destination = $directoryInArchive . $name;

        if (!$this->zip->addFile($fileDownloadCache, $destination)) {
            $translatedErrorMessage = $this->translator->trans(
                "An error occurred while adding this file to the archive: %file",
                [
                    "%file" => $fileDownloadCache
                ]
            );

            $this->logger->error($translatedErrorMessage);

            // if error delete the cache file
            unlink($fileDownloadCache);

            throw new \ErrorException($translatedErrorMessage);
        }

        $this->commit();

        // Delete the temp file
        unlink($fileDownloadCache);

        return $this;
    }

    /**
     * @param $content
     * @param $name
     * @param  string          $directoryInArchive
     * @return mixed
     * @throws \ErrorException
     *
     * This method creates a file in the archive with its content
     */
    public function addFileFromString($content, $name, $directoryInArchive = "/")
    {
        $directoryInArchive = $this->formatDirectoryPath($directoryInArchive);

        if (!empty($directoryInArchive) && $directoryInArchive !== "/") {
            $this->addDirectory($directoryInArchive);
        }

        if (empty($name) || !is_scalar($name)) {
            throw new \ErrorException(
                $this->translator->trans(
                    "The filename is not correct"
                )
            );
        }

        $filePath = $this->getFilePath($directoryInArchive . DS . $name);

        if (!$this->zip->addFromString($filePath, $content)) {
            throw new \ErrorException(
                $this->translator->trans(
                    "Unable to write the file %file into the archive",
                    [
                        "%file" => $filePath,
                    ]
                )
            );
        }

        $this->commit();
    }


    /**
     * @param $directoryPath
     * @return $this
     * @throws \ErrorException
     *
     * This method creates an empty directory
     */
    public function addDirectory($directoryPath)
    {
        $directoryInArchive = $this->formatDirectoryPath($directoryPath);

        if (!empty($directoryInArchive)) {
=======
=======
        $directoryInArchive = $this->formatDirectoryPath($directoryInArchive);

>>>>>>> Finish implementing and testing zip
        /**
         * Add empty directory if it doesn't exist
         */

        if(!empty($directoryInArchive)) {
            $this->addDirectory($directoryInArchive);
        }

<<<<<<< HEAD
>>>>>>> Define archive builders and formatters
            if (!$this->zip->addEmptyDir($directoryInArchive)) {
                throw new \ErrorException(
                    $this->translator->trans(
                        "The directory %dir has not been created in the archive",
                        [
                            "%dir" => $directoryInArchive
                        ]
                    )
                );
            }
=======
        if (empty($name) || !is_scalar($name)) {
            $name = basename($filePath);
>>>>>>> Finish implementing and testing zip
        }

<<<<<<< HEAD
<<<<<<< HEAD
        return $this;
    }

    /**
     * @param  string                                     $pathToFile
     * @return null|string
     * @throws \Thelia\Exception\FileNotFoundException
     * @throws \Thelia\Exception\FileNotReadableException
     * @throws \ErrorException
     *
     * This method returns a file content
     */
    public function getFileContent($pathToFile)
    {
        $pathToFile = $this->formatFilePath($pathToFile);

        if (!$this->hasFile($pathToFile)) {
            $this->throwFileNotFound($pathToFile);
        }

        $stream = $this->zip->getStream($pathToFile);
        $content = "";

        while (!feof($stream)) {
            $content .= fread($stream, 2);
        }

        fclose($stream);

        return $content;
    }


    /**
     * @param  string $initialString
     * @return string
     *
     * Gives a valid file path for \ZipArchive
     */
    public function getFilePath($initialString)
    {
        /**
         * Remove the / at the beginning and the end.
         */
        $initialString = trim($initialString, "/");

        /**
         * Remove the double, triple, ... slashes
         */
        $initialString = preg_replace("#\/{2,}#", "/", $initialString);

        if (preg_match("#\/?[^\/]+\/[^\/]+\/?#", $initialString)) {
            $initialString = "/" . $initialString;
        }

        return $initialString;
    }

    /**
     * @param  string $initialString
     * @return string
     *
     * Gives a valid directory path for \ZipArchive
     */
    public function getDirectoryPath($initialString)
    {
        $initialString = $this->getFilePath($initialString);

        if ($initialString[0] !== "/") {
            $initialString = "/" . $initialString;
        }

        return $initialString . "/";
=======
=======
        /**
         * Download the file if it is online
         * If it's local check if the file exists and if it is redable
         */
<<<<<<< HEAD
>>>>>>> Begin tar, tar.bz2 and tar.gz formatter, fix zip test resources
        if ($isOnline) {
            $fileDownloadCache = $this->cacheDir . DS . "download";

            $this->getFileDownloader()
                ->download($filePath, $fileDownloadCache)
            ;

            $filePath = $fileDownloadCache;
        } else {
            if (!file_exists($filePath)) {
                $this->throwFileNotFound($filePath);
            } else if (!is_readable($filePath)) {
                throw new FileNotReadableException(
                    $this->translator
                        ->trans(
                            "The file %file is not readable",
                            [
                                "%file" => $filePath,
                            ]
                        )
                );
            }
        }

        if (empty($name)) {
            $name = basename($filePath);
        }
=======
        $fileDownloadCache = $this->cacheDir . DS . "download.tmp";
        $this->copyFile($filePath, $fileDownloadCache, $isOnline);
>>>>>>> Finish implementing and testing zip

        /**
         * Then write the file in the archive and commit the changes
         */
        $destination = $directoryInArchive . $name;

        if (!$this->zip->addFile($fileDownloadCache, $destination)) {
            $translatedErrorMessage = $this->translator->trans(
                "An error occurred while adding this file to the archive: %file",
                [
                    "%file" => $fileDownloadCache
                ]
            );

            $this->logger->error($translatedErrorMessage);

            throw new \ErrorException($translatedErrorMessage);
        }

        $this->commit();

        return $this;
>>>>>>> Define archive builders and formatters
    }

    /**
     * @param $content
     * @param $name
     * @param string $directoryInArchive
     * @return mixed
     * @throws \ErrorException
     *
     * This method creates a file in the archive with its content
     */
    public function addFileFromString($content, $name, $directoryInArchive = "/")
    {
        $directoryInArchive = $this->formatDirectoryPath($directoryInArchive);

        if (!empty($directoryInArchive) && $directoryInArchive !== "/") {
            $this->addDirectory($directoryInArchive);
        }

        if (empty($name) || !is_scalar($name)) {
            throw new \ErrorException(
                $this->translator->trans(
                    "The filename is not correct"
                )
            );
        }

        $filePath = $this->getFilePath($directoryInArchive . DS . $name);

        if (!$this->zip->addFromString($filePath, $content)) {
            throw new \ErrorException(
                $this->translator->trans(
                    "Unable to write the file %file into the archive",
                    [
                        "%file" => $filePath,
                    ]
                )
            );
        }

        $this->commit();
    }


    /**
     * @param $directoryPath
     * @return $this
     * @throws \ErrorException
     *
     * This method creates an empty directory
     */
    public function addDirectory($directoryPath)
    {
        $directoryInArchive = $this->formatDirectoryPath($directoryPath);

        if (!empty($directoryInArchive)) {
            if (!$this->zip->addEmptyDir($directoryInArchive)) {
                throw new \ErrorException(
                    $this->translator->trans(
                        "The directory %dir has not been created in the archive",
                        [
                            "%dir" => $directoryInArchive
                        ]
                    )
                );
            }
        }

        return $this;
    }

    /**
     * @param string $pathToFile
     * @return null|string
     * @throws \Thelia\Exception\FileNotFoundException
     * @throws \Thelia\Exception\FileNotReadableException
     * @throws \ErrorException
     *
     * This method returns a file content
     */
    public function getFileContent($pathToFile)
    {
        $pathToFile = $this->formatFilePath($pathToFile);

        if (!$this->hasFile($pathToFile)) {
            $this->throwFileNotFound($pathToFile);
        }

        $stream = $this->zip->getStream($pathToFile);
        $content = "";

        while (!feof($stream)) {
            $content .= fread($stream, 2);
        }

        fclose($stream);

        return $content;
    }


    /**
     * @param string $initialString
     * @return string
     *
     * Gives a valid file path for \ZipArchive
     */
    public function getFilePath($initialString)
    {
        /**
         * Remove the / at the beginning and the end.
         */
        $initialString = trim($initialString, "/");

        /**
         * Remove the double, triple, ... slashes
         */
        $initialString = preg_replace("#\/{2,}#", "/", $initialString);

        if (preg_match("#\/?[^\/]+\/[^\/]+\/?#", $initialString)) {
            $initialString = "/" . $initialString;
        }
        return $initialString;
    }

    /**
     * @param string $initialString
     * @return string
     *
     * Gives a valid directory path for \ZipArchive
     */
    public function getDirectoryPath($initialString)
    {
        $initialString = $this->getFilePath($initialString);

        if ($initialString[0] !== "/") {
            $initialString = "/" . $initialString;
        }

        return $initialString . "/";
    }

    /**
     * @param $pathInArchive
     * @return $this
     * @throws \Thelia\Exception\FileNotFoundException
     * @throws \ErrorException
     *
     * This method deletes a file in the archive
     */
    public function deleteFile($pathInArchive)
    {
<<<<<<< HEAD
<<<<<<< HEAD
        $pathInArchive = $this->formatFilePath($pathInArchive);
=======
        $pathInArchive = $this->getFilePath($pathInArchive);
>>>>>>> Define archive builders and formatters
=======
        $pathInArchive = $this->formatFilePath($pathInArchive);
>>>>>>> Finish implementing and testing zip

        if (!$this->hasFile($pathInArchive)) {
            $this->throwFileNotFound($pathInArchive);
        }

        $deleted = $this->zip->deleteName($pathInArchive);

        if (!$deleted) {
            throw new \ErrorException(
                $this->translator->trans(
                    "The file %file has not been deleted",
                    [
                        "%file" => $pathInArchive,
                    ]
                )
            );
        }

        return $this;
    }

    /**
     * @return \Thelia\Core\HttpFoundation\Response
     *
     * This method return an instance of a Response with the archive as content.
     */
<<<<<<< HEAD
    public function buildArchiveResponse($filename)
=======
    public function buildArchiveResponse()
>>>>>>> Define archive builders and formatters
    {
        $this->zip->comment = "Generated by Thelia v" . Thelia::THELIA_VERSION;

        $this->commit();

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
        if (!file_exists($this->cacheFile)) {
            $this->throwFileNotFound($this->cacheFile);
        }

        if (!is_readable($this->cacheFile)) {
=======
        if (!file_exists($this->zip_cache_file)) {
            $this->throwFileNotFound($this->zip_cache_file);
        }

        if (!is_readable($this->zip_cache_file)) {
>>>>>>> Define archive builders and formatters
=======
        if (!file_exists($this->zipCacheFile)) {
            $this->throwFileNotFound($this->zipCacheFile);
        }

        if (!is_readable($this->zipCacheFile)) {
>>>>>>> Begin tar, tar.bz2 and tar.gz formatter, fix zip test resources
=======
        if (!file_exists($this->cacheFile)) {
            $this->throwFileNotFound($this->cacheFile);
        }

        if (!is_readable($this->cacheFile)) {
>>>>>>> Finish implementing and testing zip
            throw new FileNotReadableException(
                $this->translator->trans(
                    "The cache file %file is not readable",
                    [
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
                        "%file" => $this->cacheFile
=======
                        "%file" => $this->zip_cache_file
>>>>>>> Define archive builders and formatters
=======
                        "%file" => $this->zipCacheFile
>>>>>>> Begin tar, tar.bz2 and tar.gz formatter, fix zip test resources
=======
                        "%file" => $this->cacheFile
>>>>>>> Finish implementing and testing zip
                    ]
                )
            );
        }

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
        $content = file_get_contents($this->cacheFile);
=======
        $content = file_get_contents($this->zip_cache_file);
>>>>>>> Define archive builders and formatters
=======
        $content = file_get_contents($this->zipCacheFile);
>>>>>>> Begin tar, tar.bz2 and tar.gz formatter, fix zip test resources
=======
        $content = file_get_contents($this->cacheFile);
>>>>>>> Finish implementing and testing zip

        $this->zip->close();

        return new Response(
            $content,
            200,
            [
<<<<<<< HEAD
                "Content-Type" => $this->getMimeType(),
                "Content-Disposition" => $filename . "." . $this->getExtension(),
=======
                "Content-Type" => $this->getMimeType()
>>>>>>> Define archive builders and formatters
            ]
        );
    }

    /**
<<<<<<< HEAD
     * @param  string                                  $pathToArchive
     * @param  bool                                    $isOnline
     * @param  FileDownloaderInterface                 $fileDownloader
     * @return ZipArchiveBuilder
=======
     * @param string $pathToArchive
     * @param string $environment
     * @param bool $isOnline
     * @param FileDownloaderInterface $fileDownloader
     * @return $this
>>>>>>> Define archive builders and formatters
     * @throws \Thelia\Exception\FileNotFoundException
     * @throws \Thelia\Exception\HttpUrlException
     *
     * Loads an archive
     */
<<<<<<< HEAD
    public function loadArchive($pathToArchive, $isOnline = false)
    {
        $back = $this->zip;
        $this->zip = new \ZipArchive();
        $zip = clone $this;
        $this->zip = $back;

        $zip->setEnvironment($this->environment);

        $zip->copyFile(
            $pathToArchive,
            $zip->getCacheFile(),
            $isOnline
        );

        if (true !== $return = $zip->getRawZipArchive()->open($zip->getCacheFile())) {
            throw new ZipArchiveException(
                $zip->getZipErrorMessage($return)
            );
        }

        return $zip;
=======
    public static function loadArchive(
        $pathToArchive,
        $environment,
        $isOnline = false,
        FileDownloaderInterface $fileDownloader = null
    ) {
        /** @var ZipArchiveBuilder $instance */
        $instance = new static();

        $instance->setEnvironment($environment);
        $zip = $instance->getRawZipArchive();
        $zip->close();

        if ($fileDownloader !== null) {
            $instance->setFileDownloader($fileDownloader);
        }

        $instance->copyFile($pathToArchive, $instance->getCacheFile(), $isOnline);

        if (true !== $return = $zip->open($instance->getCacheFile())) {
            throw new ZipArchiveException(
                $instance->getZipErrorMessage($return)
            );
        }

        return $instance;
>>>>>>> Define archive builders and formatters
    }

    /**
     * @param $pathToFile
     * @return bool
     *
     * Checks if the archive has a file
     */
    public function hasFile($pathToFile)
    {
        return $this->zip
<<<<<<< HEAD
<<<<<<< HEAD
            ->locateName($this->formatFilePath($pathToFile)) !== false
=======
            ->locateName($this->getFilePath($pathToFile)) !== false
>>>>>>> Define archive builders and formatters
=======
            ->locateName($this->formatFilePath($pathToFile)) !== false
>>>>>>> Finish implementing and testing zip
        ;
    }

    /**
<<<<<<< HEAD
     * @param  string $directory
=======
     * @param string $directory
>>>>>>> Define archive builders and formatters
     * @return bool
     *
     * Checks if the link $directory exists and if it's not a file.
     */
    public function hasDirectory($directory)
    {
<<<<<<< HEAD
<<<<<<< HEAD
        $link = $this->zip->locateName($this->formatDirectoryPath($directory));
=======
        $link = $this->zip->locateName($this->getDirectoryPath($directory));
>>>>>>> Define archive builders and formatters
=======
        $link = $this->zip->locateName($this->formatDirectoryPath($directory));
>>>>>>> Finish implementing and testing zip

        return  $link !== false;
    }

    /**
<<<<<<< HEAD
     * @param  string $environment
=======
     * @param string $environment
>>>>>>> Define archive builders and formatters
     * @return $this
     *
     * Sets the execution environment of the Kernel,
     * used to know which cache is used.
     */
    public function setEnvironment($environment)
    {
<<<<<<< HEAD
<<<<<<< HEAD
        parent::setEnvironment($environment);

        $cacheFile = $this->generateCacheFile($environment);
=======
        $theliaCacheDir = THELIA_CACHE_DIR . $environment . DS;

        if (!is_writable($theliaCacheDir)) {
            throw new \ErrorException(
                $this->translator->trans(
                    "The cache directory \"%env\" is not writable",
                    [
                        "%env" => $environment
                    ]
                )
            );
        }

        $archiveBuilderCacheDir = $this->cache_dir = $theliaCacheDir . static::TEMP_DIRECTORY_NAME;

        if (!is_dir($archiveBuilderCacheDir) && !mkdir($archiveBuilderCacheDir, 0755)) {
            throw new \ErrorException(
                $this->translator->trans(
                    "Error while creating the directory \"%directory\"",
                    [
                        "%directory" => static::TEMP_DIRECTORY_NAME
                    ]
                )
            );
        }

        $cacheFileName = md5 (uniqid());

        $cacheFile  = $archiveBuilderCacheDir . DS . $cacheFileName;
        $cacheFile .= "." . $this->getExtension();
>>>>>>> Define archive builders and formatters
=======

<<<<<<< HEAD
        $cacheFileName = md5 (uniqid());

        $cacheFile  = $this->getArchiveBuilderCacheDirectory($environment) . DS;
        $cacheFile .= $cacheFileName . "." . $this->getExtension();
>>>>>>> Begin tar, tar.bz2 and tar.gz formatter, fix zip test resources
=======
        $cacheFile = $this->generateCacheFile($environment);
>>>>>>> Finish implementing and testing zip

        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }

        $opening = $this->zip->open(
            $cacheFile,
            \ZipArchive::CREATE
        );

<<<<<<< HEAD
        if ($opening !== true) {
            throw new \ErrorException(
                $this->translator->trans(
                    "An unknown error append"
=======
        if($opening !== true) {
            throw new \ErrorException(
                $this->translator->trans(
<<<<<<< HEAD
                    "Unknown"
>>>>>>> Define archive builders and formatters
=======
                    "An unknown error append"
>>>>>>> Begin tar, tar.bz2 and tar.gz formatter, fix zip test resources
                )
            );
        }

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
        $this->cacheFile = $cacheFile;
=======
        $this->zip_cache_file = $cacheFile;
>>>>>>> Define archive builders and formatters
=======
        $this->zipCacheFile = $cacheFile;
>>>>>>> Begin tar, tar.bz2 and tar.gz formatter, fix zip test resources
=======
        $this->cacheFile = $cacheFile;
>>>>>>> Finish implementing and testing zip

        return $this;
    }

    /**
     * @param $errorCode
     * @return string
     *
     * Give the error message of a \ZipArchive error code
     */
    public function getZipErrorMessage($errorCode)
    {
        switch ($errorCode) {
            case \ZipArchive::ER_EXISTS:
                $message = "The archive already exists";
                break;

            case \ZipArchive::ER_INCONS:
                $message = "The archive is inconsistent";
                break;

            case \ZipArchive::ER_INVAL:
                $message = "Invalid argument";
                break;

            case \ZipArchive::ER_MEMORY:
                $message = "Memory error";
                break;

            case \ZipArchive::ER_NOENT:
                $message = "The file doesn't exist";
                break;

            case \ZipArchive::ER_NOZIP:
                $message = "The file is not a zip archive";
                break;

            case \ZipArchive::ER_OPEN:
                $message = "The file could not be open";
                break;

            case \ZipArchive::ER_READ:
                $message = "The file could not be read";
                break;

            case \ZipArchive::ER_SEEK:
                $message = "Position error";
                break;

            default:
                $message = "Unknown error on the ZIP archive";
                break;
        }

        $zipMessageHead = $this->translator->trans(
            "Zip Error"
        );

        $message = $this->translator->trans(
            "[%zip_head] " . $message,
            [
                "%zip_head" => $zipMessageHead
            ]
        );

        return $message;
    }

    public function commit()
    {
        $this->zip->close();
<<<<<<< HEAD
<<<<<<< HEAD
        $result = $this->zip->open($this->getCacheFile());
=======
        $result = $this->zip->open($this->getZipCacheFile());
>>>>>>> Define archive builders and formatters
=======
        $result = $this->zip->open($this->getCacheFile());
>>>>>>> Finish implementing and testing zip

        if ($result !== true) {
            throw new \ErrorException(
                $this->translator->trans(
                    "The changes could on the Zip Archive not be commited"
                )
            );
        }

        return $this;
    }

    /**
<<<<<<< HEAD
     * @param  string $initialString
=======
     * @param string $initialString
>>>>>>> Define archive builders and formatters
     * @return string
     *
     * Gives a valid file path for \ZipArchive
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function formatFilePath($initialString)
=======
    public function getFilePath($initialString)
>>>>>>> Define archive builders and formatters
=======
    public function formatFilePath($initialString)
>>>>>>> Finish implementing and testing zip
    {
        /**
         * Remove the / at the beginning and the end.
         */
        $initialString = trim($initialString, "/");

        /**
         * Remove the double, triple, ... slashes
         */
        $initialString = preg_replace("#\/{2,}#", "/", $initialString);

        if (preg_match("#\/?[^\/]+\/[^/]+\/?#", $initialString)) {
            $initialString = "/" . $initialString;
        }
<<<<<<< HEAD

=======
>>>>>>> Define archive builders and formatters
        return $initialString;
    }

    /**
<<<<<<< HEAD
     * @param  string $initialString
=======
     * @param string $initialString
>>>>>>> Define archive builders and formatters
     * @return string
     *
     * Gives a valid directory path for \ZipArchive
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function formatDirectoryPath($initialString)
    {
        $initialString = $this->formatFilePath($initialString);

        if ($initialString !== "" && $initialString[0] !== "/") {
=======
    public function getDirectoryPath($initialString)
=======
    public function formatDirectoryPath($initialString)
>>>>>>> Finish implementing and testing zip
    {
        $initialString = $this->formatFilePath($initialString);

<<<<<<< HEAD
        if ($initialString[0] !== "/") {
>>>>>>> Define archive builders and formatters
=======
        if ($initialString !== "" && $initialString[0] !== "/") {
>>>>>>> Finish implementing and testing zip
            $initialString = "/" . $initialString;
        }

        return $initialString . "/";
    }

<<<<<<< HEAD
<<<<<<< HEAD
=======
    public function throwFileNotFound($file)
    {

        throw new FileNotFoundException(
            $this->getTranslator()
                ->trans(
                    "The file %file is missing or is not readable",
                    [
                        "%file" => $file,
                    ]
                )
        );
    }

>>>>>>> Define archive builders and formatters
=======
>>>>>>> Finish implementing and testing zip
    /**
     * @return string
     *
     * This method must return a string, the name of the format.
     *
     * example:
     * return "XML";
     */
    public function getName()
    {
        return "ZIP";
    }

    /**
     * @return string
     *
     * This method must return a string, the extension of the file format, without the ".".
     * The string should be lowercase.
     *
     * example:
     * return "xml";
     */
    public function getExtension()
    {
        return "zip";
    }

    /**
     * @return string
     *
     * This method must return a string, the mime type of the file format.
     *
     * example:
     * return "application/json";
     */
    public function getMimeType()
    {
        return "application/zip";
    }

    /**
<<<<<<< HEAD
<<<<<<< HEAD
=======
     * @return Tlog
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @return Translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
>>>>>>> Define archive builders and formatters
=======
>>>>>>> Begin tar, tar.bz2 and tar.gz formatter, fix zip test resources
     * @return \ZipArchive
     */
    public function getRawZipArchive()
    {
        return $this->zip;
    }
<<<<<<< HEAD
<<<<<<< HEAD
}
=======

    public function getZipCacheFile()
    {
        return $this->zipCacheFile;
    }

<<<<<<< HEAD
    public function getCacheDir()
    {
        return $this->cache_dir;
    }
} 
>>>>>>> Define archive builders and formatters
=======
} 
>>>>>>> Begin tar, tar.bz2 and tar.gz formatter, fix zip test resources
=======
} 
>>>>>>> Finish implementing and testing zip
