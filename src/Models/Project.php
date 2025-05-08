<?php

namespace Changerawr\Models;

/**
 * Project model.
 */
class Project implements \JsonSerializable
{
    /**
     * @var string|null
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $isPublic;

    /**
     * @var bool
     */
    private $allowAutoPublish;

    /**
     * @var bool
     */
    private $requireApproval;

    /**
     * @var array
     */
    private $defaultTags;

    /**
     * @var \DateTimeInterface|null
     */
    private $createdAt;

    /**
     * @var \DateTimeInterface|null
     */
    private $updatedAt;

    /**
     * Create a new Project instance.
     *
     * @param string $name Project name
     * @param bool $isPublic Whether the project is public
     * @param bool $allowAutoPublish Whether to allow auto-publishing
     * @param bool $requireApproval Whether approval is required
     * @param array $defaultTags Default tags for the project
     */
    public function __construct(
        string $name,
        bool $isPublic = false,
        bool $allowAutoPublish = false,
        bool $requireApproval = true,
        array $defaultTags = []
    ) {
        $this->name = $name;
        $this->isPublic = $isPublic;
        $this->allowAutoPublish = $allowAutoPublish;
        $this->requireApproval = $requireApproval;
        $this->defaultTags = $defaultTags;
    }

    /**
     * Create a Project from API response data.
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $project = new self(
            $data['name'],
            $data['isPublic'] ?? false,
            $data['allowAutoPublish'] ?? false,
            $data['requireApproval'] ?? true,
            $data['defaultTags'] ?? []
        );

        if (isset($data['id'])) {
            $project->setId($data['id']);
        }

        if (isset($data['createdAt'])) {
            $project->setCreatedAt(new \DateTime($data['createdAt']));
        }

        if (isset($data['updatedAt'])) {
            $project->setUpdatedAt(new \DateTime($data['updatedAt']));
        }

        return $project;
    }

    /**
     * Get the ID.
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Set the ID.
     *
     * @param string $id
     * @return self
     */
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the name.
     *
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Check if the project is public.
     *
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->isPublic;
    }

    /**
     * Set whether the project is public.
     *
     * @param bool $isPublic
     * @return self
     */
    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;
        return $this;
    }

    /**
     * Check if the project allows auto-publishing.
     *
     * @return bool
     */
    public function allowsAutoPublish(): bool
    {
        return $this->allowAutoPublish;
    }

    /**
     * Set whether the project allows auto-publishing.
     *
     * @param bool $allowAutoPublish
     * @return self
     */
    public function setAllowAutoPublish(bool $allowAutoPublish): self
    {
        $this->allowAutoPublish = $allowAutoPublish;
        return $this;
    }

    /**
     * Check if the project requires approval.
     *
     * @return bool
     */
    public function requiresApproval(): bool
    {
        return $this->requireApproval;
    }

    /**
     * Set whether the project requires approval.
     *
     * @param bool $requireApproval
     * @return self
     */
    public function setRequireApproval(bool $requireApproval): self
    {
        $this->requireApproval = $requireApproval;
        return $this;
    }

    /**
     * Get the default tags.
     *
     * @return array
     */
    public function getDefaultTags(): array
    {
        return $this->defaultTags;
    }

    /**
     * Set the default tags.
     *
     * @param array $defaultTags
     * @return self
     */
    public function setDefaultTags(array $defaultTags): self
    {
        $this->defaultTags = $defaultTags;
        return $this;
    }

    /**
     * Get the created at date.
     *
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Set the created at date.
     *
     * @param \DateTimeInterface $createdAt
     * @return self
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get the updated at date.
     *
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Set the updated at date.
     *
     * @param \DateTimeInterface $updatedAt
     * @return self
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Convert the model to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'isPublic' => $this->isPublic,
            'allowAutoPublish' => $this->allowAutoPublish,
            'requireApproval' => $this->requireApproval,
            'defaultTags' => $this->defaultTags,
            'createdAt' => $this->createdAt ? $this->createdAt->format(\DateTime::ISO8601) : null,
            'updatedAt' => $this->updatedAt ? $this->updatedAt->format(\DateTime::ISO8601) : null,
        ];
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}