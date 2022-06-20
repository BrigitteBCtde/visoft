# docker-compose-laravel

docker-compose up -d --build site

Puertos exouestos

- **nginx** - `:8080`
- **mysql** - `:3306`
- **php** - `:9000`

Comandos de laravel

- `docker-compose run --rm composer update`
- `docker-compose run --rm npm run dev`
- `docker-compose run --rm artisan migrate` 


//variables a enviar linea de codigo (Blockchain) archivo index.php
      
     //fecha y hora para el nombre de cada carpeta que guarda los archivos enviados a blockchain y el certificado.
      $today = date("m-d-y_H-i-s") .'';
     //nombre de la carpeta donde se ponen los archivos
      $origin = 'inbox/';
    //nombre de la carpeta donde va a guardar las carpetas con fecha y hora que guardan los archivos enviados a blockchain.
    $destiny = 'outbox/';
    
 //variables a enviar a ubicarme (Mensajes de texto) archivo clase sendSms.php

    
    //El mensaje de texto a enviar
    string $messageSMS = "Hola prueba";
     // un array con tres valores 
    $configSms = [   
                    'from'='', //número de teleéono quien lo envia
                    'user'='', //usuario de acceso a ubicarme
                    'pass'='' //contraseña de acceso a ubicarme
                    ]
    //número de teléfono a quien va dirigido    
    $numberTo = '';
    
     llamar la clase y la funcion build
     $resultSms = new SendSms();
     $resultSms1 = $resultSms->build($messageSMS, $configSms);
