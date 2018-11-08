<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JobRepository")
 */
class Job
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\Length(
     *      min = 1,
     *      max = 150,
     *      minMessage = "Le nombre de caractères admis pour le métier doit se situer entre 1 et 150 compris",
     *      maxMessage = "Le nombre de caractères admis pour le métier doit se situer entre 1 et 150 compris"
     * )
     */
    private $job;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(string $Job): self
    {
        $this->job = $Job;

        return $this;
    }
}
