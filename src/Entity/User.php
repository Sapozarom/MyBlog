<?php
// src/Entity/User.php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints\PolishAlphanumeric;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class User extends BaseUser
{
    /**
     * @var int
     * 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Post[]|ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="author")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $posts;

    /**
     * @var Comment[]|ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="author")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $comments;

    /**
     * @var string
     * 
     * @ORM\Column( type="string", length=255)
     * @ORM\JoinColumn(nullable=false)
     */
    protected $name;

    /**
     * @var string
     * 
     * @ORM\Column(type="text", nullable=true)
     */
    private $about;

    /**
     * @var string
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @var File
     * 
     * @Vich\UploadableField(mapping="user_images", fileNameProperty="photo")
     * 
     */
    private $photoFile;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;
  
    public function __construct()
    {
        parent::__construct();
        $this->posts = new ArrayCollection();
        $this->comments = new ArrayCollection();
       
    }

    /**
     * @return Post[]|ArrayCollection
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }


    /**
     * @param Post $post
     * 
     * @return self
     */
    public function addPost(post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setAuthor($this);
        }

        return $this;
    }

    /**
     * @param Post $post
     * 
     * @return self
     */
    public function removePost(post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getId(){
        return $this->id;
    }

    
    /**
     * @return Comment[]|ArrayCollection
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @param Comment $comment
     * 
     * @return self
     */
    public function addComment(comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAuthor($this);
        }

        return $this;
    }

    /**
     * @param Comment $comment
     * 
     * @return self
     */
    public function removeComment(comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * 
     * @return self
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getAbout(): ?string
    {
        return $this->about;
    }

    /**
     * @param string $about
     * 
     * @return self
     */
    public function setAbout(?string $about): self
    {
        $this->about = $about;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    /**
     * @param string $photo
     * 
     * @return self
     */
    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @param File $photo
     * 
     * @return void
     */
    public function setPhotoFile(File $photo = null)
    {
        $this->photoFile = $photo;

        if ($photo) {
            //update for event listener
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getPhotoFile()
    {
        return $this->photoFile;
    }
    /**
     * to string, returns username
     * 
     * @return string
     */
    public function __toString(){
        return $this->username;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeInterface $updatedAt
     * 
     * @return self
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Validates the data
     * 
     * @param ClassMetadata $metadata
     * 
     * @return void
     * 
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {   
        //username
        $metadata->addPropertyConstraint('username', new Assert\Length([
            'min' => 6, 
            'max'=> 16,
            'minMessage' => "Your username must be at least {{ limit }} characters long",
            'maxMessage' => "Your username cannot be longer than {{ limit }} characters",
            
        ]));
        
         //pasword
         $metadata->addPropertyConstraint('plainPassword', new Assert\Length([
            'min' => 8, 
            'max'=> 18,
            'minMessage' => "Your password must be at least {{ limit }} characters long",
            'maxMessage' => "Your password cannot be longer than {{ limit }} characters",
            
        ]));

        //email
        $metadata->addPropertyConstraint('email', new Assert\Email([
            'message' => 'The email {{ value }} is not valid.',
            'groups' => ['email'],
        ]));

        //name
        $metadata->addPropertyConstraint('name', new Assert\NotBlank([
            'groups' => ['Default', 'name'],
        ]));
        $metadata->addPropertyConstraint('name', new Assert\Length([
            'min' => 5, 
            'max'=> 255,
            'minMessage' => "Your full name must be at least {{ limit }} characters long",
            'maxMessage' => "Your full name cannot be longer than {{ limit }} characters",
            'groups' => ['Default', 'name'],
        ]));       
        $metadata->addPropertyConstraint('name', new PolishAlphanumeric([
           'groups' => ['Default', 'name'],
        ]));
        
        
    }

    /**
     * @return void
     * 
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->updatedAt = new \DateTime();
        
    }

    /**
     * @return void
     * 
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

   

}