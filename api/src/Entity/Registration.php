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
 *          },
 *          "accept" = {
 *              "mutation" = RegistrationMutationResolver::class,
 *              "args" = {"id"={"type" = "ID!", "description" =  "the identifier"}},
 *              "read" = false,
 *              "deserialize" = false,
 *              "validate" = false,
 *              "write" = false
 *          }
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"languageHouseId": "exact"})
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $languageHouseId;

    /**
     *@var array|null The student
     *
     * @Groups({"read", "write"})
     * @ORM\Column(type="json", nullable=true)
     */
    private $student;

    /**
     * @var array|null
     *
     * @Groups({"read", "write"})
     * @ORM\Column(type="json", nullable=true)
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

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="json", length=255, nullable=true)
     */
    private $civicIntegrationDetails;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="json", length=255, nullable=true)
     */
    private $personDetails;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="json", length=255, nullable=true)
     */
    private $contactDetails;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="json", length=255, nullable=true)
     */
    private $generalDetails;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="json", length=255, nullable=true)
     */
    private $referrerDetails;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="json", length=255, nullable=true)
     */
    private $backgroundDetails;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="json", length=255, nullable=true)
     */
    private $dutchNTDetails;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $speakingLevel;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="json", length=255, nullable=true)
     */
    private $educationDetails;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="json", length=255, nullable=true)
     */
    private $courseDetails;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="json", length=255, nullable=true)
     */
    private $jobDetails;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="json", length=255, nullable=true)
     */
    private $motivationDetails;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="json", length=255, nullable=true)
     */
    private $availabilityDetails;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="json", length=255, nullable=true)
     */
    private $readingTestResult;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="json", length=255, nullable=true)
     */
    private $writingTestResult;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="json", length=255, nullable=true)
     */
    private $permissionDetails;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="json", length=255, nullable=true)
     */
    private $intakeDetail;

    /**
     * @Groups({"write"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreated;

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function setId(?UuidInterface $uuid): self
    {
        $this->id = $uuid;
        return $this;
    }

    public function getLanguageHouseId(): ?string
    {
        return $this->languageHouseId;
    }

    public function setLanguageHouseId(string $languageHouseId): self
    {
        $this->languageHouseId = $languageHouseId;

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

    public function getStudent(): ?array
    {
        return $this->student;
    }

    public function setStudent(?array $student): self
    {
        $this->student = $student;

        return $this;
    }

    public function getRegistrar(): ?array
    {
        return $this->registrar;
    }

    public function setRegistrar(?array $registrar): self
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCivicIntegrationDetails(): ?array
    {
        return $this->civicIntegrationDetails;
    }

    public function setCivicIntegrationDetails(?array $civicIntegrationDetails): self
    {
        $this->civicIntegrationDetails = $civicIntegrationDetails;

        return $this;
    }

    public function getPersonDetails(): ?array
    {
        return $this->personDetails;
    }

    public function setPersonDetails(?array $personDetails): self
    {
        $this->personDetails = $personDetails;

        return $this;
    }

    public function getContactDetails(): ?array
    {
        return $this->contactDetails;
    }

    public function setContactDetails(?array $contactDetails): self
    {
        $this->contactDetails = $contactDetails;

        return $this;
    }

    public function getGeneralDetails(): ?array
    {
        return $this->generalDetails;
    }

    public function setGeneralDetails(?array $generalDetails): self
    {
        $this->generalDetails = $generalDetails;

        return $this;
    }

    public function getReferrerDetails(): ?array
    {
        return $this->referrerDetails;
    }

    public function setReferrerDetails(?array $referrerDetails): self
    {
        $this->referrerDetails = $referrerDetails;

        return $this;
    }

    public function getBackgroundDetails(): ?array
    {
        return $this->backgroundDetails;
    }

    public function setBackgroundDetails(?array $backgroundDetails): self
    {
        $this->backgroundDetails = $backgroundDetails;

        return $this;
    }

    public function getDutchNTDetails(): ?array
    {
        return $this->dutchNTDetails;
    }

    public function setDutchNTDetails(?array $dutchNTDetails): self
    {
        $this->dutchNTDetails = $dutchNTDetails;

        return $this;
    }

    public function getSpeakingLevel(): ?string
    {
        return $this->speakingLevel;
    }

    public function setSpeakingLevel(?string $speakingLevel): self
    {
        $this->speakingLevel = $speakingLevel;

        return $this;
    }

    public function getEducationDetails(): ?array
    {
        return $this->educationDetails;
    }

    public function setEducationDetails(?array $educationDetails): self
    {
        $this->educationDetails = $educationDetails;

        return $this;
    }

    public function getCourseDetails(): ?array
    {
        return $this->courseDetails;
    }

    public function setCourseDetails(?array $courseDetails): self
    {
        $this->courseDetails = $courseDetails;

        return $this;
    }

    public function getJobDetails(): ?array
    {
        return $this->jobDetails;
    }

    public function setJobDetails(?array $jobDetails): self
    {
        $this->jobDetails = $jobDetails;

        return $this;
    }

    public function getMotivationDetails(): ?array
    {
        return $this->motivationDetails;
    }

    public function setMotivationDetails(?array $motivationDetails): self
    {
        $this->motivationDetails = $motivationDetails;

        return $this;
    }

    public function getAvailabilityDetails(): ?array
    {
        return $this->availabilityDetails;
    }

    public function setAvailabilityDetails(?array $availabilityDetails): self
    {
        $this->availabilityDetails = $availabilityDetails;

        return $this;
    }

    public function getReadingTestResult(): ?string
    {
        return $this->readingTestResult;
    }

    public function setReadingTestResult(?string $readingTestResult): self
    {
        $this->readingTestResult = $readingTestResult;

        return $this;
    }

    public function getWritingTestResult(): ?string
    {
        return $this->writingTestResult;
    }

    public function setWritingTestResult(?string $writingTestResult): self
    {
        $this->writingTestResult = $writingTestResult;

        return $this;
    }

    public function getPermissionDetails(): ?array
    {
        return $this->permissionDetails;
    }

    public function setPermissionDetails(?array $permissionDetails): self
    {
        $this->permissionDetails = $permissionDetails;

        return $this;
    }

    public function getIntakeDetail(): ?string
    {
        return $this->intakeDetail;
    }

    public function setIntakeDetails(?string $intakeDetail): self
    {
        $this->intakeDetail = $intakeDetail;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(?\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }
}
