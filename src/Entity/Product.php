<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Validator\Validator\ValidatorInterface;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]

    //@Assert\NotBlank(message="veuillez renseigner ce champ")
    //@Assert\Length(min=2,minMessage="le nom est trés court,veuillez avoir au moins 2 caractéres")
    #[Assert\NotBlank]
    private ?string $Name = null;

    #[ORM\Column]
    private ?int $Price = null;

    #[ORM\Column(length: 255)]
    private ?string $Color = null;

    #[ORM\Column(length: 255)]
    private ?string $DC = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Image = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ImgLink = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->Price;
    }

    public function setPrice(int $Price): self
    {
        $this->Price = $Price;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->Color;
    }

    public function setColor(string $Color): self
    {
        $this->Color = $Color;

        return $this;
    }

    public function getDC(): ?string
    {
        return $this->DC;
    }

    public function setDC(string $DC): self
    {
        $this->DC = $DC;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(?string $Image): self
    {
        $this->Image = $Image;

        return $this;
    }

    public function getImgLink(): ?string
    {
        return $this->ImgLink;
    }

    public function setImgLink(?string $ImgLink): self
    {
        $this->ImgLink = $ImgLink;

        return $this;
    }
}
