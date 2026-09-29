<?php

declare(strict_types=1);

namespace Bpt\Facade;

use Bpt\TL\Builder;
use Bpt\TL\Deserializer;

/**
 * Auto-generated facade for every fragment.* method (see tools/gen_methods.php).
 *
 * Peer-ish inputs accept friendly forms ('me', '\@username', ids, arrays);
 * complex objects are passed as pre-packed blobs (Bpt\TL\Peer / Bpt\TL\Builder).
 * Every method returns a parsed array/scalar (see Bpt\TL\Deserializer).
 */
class Fragment extends Group
{

    /**
     * fragment.getCollectibleInfo#be1e85ba = fragment.CollectibleInfo.
     */
    public function getCollectibleInfo(string $collectible): mixed
    {
        $b = Builder::ctor(0xBE1E85BA);
        $b->rawBlob($collectible);
        return Deserializer::parse($this->client->rpc($b->build()), 'fragment.CollectibleInfo');
    }
}
