<?php
    /**
     * @function dataService encargada de enviar los archivos a la red Blockchain de LDC.
     * @param {string} token - Token de acceso API LDC.
     * @param {string} fileName - Nombre del archivo para procesar.
     * @param {string} hash - Hash del archivo para procesar.
     * @returns {response} retornara response con la respuesta del API  .
     */
    function dataService($token, $fileName, $hash){
        try {
            $curl = curl_init();
            $data = array(
                "name" => $fileName,
                "hash" => $hash
            );
            $query_string = http_build_query($data);
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://sandbox-registry.lineadecodigo.net/v1/transaction/resources/assets',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $query_string,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $token
                ),
            ));
            $response = curl_exec($curl);
            $dataResponse = json_decode($response);
            if($dataResponse->error) {
                echo "error";
                return false;
            }
            curl_close($curl);
            return $dataResponse;
        }catch (Exception $e){
            echo $e;
        }
    }
    /**
     * @function login encargada de generar el token de acceso a LDC API.
     * @returns {response} retornara response del API.
     **/
    function login(){
        try{
            $curl = curl_init();
            $data = array(
                "password"=> '%$V1Sof*',
                "email"=> "magalyuo14@gmail.com"
            );
            $query_string = http_build_query($data);
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://sandbox-registry.lineadecodigo.net/v1/auth/login',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $query_string,
            ));
            $response = curl_exec($curl);
            $jsonResponse = json_decode($response);
            if($jsonResponse->error) {
                return false;
            }
            curl_close($curl);
            return $jsonResponse;
        }catch (Exception $e){
            echo $e;
        }
    }
    /**
     * @function hashFile encargada hacer el hash de los archivos.
     * @param {string} urlFile - url de localizacion del archivo.
     * @returns {string} retornara un string con el hash del archivo.
     */
    function hashFile($urlFile){
        try{
            return hash_file('md5', $urlFile);
        }catch (Exception $e){
            echo $e;
        }
    }
    /**
     * @function fileMove encargada de mover un archivo.
     * @param {string} fileName - url de localizacion del archivo.
     * @returns {bool} retornara un true si se puedo ejecutar correctamente de lo contrario retornara un false.
     */
    function fileMove($fileName, $origin, $destiny, $date): bool{
        try{
            $path = $destiny . $date. '/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $moved = rename($origin . $fileName, $destiny. $date .'/' . $fileName);
            if($moved)
            {
                return true;
            }
            return false;
        }catch (Exception $e){
            echo $e;
        }
    }
    /**
     * @function listFile encargada listar en un array los archivos para ser procesados.
     * @param {string} $path - url de la carpeta donde se almacenan los archivos.
     * @returns {array} retornara array con los datos de los archivos.
     */
    function listFile($path): array
    {
        try {
            $list = array();
            if ($handler = opendir($path)) {
                while (false !== ($file = readdir($handler))) {
                    $list[] = $file;
                }
                closedir($handler);
            }
            return $list;
        }catch (Exception $e){
            echo $e;
        }
    }
    /**
     * @function generatePdf encargada de generar el archivo .pdf de certificado Blockcahin del API de LDC.
     * @param {string} $base64 - string en formato base64 con el archivo dado por el API.
     * @param {string} $assetId - id del certificado emitido por el API.
     * @returns {bool}  retornara true si se pudo generar el achivo .pdf.
     */
    function generatePdf($base64, $assetId, $date, $destiny): bool{
        try {
            $pdf_decoded = base64_decode ($base64);
            if (!file_exists($destiny . $date . '/'. 'certificate/')) {
                mkdir($destiny . $date . '/'.'certificate/', 0777, true);
            }
            $pdf = fopen ($destiny . $date . '/'. 'certificate/'.$assetId.'.pdf','w');
            fwrite ($pdf,$pdf_decoded);
            return true;
        }catch (Exception $e){
            echo $e;
        }
    }
    /**
     * @function createJson encargada de generar el archivo .json con la informacion de los archivos y hash para procesarlos en el API.
     * @param {string} $content - string con la informacion que contendra el archivo json que se retorna.
     * @returns {string}  retornara string con la ubicacion y nombre del archivo json generado.
     */
    function createJson($content, $date, $destiny): string
    {
        try {
            $path = $destiny . $date . '/' . 'certificate/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $jsonData = json_encode($content);
            $urlFile = $destiny . $date . '/' . 'certificate/' . $date . '.json';
            file_put_contents($urlFile, $jsonData);
            return $destiny . $date . '/' . 'certificate/' . $date . '.json';
        }catch (Exception $e){
            echo $e;
        }
    }
    /**
     * @function processFiles encargada de generar el archivo .pdf de certificado Blockcahin del API de LDC.
     * @param {string} $base64 - string en formato base64 con el archivo dado por el API.
     * @param {string} $assetId - id del certificado emitido por el API.
     * @returns {bool}  retornara true si se pudo generar el achivo .pdf.
     */
    function processFiles($files, $origin, $destiny, $date): array
    {
        try {
            $dataArray = array();
            foreach ($files as $file) {
                $urlFiles = $origin . $file;
                $fileHash = hashFile($urlFiles);
                if ($fileHash == true) {
                    fileMove($file, $origin, $destiny, $date);
                    $dataToJson = array(
                        "name" => $file,
                        "hash" => $fileHash);
                    $dataArray[] = $dataToJson;
                }
            }
            return $dataArray;
        }catch (Exception $e){
            echo $e;
        }
    }
    /**
     * @function restore encargada de devolver los archivos a su origen y borrar el cetificadi.
     * @param {bool} $delete - bool que condiciona el borrado de certificados.
     */
    function restore($delete, $origin, $destiny, $date)
    {
        try {
            if($delete == true){
                exec(sprintf("rm -rf %s", escapeshellarg($destiny . $date . '/certificate')));
            }
            $result = listFile($destiny . $date . '/' );

            if(count($result) <= 2){
                echo 'NOT-FOUND-FILES';
            }
            print_r($result);
            foreach ($result as $file) {
                rename($destiny . $date . '/' . $file, $origin . $file);
            }
            exec(sprintf("rm -rf %s", escapeshellarg($destiny . $date)));

        }catch (Exception $e){
            echo $e;
        }
    }
    /**
     * @function main encargada correr secusencialmente las funciones para realizar el proceso de certificacion con el API de LDC.
     * @returns {string}  retornara string con la confirmación del proceso.
     */
    function main($date,$origin,$destiny)
    {
        try{
            $result = listFile($origin);
            if(count($result) <= 2){
                echo 'NOT-FOUND-FILES';
                return false;
            }
            $process = processFiles($result, $origin, $destiny, $date);
            $fileJson = json_encode($process);
            $jsonCreate = createJson($fileJson, $date, $destiny);
            $fileHashJson = hashFile($jsonCreate);
            $token = login();
            if($token == false) {
                restore(true,$origin,$destiny,$date);
            }
            $dataResponse = dataService($token->data, $jsonCreate, $fileHashJson);
            if($dataResponse == false) {
                restore(true,$origin,$destiny,$date);
            }
            generatePdf($dataResponse->data->certificate, $dataResponse->data->asset, $date, $destiny);
            return 'process successful';
        } catch (Exception $e){
            echo $e;
        }

    }
    //variables de prueba
    $today = date("m-d-y_H-i-s") .'';
    $origin = 'inbox/';
    $destiny = 'outbox/';
    /**
     * Llamado a la accion de la funcion main().
     */
    main($today,$origin,$destiny);
?>
