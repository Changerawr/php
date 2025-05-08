<?php

namespace Changerawr\Models;

/**
 * Changelog entry model.
 */
class ChangelogEntry implements \JsonSerializable
{
    /**
     * @var string|null
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string|null
     */
    private $version;

    /**
     * @var \DateTimeInterface|null
     */
    private $publishedAt;

    /**
     * @var \DateTimeInterface|null
     */
    private $createdAt;

    /**
     * @var \DateTimeInterface|null
     */
    private $updatedAt;

    /**
     * @var string
     */
    private $changelogId;

    /**
     * @var array
     */
    private $tags = [];

    /**
     * Create a new ChangelogEntry instance.
     *
     * @param string $title Entry title
     * @param string $content Entry content (markdown)
     * @param string|null $version Version number
     * @param array $tags Array of tags
     */
    public function __construct(string $title, string $content, ?string $version = null, array $tags = [])
    {
        $this->title = $title;
        $this->content = $content;
        $this->version = $version;
        $this->tags = $tags;
    }

    /**
     * Create a ChangelogEntry from API response data.
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $entry = new self(
            $data['title'],
            $data['content'],
            $data['version'] ?? null,
            $data['tags'] ?? []
        );

        if (isset($data['id'])) {
            $entry->setId($data['id']);
        }

        if (isset($data['publishedAt'])) {
            $entry->setPublishedAt(new \DateTime($data['publishedAt']));
        }

        if (isset($data['createdAt'])) {
            $entry->setCreatedAt(new \DateTime($data['createdAt']));
        }

        if (isset($data['updatedAt'])) {
            $entry->setUpdatedAt(new \DateTime($data['updatedAt']));
        }

        if (isset($data['changelogId'])) {
            $entry->setChangelogId($data['changelogId']);
        }

        return $entry;
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
     * Get the title.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set the title.
     *
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get the content.
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Set the content.
     *
     * @param string $content
     * @return self
     */
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get the version.
     *
     * @return string|null
     */
    public function getVersion(): ?string
    {
        return $this->version;
    }

    /**
     * Set the version.
     *
     * @param string|null $version
     * @return self
     */
    public function setVersion(?string $version): self
    {
        $this->version = $version;
        return $this;
    }

    /**
     * Get the published at date.
     *
     * @return \DateTimeInterface|null
     */
    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    /**
     * Set the published at date.
     *
     * @param \DateTimeInterface|null $publishedAt
     * @return self
     */
    public function setPublishedAt(?\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;
        return $this;
    }

    /**
     * Check if the entry is published.
     *
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->publishedAt !== null;
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
     * Get the changelog ID.
     *
     * @return string|null
     */
    public function getChangelogId(): ?string
    {
        return $this->changelogId;
    }

    /**
     * Set the changelog ID.
     *
     * @param string $changelogId
     * @return self
     */
    public function setChangelogId(string $changelogId): self
    {
        $this->changelogId = $changelogId;
        return $this;
    }

    /**
     * Get the tags.
     *
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * Set the tags.
     *
     * @param array $tags
     * @return self
     */
    public function setTags(array $tags): self
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * Add a tag.
     *
     * @param string|array $tag
     * @return self
     */
    public function addTag($tag): self
    {
        if (is_array($tag)) {
            $this->tags[] = $tag;
        } else {
            $this->tags[] = ['name' => $tag];
        }

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
            'title' => $this->title,
            'content' => $this->content,
            'version' => $this->version,
            'publishedAt' => $this->publishedAt ? $this->publishedAt->format(\DateTime::ISO8601) : null,
            'createdAt' => $this->createdAt ? $this->createdAt->format(\DateTime::ISO8601) : null,
            'updatedAt' => $this->updatedAt ? $this->updatedAt->format(\DateTime::ISO8601) : null,
            'changelogId' => $this->changelogId,
            'tags' => $this->tags,
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