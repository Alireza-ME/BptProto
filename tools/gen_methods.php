<?php

declare(strict_types=1);

/**
 * Generates the complete, easy-to-use facade for EVERY MTProto API method.
 *
 * Reads a Telegram TL schema (tdlib's telegram_api.tl) and emits:
 *
 *   src/TL/Types.php       — constructor map used by the deserializer
 *   src/Facade/<Ns>.php    — one complete facade class per namespace
 *
 * Each generated method resolves friendly peer inputs ('me', '@username', ids,
 * arrays), serializes with Bpt\TL\Builder and returns a parsed array/scalar
 * (see Bpt\TL\Deserializer). The generated classes extend Bpt\Facade\Group, so
 * they are wired straight into BptProto as $tg->messages, $tg->channels, …
 *
 * Namespaces in SKIP_NAMESPACES are library-specific (not generated): their
 * facade is hand-written in src/Facade (e.g. Updates = realtime polling).
 *
 * Regenerate (schema is bundled at tools/telegram_api.tl):
 *   php tools/gen_methods.php tools/telegram_api.tl
 *
 * To update the schema itself:
 *   curl -fsSL https://raw.githubusercontent.com/tdlib/td/master/td/generate/scheme/telegram_api.tl -o tools/telegram_api.tl
 */

const RESERVED = [
    'abstract', 'and', 'array', 'as', 'break', 'callable', 'case', 'catch',
    'class', 'clone', 'const', 'continue', 'declare', 'default', 'die', 'do',
    'echo', 'else', 'elseif', 'empty', 'enddeclare', 'endfor', 'endforeach',
    'endif', 'endswitch', 'endwhile', 'enum', 'eval', 'exit', 'extends',
    'final', 'finally', 'fn', 'for', 'foreach', 'function', 'global', 'goto',
    'if', 'implements', 'include', 'include_once', 'instanceof', 'insteadof',
    'interface', 'isset', 'list', 'match', 'namespace', 'new', 'or', 'print',
    'private', 'protected', 'public', 'readonly', 'require', 'require_once',
    'return', 'static', 'switch', 'throw', 'trait', 'try', 'unset', 'use',
    'var', 'while', 'xor', 'yield', 'bool', 'int', 'float', 'string', 'object',
    'mixed', 'void', 'never', 'iterable', 'null', 'true', 'false', 'parent',
    'self', 'this', 'resource', 'numeric',
];

/** Library-specific namespaces with a hand-written facade (skip generation). */
const SKIP_NAMESPACES = ['updates'];

/** Peer-like input types → PHP expression resolving a value to a blob. */
const RESOLVER_EXPR = [
    'InputPeer' => '$this->peerBlob',
    'InputChannel' => '$this->channelBlob',
    'InputUser' => '$this->peers()->resolveUser',
    'InputDialogPeer' => '$this->peers()->resolveDialogPeer',
];

function varName(string $name): string
{
    return in_array($name, RESERVED, true) ? $name . '_' : $name;
}

function norm(string $t): string
{
    return str_starts_with($t, 'vector<') ? 'Vector<' . substr($t, 7) : $t;
}

function parseField(string $name, string $type): array
{
    if ($type === '#') {
        return ['kind' => 'F', 'var' => $name];
    }
    if (preg_match('/^flags\.(\d+)\?true$/', $type, $m)) {
        return ['kind' => 'B', 'name' => $name, 'bit' => (int)$m[1], 'var' => 'flags'];
    }
    if (preg_match('/^flags2\.(\d+)\?true$/', $type, $m)) {
        return ['kind' => 'B', 'name' => $name, 'bit' => (int)$m[1], 'var' => 'flags2'];
    }
    if (preg_match('/^flags\.(\d+)\?(.+)$/', $type, $m)) {
        return ['kind' => 'O', 'name' => $name, 'bit' => (int)$m[1], 'type' => norm($m[2]), 'var' => 'flags'];
    }
    if (preg_match('/^flags2\.(\d+)\?(.+)$/', $type, $m)) {
        return ['kind' => 'O', 'name' => $name, 'bit' => (int)$m[1], 'type' => norm($m[2]), 'var' => 'flags2'];
    }
    return ['kind' => 'R', 'name' => $name, 'type' => norm($type)];
}

