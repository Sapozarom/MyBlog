<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    /**
     *  @var string
     */
    private $postDirectory;

    /**
     *  @var string
     */
    private $userDirectory;

    /**
     * @param string $postDirectory
     */
    public function __construct(string $postDirectory, string $userDirectory)
    {
        $this->postDirectory = $postDirectory;
        $this->userDirectory = $userDirectory;
    }

    /**
     * @return string
     */
    public function getPostDirectory(): string
    {
        return $this->postDirectory;
    }

     /**
     * @return string
     */
    public function getUserDirectory(): string
    {
        return $this->postDirectory;
    }

    /**
     * uploads article cover picture
     * 
     * @param UploadedFile $file
     *
     * @return string
     */
    public function uploadPostCover(UploadedFile $file) : string
    {
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();

        $file->move($this->postDirectory, $fileName);

        return $fileName;
    }

    /**
     * uploads user photo
     * 
     * @param UploadedFile $file
     *
     * @return string
     */
    public function uploadUserPhoto(UploadedFile $file) : string
    {
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();

        $file->move($this->userDirectory, $fileName);

        return $fileName;
    }
}