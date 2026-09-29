<?php

declare(strict_types=1);

namespace Bpt\Transport;

/**
 * Monotonic MTProto msg_id generator (msg_id % 4 == 0, strictly increasing).
 *
 * The counter starts at a random offset so two processes that never shared
 * state still (overwhelmingly) avoid duplicate msg_ids; continuity across
 * restarts comes from persisting last() in storage (see Client session).
 */
final class MsgIdGenerator
{
    private int $ctr;
    private int $last;
    private int $offset = 0;

    /**
     * @param int $timeOffset server_time - time() (0 for plain handshake).
     * @param int $last      Last issued msg_id (resume point, 0 = none).
     */
    public function __construct(int $timeOffset = 0, int $last = 0)
    {
        $this->offset = $timeOffset;
        $this->ctr = random_int(0, 1 << 20);
        $this->last = $last;
    }

    /**
     * Next message id.
     */
    public function next(): int
    {
        $t = time() + $this->offset;
        $id = ($t * 4294967296) + ($this->ctr * 4 + 4);
        $this->ctr++;
        if ($id <= $this->last) {
            $id = $this->last + 4;
        }
        $id -= $id % 4;
        $this->last = $id;
        return $id;
    }

    /** Last issued msg_id (persist to resume monotonicity). */
    public function getLast(): int
    {
        return $this->last;
    }

    /**
     * Update clock offset (from server_DH_inner_data.server_time).
     */
    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
    }
}
