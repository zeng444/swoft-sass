<?php declare(strict_types=1);


namespace App\Model\Entity;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;


/**
 *
 * Class ServiceDatabase
 *
 * @since 2.0
 *
 * @Entity(table="service_database")
 */
class ServiceDatabase extends BaseEntity
{
    use EntityTrait;

    /**
     *
     * @Id()
     * @Column()
     *
     * @var int
     */
    private $id;

    /**
     *
     *
     * @Column()
     *
     * @var int
     */
    private $serverId;

    /**
     * 服务
     *
     * @Column()
     *
     * @var int
     */
    private $serviceId;

    /**
     * 数据库
     *
     * @Column()
     *
     * @var string
     */
    private $database;

    /**
     *
     *
     * @Column()
     *
     * @var string|null
     */
    private $createdAt;

    /**
     *
     *
     * @Column()
     *
     * @var string|null
     */
    private $updatedAt;


    /**
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param int $serverId
     *
     * @return self
     */
    public function setServerId(int $serverId): self
    {
        $this->serverId = $serverId;

        return $this;
    }

    /**
     * @param int $serviceId
     *
     * @return self
     */
    public function setServiceId(int $serviceId): self
    {
        $this->serviceId = $serviceId;

        return $this;
    }

    /**
     * @param string $database
     *
     * @return self
     */
    public function setDatabase(string $database): self
    {
        $this->database = $database;

        return $this;
    }

    /**
     * @param string|null $createdAt
     *
     * @return self
     */
    public function setCreatedAt(?string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @param string|null $updatedAt
     *
     * @return self
     */
    public function setUpdatedAt(?string $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getServerId(): ?int
    {
        return $this->serverId;
    }

    /**
     * @return int
     */
    public function getServiceId(): ?int
    {
        return $this->serviceId;
    }

    /**
     * @return string
     */
    public function getDatabase(): ?string
    {
        return $this->database;
    }

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

}
