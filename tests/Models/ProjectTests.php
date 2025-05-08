<?php

namespace Changerawr\Tests\Models;

use Changerawr\Models\Project;
use PHPUnit\Framework\TestCase;

class ProjectTest extends TestCase
{
    public function testCanCreateProject()
    {
        $project = new Project('Test Project');

        $this->assertEquals('Test Project', $project->getName());
        $this->assertFalse($project->isPublic());
        $this->assertFalse($project->allowsAutoPublish());
        $this->assertTrue($project->requiresApproval());
        $this->assertEmpty($project->getDefaultTags());
    }

    public function testCanCreateProjectWithAllParameters()
    {
        $project = new Project(
            'Test Project',
            true,
            true,
            false,
            ['Feature', 'Bugfix']
        );

        $this->assertEquals('Test Project', $project->getName());
        $this->assertTrue($project->isPublic());
        $this->assertTrue($project->allowsAutoPublish());
        $this->assertFalse($project->requiresApproval());
        $this->assertEquals(['Feature', 'Bugfix'], $project->getDefaultTags());
    }

    public function testCanCreateFromArray()
    {
        $data = [
            'id' => 'test-123',
            'name' => 'Test Project',
            'isPublic' => true,
            'allowAutoPublish' => true,
            'requireApproval' => false,
            'defaultTags' => ['Feature', 'Bugfix'],
            'createdAt' => '2023-01-01T00:00:00Z',
            'updatedAt' => '2023-01-02T00:00:00Z'
        ];

        $project = Project::fromArray($data);

        $this->assertEquals('test-123', $project->getId());
        $this->assertEquals('Test Project', $project->getName());
        $this->assertTrue($project->isPublic());
        $this->assertTrue($project->allowsAutoPublish());
        $this->assertFalse($project->requiresApproval());
        $this->assertEquals(['Feature', 'Bugfix'], $project->getDefaultTags());
        $this->assertInstanceOf(\DateTimeInterface::class, $project->getCreatedAt());
        $this->assertInstanceOf(\DateTimeInterface::class, $project->getUpdatedAt());
        $this->assertEquals('2023-01-01', $project->getCreatedAt()->format('Y-m-d'));
        $this->assertEquals('2023-01-02', $project->getUpdatedAt()->format('Y-m-d'));
    }

    public function testCanConvertToArray()
    {
        $project = new Project('Test Project');
        $project->setId('test-123');
        $project->setIsPublic(true);
        $project->setAllowAutoPublish(true);
        $project->setRequireApproval(false);
        $project->setDefaultTags(['Feature', 'Bugfix']);

        $createdAt = new \DateTime('2023-01-01T00:00:00Z');
        $updatedAt = new \DateTime('2023-01-02T00:00:00Z');

        $project->setCreatedAt($createdAt);
        $project->setUpdatedAt($updatedAt);

        $array = $project->toArray();

        $this->assertEquals('test-123', $array['id']);
        $this->assertEquals('Test Project', $array['name']);
        $this->assertTrue($array['isPublic']);
        $this->assertTrue($array['allowAutoPublish']);
        $this->assertFalse($array['requireApproval']);
        $this->assertEquals(['Feature', 'Bugfix'], $array['defaultTags']);
        $this->assertEquals($createdAt->format(\DateTime::ISO8601), $array['createdAt']);
        $this->assertEquals($updatedAt->format(\DateTime::ISO8601), $array['updatedAt']);
    }

    public function testImplementsJsonSerializable()
    {
        $project = new Project('Test Project');
        $project->setId('test-123');

        $json = json_encode($project);
        $decoded = json_decode($json, true);

        $this->assertIsString($json);
        $this->assertEquals('test-123', $decoded['id']);
        $this->assertEquals('Test Project', $decoded['name']);
    }
}