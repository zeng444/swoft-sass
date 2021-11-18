<?php declare(strict_types=1);

namespace AppTest\Unit\Model\Logic;

use App\Model\Logic\SystemSettingLogic;
use PHPUnit\Framework\TestCase;

/**
 * Author:Robert
 *
 * Class SystemSettingTestLogic
 * @package AppTest\Unit\Model\Logic
 */
class SystemSettingLogicTest extends TestCase
{

    public function testSetCase(): void
    {
        /** @var SystemSettingLogic $systemSettingLogic */
        $systemSettingLogic = \Swoft::getBean(SystemSettingLogic::class);
        $this->assertTrue($systemSettingLogic->set('allowedUsers', '10', 4));
    }

}
