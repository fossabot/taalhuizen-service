<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use App\Resolver\DocumentMutationResolver;
use App\Resolver\DocumentQueryCollectionResolver;
use App\Resolver\DocumentQueryItemResolver;
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
 *              "item_query" = DocumentQueryItemResolver::class,
 *              "read" = false
 *          },
 *          "collection_query" = {
 *              "collection_query" = DocumentQueryCollectionResolver::class
 *          },
 *          "create" = {
 *              "mutation" = DocumentMutationResolver::class,
 *              "read" = false,
 *              "deserialize" = false,
 *              "validate" = false,
 *              "write" = false
 *          },
 *          "download" = {
 *              "mutation" = DocumentMutationResolver::class,
 *              "args" = {"studentDocumentId"={"type" = "ID"}, "aanbiederEmployeeDocumentId"={"type" = "ID"}},
 *              "read" = false,
 *              "deserialize" = false,
 *              "validate" = false,
 *              "write" = false
 *          },
*          "remove" = {
 *              "mutation" = DocumentMutationResolver::class,
 *              "args" = {"studentDocumentId"={"type" = "ID"}, "aanbiederEmployeeDocumentId"={"type" = "ID"}},
 *              "read" = false,
 *              "deserialize" = false,
 *              "validate" = false,
 *              "write" = false
 *          },
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"studentId": "exact", "aanbiederEmployeeId": "exact"})
 * @ORM\Entity(repositoryClass=DocumentRepository::class)
 */
class Document
{
    /**
     * @var UuidInterface The UUID identifier of this resource
     *
     * @example e2984465-190a-4562-829e-a8cca81aa35d
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true, nullable=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private UuidInterface $id;

    /**
     * @var string the base64 of the document
     *
     * @Assert\NotNull
     * @ORM\Column(type="text")
     */
    private string $base64data;

    /**
     * @var string the name of the file
     *
     * @Assert\NotNull
     * @ORM\Column(type="string", length=255)
     */
    private string $filename;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $aanbiederEmployeeId = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $studentId = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $aanbiederEmployeeDocumentId = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $studentDocumentId = null;

    /**
     * @Groups({"write"})
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $dateCreated;

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function setId(?UuidInterface $uuid): self
    {
        $this->id = $uuid;
        return $this;
    }

    public function getBase64data(): ?string
    {
        return $this->base64data;
    }

    public function setBase64data(string $base64data): self
    {
        $this->base64data = $base64data;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getAanbiederEmployeeId(): ?string
    {
        return $this->aanbiederEmployeeId;
    }

    public function setAanbiederEmployeeId(string $aanbiederEmployeeId): self
    {
        $this->aanbiederEmployeeId = $aanbiederEmployeeId;

        return $this;
    }

    public function getStudentId(): ?string
    {
        return $this->studentId;
    }

    public function setStudentId(string $studentId): self
    {
        $this->studentId = $studentId;

        return $this;
    }

    public function getAanbiederEmployeeDocumentId(): ?string
    {
        return $this->aanbiederEmployeeDocumentId;
    }

    public function setAanbiederEmployeeDocumentId(?string $aanbiederEmployeeDocumentId): self
    {
        $this->aanbiederEmployeeDocumentId = $aanbiederEmployeeDocumentId;

        return $this;
    }

    public function getStudentDocumentId(): ?string
    {
        return $this->studentDocumentId;
    }

    public function setStudentDocumentId(?string $studentDocumentId): self
    {
        $this->studentDocumentId = $studentDocumentId;

        return $this;
    }

    public function getDateCreated(): ?string
    {
        return $this->dateCreated;
    }

    public function setDateCreated(?string $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }
}