function phpType(string $type): string
{
    if ($type === 'int' || $type === 'long') {
        return 'int';
    }
    if ($type === 'double') {
        return 'float';
    }
    if ($type === 'Bool') {
        return 'bool';
    }
    if ($type === 'string' || $type === 'bytes' || $type === 'int128' || $type === 'int256') {
        return 'string';
    }
    if (str_starts_with($type, 'Vector<')) {
        return 'array';
    }
    return 'string';
}

function facadeType(string $type): string
{
    if (isset(RESOLVER_EXPR[$type])) {
        return 'mixed';
    }
    return phpType($type);
}

function serializer(string $type, string $var): string
{
    if ($type === 'int') {
        return '$b->int((int)' . $var . ');';
    }
    if ($type === 'long') {
        return '$b->long((int)' . $var . ');';
    }
    if ($type === 'double') {
        return '$b->double((float)' . $var . ');';
    }
    if ($type === 'string' || $type === 'bytes') {
        return '$b->string((string)' . $var . ');';
    }
    if ($type === 'int128' || $type === 'int256') {
        return '$b->rawBlob(' . $var . ');';
    }
    if ($type === 'Bool') {
        return '$b->bool((bool)' . $var . ');';
    }
    if ($type === 'Vector<int>') {
        return '$b->vectorInt(' . $var . ');';
    }
    if ($type === 'Vector<long>') {
        return '$b->vectorLong(' . $var . ');';
    }
    if ($type === 'Vector<double>') {
        return '$b->vectorDouble(' . $var . ');';
    }
    if ($type === 'Vector<string>') {
        return '$b->vectorString(' . $var . ');';
    }
    if ($type === 'Vector<bytes>') {
        return '$b->vectorBytes(' . $var . ');';
    }
    if (str_starts_with($type, 'Vector<')) {
        return '$b->vector(' . $var . ');';
    }
    return '$b->rawBlob(' . $var . ');';
}

function inputStmt(string $type, string $var): string
{
    if (isset(RESOLVER_EXPR[$type])) {
        return '$b->rawBlob(' . RESOLVER_EXPR[$type] . '(' . $var . '));';
    }
    if (preg_match('/^Vector<(.+)>$/', $type, $m) && isset(RESOLVER_EXPR[$m[1]])) {
        return '$b->vector(array_map(fn($x) => ' . RESOLVER_EXPR[$m[1]] . '($x), ' . $var . '));';
    }
    return serializer($type, $var);
}

