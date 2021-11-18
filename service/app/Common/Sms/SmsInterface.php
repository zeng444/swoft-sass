<?php declare(strict_types=1);


namespace App\Common\Sms;

/**
 * Interface SmsInterface
 * @author Robert
 * @package App\Common\Sms
 */
interface SmsInterface
{

    /**
     * @param string $mobile
     * @param string $message
     * @param string $sign
     * @param string|null $sendAt
     * @param string $ext
     * @return string
     * @author Robert
     */
    public function send(string $mobile, string $message, string $sign = '',string $sendAt =  null, string $ext = ''): string;

    /**
     * @param array $mobiles
     * @param string $message
     * @param string $sign
     * @param string|null $sendAt
     * @param string $ext
     * @return string
     * @author Robert
     */
    public function batchSend(array $mobiles, string $message, string $sign = '',string $sendAt =  null, string $ext = ''): string;
}
