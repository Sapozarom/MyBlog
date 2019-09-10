<?php

// src/Entity/Tag.php

namespace App\Entity;

use Beelab\TagBundle\Tag\TagInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Tag implements TagInterface
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string|null
     *
     * @ORM\Column()
     */
    protected $name;

    /**
     * @var Post[]|ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="App\Entity\Post", mappedBy="tags")
     */
    private $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    /**
     * to string, returns name
     * 
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $name
     * 
     * @return void
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
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
    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
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
        }

        return $this;
    }
 
}

