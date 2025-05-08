<?php

namespace Changerawr\Models;

/**
 * Email configuration model.
 */
class EmailConfig implements \JsonSerializable
{
    /**
     * @var string|null
     */
    private $id;

    /**
     * @var string
     */
    private $projectId;

    /**
     * @var bool
     */
    private $enabled;

    /**
     * @var string
     */
    private $smtpHost;

    /**
     * @var int
     */
    private $smtpPort;

    /**
     * @var string|null
     */
    private $smtpUser;

    /**
     * @var string|null
     */
    private $smtpPassword;

    /**
     * @var bool
     */
    private $smtpSecure;

    /**
     * @var string
     */
    private $fromEmail;

    /**
     * @var string|null
     */
    private $fromName;

    /**
     * @var string|null
     */
    private $replyToEmail;

    /**
     * @var string|null
     */
    private $defaultSubject;

    /**
     * @var \DateTimeInterface|null
     */
    private $createdAt;

    /**
     * @var \DateTimeInterface|null
     */
    private $updatedAt;

    /**
     * @var \DateTimeInterface|null
     */
    private $lastTestedAt;

    /**
     * @var string|null
     */
    private $testStatus;

    /**
     * Create a new EmailConfig instance.
     *
     * @param string $projectId
     * @param string $smtpHost
     * @param int $smtpPort
     * @param string $fromEmail
     * @param bool $enabled
     * @param string|null $smtpUser
     * @param string|null $smtpPassword
     * @param bool $smtpSecure
     * @param string|null $fromName
     * @param string|null $replyToEmail
     * @param string|null $defaultSubject
     */
    public function __construct(
        string $projectId,
        string $smtpHost,
        int $smtpPort,
        string $fromEmail,
        bool $enabled = false,
        ?string $smtpUser = null,
        ?string $smtpPassword = null,
        bool $smtpSecure = true,
        ?string $fromName = null,
        ?string $replyToEmail = null,
        ?string $defaultSubject = 'New Changelog Update'
    ) {
        $this->projectId = $projectId;
        $this->smtpHost = $smtpHost;
        $this->smtpPort = $smtpPort;
        $this->fromEmail = $fromEmail;
        $this->enabled = $enabled;
        $this->smtpUser = $smtpUser;
        $this->smtpPassword = $smtpPassword;
        $this->smtpSecure = $smtpSecure;
        $this->fromName = $fromName;
        $this->replyToEmail = $replyToEmail;
        $this->defaultSubject = $defaultSubject;
    }

    /**
     * Create an EmailConfig from API response data.
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $config = new self(
            $data['projectId'],
            $data['smtpHost'],
            (int) $data['smtpPort'],
            $data['fromEmail'],
            $data['enabled'] ?? false,
            $data['smtpUser'] ?? null,
            $data['smtpPassword'] ?? null,
            $data['smtpSecure'] ?? true,
            $data['fromName'] ?? null,
            $data['replyToEmail'] ?? null,
            $data['defaultSubject'] ?? 'New Changelog Update'
        );

        if (isset($data['id'])) {
            $config->setId($data['id']);
        }

        if (isset($data['createdAt'])) {
            $config->setCreatedAt(new \DateTime($data['createdAt']));
        }

        if (isset($data['updatedAt'])) {
            $config->setUpdatedAt(new \DateTime($data['updatedAt']));
        }

        if (isset($data['lastTestedAt']) && $data['lastTestedAt'] !== null) {
            $config->setLastTestedAt(new \DateTime($data['lastTestedAt']));
        }

        if (isset($data['testStatus'])) {
            $config->setTestStatus($data['testStatus']);
        }

        return $config;
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
     * Get the project ID.
     *
     * @return string
     */
    public function getProjectId(): string
    {
        return $this->projectId;
    }

    /**
     * Set the project ID.
     *
     * @param string $projectId
     * @return self
     */
    public function setProjectId(string $projectId): self
    {
        $this->projectId = $projectId;
        return $this;
    }

    /**
     * Check if email notifications are enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Set whether email notifications are enabled.
     *
     * @param bool $enabled
     * @return self
     */
    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * Get the SMTP host.
     *
     * @return string
     */
    public function getSmtpHost(): string
    {
        return $this->smtpHost;
    }

    /**
     * Set the SMTP host.
     *
     * @param string $smtpHost
     * @return self
     */
    public function setSmtpHost(string $smtpHost): self
    {
        $this->smtpHost = $smtpHost;
        return $this;
    }

    /**
     * Get the SMTP port.
     *
     * @return int
     */
    public function getSmtpPort(): int
    {
        return $this->smtpPort;
    }

    /**
     * Set the SMTP port.
     *
     * @param int $smtpPort
     * @return self
     */
    public function setSmtpPort(int $smtpPort): self
    {
        $this->smtpPort = $smtpPort;
        return $this;
    }

    /**
     * Get the SMTP username.
     *
     * @return string|null
     */
    public function getSmtpUser(): ?string
    {
        return $this->smtpUser;
    }

    /**
     * Set the SMTP username.
     *
     * @param string|null $smtpUser
     * @return self
     */
    public function setSmtpUser(?string $smtpUser): self
    {
        $this->smtpUser = $smtpUser;
        return $this;
    }

