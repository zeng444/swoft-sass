<?php declare(strict_types=1);

namespace App\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;

/**
 * Class Date
 *
 * @Annotation
 * @Attributes({
 *     @Attribute("message",type="string"),
 *     @Attribute("format",type="string"),
 *     @Attribute("allowEmpty",type="bool")
 * })
 */
class Date
{

    /**
     * @var string
     */
    private $message = '';

    /**
     * @var string
     */
    private $format = 'Y-m-d';

    /**
     * @var bool
     */
    private $allowEmpty = false;

    /**
     * @var string
     */
    private $name = '';

    /**
     * StringType constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->message = $values['value'];
        }
        if (isset($values['message'])) {
            $this->message = $values['message'];
        }
        if (isset($values['name'])) {
            $this->name = $values['name'];
        }
        if (isset($values['format'])) {
            $this->format = $values['format'];
        }
        if (isset($values['allowEmpty'])) {
            $this->allowEmpty = $values['allowEmpty'];
        }
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * @return string
     * @author Robert
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * Author:Robert
     *
     * @return bool
     */
    public function getAllowEmpty(): bool
    {
        return $this->allowEmpty;
    }

}
