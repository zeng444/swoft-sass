<?php declare(strict_types=1);


namespace App\Model\Entity;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;


/**
 * 租客
 * Class Tenant
 *
 * @since 2.0
 *
 * @Entity(table="tenant")
 */
class Tenant extends BaseEntity
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
     * 是否可用
     *
     * @Column()
     *
     * @var int
     */
    private $isAvailable;

    /**
     * 公司登录名
     *
     * @Column()
     *
     * @var string
     */
    private $name;

    /**
     * 省份
     *
     * @Column()
     *
     * @var string|null
     */
    private $province;

    /**
     * 城市
     *
     * @Column()
     *
     * @var string|null
     */
    private $city;

    /**
     * 联系人
     *
     * @Column()
     *
     * @var string|null
     */
    private $linkman;

    /**
     * 联系电话
     *
     * @Column()
     *
     * @var string|null
     */
    private $contact;

    /**
     * 有效期开始
     *
     * @Column()
     *
     * @var string|null
     */
    private $beginAt;

    /**
     * 有效期结束
     *
     * @Column()
     *
     * @var string|null
     */
    private $endAt;

    /**
     * 公司全名
     *
     * @Column()
     *
     * @var string|null
     */
    private $fullName;

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
     * @param int $isAvailable
     *
     * @return self
     */
    public function setIsAvailable(int $isAvailable): self
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string|null $province
     *
     * @return self
     */
    public function setProvince(?string $province): self
    {
        $this->province = $province;

        return $this;
    }

    /**
     * @param string|null $city
     *
     * @return self
     */
    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @param string|null $linkman
     *
     * @return self
     */
    public function setLinkman(?string $linkman): self
    {
        $this->linkman = $linkman;

        return $this;
    }

    /**
     * @param string|null $contact
     *
     * @return self
     */
    public function setContact(?string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * @param string|null $beginAt
     *
     * @return self
     */
    public function setBeginAt(?string $beginAt): self
    {
        $this->beginAt = $beginAt;

        return $this;
    }

    /**
     * @param string|null $endAt
     *
     * @return self
     */
    public function setEndAt(?string $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    /**
     * @param string|null $fullName
     *
     * @return self
     */
    public function setFullName(?string $fullName): self
    {
        $this->fullName = $fullName;

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
    public function getIsAvailable(): ?int
    {
        return $this->isAvailable;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getProvince(): ?string
    {
        return $this->province;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @return string|null
     */
    public function getLinkman(): ?string
    {
        return $this->linkman;
    }

    /**
     * @return string|null
     */
    public function getContact(): ?string
    {
        return $this->contact;
    }

    /**
     * @return string|null
     */
    public function getBeginAt(): ?string
    {
        return $this->beginAt;
    }

    /**
     * @return string|null
     */
    public function getEndAt(): ?string
    {
        return $this->endAt;
    }

    /**
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return $this->fullName;
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
