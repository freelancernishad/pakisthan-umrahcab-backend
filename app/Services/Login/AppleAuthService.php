<?php

namespace App\Services\Login;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class AppleAuthService
{
    public function login(Request $request)
    {
        try {
            $identityToken = $request->identity_token;
            $name = $request->name;

            // Decode the Apple Identity Token
            $appleUserInfo = $this->decodeAndVerifyAppleIdentityToken($identityToken);

            if (!$appleUserInfo || !isset($appleUserInfo['email'])) {
                return response()->json([
                    'error' => 'Invalid or expired Apple identity token.',
                ], 400);
            }

            // Check if the user already exists
            $user = User::where('email', $appleUserInfo['email'])->first();

            if (!$user) {
                $user = User::create([
                    'name' => $name ?? explode('@', $appleUserInfo['email'])[0],
                    'email' => $appleUserInfo['email'],
                    'password' => Hash::make(Str::random(16)),
                    'email_verified_at' => now(),
                    'profile_completion' => 10,
                ]);
            } else {
                $user->update(['email_verified_at' => now()]);
            }

            // Authenticate the user
            Auth::login($user);

            // Generate JWT
            try {
                $token = JWTAuth::fromUser($user, ['guard' => 'user']);
            } catch (JWTException $e) {
                return response()->json([
                    'error' => 'Could not generate JWT token for Apple login.',
                ], 500);
            }

            $secure = config('session.secure') ?? request()->secure();
            $domain = config('session.domain');
            $sameSite = config('session.same_site', 'lax');

            $cookie = cookie('token', $token, config('jwt.ttl', 43200), '/', $domain, $secure, true, false, $sameSite);
            $userCookie = cookie('user_token', $token, config('jwt.ttl', 43200), '/', $domain, $secure, true, false, $sameSite);

            return response()->json([
                'success' => true,
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
                'profile_completion' => $user->profile_completion,
                'message' => 'Login successful via Apple',
            ], 200)
            ->withCookie($cookie)
            ->withCookie($userCookie);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Apple Login failed due to an internal error.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Decode and Cryptographically Verify Apple Identity Token
     */
    private function decodeAndVerifyAppleIdentityToken($identityToken)
    {
        try {
            $tokenParts = explode(".", $identityToken);
            if (count($tokenParts) !== 3) {
                return null;
            }

            $header = json_decode(base64_decode(strtr($tokenParts[0], '-_', '+/')), true);
            $payload = json_decode(base64_decode(strtr($tokenParts[1], '-_', '+/')), true);
            $signature = base64_decode(strtr($tokenParts[2], '-_', '+/'));

            if (!$header || !$payload || !$signature) {
                return null;
            }

            // 1. Verify Issuer
            if (!isset($payload['iss']) || $payload['iss'] !== 'https://appleid.apple.com') {
                return null;
            }

            // 2. Verify Expiration Time
            if (!isset($payload['exp']) || $payload['exp'] < time()) {
                return null;
            }

            // 3. Verify Audience (Client ID / Services ID) if configured
            $clientId = config('services.apple.client_id');
            if ($clientId && (!isset($payload['aud']) || $payload['aud'] !== $clientId)) {
                return null;
            }

            // 4. Fetch Apple's Public JWKs
            $response = Http::timeout(5)->withoutVerifying()->get('https://appleid.apple.com/auth/keys');
            if ($response->failed()) {
                return null;
            }

            $keys = $response->json()['keys'] ?? [];
            $jwk = null;
            foreach ($keys as $key) {
                if (isset($key['kid']) && $key['kid'] === ($header['kid'] ?? null)) {
                    $jwk = $key;
                    break;
                }
            }

            if (!$jwk) {
                return null;
            }

            // 5. Convert JWK modulus (n) and exponent (e) to PEM format
            $publicKeyPem = $this->jwkToPem($jwk);

            // 6. Verify Signature
            $dataToVerify = $tokenParts[0] . '.' . $tokenParts[1];
            $verified = openssl_verify($dataToVerify, $signature, $publicKeyPem, OPENSSL_ALGO_SHA256);

            if ($verified !== 1) {
                return null;
            }

            return $payload;
        } catch (\Exception $e) {
            Log::error('Apple Token Verification failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Convert JWK (Modulus/Exponent) to PEM Public Key
     */
    private function jwkToPem($jwk)
    {
        $n = base64_decode(strtr($jwk['n'], '-_', '+/'));
        $e = base64_decode(strtr($jwk['e'], '-_', '+/'));

        // Modulus and exponent length-encoded sequences
        $components = [
            'modulus' => pack('Ca*a*', 0x02, $this->encodeLength(strlen($n)), $n),
            'exponent' => pack('Ca*a*', 0x02, $this->encodeLength(strlen($e)), $e),
        ];

        // Create sequence block
        $sequence = pack('Ca*a*', 0x30, $this->encodeLength(strlen($components['modulus'] . $components['exponent'])), $components['modulus'] . $components['exponent']);
        
        // Wrap in standard public key OID (RSA Encryption OID)
        $oid = pack('H*', '300d06092a864886f70d0101010500');
        $bitString = pack('Ca*a*', 0x03, $this->encodeLength(strlen($sequence) + 1), "\x00" . $sequence);
        $publicKey = pack('Ca*a*', 0x30, $this->encodeLength(strlen($oid . $bitString)), $oid . $bitString);

        return "-----BEGIN PUBLIC KEY-----\n" . chunk_split(base64_encode($publicKey), 64, "\n") . "-----END PUBLIC KEY-----";
    }

    /**
     * DER ASN.1 length helper
     */
    private function encodeLength($length)
    {
        if ($length <= 0x7F) {
            return pack('C', $length);
        }
        $temp = ltrim(pack('N', $length), "\x00");
        return pack('Ca*', 0x80 | strlen($temp), $temp);
    }
}
