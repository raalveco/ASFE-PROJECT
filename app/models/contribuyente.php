<?php
	class Contribuyente extends ActiveRecord{
		public function rutaArchivoKEY($id = 0){
			if($id == 0){
				$certificado = Certificado::consultar("contribuyente_id = ".$this -> id." AND status = 'ACTIVO'");
			}
			else{
				$certificado = Certificado::consultar($this -> id);
			}
			
			return APP_PATH."public/ciberfactura/".$this -> rfc."/certificados/".$this -> rfc."_".$certificado -> numero_serie."_".$certificado -> id.".key.pem";
		}	
		
		public function rutaArchivoCER($id = 0){
			if($id == 0){
				$certificado = Certificado::consultar("contribuyente_id = ".$this -> id." AND status = 'ACTIVO'");
			}
			else{
				$certificado = Certificado::consultar($this -> id);
			}
			
			return APP_PATH."public/ciberfactura/".$this -> rfc."/certificados/".$this -> rfc."_".$certificado -> numero_serie."_".$certificado -> id.".cer.pem";
		}	
	}
?>