function generateFacadeClass(string $namespace, array $methods, string $outDir): void
{
    $class = ucfirst($namespace);
    $lines = [];
    $lines[] = '<?php';
    $lines[] = '';
    $lines[] = 'declare(strict_types=1);';
    $lines[] = '';
    $lines[] = 'namespace Bpt\Facade;';
    $lines[] = '';
    $lines[] = 'use Bpt\TL\Builder;';
    $lines[] = 'use Bpt\TL\Deserializer;';
    $lines[] = '';
    $lines[] = '/**';
    $lines[] = " * Auto-generated facade for every {$namespace}.* method (see tools/gen_methods.php).";
    $lines[] = ' *';
    $lines[] = " * Peer-ish inputs accept friendly forms ('me', '\@username', ids, arrays);";
    $lines[] = ' * complex objects are passed as pre-packed blobs (Bpt\TL\Peer / Bpt\TL\Builder).';
    $lines[] = ' * Every method returns a parsed array/scalar (see Bpt\TL\Deserializer).';
    $lines[] = ' */';
    $lines[] = "class {$class} extends Group";
    $lines[] = '{';

    foreach ($methods as $name => $m) {
        $ctor = $m['ctor'];
        $fields = $m['fields'];
        $result = $m['result'];

        $methodName = varName($name);

        $params = [];
        foreach ($fields as $f) {
            if ($f['kind'] === 'F') {
                continue;
            }
            if ($f['kind'] === 'B') {
                $params[] = 'bool $' . varName($f['name']) . ' = false';
            } elseif ($f['kind'] === 'O') {
                $t = facadeType($f['type']);
                $params[] = ($t === 'mixed' ? 'mixed' : '?' . $t) . ' $' . varName($f['name']) . ' = null';
            } else {
                $params[] = facadeType($f['type']) . ' $' . varName($f['name']);
            }
        }
        usort($params, static function (string $a, string $b): int {
            return (str_contains($a, '=') ? 1 : 0) <=> (str_contains($b, '=') ? 1 : 0);
        });
        $sig = count($params) > 0 ? implode(', ', $params) : '';

        $lines[] = '';
        $lines[] = '    /**';
        $lines[] = "     * {$namespace}.{$name}#{$ctor}" . ($result !== '' ? " = {$result}." : '');
        $lines[] = '     */';
        $lines[] = "    public function {$methodName}({$sig}): mixed";
        $lines[] = '    {';

        $hasFlags = false;
        foreach ($fields as $f) {
            if ($f['kind'] === 'F') {
                $hasFlags = true;
            }
        }
        if ($hasFlags) {
            $lines[] = '        $flags = 0;';
        }
        foreach ($fields as $f) {
            if ($f['kind'] === 'B') {
                $lines[] = '        if ($' . varName($f['name']) . ') { $flags |= (1 << ' . $f['bit'] . '); }';
            } elseif ($f['kind'] === 'O') {
                $lines[] = '        if ($' . varName($f['name']) . ' !== null) { $flags |= (1 << ' . $f['bit'] . '); }';
            }
        }

        $lines[] = '        $b = Builder::ctor(0x' . strtoupper($ctor) . ');';
        foreach ($fields as $f) {
            if ($f['kind'] === 'F') {
                $lines[] = '        $b->int($flags);';
            } elseif ($f['kind'] === 'B') {
                // boolean flags carry no value on the wire
            } elseif ($f['kind'] === 'O') {
                $v = '$' . varName($f['name']);
                $lines[] = '        if (' . $v . ' !== null) { ' . inputStmt($f['type'], $v) . ' }';
            } else {
                $v = '$' . varName($f['name']);
                $lines[] = '        ' . inputStmt($f['type'], $v);
            }
        }
        $lines[] = '        return Deserializer::parse($this->client->rpc($b->build()), ' . var_export($result, true) . ');';
        $lines[] = '    }';
    }

    $lines[] = '}';
    $lines[] = '';

    file_put_contents($outDir . '/' . $class . '.php', implode("\n", $lines));
}

function generateTypes(array $map, string $outDir): void
{
    $lines = [];
    $lines[] = '<?php';
    $lines[] = '';
    $lines[] = 'declare(strict_types=1);';
    $lines[] = '';
    $lines[] = 'namespace Bpt\TL;';
    $lines[] = '';
    $lines[] = '/**';
    $lines[] = ' * Auto-generated constructor map for the TL deserializer.';
    $lines[] = ' *';
    $lines[] = ' * Each entry: ctorId => [typeName, constructorName, fields]. A field is';
    $lines[] = " * ['F', 'flags'|'flags2'], ['B', name, bit, flagsVar],";
    $lines[] = " * ['O', name, type, bit, flagsVar] or ['R', name, type].";
    $lines[] = ' *';
    $lines[] = ' * @see Deserializer';
    $lines[] = ' */';
    $lines[] = 'final class Types';
    $lines[] = '{';
    $lines[] = '    /**';
    $lines[] = '     * @return array<int,array{0:string,1:string,2:array}>';
    $lines[] = '     */';
    $lines[] = '    public static function map(): array';
    $lines[] = '    {';
    $lines[] = '        static $m = null;';
    $lines[] = '        if ($m !== null) {';
    $lines[] = '            return $m;';
    $lines[] = '        }';
    $lines[] = '        return $m = [';

    foreach ($map as $ctor => $def) {
        [$type, $name, $fields] = $def;
        $encoded = [];
        foreach ($fields as $f) {
            if ($f['kind'] === 'F') {
                $encoded[] = "['F', " . var_export($f['var'], true) . ']';
            } elseif ($f['kind'] === 'B') {
                $encoded[] = "['B', " . var_export($f['name'], true) . ', ' . $f['bit'] . ', ' . var_export($f['var'], true) . ']';
            } elseif ($f['kind'] === 'O') {
                $encoded[] = "['O', " . var_export($f['name'], true) . ', ' . var_export($f['type'], true) . ', ' . $f['bit'] . ', ' . var_export($f['var'], true) . ']';
            } else {
                $encoded[] = "['R', " . var_export($f['name'], true) . ', ' . var_export($f['type'], true) . ']';
            }
        }
        $lines[] = '            ' . $ctor . ' => [' . var_export($type, true) . ', ' . var_export($name, true) . ', [' . implode(', ', $encoded) . ']],';
    }

    $lines[] = '        ];';
    $lines[] = '    }';
    $lines[] = '}';
    $lines[] = '';
    file_put_contents($outDir . '/Types.php', implode("\n", $lines));
}

