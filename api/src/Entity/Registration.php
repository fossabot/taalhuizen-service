<?php

namespace App\Entity;

use App\Repository\RegistrationRepository;
use App\Resolver\RegistrationMutationResolver;
use App\Resolver\RegistrationQueryCollectionResolver;
use App\Resolver\RegistrationQueryItemResolver;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     graphql={
 *          "item_query" = {
 *              "item_query" = RegistrationQueryItemResolver::class,
 *              "read" = false
 *          },
 *          "collection_query" = {
 *              "collection_query" = RegistrationQueryCollectionResolver::class
 *          },
 *          "create" = {
 *              "mutation" = RegistrationMutationResolver::class,
 *              "read" = false,
 *              "deserialize" = false,
 *              "validate" = false,
 *              "write" = false
 *          },
 *          "update" = {
 *              "mutation" = RegistrationMutationResolver::class,
 *              "read" = false,
 *              "deserialize" = false,
 *              "validate" = false,
 *              "write" = false
 *          },
 *          "remove" = {
 *              "mutation" = RegistrationMutationResolver::class,
 *              "args" = {"id"={"type" = "ID!", "description" =  "the identifier"}},
 *              "read" = false,
 *              "deserialize" = false,
 *              "validate" = false,
 *              "write" = false
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass=RegistrationRepository::class)
 */
class Registration
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *
     * @Assert\NotNull
     * @ORM\Column(type="string", length=255)
     */
    private $taalhuisId;

    /**
     *
     * @ORM\ManyToOne(targetEntity=RegisterStudent::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $student;

    /**
     * @ORM\ManyToOne(targetEntity=RegisterStudentRegistrar::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $registrar;

    /**
     * @ORM\Column(type="string", length=2550, nullable=true)
     */
    private $memo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $studentId;

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function setId(?UuidInterface $uuid): self
    {
        $this->id = $uuid;
        return $this;
    }

    public function getTaalhuisId(): ?string
    {
        return $this->taalhuisId;
    }

    public function setTaalhuisId(?string $taalhuisId): self
    {
        $this->taalhuisId = $taalhuisId;

        return $this;
    }

    public function getMemo(): ?string
    {
        return $this->memo;
    }

    public function setMemo(?string $memo): self
    {
        $this->memo = $memo;

        return $this;
    }

    public function getStudent(): ?RegisterStudent
    {
        return $this->student;
    }

    public function setStudent(?RegisterStudent $student): self
    {
        $this->student = $student;

        return $this;
    }

    public function getRegistrar(): ?RegisterStudentRegistrar
    {
        return $this->registrar;
    }

    public function setRegistrar(?RegisterStudentRegistrar $registrar): self
    {
        $this->registrar = $registrar;

        return $this;
    }

    public function getStudentId(): ?string
    {
        return $this->studentId;
    }

    public function setStudentId(?string $studentId): self
    {
        $this->studentId = $studentId;

        return $this;
    }
}
