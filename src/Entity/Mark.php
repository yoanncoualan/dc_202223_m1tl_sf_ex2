<?php

namespace App\Entity;

use App\Repository\MarkRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MarkRepository::class)]
class Mark
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\Range(
        min: 0,
        max: 20,
        notInRangeMessage: 'You must be between {{ min }} and {{ max }}',
    )]
    private ?float $mark = null;

    #[ORM\ManyToOne(inversedBy: 'marks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Subject $subject = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $added_date = null;

    public function __construct()
    {
        $this->added_date = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMark(): ?float
    {
        return $this->mark;
    }

    public function setMark(float $mark): self
    {
        $this->mark = $mark;

        return $this;
    }

    public function getSubject(): ?Subject
    {
        return $this->subject;
    }

    public function setSubject(?Subject $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getAddedDate(): ?\DateTimeInterface
    {
        return $this->added_date;
    }

    public function setAddedDate(\DateTimeInterface $added_date): self
    {
        $this->added_date = $added_date;

        return $this;
    }
}
