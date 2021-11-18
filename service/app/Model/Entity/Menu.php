<?php declare(strict_types=1);


namespace App\Model\Entity;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;



/**
 * 
 * Class Menu
 *
 * @since 2.0
 *
 * @Entity(table="menu")
 */
class Menu extends BaseEntity
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
     * 租户Id
     *
     * @Column()
     *
     * @var int
     */
    private $tenantId;

    /**
     * 
     *
     * @Column()
     *
     * @var int
     */
    private $sort;

    /**
     * 是否可见
     *
     * @Column()
     *
     * @var int
     */
    private $isVisible;

    /**
     * 栏目名
     *
     * @Column()
     *
     * @var string
     */
    private $name;

    /**
     * 父级栏目
     *
     * @Column()
     *
     * @var int|null
     */
    private $parentId;

    /**
     * 图标
     *
     * @Column()
     *
     * @var string|null
     */
    private $icon;

    /**
     * 链接
     *
     * @Column()
     *
     * @var string|null
     */
    private $link;

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
     * @param int $tenantId
     *
     * @return self
     */
    public function setTenantId(int $tenantId): self
    {
        $this->tenantId = $tenantId;

        return $this;
    }

    /**
     * @param int $sort
     *
     * @return self
     */
    public function setSort(int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @param int $isVisible
     *
     * @return self
     */
    public function setIsVisible(int $isVisible): self
    {
        $this->isVisible = $isVisible;

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
     * @param int|null $parentId
     *
     * @return self
     */
    public function setParentId(?int $parentId): self
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * @param string|null $icon
     *
     * @return self
     */
    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @param string|null $link
     *
     * @return self
     */
    public function setLink(?string $link): self
    {
        $this->link = $link;

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
    public function getTenantId(): ?int
    
    {
        return $this->tenantId;
    }

    /**
     * @return int
     */
    public function getSort(): ?int
    
    {
        return $this->sort;
    }

    /**
     * @return int
     */
    public function getIsVisible(): ?int
    
    {
        return $this->isVisible;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    
    {
        return $this->name;
    }

    /**
     * @return int|null
     */
    public function getParentId(): ?int
    
    {
        return $this->parentId;
    }

    /**
     * @return string|null
     */
    public function getIcon(): ?string
    
    {
        return $this->icon;
    }

    /**
     * @return string|null
     */
    public function getLink(): ?string
    
    {
        return $this->link;
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
