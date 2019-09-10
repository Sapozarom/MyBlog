<?php
// src/Entity/Comment.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Comment
{
    /**
     * @var int
     * 
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * 
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var User
     * 
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(nullable=true)
     */
    private $author;

    /**
     * @var Post
     * 
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="comments")
     */
    private $post;

    /**
     * @var bool
     * 
     * @ORM\Column(type="boolean", options={"default" = false})
     */
    private $deleted;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    /**
     * @return string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * 
     * @return int
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     * 
     * @return self
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /** 
     * @return User
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param User $author
     * 
     * @return self
     */
    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
    /** 
     * @return Post
     */
    public function getPost(): ?Post
    {
        return $this->post;
    }

    /**
     * @param Post $post
     * 
     * @return self
     */
    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }
    
    /**
     * @return bool
     */
    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    /**
     * @param bool $deleted
     * 
     * @return self
     */
    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }
    
    /**
     * Sets delete status to true and replace content with "deleted by author" message
     * 
     * @return self
     */
    public function authorRemove()
    {
        $this->setDeleted(true);
        $this->setContent('<p class="font-italic">[comment deleted by author]</p>');

        return $this;
    }

    /**
     * Sets delete status to true and replace content with "deleted by admin" message
     * 
     * @return void
     */
    public function adminRemove()
    {
        $this->setDeleted(true);
        $this->setContent('<p class="font-italic">[comment deleted by admin]</p>');

        return $this;
    }

    /**
     * To string, returns content of the comment
     * 
     * @return string
     */
    public function __toString(): string
    {
        return $this->content;
    }

    /**
     * @return void
     * 
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
        $this->deleted = false;    
    }

    /**
     * @return void
     * 
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
       
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
        //content
        $metadata->addPropertyConstraint('content', new Assert\NotBlank());
        $metadata->addPropertyConstraint('content', new Assert\Length([ 
            'max'=> 1000,
            'maxMessage' => "Title cannot be longer than {{ limit }} characters"
        ]));  
    }
}
