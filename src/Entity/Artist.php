<?php

namespace App\Entity;

use App\Repository\ArtistRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Image;

#[ORM\Entity(repositoryClass: ArtistRepository::class)]
class Artist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $desc = null;

    #[ORM\Column(length: 255)]
    private ?Image $image = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string{
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDesc(): ?string{
        return $this->desc;
    }
    public function setDesc(string $desc): void
    {
        $this->desc = $desc;
    }
    public function getImage(): ?Image{
        return $this->image;
    }
    public function setImage(Image $image): void
    {
        $this->image = $image;
    }

}
