<?php
/**
 * prestashop Project ${PROJECT_URL}
 *
 * @link      ${GITHUB_URL} Source code
 */

namespace PhpSigep\Services\Real;

use PhpSigep\Bootstrap;
use PhpSigep\Config;

class SoapClientFactory
{
    const WEB_SERVICE_CHARSET = 'ISO-8859-1';

    /**
     * @var \SoapClient
     */
    protected static $_soapClient;
    /**
     * @var \SoapClient
     */
    protected static $_soapCalcPrecoPrazo;

    public static function getSoapClient()
    {
        if (!self::$_soapClient) {
            $wsdl = Bootstrap::getConfig()->getWsdlAtendeCliente();

            self::$_soapClient = new \SoapClient($wsdl, array(
                "trace"              => Bootstrap::getConfig()->getEnv() != Config::ENV_PRODUCTION,
                "exceptions"         => Bootstrap::getConfig()->getEnv() != Config::ENV_PRODUCTION,
                'encoding'           => self::WEB_SERVICE_CHARSET,
                'connection_timeout' => 60,
            ));
        }

        return self::$_soapClient;
    }

    public static function getSoapCalcPrecoPrazo()
    {
        if (!self::$_soapCalcPrecoPrazo) {
            $wsdl = Bootstrap::getConfig()->getWsdlCalcPrecoPrazo();

            self::$_soapCalcPrecoPrazo = new \SoapClient($wsdl, array(
                "trace"              => Bootstrap::getConfig()->getEnv() != Config::ENV_PRODUCTION,
                "exceptions"         => Bootstrap::getConfig()->getEnv() != Config::ENV_PRODUCTION,
                'encoding'           => self::WEB_SERVICE_CHARSET,
                'connection_timeout' => 60,
            ));
        }

        return self::$_soapCalcPrecoPrazo;
    }

    /**
     * Se possível converte a string recebida.
     * @param $string
     * @return bool|string
     */
    public static function convertEncoding($string)
    {
        $to     = 'UTF-8';
        $from   = self::WEB_SERVICE_CHARSET;
        $str = false;
        
        if (function_exists('iconv')) {
            $str = iconv($from, $to . '//TRANSLIT', $string);
        } elseif (function_exists('mb_convert_encoding')) {
            $str = mb_convert_encoding($string, $to, $from);
        }

        if ($str === false) {
            $str = $string;
        }

        return $str;
    }
} 