    /**
     * Get the SMTP password.
     *
     * @return string|null
     */
    public function getSmtpPassword(): ?string
    {
        return $this->smtpPassword;
    }

    /**
     * Set the SMTP password.
     *
     * @param string|null $smtpPassword
     * @return self
     */
    public function setSmtpPassword(?string $smtpPassword): self
    {
        $this->smtpPassword = $smtpPassword;
        return $this;
    }

    /**
     * Check if SMTP secure connection is enabled.
     *
     * @return bool
     */
    public function isSmtpSecure(): bool
    {
        return $this->smtpSecure;
    }

    /**
     * Set whether SMTP secure connection is enabled.
     *
     * @param bool $smtpSecure
     * @return self
     */
    public function setSmtpSecure(bool $smtpSecure): self
    {
        $this->smtpSecure = $smtpSecure;
        return $this;
    }

    /**
     * Get the from email address.
     *
     * @return string
     */
    public function getFromEmail(): string
    {
        return $this->fromEmail;
    }

    /**
     * Set the from email address.
     *
     * @param string $fromEmail
     * @return self
     */
    public function setFromEmail(string $fromEmail): self
    {
        $this->fromEmail = $fromEmail;
        return $this;
    }

    /**
     * Get the from name.
     *
     * @return string|null
     */
    public function getFromName(): ?string
    {
        return $this->fromName;
    }

    /**
     * Set the from name.
     *
     * @param string|null $fromName
     * @return self
     */
    public function setFromName(?string $fromName): self
    {
        $this->fromName = $fromName;
        return $this;
    }

    /**
     * Get the reply-to email address.
     *
     * @return string|null
     */
    public function getReplyToEmail(): ?string
    {
        return $this->replyToEmail;
    }

    /**
     * Set the reply-to email address.
     *
     * @param string|null $replyToEmail
     * @return self
     */
    public function setReplyToEmail(?string $replyToEmail): self
    {
        $this->replyToEmail = $replyToEmail;
        return $this;
    }

    /**
     * Get the default subject.
     *
     * @return string|null
     */
    public function getDefaultSubject(): ?string
    {
        return $this->defaultSubject;
    }

    /**
     * Set the default subject.
     *
     * @param string|null $defaultSubject
     * @return self
     */
    public function setDefaultSubject(?string $defaultSubject): self
    {
        $this->defaultSubject = $defaultSubject;
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
     * Get the last tested at date.
     *
     * @return \DateTimeInterface|null
     */
    public function getLastTestedAt(): ?\DateTimeInterface
    {
        return $this->lastTestedAt;
    }

    /**
     * Set the last tested at date.
     *
     * @param \DateTimeInterface|null $lastTestedAt
     * @return self
     */
    public function setLastTestedAt(?\DateTimeInterface $lastTestedAt): self
    {
        $this->lastTestedAt = $lastTestedAt;
        return $this;
    }

    /**
     * Get the test status.
     *
     * @return string|null
     */
    public function getTestStatus(): ?string
    {
        return $this->testStatus;
    }

    /**
     * Set the test status.
     *
     * @param string|null $testStatus
     * @return self
     */
    public function setTestStatus(?string $testStatus): self
    {
        $this->testStatus = $testStatus;
        return $this;
    }

    /**
     * Check if the configuration has been tested.
     *
     * @return bool
     */
    public function isTested(): bool
    {
        return $this->lastTestedAt !== null;
    }

    /**
     * Check if the last test was successful.
     *
     * @return bool
     */
    public function isTestSuccessful(): bool
    {
        return $this->testStatus === 'success';
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
            'projectId' => $this->projectId,
            'enabled' => $this->enabled,
            'smtpHost' => $this->smtpHost,
            'smtpPort' => $this->smtpPort,
            'smtpUser' => $this->smtpUser,
            // Don't include password in the array representation for security
            'smtpSecure' => $this->smtpSecure,
            'fromEmail' => $this->fromEmail,
            'fromName' => $this->fromName,
            'replyToEmail' => $this->replyToEmail,
            'defaultSubject' => $this->defaultSubject,
            'createdAt' => $this->createdAt ? $this->createdAt->format(\DateTime::ISO8601) : null,
            'updatedAt' => $this->updatedAt ? $this->updatedAt->format(\DateTime::ISO8601) : null,
            'lastTestedAt' => $this->lastTestedAt ? $this->lastTestedAt->format(\DateTime::ISO8601) : null,
            'testStatus' => $this->testStatus,
        ];
    }

    /**
     * Get data for making API requests.
     *
     * @param bool $includePassword Whether to include the password
     * @return array
     */
    public function toRequestData(bool $includePassword = false): array
    {
        $data = $this->toArray();

        // Remove unnecessary fields
        unset($data['id']);
        unset($data['createdAt']);
        unset($data['updatedAt']);
        unset($data['lastTestedAt']);
        unset($data['testStatus']);

        // Include password if requested
        if ($includePassword && $this->smtpPassword !== null) {
            $data['smtpPassword'] = $this->smtpPassword;
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}