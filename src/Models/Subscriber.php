<?php

namespace Changerawr\Models;

/**
 * Subscriber model.
 */
class Subscriber implements \JsonSerializable
{
    /**
     * @var string|null
     */
    private $id;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var bool
     */
    private $isActive;

    /**
     * @var string|null
     */
    private $subscriptionType;

    /**
     * @var \DateTimeInterface|null
     */
    private $createdAt;

    /**
     * @var \DateTimeInterface|null
     */
    private $lastEmailSentAt;

    /**
     * Create a new Subscriber instance.
     *
     * @param string $email
     * @param string|null $name
     * @param bool $isActive
     * @param string|null $subscriptionType
     */
    public function __construct(
        string $email,
        ?string $name = null,
        bool $isActive = true,
        ?string $subscriptionType = 'ALL_UPDATES'
    ) {
        $this->email = $email;
        $this->name = $name;
        $this->isActive = $isActive;
        $this->subscriptionType = $subscriptionType;
    }

    /**
     * Create a Subscriber from API response data.
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $subscriber = new self(
            $data['email'],
            $data['name'] ?? null,
            $data['isActive'] ?? true,
            $data['subscriptionType'] ?? 'ALL_UPDATES'
        );

        if (isset($data['id'])) {
            $subscriber->setId($data['id']);
        }

        if (isset($data['createdAt'])) {
            $subscriber->setCreatedAt(new \DateTime($data['createdAt']));
        }

        if (isset($data['lastEmailSentAt']) && $data['lastEmailSentAt'] !== null) {
            $subscriber->setLastEmailSentAt(new \DateTime($data['lastEmailSentAt']));
        }

        return $subscriber;
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
     * Get the email.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the email.
     *
     * @param string $email
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get the name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the name.
     *
     * @param string|null $name
     * @return self
     */
    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Check if the subscriber is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * Set whether the subscriber is active.
     *
     * @param bool $isActive
     * @return self
     */
    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * Get the subscription type.
     *
     * @return string|null
     */
    public function getSubscriptionType(): ?string
    {
        return $this->subscriptionType;
    }

    /**
     * Set the subscription type.
     *
     * @param string $subscriptionType
     * @return self
     */
    public function setSubscriptionType(string $subscriptionType): self
    {
        $this->subscriptionType = $subscriptionType;
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
     * Get the last email sent at date.
     *
     * @return \DateTimeInterface|null
     */
    public function getLastEmailSentAt(): ?\DateTimeInterface
    {
        return $this->lastEmailSentAt;
    }

    /**
     * Set the last email sent at date.
     *
     * @param \DateTimeInterface|null $lastEmailSentAt
     * @return self
     */
    public function setLastEmailSentAt(?\DateTimeInterface $lastEmailSentAt): self
    {
        $this->lastEmailSentAt = $lastEmailSentAt;
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
            'email' => $this->email,
            'name' => $this->name,
            'isActive' => $this->isActive,
            'subscriptionType' => $this->subscriptionType,
            'createdAt' => $this->createdAt ? $this->createdAt->format(\DateTime::ISO8601) : null,
            'lastEmailSentAt' => $this->lastEmailSentAt ? $this->lastEmailSentAt->format(\DateTime::ISO8601) : null,
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