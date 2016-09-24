<?php
/**
 * Created by PhpStorm.
 * User: leo108
 * Date: 2016/9/24
 * Time: 15:53
 */

namespace App\Services;

use Leo108\CAS\Contracts\TicketLocker as Contract;
use NinjaMutex\Lock\LockAbstract;

class TickerLocker implements Contract
{
    /**
     * @var LockAbstract
     */
    protected $locker;

    /**
     * TickerLocker constructor.
     * @param LockAbstract $locker
     */
    public function __construct(LockAbstract $locker)
    {
        $this->locker = $locker;
    }

    public function acquireLock($key, $timeout)
    {
        return $this->locker->acquireLock($key, $timeout);
    }

    public function releaseLock($key)
    {
        return $this->locker->releaseLock($key);
    }
}
