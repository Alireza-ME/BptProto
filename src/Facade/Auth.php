<?php

declare(strict_types=1);

namespace Bpt\Facade;

use Bpt\TL\Builder;
use Bpt\TL\Deserializer;

/**
 * Auto-generated facade for every auth.* method (see tools/gen_methods.php).
 *
 * Peer-ish inputs accept friendly forms ('me', '\@username', ids, arrays);
 * complex objects are passed as pre-packed blobs (Bpt\TL\Peer / Bpt\TL\Builder).
 * Every method returns a parsed array/scalar (see Bpt\TL\Deserializer).
 */
class Auth extends Group
{

    /**
     * auth.acceptLoginToken#e894ad4d = Authorization.
     */
    public function acceptLoginToken(string $token): mixed
    {
        $b = Builder::ctor(0xE894AD4D);
        $b->string((string)$token);
        return Deserializer::parse($this->client->rpc($b->build()), 'Authorization');
    }

    /**
     * auth.bindTempAuthKey#cdd42a05 = Bool.
     */
    public function bindTempAuthKey(int $perm_auth_key_id, int $nonce, int $expires_at, string $encrypted_message): mixed
    {
        $b = Builder::ctor(0xCDD42A05);
        $b->long((int)$perm_auth_key_id);
        $b->long((int)$nonce);
        $b->int((int)$expires_at);
        $b->string((string)$encrypted_message);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * auth.cancelCode#1f040578 = Bool.
     */
    public function cancelCode(string $phone_number, string $phone_code_hash): mixed
    {
        $b = Builder::ctor(0x1F040578);
        $b->string((string)$phone_number);
        $b->string((string)$phone_code_hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * auth.checkPaidAuth#56e59f9c = auth.SentCode.
     */
    public function checkPaidAuth(string $phone_number, string $phone_code_hash, int $form_id): mixed
    {
        $b = Builder::ctor(0x56E59F9C);
        $b->string((string)$phone_number);
        $b->string((string)$phone_code_hash);
        $b->long((int)$form_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'auth.SentCode');
    }

    /**
     * auth.checkPassword#d18b4d16 = auth.Authorization.
     */
    public function checkPassword(string $password): mixed
    {
        $b = Builder::ctor(0xD18B4D16);
        $b->rawBlob($password);
        return Deserializer::parse($this->client->rpc($b->build()), 'auth.Authorization');
    }

    /**
     * auth.checkRecoveryPassword#d36bf79 = Bool.
     */
    public function checkRecoveryPassword(string $code): mixed
    {
        $b = Builder::ctor(0xD36BF79);
        $b->string((string)$code);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * auth.dropTempAuthKeys#8e48a188 = Bool.
     */
    public function dropTempAuthKeys(array $except_auth_keys): mixed
    {
        $b = Builder::ctor(0x8E48A188);
        $b->vectorLong($except_auth_keys);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * auth.exportAuthorization#e5bfffcd = auth.ExportedAuthorization.
     */
    public function exportAuthorization(int $dc_id): mixed
    {
        $b = Builder::ctor(0xE5BFFFCD);
        $b->int((int)$dc_id);
        return Deserializer::parse($this->client->rpc($b->build()), 'auth.ExportedAuthorization');
    }

    /**
     * auth.exportLoginToken#b7e085fe = auth.LoginToken.
     */
    public function exportLoginToken(int $api_id, string $api_hash, array $except_ids): mixed
    {
        $b = Builder::ctor(0xB7E085FE);
        $b->int((int)$api_id);
        $b->string((string)$api_hash);
        $b->vectorLong($except_ids);
        return Deserializer::parse($this->client->rpc($b->build()), 'auth.LoginToken');
    }

    /**
     * auth.finishFirebasePnvLogin#2c85094c = auth.Authorization.
     */
    public function finishFirebasePnvLogin(string $google_token): mixed
    {
        $b = Builder::ctor(0x2C85094C);
        $b->string((string)$google_token);
        return Deserializer::parse($this->client->rpc($b->build()), 'auth.Authorization');
    }

    /**
     * auth.finishPasskeyLogin#9857ad07 = auth.Authorization.
     */
    public function finishPasskeyLogin(string $credential, ?int $from_dc_id = null, ?int $from_auth_key_id = null): mixed
    {
        $flags = 0;
        if ($from_dc_id !== null) { $flags |= (1 << 0); }
        if ($from_auth_key_id !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x9857AD07);
        $b->int($flags);
        $b->rawBlob($credential);
        if ($from_dc_id !== null) { $b->int((int)$from_dc_id); }
        if ($from_auth_key_id !== null) { $b->long((int)$from_auth_key_id); }
        return Deserializer::parse($this->client->rpc($b->build()), 'auth.Authorization');
    }

    /**
     * auth.firebasePnvSignUp#783f6b56 = auth.Authorization.
     */
    public function firebasePnvSignUp(string $first_name, string $last_name, bool $no_joined_notifications = false): mixed
    {
        $flags = 0;
        if ($no_joined_notifications) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x783F6B56);
        $b->int($flags);
        $b->string((string)$first_name);
        $b->string((string)$last_name);
        return Deserializer::parse($this->client->rpc($b->build()), 'auth.Authorization');
    }

    /**
     * auth.importAuthorization#a57a7dad = auth.Authorization.
     */
    public function importAuthorization(int $id, string $bytes): mixed
    {
        $b = Builder::ctor(0xA57A7DAD);
        $b->long((int)$id);
        $b->string((string)$bytes);
        return Deserializer::parse($this->client->rpc($b->build()), 'auth.Authorization');
    }

    /**
     * auth.importBotAuthorization#67a3ff2c = auth.Authorization.
     */
    public function importBotAuthorization(int $flags, int $api_id, string $api_hash, string $bot_auth_token): mixed
    {
        $b = Builder::ctor(0x67A3FF2C);
        $b->int((int)$flags);
        $b->int((int)$api_id);
        $b->string((string)$api_hash);
        $b->string((string)$bot_auth_token);
        return Deserializer::parse($this->client->rpc($b->build()), 'auth.Authorization');
    }

    /**
     * auth.importLoginToken#95ac5ce4 = auth.LoginToken.
     */
    public function importLoginToken(string $token): mixed
    {
        $b = Builder::ctor(0x95AC5CE4);
        $b->string((string)$token);
        return Deserializer::parse($this->client->rpc($b->build()), 'auth.LoginToken');
    }

    /**
     * auth.importWebTokenAuthorization#2db873a9 = auth.Authorization.
     */
    public function importWebTokenAuthorization(int $api_id, string $api_hash, string $web_auth_token): mixed
    {
        $b = Builder::ctor(0x2DB873A9);
        $b->int((int)$api_id);
        $b->string((string)$api_hash);
        $b->string((string)$web_auth_token);
        return Deserializer::parse($this->client->rpc($b->build()), 'auth.Authorization');
    }

    /**
     * auth.initFirebasePnvLogin#777df37a = auth.FirebasePnvIntent.
     */
    public function initFirebasePnvLogin(int $api_id, string $api_hash): mixed
    {
        $b = Builder::ctor(0x777DF37A);
        $b->int((int)$api_id);
        $b->string((string)$api_hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'auth.FirebasePnvIntent');
    }

    /**
     * auth.initPasskeyLogin#518ad0b7 = auth.PasskeyLoginOptions.
     */
    public function initPasskeyLogin(int $api_id, string $api_hash): mixed
    {
        $b = Builder::ctor(0x518AD0B7);
        $b->int((int)$api_id);
        $b->string((string)$api_hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'auth.PasskeyLoginOptions');
    }

    /**
     * auth.logOut#3e72ba19 = auth.LoggedOut.
     */
    public function logOut(): mixed
    {
        $b = Builder::ctor(0x3E72BA19);
        return Deserializer::parse($this->client->rpc($b->build()), 'auth.LoggedOut');
    }

    /**
     * auth.recoverPassword#37096c70 = auth.Authorization.
     */
    public function recoverPassword(string $code, ?string $new_settings = null): mixed
    {
        $flags = 0;
        if ($new_settings !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0x37096C70);
        $b->int($flags);
        $b->string((string)$code);
        if ($new_settings !== null) { $b->rawBlob($new_settings); }
        return Deserializer::parse($this->client->rpc($b->build()), 'auth.Authorization');
    }

    /**
     * auth.reportMissingCode#cb9deff6 = Bool.
     */
    public function reportMissingCode(string $phone_number, string $phone_code_hash, string $mnc): mixed
    {
        $b = Builder::ctor(0xCB9DEFF6);
        $b->string((string)$phone_number);
        $b->string((string)$phone_code_hash);
        $b->string((string)$mnc);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * auth.requestFirebaseSms#8e39261e = Bool.
     */
    public function requestFirebaseSms(string $phone_number, string $phone_code_hash, ?string $safety_net_token = null, ?string $play_integrity_token = null, ?string $ios_push_secret = null): mixed
    {
        $flags = 0;
        if ($safety_net_token !== null) { $flags |= (1 << 0); }
        if ($play_integrity_token !== null) { $flags |= (1 << 2); }
        if ($ios_push_secret !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x8E39261E);
        $b->int($flags);
        $b->string((string)$phone_number);
        $b->string((string)$phone_code_hash);
        if ($safety_net_token !== null) { $b->string((string)$safety_net_token); }
        if ($play_integrity_token !== null) { $b->string((string)$play_integrity_token); }
        if ($ios_push_secret !== null) { $b->string((string)$ios_push_secret); }
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * auth.requestPasswordRecovery#d897bc66 = auth.PasswordRecovery.
     */
    public function requestPasswordRecovery(): mixed
    {
        $b = Builder::ctor(0xD897BC66);
        return Deserializer::parse($this->client->rpc($b->build()), 'auth.PasswordRecovery');
    }

    /**
     * auth.resendCode#cae47523 = auth.SentCode.
     */
    public function resendCode(string $phone_number, string $phone_code_hash, ?string $reason = null): mixed
    {
        $flags = 0;
        if ($reason !== null) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xCAE47523);
        $b->int($flags);
        $b->string((string)$phone_number);
        $b->string((string)$phone_code_hash);
        if ($reason !== null) { $b->string((string)$reason); }
        return Deserializer::parse($this->client->rpc($b->build()), 'auth.SentCode');
    }

    /**
     * auth.resetAuthorizations#9fab0d1a = Bool.
     */
    public function resetAuthorizations(): mixed
    {
        $b = Builder::ctor(0x9FAB0D1A);
        return Deserializer::parse($this->client->rpc($b->build()), 'Bool');
    }

    /**
     * auth.resetLoginEmail#7e960193 = auth.SentCode.
     */
    public function resetLoginEmail(string $phone_number, string $phone_code_hash): mixed
    {
        $b = Builder::ctor(0x7E960193);
        $b->string((string)$phone_number);
        $b->string((string)$phone_code_hash);
        return Deserializer::parse($this->client->rpc($b->build()), 'auth.SentCode');
    }

    /**
     * auth.sendCode#a677244f = auth.SentCode.
     */
    public function sendCode(string $phone_number, int $api_id, string $api_hash, string $settings): mixed
    {
        $b = Builder::ctor(0xA677244F);
        $b->string((string)$phone_number);
        $b->int((int)$api_id);
        $b->string((string)$api_hash);
        $b->rawBlob($settings);
        return Deserializer::parse($this->client->rpc($b->build()), 'auth.SentCode');
    }

    /**
     * auth.signIn#8d52a951 = auth.Authorization.
     */
    public function signIn(string $phone_number, string $phone_code_hash, ?string $phone_code = null, ?string $email_verification = null): mixed
    {
        $flags = 0;
        if ($phone_code !== null) { $flags |= (1 << 0); }
        if ($email_verification !== null) { $flags |= (1 << 1); }
        $b = Builder::ctor(0x8D52A951);
        $b->int($flags);
        $b->string((string)$phone_number);
        $b->string((string)$phone_code_hash);
        if ($phone_code !== null) { $b->string((string)$phone_code); }
        if ($email_verification !== null) { $b->rawBlob($email_verification); }
        return Deserializer::parse($this->client->rpc($b->build()), 'auth.Authorization');
    }

    /**
     * auth.signUp#aac7b717 = auth.Authorization.
     */
    public function signUp(string $phone_number, string $phone_code_hash, string $first_name, string $last_name, bool $no_joined_notifications = false): mixed
    {
        $flags = 0;
        if ($no_joined_notifications) { $flags |= (1 << 0); }
        $b = Builder::ctor(0xAAC7B717);
        $b->int($flags);
        $b->string((string)$phone_number);
        $b->string((string)$phone_code_hash);
        $b->string((string)$first_name);
        $b->string((string)$last_name);
        return Deserializer::parse($this->client->rpc($b->build()), 'auth.Authorization');
    }
}
