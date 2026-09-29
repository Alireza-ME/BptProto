<?php

declare(strict_types=1);

namespace Bpt\Entity;

/**
 * Thrown when a TL payload cannot be consumed exactly (media, keyboards,
 * exotic actions...). Vector walkers catch it: items parsed so far are
 * kept and the walk stops.
 */
final class StopWalk extends \RuntimeException
{
}
