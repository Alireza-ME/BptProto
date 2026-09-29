<?php

declare(strict_types=1);

namespace Bpt\Facade;

use Bpt\TL\Builder;
use Bpt\TL\Deserializer;

/**
 * Auto-generated facade for every folders.* method (see tools/gen_methods.php).
 *
 * Peer-ish inputs accept friendly forms ('me', '\@username', ids, arrays);
 * complex objects are passed as pre-packed blobs (Bpt\TL\Peer / Bpt\TL\Builder).
 * Every method returns a parsed array/scalar (see Bpt\TL\Deserializer).
 */
class Folders extends Group
{

    /**
     * folders.editPeerFolders#6847d0ab = Updates.
     */
    public function editPeerFolders(array $folder_peers): mixed
    {
        $b = Builder::ctor(0x6847D0AB);
        $b->vector($folder_peers);
        return Deserializer::parse($this->client->rpc($b->build()), 'Updates');
    }
}
