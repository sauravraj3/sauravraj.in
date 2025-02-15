<?php
namespace RobRichards\XMLSecLibs;
use DOMElement;
use Exception;

class XMLSecurityKey
{
    const TRIPLEDES_CBC = 'http://www.w3.org/2001/04/xmlenc#tripledes-cbc';
    const AES128_CBC = 'http://www.w3.org/2001/04/xmlenc#aes128-cbc';
    const AES192_CBC = 'http://www.w3.org/2001/04/xmlenc#aes192-cbc';
    const AES256_CBC = 'http://www.w3.org/2001/04/xmlenc#aes256-cbc';
    const RSA_1_5 = 'http://www.w3.org/2001/04/xmlenc#rsa-1_5';
    const RSA_OAEP_MGF1P = 'http://www.w3.org/2001/04/xmlenc#rsa-oaep-mgf1p';
    const DSA_SHA1 = 'http://www.w3.org/2000/09/xmldsig#dsa-sha1';
    const RSA_SHA1 = 'http://www.w3.org/2000/09/xmldsig#rsa-sha1';
    const RSA_SHA256 = 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256';
    const RSA_SHA384 = 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha384';
    const RSA_SHA512 = 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha512';
    const HMAC_SHA1 = 'http://www.w3.org/2000/09/xmldsig#hmac-sha1';
    
    private $cryptParams = array();
    
    public $type = 0;
    
    public $key = null;
    
    public $passphrase = "";
    
    public $iv = null;
    
    public $name = null;
    
    public $keyChain = null;
    
    public $isEncrypted = false;
    
    public $encryptedCtx = null;
    
    public $guid = null;
    
    private $x509Certificate = null;
    
    private $X509Thumbprint = null;
    
