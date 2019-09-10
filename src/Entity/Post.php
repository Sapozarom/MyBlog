<?php
// src/Entity/Post.php

namespace App\Entity;

use Beelab\TagBundle\Tag\TaggableInterface;
use Beelab\TagBundle\Tag\TagInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table()
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class Post implements TaggableInterface
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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="posts")
     */
    private $tags;

    /**
     * @var string
     * 
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @var string
     * 
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

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
     * @var \DateTime
     * 
     * @ORM\Column(type="datetime")
     */
    private $editedAt;

    /**
     * @var User
     * 
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @var Comment[]|ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="post", cascade={"remove"})
     */
    private $comments;

    /**
     * @var string
     * 
     * @ORM\Column(type="text")
     */
    private $prelude;

    /**
     * @var string
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     */
    private $picture;

    /**
     * @var File
     * 
     * @Vich\UploadableField(mapping="post_images", fileNameProperty="picture")
     * 
     */
    private $pictureFile;


    /**
     * @var string
     */
    private $tagsText;

    /**
     * @var bool
     * 
     * @ORM\Column(type="boolean")
     */
    private $published;

    /**
     * @var bool
     * 
     * @ORM\Column(type="boolean")
     */
    private $archived;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }


    /**
     * @param TagInterface $tag
     * 
     * @return void
     */
    public function addTag(TagInterface $tag): void
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }
    }

    /**
     * @param TagInterface $tag
     * 
     * @return void
     */
    public function removeTag(TagInterface $tag): void
    {
        $this->tags->removeElement($tag);
    }

    /**
     * @param TagInterface $tag
     * 
     * @return bool
     */
    public function hasTag(TagInterface $tag): bool
    {
        return $this->tags->contains($tag);
    }

    /**
     * @return iterable
     */
    public function getTags(): iterable
    {
        return $this->tags;
    }

    /**
     * @param string $tagsTex
     * 
     * @return void
     */
    public function setTagsText(?string $tagsText): void
    {
        $this->tagsText = $tagsText;
        $this->editedAt = new \DateTimeImmutable();
    }

    /** 
     * @return string
     */
    public function getTagsText(): ?string
    {
        $this->tagsText = \implode(', ', $this->tags->toArray());

        return $this->tagsText;
    }

    /** 
     * @return array
     */
    public function getTagNames(): array
    {
        return empty($this->tagsText) ? [] : \array_map('trim', explode(',', $this->tagsText));
    }

    /** 
     * @return int
     */
    public function getId(){

        return $this->id;
    }

    /** 
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /** 
     * @param string $title
     * 
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /** 
     * @return string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /** 
     * @param string $slug
     * 
     * @return self
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
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
     * @return self
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
     * @return \DateTimeInterface 
     */
    public function getEditedAt(): ?\DateTimeInterface
    {
        return $this->editedAt;
    }
    
    /** 
     * @param \DateTimeInterface $editedAt
     * 
     * @return self
     */
    public function setEditedAt(\DateTimeInterface $editedAt): self
    {
        $this->editedAt = $editedAt;

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
     * @return Collection|comment[]
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
    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
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
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    /** 
     * @return string
     */
    public function getPrelude(): ?string
    {
        return $this->prelude;
    }

    /** 
     * @param string $prelude
     * 
     * @return self
     */
    public function setPrelude(string $prelude): self
    {
        $this->prelude = $prelude;

        return $this;
    }

    /** 
     * @return string|null|UploadedFile
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /** 
     * @param string|null|UploadedFile $picture
     * 
     * @return self
     */
    public function setPicture($picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    /** 
     * @return bool
     */
    public function getPublished(): ?bool
    {
        return $this->published;
    }

    /** 
     * @param bool $published
     * 
     * @return self
     */
    public function setPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }

    /** 
     * @return bool
     */
    public function getArchived(): ?bool
    {
        return $this->archived;
    }

    /** 
     * @param bool $archived
     * 
     * @return self
     */
    public function setArchived(bool $archived): self
    {
        $this->archived = $archived;

        return $this;
    }

    /**
     * @param  File $picture
     * 
     * @return void
     */
    public function setPictureFile(File $picture = null)
    {
        $this->pictureFile = $picture;

        if ($picture) {
            $this->editeddAt = new \DateTime('now');
        }
    }

    /** 
     * @return File
     */
    public function getPictureFile()
    {
        return $this->pictureFile;
    }

    /**
     * @return void
     * 
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
        $this->editedAt = new \DateTime();
        $this->slugGenerator();
        $this->preludeGenerator();
        $this->setArchived(false);  
    }

    /**
     * @return void
     * 
     * @ORM\PreRemove()
     */
    public function preRemove()
    {
        //clear tags related to this post
        $allTags = $this->getTags();

        foreach ($allTags as $tag) {
            $this->removeTag($tag);
        }
    }

    /**
     * @return void
     * 
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->editedAt = new \DateTime();
        $this->preludeGenerator();
    }

    /**
     * generate slug from title. Replace space with '-' and adds 4 random characters 
     * at the end of the phrase
     * 
     * @return void
     */
    public function slugGenerator()
    {
        $slug = preg_replace('/\s+/', '-', $this->title) . '-'. \bin2hex(\random_bytes(2));
        
        $this->setSlug($slug);
    }

    /**
     * Cut first paragraph as the prelude for Homepage usage
     * 
     * @return void
     */
    public function preludeGenerator()
    {
        $start = strpos($this->content, '<p>');
        $end = strpos($this->content, '</p>', $start);
        $intro = substr($this->content, $start+3, $end-$start-3);
        $this->setPrelude($intro);
    }

    /**
     * to string, returns title of article
     * 
     * @return string
     */
    public function __toString(){
        return $this->getTitle();
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
        //title
        $metadata->addPropertyConstraint('title', new Assert\NotBlank());
        $metadata->addPropertyConstraint('title', new Assert\Length([
            'min' => 3, 
            'max'=> 255,
            'minMessage' => "Title must be at least {{ limit }} characters long",
            'maxMessage' => "Title cannot be longer than {{ limit }} characters"
        ]));  
    }
}