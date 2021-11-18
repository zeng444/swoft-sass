<?php declare(strict_types=1);


namespace App\Model\Entity;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;


/**
 * 操作日志
 * Class OperationLog
 *
 * @since 2.0
 *
 * @Entity(table="operation_log")
 */
class OperationLog extends BaseEntity
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
     * @var string|null
     */
    private $event;

    /**
     * IP地址
     *
     * @Column()
     *
     * @var string
     */
    private $ip;

    /**
     * 行为
     *
     * @Column()
     *
     * @var string|null
     */
    private $action;

    /**
     * 数据
     *
     * @Column()
     *
     * @var string
     */
    private $data;

    /**
     * 操作人ID
     *
     * @Column(name="administrator_id", prop="administratorId")
     *
     * @var string|null
     */
    private $administratorId;

    /**
     * 操作人姓名
     *
     * @Column(name="administrator_name", prop="administratorName")
     *
     * @var string|null
     */
    private $administratorName;

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
     * @param string|null $event
     *
     * @return self
     */
    public function setEvent(?string $event): self
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @param string $ip
     *
     * @return self
     */
    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * @param string|null $action
     *
     * @return self
     */
    public function setAction(?string $action): self
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @param string $data
     *
     * @return self
     */
    public function setData(string $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param string|null $administratorId
     *
     * @return self
     */
    public function setAdministratorId(?string $administratorId): self
    {
        $this->administratorId = $administratorId;

        return $this;
    }

    /**
     * @param string|null $administratorName
     *
     * @return self
     */
    public function setAdministratorName(?string $administratorName): self
    {
        $this->administratorName = $administratorName;

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
     * @return string|null
     */
    public function getEvent(): ?string
    {
        return $this->event;
    }

    /**
     * @return string
     */
    public function getIp(): ?string
    {
        return $this->ip;
    }

    /**
     * @return string|null
     */
    public function getAction(): ?string
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function getData(): ?string
    {
        return $this->data;
    }

    /**
     * @return string|null
     */
    public function getAdministratorId(): ?string
    {
        return $this->administratorId;
    }

    /**
     * @return string|null
     */
    public function getAdministratorName(): ?string
    {
        return $this->administratorName;
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