    public function __construct($type, $params=null)
    {
        switch ($type) {
            case (self::TRIPLEDES_CBC):
                $this->cryptParams['library'] = 'openssl';
                $this->cryptParams['cipher'] = 'des-ede3-cbc';
                $this->cryptParams['type'] = 'symmetric';
                $this->cryptParams['method'] = 'http://www.w3.org/2001/04/xmlenc#tripledes-cbc';
                $this->cryptParams['keysize'] = 24;
                $this->cryptParams['blocksize'] = 8;
                break;
            case (self::AES128_CBC):
                $this->cryptParams['library'] = 'openssl';
                $this->cryptParams['cipher'] = 'aes-128-cbc';
                $this->cryptParams['type'] = 'symmetric';
                $this->cryptParams['method'] = 'http://www.w3.org/2001/04/xmlenc#aes128-cbc';
                $this->cryptParams['keysize'] = 16;
                $this->cryptParams['blocksize'] = 16;
                break;
            case (self::AES192_CBC):
                $this->cryptParams['library'] = 'openssl';
                $this->cryptParams['cipher'] = 'aes-192-cbc';
                $this->cryptParams['type'] = 'symmetric';
                $this->cryptParams['method'] = 'http://www.w3.org/2001/04/xmlenc#aes192-cbc';
                $this->cryptParams['keysize'] = 24;
                $this->cryptParams['blocksize'] = 16;
                break;
            case (self::AES256_CBC):
                $this->cryptParams['library'] = 'openssl';
                $this->cryptParams['cipher'] = 'aes-256-cbc';
                $this->cryptParams['type'] = 'symmetric';
                $this->cryptParams['method'] = 'http://www.w3.org/2001/04/xmlenc#aes256-cbc';
                $this->cryptParams['keysize'] = 32;
                $this->cryptParams['blocksize'] = 16;
                break;
            case (self::RSA_1_5):
                $this->cryptParams['library'] = 'openssl';
                $this->cryptParams['padding'] = OPENSSL_PKCS1_PADDING;
                $this->cryptParams['method'] = 'http://www.w3.org/2001/04/xmlenc#rsa-1_5';
                if (is_array($params) && ! empty($params['type'])) {
                    if ($params['type'] == 'public' || $params['type'] == 'private') {
                        $this->cryptParams['type'] = $params['type'];
                        break;
                    }
                }
                throw new Exception('Certificate "type" (private/public) must be passed via parameters');
            case (self::RSA_OAEP_MGF1P):
                $this->cryptParams['library'] = 'openssl';
                $this->cryptParams['padding'] = OPENSSL_PKCS1_OAEP_PADDING;
                $this->cryptParams['method'] = 'http://www.w3.org/2001/04/xmlenc#rsa-oaep-mgf1p';
                $this->cryptParams['hash'] = null;
                if (is_array($params) && ! empty($params['type'])) {
                    if ($params['type'] == 'public' || $params['type'] == 'private') {
                        $this->cryptParams['type'] = $params['type'];
                        break;
                    }
                }
                throw new Exception('Certificate "type" (private/public) must be passed via parameters');
            case (self::RSA_SHA1):
                $this->cryptParams['library'] = 'openssl';
                $this->cryptParams['method'] = 'http://www.w3.org/2000/09/xmldsig#rsa-sha1';
                $this->cryptParams['padding'] = OPENSSL_PKCS1_PADDING;
                if (is_array($params) && ! empty($params['type'])) {
                    if ($params['type'] == 'public' || $params['type'] == 'private') {
                        $this->cryptParams['type'] = $params['type'];
                        break;
                    }
                }
                throw new Exception('Certificate "type" (private/public) must be passed via parameters');
            case (self::RSA_SHA256):
                $this->cryptParams['library'] = 'openssl';
                $this->cryptParams['method'] = 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256';
                $this->cryptParams['padding'] = OPENSSL_PKCS1_PADDING;
                $this->cryptParams['digest'] = 'SHA256';
                if (is_array($params) && ! empty($params['type'])) {
                    if ($params['type'] == 'public' || $params['type'] == 'private') {
                        $this->cryptParams['type'] = $params['type'];
                        break;
                    }
                }
                throw new Exception('Certificate "type" (private/public) must be passed via parameters');
            case (self::RSA_SHA384):
                $this->cryptParams['library'] = 'openssl';
                $this->cryptParams['method'] = 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha384';
                $this->cryptParams['padding'] = OPENSSL_PKCS1_PADDING;
                $this->cryptParams['digest'] = 'SHA384';
                if (is_array($params) && ! empty($params['type'])) {
                    if ($params['type'] == 'public' || $params['type'] == 'private') {
                        $this->cryptParams['type'] = $params['type'];
                        break;
                    }
                }
                throw new Exception('Certificate "type" (private/public) must be passed via parameters');
            case (self::RSA_SHA512):
                $this->cryptParams['library'] = 'openssl';
                $this->cryptParams['method'] = 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha512';
                $this->cryptParams['padding'] = OPENSSL_PKCS1_PADDING;
                $this->cryptParams['digest'] = 'SHA512';
                if (is_array($params) && ! empty($params['type'])) {
                    if ($params['type'] == 'public' || $params['type'] == 'private') {
                        $this->cryptParams['type'] = $params['type'];
                        break;
                    }
                }
                throw new Exception('Certificate "type" (private/public) must be passed via parameters');
            case (self::HMAC_SHA1):
                $this->cryptParams['library'] = $type;
                $this->cryptParams['method'] = 'http://www.w3.org/2000/09/xmldsig#hmac-sha1';
                break;
            default:
                throw new Exception('Invalid Key Type');
        }
        $this->type = $type;
    }
    
    public function getSymmetricKeySize()
    {
        if (! isset($this->cryptParams['keysize'])) {
            return null;
        }
        return $this->cryptParams['keysize'];
    }
    
    public function generateSessionKey()
    {
        if (!isset($this->cryptParams['keysize'])) {
            throw new Exception('Unknown key size for type "' . $this->type . '".');
        }
        $keysize = $this->cryptParams['keysize'];
        
        $key = openssl_random_pseudo_bytes($keysize);
        
        if ($this->type === self::TRIPLEDES_CBC) {
            
            for ($i = 0; $i < strlen($key); $i++) {
                $byte = ord($key[$i]) & 0xfe;
                $parity = 1;
                for ($j = 1; $j < 8; $j++) {
                    $parity ^= ($byte >> $j) & 1;
                }
                $byte |= $parity;
                $key[$i] = chr($byte);
            }
        }
        
        $this->key = $key;
        return $key;
    }
    
    public static function getRawThumbprint($cert)
    {
        $arCert = explode("\n", $cert);
        $data = '';
        $inData = false;
        foreach ($arCert AS $curData) {
            if (! $inData) {
                if (strncmp($curData, '-----BEGIN CERTIFICATE', 22) == 0) {
                    $inData = true;
                }
            } else {
                if (strncmp($curData, '-----END CERTIFICATE', 20) == 0) {
                    break;
                }
                $data .= trim($curData);
            }
        }
        if (! empty($data)) {
            return strtolower(sha1(base64_decode($data)));
        }
        return null;
    }
    
