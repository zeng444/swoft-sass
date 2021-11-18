<?php declare(strict_types=1);


namespace App\Model\Entity;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;


/**
 * 资金明细
 * Class TenantBalance
 *
 * @since 2.0
 *
 * @Entity(table="tenant_balance")
 */
class TenantBalance extends BaseEntity
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
     * 租客Id
     *
     * @Column()
     *
     * @var int
     */
    private $tenantId;

    /**
     * 本地订单号
     *
     * @Column()
     *
     * @var string
     */
    private $outTradeNo;

    /**
     * 支付渠道订单号
     *
     * @Column()
     *
     * @var string
     */
    private $transactionId;

    /**
     * 业务类型
     *
     * @Column()
     *
     * @var string
     */
    private $biz;

    /**
     * 业务数据
     *
     * @Column()
     *
     * @var string|null
     */
    private $bizData;

    /**
     * 金额
     *
     * @Column()
     *
     * @var int
     */
    private $fee;

    /**
     * 业务备注
     *
     * @Column()
     *
     * @var string|null
     */
    private $remark;

    /**
     * 支付时间
     *
     * @Column()
     *
     * @var string|null
     */
    private $paidAt;

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
     * @param string $outTradeNo
     *
     * @return self
     */
    public function setOutTradeNo(string $outTradeNo): self
    {
        $this->outTradeNo = $outTradeNo;

        return $this;
    }

    /**
     * @param string $transactionId
     *
     * @return self
     */
    public function setTransactionId(string $transactionId): self
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * @param string $biz
     *
     * @return self
     */
    public function setBiz(string $biz): self
    {
        $this->biz = $biz;

        return $this;
    }

    /**
     * @param string|null $bizData
     *
     * @return self
     */
    public function setBizData(?string $bizData): self
    {
        $this->bizData = $bizData;

        return $this;
    }

    /**
     * @param int $fee
     *
     * @return self
     */
    public function setFee(int $fee): self
    {
        $this->fee = $fee;

        return $this;
    }

    /**
     * @param string|null $remark
     *
     * @return self
     */
    public function setRemark(?string $remark): self
    {
        $this->remark = $remark;

        return $this;
    }

    /**
     * @param string|null $paidAt
     *
     * @return self
     */
    public function setPaidAt(?string $paidAt): self
    {
        $this->paidAt = $paidAt;

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
     * @return string
     */
    public function getOutTradeNo(): ?string
    {
        return $this->outTradeNo;
    }

    /**
     * @return string
     */
    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    /**
     * @return string
     */
    public function getBiz(): ?string
    {
        return $this->biz;
    }

    /**
     * @return string|null
     */
    public function getBizData(): ?string
    {
        return $this->bizData;
    }

    /**
     * @return int
     */
    public function getFee(): ?int
    {
        return $this->fee;
    }

    /**
     * @return string|null
     */
    public function getRemark(): ?string
    {
        return $this->remark;
    }

    /**
     * @return string|null
     */
    public function getPaidAt(): ?string
    {
        return $this->paidAt;
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
