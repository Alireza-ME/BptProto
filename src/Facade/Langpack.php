<?php

declare(strict_types=1);

namespace Bpt\Facade;

use Bpt\TL\Builder;
use Bpt\TL\Deserializer;

/**
 * Auto-generated facade for every langpack.* method (see tools/gen_methods.php).
 *
 * Peer-ish inputs accept friendly forms ('me', '\@username', ids, arrays);
 * complex objects are passed as pre-packed blobs (Bpt\TL\Peer / Bpt\TL\Builder).
 * Every method returns a parsed array/scalar (see Bpt\TL\Deserializer).
 */
class Langpack extends Group
{

    /**
     * langpack.getDifference#cd984aa5 = LangPackDifference.
     */
    public function getDifference(string $lang_pack, string $lang_code, int $from_version): mixed
    {
        $b = Builder::ctor(0xCD984AA5);
        $b->string((string)$lang_pack);
        $b->string((string)$lang_code);
        $b->int((int)$from_version);
        return Deserializer::parse($this->client->rpc($b->build()), 'LangPackDifference');
    }

    /**
     * langpack.getLangPack#f2f2330a = LangPackDifference.
     */
    public function getLangPack(string $lang_pack, string $lang_code): mixed
    {
        $b = Builder::ctor(0xF2F2330A);
        $b->string((string)$lang_pack);
        $b->string((string)$lang_code);
        return Deserializer::parse($this->client->rpc($b->build()), 'LangPackDifference');
    }

    /**
     * langpack.getLanguage#6a596502 = LangPackLanguage.
     */
    public function getLanguage(string $lang_pack, string $lang_code): mixed
    {
        $b = Builder::ctor(0x6A596502);
        $b->string((string)$lang_pack);
        $b->string((string)$lang_code);
        return Deserializer::parse($this->client->rpc($b->build()), 'LangPackLanguage');
    }

    /**
     * langpack.getLanguages#42c6978f = Vector<LangPackLanguage>.
     */
    public function getLanguages(string $lang_pack): mixed
    {
        $b = Builder::ctor(0x42C6978F);
        $b->string((string)$lang_pack);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<LangPackLanguage>');
    }

    /**
     * langpack.getStrings#efea3803 = Vector<LangPackString>.
     */
    public function getStrings(string $lang_pack, string $lang_code, array $keys): mixed
    {
        $b = Builder::ctor(0xEFEA3803);
        $b->string((string)$lang_pack);
        $b->string((string)$lang_code);
        $b->vectorString($keys);
        return Deserializer::parse($this->client->rpc($b->build()), 'Vector<LangPackString>');
    }
}