    public function loadKey($key, $isFile=false, $isCert = false)
    {
        if ($isFile) {
            $this->key = file_get_contents($key);
        } else {
            $this->key = $key;
        }
        if ($isCert) {
            $this->key = openssl_x509_read($this->key);
            openssl_x509_export($this->key, $str_cert);
            $this->x509Certificate = $str_cert;
            $this->key = $str_cert;
        } else {
            $this->x509Certificate = null;
        }
        if ($this->cryptParams['library'] == 'openssl') {
            switch ($this->cryptParams['type']) {
                case 'public':
	                if ($isCert) {
	                    
	                    $this->X509Thumbprint = self::getRawThumbprint($this->key);
	                }
	                $this->key = openssl_get_publickey($this->key);
	                if (! $this->key) {
	                    throw new Exception('Unable to extract public key');
	                }
	                break;
	            case 'private':
                    $this->key = openssl_get_privatekey($this->key, $this->passphrase);
                    break;
                case'symmetric':
                    if (strlen($this->key) < $this->cryptParams['keysize']) {
                        throw new Exception('Key must contain at least 25 characters for this cipher');
                    }
                    break;
                default:
                    throw new Exception('Unknown type');
            }
        }
    }
    
    private function padISO10126($data, $blockSize)
    {
        if ($blockSize > 256) {
            throw new Exception('Block size higher than 256 not allowed');
        }
        $padChr = $blockSize - (strlen($data) % $blockSize);
        $pattern = chr($padChr);
        return $data . str_repeat($pattern, $padChr);
    }
    
    private function unpadISO10126($data)
    {
        $padChr = substr($data, -1);
        $padLen = ord($padChr);
        return substr($data, 0, -$padLen);
    }
    
