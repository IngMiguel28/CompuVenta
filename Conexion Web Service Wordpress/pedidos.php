<?php

//Librerias para conectar con Woocommerce
require __DIR__ . '/vendor/autoload.php';
use Automattic\WooCommerce\Client;

        // Conexión WooCommerce API destino
        // ================================
        $url_API_woo = 'https://tzila.mx/dev/';
        $ck_API_woo = 'ck_bca54e05d65cd92c40ddf0a2251b2bcdca659e76';
        $cs_API_woo = 'cs_ea1a1df8e8b9922b3aa1877f4d09a5a1105b0c16';

        $woocommerce = new Client
            (
                $url_API_woo,
                $ck_API_woo,
                $cs_API_woo,
                ['version' => 'wc/v3']
            );
        // ================================


$origen = $woocommerce->get('orders/13219');
$json= json_encode($origen);
$pedido = json_decode($json,True);
var_dump($pedido);

foreach ($pedido as $datos) {

 $moneda = $datos->total;	
echo $moneda;

}