function main(string $schemaFile, string $outDir): void
{
    $raw = file_get_contents($schemaFile);
    if ($raw === false) {
        fwrite(STDERR, "cannot read {$schemaFile}\n");
        exit(1);
    }

    $functions = [];
    $typeCtors = [];
    $section = 'preamble';
    foreach (explode("\n", $raw) as $line) {
        $line = trim($line);
        if ($line === '---functions---') {
            $section = 'functions';
            continue;
        }
        if ($line === '---types---') {
            $section = 'types';
            continue;
        }
        if ($line === '' || str_contains($line, '{')) {
            continue;
        }
        if (!preg_match('/^([A-Za-z0-9.]+)#([0-9a-f]+)\s+(.*?)\s*=\s*(.*);$/', $line, $m)) {
            continue;
        }
        $name = $m[1];
        $ctorHex = $m[2];
        $ctorInt = hexdec($m[2]);
        $argsStr = $m[3];
        $result = norm($m[4]);

        $fields = [];
        foreach (preg_split('/\s+/', $argsStr) as $token) {
            if ($token === '') {
                continue;
            }
            $pos = strpos($token, ':');
            if ($pos === false) {
                continue;
            }
            $fields[] = parseField(substr($token, 0, $pos), substr($token, $pos + 1));
        }

        if ($section === 'functions') {
            $functions[$name] = ['ctor' => $ctorHex, 'fields' => $fields, 'result' => $result];
        } else {
            $typeCtors[$ctorInt] = [$result, $name, $fields];
        }
    }

    $byNs = [];
    foreach ($functions as $fullName => $def) {
        $dot = strpos($fullName, '.');
        if ($dot === false) {
            continue;
        }
        $ns = substr($fullName, 0, $dot);
        $method = substr($fullName, $dot + 1);
        $byNs[$ns][$method] = $def;
    }

    ksort($byNs);
    ksort($typeCtors);

    if (!is_dir($outDir) && !mkdir($outDir, 0755, true)) {
        fwrite(STDERR, "cannot create {$outDir}\n");
        exit(1);
    }

    generateTypes($typeCtors, dirname($outDir) . '/TL');

    $generated = 0;
    foreach ($byNs as $ns => $methods) {
        if (in_array($ns, SKIP_NAMESPACES, true)) {
            continue;
        }
        ksort($methods);
        generateFacadeClass($ns, $methods, $outDir);
        $generated++;
    }

    fwrite(STDOUT, 'Generated ' . $generated . ' facade classes + ' . count($typeCtors) . " type ctors\n");
    fwrite(STDOUT, 'Namespaces: ' . implode(', ', array_keys($byNs)) . "\n");
}

if ($argc !== 2) {
    fwrite(STDERR, "Usage: php tools/gen_methods.php <telegram_api.tl>\n");
    exit(1);
}

main($argv[1], dirname(__DIR__) . '/src/Facade');