    private function encryptSymmetric($data)
    {
        $this->iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->cryptParams['cipher']));
        $data = $this->padISO10126($data, $this->cryptParams['blocksize']);
        $encrypted = openssl_encrypt($data, $this->cryptParams['cipher'], $this->key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $this->iv);
        if (false === $encrypted) {
            throw new Exception('Failure encrypting Data (openssl symmetric) - ' . openssl_error_string());
        }
        return $this->iv . $encrypted;
    }
    
    private function decryptSymmetric($data)
    {
        $iv_length = openssl_cipher_iv_length($this->cryptParams['cipher']);
        $this->iv = substr($data, 0, $iv_length);
        $data = substr($data, $iv_length);
        $decrypted = openssl_decrypt($data, $this->cryptParams['cipher'], $this->key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $this->iv);
        if (false === $decrypted) {
            throw new Exception('Failure decrypting Data (openssl symmetric) - ' . openssl_error_string());
        }
        return $this->unpadISO10126($decrypted);
    }
    
    private function encryptPublic($data)
    {
        if (! openssl_public_encrypt($data, $encrypted, $this->key, $this->cryptParams['padding'])) {
            throw new Exception('Failure encrypting Data (openssl public) - ' . openssl_error_string());
        }
        return $encrypted;
    }
    
    private function decryptPublic($data)
    {
        if (! openssl_public_decrypt($data, $decrypted, $this->key, $this->cryptParams['padding'])) {
            throw new Exception('Failure decrypting Data (openssl public) - ' . openssl_error_string());
        }
        return $decrypted;
    }
    
    private function encryptPrivate($data)
    {
        if (! openssl_private_encrypt($data, $encrypted, $this->key, $this->cryptParams['padding'])) {
            throw new Exception('Failure encrypting Data (openssl private) - ' . openssl_error_string());
        }
        return $encrypted;
    }
    
    private function decryptPrivate($data)
    {
        if (! openssl_private_decrypt($data, $decrypted, $this->key, $this->cryptParams['padding'])) {
            throw new Exception('Failure decrypting Data (openssl private) - ' . openssl_error_string());
        }
        return $decrypted;
    }
    
    private function signOpenSSL($data)
    {
        $algo = OPENSSL_ALGO_SHA1;
        if (! empty($this->cryptParams['digest'])) {
            $algo = $this->cryptParams['digest'];
        }
        if (! openssl_sign($data, $signature, $this->key, $algo)) {
            throw new Exception('Failure Signing Data: ' . openssl_error_string() . ' - ' . $algo);
        }
        return $signature;
    }
    
    private function verifyOpenSSL($data, $signature)
    {
        $algo = OPENSSL_ALGO_SHA1;
        if (! empty($this->cryptParams['digest'])) {
            $algo = $this->cryptParams['digest'];
        }
        return openssl_verify($data, $signature, $this->key, $algo);
    }
    
    public function encryptData($data)
    {
        if ($this->cryptParams['library'] === 'openssl') {
            switch ($this->cryptParams['type']) {
                case 'symmetric':
                    return $this->encryptSymmetric($data);
                case 'public':
                    return $this->encryptPublic($data);
                case 'private':
                    return $this->encryptPrivate($data);
            }
        }
    }
    
    public function decryptData($data)
    {
        if ($this->cryptParams['library'] === 'openssl') {
            switch ($this->cryptParams['type']) {
                case 'symmetric':
                    return $this->decryptSymmetric($data);
                case 'public':
                    return $this->decryptPublic($data);
                case 'private':
                    return $this->decryptPrivate($data);
            }
        }
    }
    
    public function signData($data)
    {
        switch ($this->cryptParams['library']) {
            case 'openssl':
                return $this->signOpenSSL($data);
            case (self::HMAC_SHA1):
                return hash_hmac("sha1", $data, $this->key, true);
        }
    }
    
    public function verifySignature($data, $signature)
    {
        switch ($this->cryptParams['library']) {
            case 'openssl':
                return $this->verifyOpenSSL($data, $signature);
            case (self::HMAC_SHA1):
                $expectedSignature = hash_hmac("sha1", $data, $this->key, true);
                return strcmp($signature, $expectedSignature) == 0;
        }
    }
    
    public function getAlgorith()
    {
        return $this->getAlgorithm();
    }
    
    public function getAlgorithm()
    {
        return $this->cryptParams['method'];
    }
    
    public static function makeAsnSegment($type, $string)
    {
        switch ($type) {
            case 0x02:
                if (ord($string) > 0x7f)
                    $string = chr(0).$string;
                break;
            case 0x03:
                $string = chr(0).$string;
                break;
        }
        $length = strlen($string);
        if ($length < 128) {
            $output = sprintf("%c%c%s", $type, $length, $string);
        } else if ($length < 0x0100) {
            $output = sprintf("%c%c%c%s", $type, 0x81, $length, $string);
        } else if ($length < 0x010000) {
            $output = sprintf("%c%c%c%c%s", $type, 0x82, $length / 0x0100, $length % 0x0100, $string);
        } else {
            $output = null;
        }
        return $output;
    }
    
    public static function convertRSA($modulus, $exponent)
    {
        
        $exponentEncoding = self::makeAsnSegment(0x02, $exponent);
        $modulusEncoding = self::makeAsnSegment(0x02, $modulus);
        $sequenceEncoding = self::makeAsnSegment(0x30, $modulusEncoding.$exponentEncoding);
        $bitstringEncoding = self::makeAsnSegment(0x03, $sequenceEncoding);
        $rsaAlgorithmIdentifier = pack("H*", "300D06092A864886F70D0101010500");
        $publicKeyInfo = self::makeAsnSegment(0x30, $rsaAlgorithmIdentifier.$bitstringEncoding);
        
        $publicKeyInfoBase64 = base64_encode($publicKeyInfo);
        $encoding = "-----BEGIN PUBLIC KEY-----\n";
        $offset = 0;
        while ($segment = substr($publicKeyInfoBase64, $offset, 64)) {
            $encoding = $encoding.$segment."\n";
            $offset += 64;
        }
        return $encoding."-----END PUBLIC KEY-----\n";
    }
    
    public function serializeKey($parent)
    {
    }
    
    public function getX509Certificate()
    {
        return $this->x509Certificate;
    }
    
    public function getX509Thumbprint()
    {
        return $this->X509Thumbprint;
    }
    
    public static function fromEncryptedKeyElement(DOMElement $element)
    {
        $objenc = new XMLSecEnc();
        $objenc->setNode($element);
        if (! $objKey = $objenc->locateKey()) {
            throw new Exception("Unable to locate algorithm for this Encrypted Key");
        }
        $objKey->isEncrypted = true;
        $objKey->encryptedCtx = $objenc;
        XMLSecEnc::staticLocateKeyInfo($objKey, $element);
        return $objKey;
    }
}