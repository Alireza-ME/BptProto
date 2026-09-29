<?php

declare(strict_types=1);

namespace Bpt;

/**
 * File-based key/value storage for runtime session data.
 *
 * All auth keys, salts, hashes and API responses live in one directory
 * instead of being scattered next to the code, e.g.:
 *   database/authkey_dc4.bin, database/phone_hash.txt, database/dc.txt
 *
 * Files here are secrets (full account access): keep the directory 0700
 * and out of version control.
 */
final class Storage
{
    /**
     * @param string $dir Absolute path of the storage directory.
     * @throws \RuntimeException If the directory cannot be created.
     */
    public function __construct(private readonly string $dir)
    {
        if (!is_dir($this->dir) && !mkdir($this->dir, 0700, true) && !is_dir($this->dir)) {
            throw new \RuntimeException("cannot create storage dir {$this->dir}");
        }
    }

    /**
     * Absolute path of a stored entry.
     *
     * @param string $name File name, e.g. 'authkey_dc4.bin'.
     */
    public function path(string $name): string
    {
        return $this->dir . '/' . $name;
    }

    /**
     * Check that an entry exists.
     */
    public function exists(string $name): bool
    {
        return file_exists($this->path($name));
    }

    /**
     * Read an entry.
     *
     * @return string|null Null when the entry does not exist.
     */
    public function get(string $name): ?string
    {
        $p = $this->path($name);
        if (!file_exists($p)) {
            return null;
        }
        $data = file_get_contents($p);
        return $data === false ? null : $data;
    }

    /**
     * Write an entry (creates the directory on demand).
     *
     * @param string $name File name.
     * @param string $data Raw bytes.
     */
    public function put(string $name, string $data): void
    {
        file_put_contents($this->path($name), $data);
    }

    /**
     * Delete an entry if it exists.
     */
    public function delete(string $name): void
    {
        @unlink($this->path($name));
    }

    /**
     * Check that an entry exists and is fresher than $ttlSeconds.
     *
     * @param string $name File name.
     * @param int    $ttlSeconds Time-to-live in seconds.
     */
    public function isFresh(string $name, int $ttlSeconds): bool
    {
        $p = $this->path($name);
        return file_exists($p) && filesize($p) > 0 && (time() - filemtime($p) < $ttlSeconds);
    }

    /** @var resource|null */
    private $lockHandle = null;

    /**
     * Exclusive per-directory lock (single live session at a time).
     *
     * Two processes sharing one authkey/session would fork msg_id/seq
     * sequences — servers treat that as replay abuse. The lock is held
     * from connect() until disconnect().
     *
     * @throws \RuntimeException If another process holds the lock past $waitSeconds.
     */
    public function acquireLock(int $waitSeconds = 30): void
    {
        if (is_resource($this->lockHandle)) {
            return;
        }
        $h = fopen($this->path('.lock'), 'c');
        if ($h === false) {
            throw new \RuntimeException('cannot open lock file');
        }
        $start = time();
        while (!flock($h, LOCK_EX | LOCK_NB)) {
            if (time() - $start >= $waitSeconds) {
                fclose($h);
                throw new \RuntimeException('another process is using this session (database/.lock)');
            }
            sleep(1);
        }
        $this->lockHandle = $h;
    }

    /**
     * Release the directory lock (idempotent).
     */
    public function releaseLock(): void
    {
        if (is_resource($this->lockHandle)) {
            flock($this->lockHandle, LOCK_UN);
            fclose($this->lockHandle);
        }
        $this->lockHandle = null;
    }
}
