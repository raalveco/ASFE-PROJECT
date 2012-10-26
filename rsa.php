<?php
	class Rsa{
		static function cargarCertificado($cer){
			$archivo = fopen($cer,"r") or die("No se pudo abrir el archivo");
			
			$certificado = "";
			
			while (!feof($archivo))
			{
				$linea = trim(fgets($archivo));
				
				if(stripos($linea, "BEGIN CERTIFICATE") !== false || stripos($linea, "END CERTIFICATE") !== false){
					continue;
				}
				
				$certificado .= $linea;
		  	}
			
			fclose($archivo);
			
			return trim($certificado);
		}
		
		static function cargarLlavePrivada($key){
			$archivo = fopen($key,"r") or die("No se pudo abrir el archivo");
			
			$private_key = fread($archivo, 8192);
			
			fclose($archivo);
			
			return $private_key;
		}
		
		static function obtenerLlavePrivada($key){
			return openssl_get_privatekey($key);
		}
		
		static function generarSelloDigital($cadena, $key){
			openssl_sign($cadena, $firma, $key, OPENSSL_ALGO_SHA1);
			return base64_encode($firma);
		}
		
		static function selloDigital($cadena, $archivo){
			$key = Rsa::cargarLlavePrivada($archivo);
			$private_key = Rsa::obtenerLlavePrivada($key);
			$sello = Rsa::generarSelloDigital($cadena,$private_key);
			
			return $sello;
		}
	}
?>