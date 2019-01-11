<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CVRepository")
 */
class CV
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "Le nombre de caractères admis pour la motivation doit se situer entre 1 et 255 compris",
     *      maxMessage = "Le nombre de caractères admis pour la motivation doit se situer entre 1 et 255 compris"
     * )
     * @Assert\NotBlank()
     */
    private $motivation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "Le nombre de caractères admis pour les compétences doit se situer entre 1 et 255 compris",
     *      maxMessage = "Le nombre de caractères admis pour les compétences doit se situer entre 1 et 255 compris"
     * )
     * @Assert\NotBlank()
     */
    private $skills;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "Le nombre de caractères admis pour les études et l'expérience doit se situer entre 1 et 255 compris",
     *      maxMessage = "Le nombre de caractères admis pour les études et l'expérience doit se situer entre 1 et 255 compris"
     * )
     * @Assert\NotBlank()
     */
    private $studies;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Job")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $job;

    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\Length(
     *      min = 2,
     *      max = 25,
     *      minMessage = "Le nombre de caractères admis pour le nom doit se situer entre 2 et 25 compris",
     *      maxMessage = "Le nombre de caractères admis pour le nom doit se situer entre 2 et 25 compris"
     * )
     * @Assert\NotBlank()
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\Length(
     *      min = 2,
     *      max = 25,
     *      minMessage = "Le nombre de caractères admis pour le prénom doit se situer entre 2 et 25 compris",
     *      maxMessage = "Le nombre de caractères admis pour le prénom doit se situer entre 2 et 25 compris"
     * )
     * @Assert\NotBlank()
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\Regex(
     *      pattern = "/^[0-3][0-9]\/[0-1][0-9]\/[0-9]{4}$/",
     *      match = true,
     *      message = "Entrer une date suivant ce modèle : jj/mm/aaaa"
     * )
     * @Assert\NotBlank()
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Email(
     *      message = "The email '{{ value }}' is not a valid email."
     * )
     * @Assert\Length(
     *      min = 7,
     *      max = 100,
     *      minMessage = "Le nombre de caractères admis pour le mail doit se situer entre 7 et 100 compris",
     *      maxMessage = "Le nombre de caractères admis pour le mail doit se situer entre 7 et 100 compris"
     * )
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Le nombre de caractères admis pour la ville doit se situer entre 2 et 50 compris",
     *      maxMessage = "Le nombre de caractères admis pour la ville doit se situer entre 2 et 50 compris"
     * )
     * @Assert\NotBlank()
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\Length(
     *      min = 2,
     *      max = 30,
     *      minMessage = "Le nombre de caractères admis pour le pays doit se situer entre 2 et 30 compris",
     *      maxMessage = "Le nombre de caractères admis pour le pays doit se situer entre 2 et 30 compris"
     * )
     * @Assert\NotBlank()
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\Regex(
     *      pattern = "/^((\+|00)32\s?|0)[1-9][0-9]{2}(\/|\s)[0-9]{3}(\/|\s)[0-9]{3}$/",
     *      match = true,
     *      message = "Entrer une numéro suivant un de ces modèles : 04xx xxx xxx ou 04xx/xxx/xxx"
     * )
     * @Assert\NotBlank()
     */
    private $phone_number;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMotivation(): ?string
    {
        return $this->motivation;
    }

    public function setMotivation(string $Motivation): self
    {
        $this->motivation = $Motivation;

        return $this;
    }

    public function getSkills(): ?string
    {
        return $this->skills;
    }

    public function setSkills(string $Skills): self
    {
        $this->skills = $Skills;

        return $this;
    }

    public function getStudies(): ?string
    {
        return $this->studies;
    }

    public function setStudies(string $Studies): self
    {
        $this->studies = $Studies;

        return $this;
    }

    public function getJob(): ?Job
    {
        return $this->job;
    }

    public function setJob(?Job $Job): self
    {
        $this->job = $Job;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $Lastname): self
    {
        $this->lastname = $Lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    public function setBirthday(string $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getPhone_number(): ?string
    {
        return $this->phone_number;
    }

    public function setPhone_number(string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getPhonenumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhonenumber(string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }
}