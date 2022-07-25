<?php
//Bibliotecas para conexion con Woocommerce
require __DIR__ . '/vendor/autoload.php';
use Automattic\WooCommerce\Client;

//Conexion con Arroba solo para un articulo

$url = "https://ws.zafirosoft.com/zwscom/Arrocomp/ExistenciasAlmacen";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
   "Content-Type: application/xml",
   "Accept: application/xml",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

$data = "<data>n<Usuario>Tzila</Usuario>n<Contrasena>TZXu*ixg</Contrasena>n<Descripcion></Descripcion>n<ClaveArticulo>CH-9226965-SP</ClaveArticulo>n</data>";

curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

//Para revisar logs!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$resp = curl_exec($curl);
curl_close($curl);

$XML = simplexml_load_string($resp,"SimpleXMLElement",LIBXML_NOCDATA);
$json = json_encode($XML);
$items_origin= json_decode($json,True); 
//Generar array para recorrer articulos
$archivo=[]; //arreglo
    foreach (array_chunk($items_origin, 10) as $i) 
        {
            $archivo[]=$i; 
        }

$array50=count($archivo);
    for($i=0;$i<$array50;$i++)
        {
            woocomerce50($archivo[$i]);
        }   

// Funcion para sumer almacenes
    function array_multisum(array $arr): float 
        {
            $sum = array_sum($arr);
                foreach($arr as $child) 
                    {
                        $sum += is_array($child) ? array_multisum($child) : 0;
                    }
                return $sum;
        }

//Funcion para atualizar un articulo o varios 
    
function woocomerce50($array50recibir)
    {


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

        // formamos el parámetro de lista de SKUs a actualizar
        $param_sku ='';
        foreach ($array50recibir as $item)
            {
                $param_sku .= $item['SKU'] . ',';
            }

        echo "➜ Obteniendo los ids de los productos... \n";

        // Obtenemos todos los productos de la lista de SKUs
        
        $products = $woocommerce->get('products/?sku='. $param_sku.'&per_page=100');
        
        // Construimos la data en base a los productos recuperados
        $item_data = [];
        foreach($products as $product)
        {
            // Filtramos el array de origen por sku
            $sku = $product->sku;
            $search_item = array_filter($array50recibir, function($item) use($sku) 
                {
                    return $item['SKU'] == $sku;
                });
            $search_item = reset($search_item);
            // Generamos un Array para los almacenes
            $item_Almacen[] =
                [
                    'CEDIS' => $search_item['CEDIS'],
                    'CEN' => $search_item['A-CEN'],
                    'GDL' => $search_item['GDL'], 
                ];

            // Sumar el inventatrio de los 3 almacenes y guardarlo en una variable
            $item_A = array_multisum($item_Almacen);
        }
        // Formamos el array a actualizar
        $item_data[] = 
            [
                'id' => $product->id,
                'regular_price' => $search_item['Precio_Pesos'],
                'stock_quantity' => $item_A,
                'weight'=>$search_item['PesoAproximado'],
                'dimensions' =>     
                    [
                        'length'=>$search_item['Largo'],
                        'width'=>$search_item['Ancho'],
                        'height'=>$search_item['Alto'],
                    ],
            ];
        // Construimos información a actualizar en lotes
        $data = 
            [
                'update' => $item_data,
            ];

        echo "➜ Actualización en lote ... \n";  
        // Actualización en lotes
        $result = $woocommerce->post('products/batch', $data);
        if (! $result) 
            {
                echo("❗Error al actualizar productos \n");
            } 
        else 
            {
                print("✔ Productos actualizados correctamente \n");
            }
    }