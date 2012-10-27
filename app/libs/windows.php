<?php
	class Windows{
		var $paneles;
		var $np;
		
		public function Windows(){
			$this -> paneles = array();
			$this -> np = 0;
		}
		
		public function panel($titulo, $estado = true){
			$this -> paneles[$this -> np] = new WPanel($titulo, $estado);
			
			return $this -> paneles[$this -> np++];
		}
		
		public function addPanel($panel){
			$this -> paneles[$this -> np++] = $panel;
		}
		
		public function renderizar(){
			echo $this -> html();
			echo $this -> javascript();
		}
		
		public function html(){
			$html = '<table border="0" id="dhtmlgoodies_xpPane" style="margin-top: 0px; height: 100%; float: left;"><tr><td valing="top" style="vertical-align: top;">';
			
			if($this -> paneles) foreach($this -> paneles as $panel){
				$html .= $panel -> html();	
			}
			
			$html .= '</td></tr><tr><td></td></tr></table>';
			
			return $html;
		}
		
		public function javascript(){
			$js = '<script type="text/javascript">';
			
			$titulos = "Array(";
			$estados = "Array(";
			if($this -> paneles) foreach($this -> paneles as $panel){
				$titulos .= "'".$panel -> titulo."',";
				
				if($panel -> estado){
					$estados .= "true,";	
				}
				else{
					$estados .= "false,";
				}
			}
			$titulos = substr($titulos,0,-1).")";
			$estados = substr($estados,0,-1).")";
			
			$js .= "initDhtmlgoodies_xpPane(".$titulos.",".$estados.",Array());";
			
			$js .= '</script>';
			
			return $js;
		}
	}
	
	class WPanel{
		var $titulo;
		var $estado;
		var $links;
		var $nlinks;
		
		public function WPanel($titulo, $estado = true){
			$this -> titulo = $titulo;
			$this -> links = array();
			$this -> nlinks = 0;
			$this -> estado = $estado;
		}
		
		public function link($url,$texto,$icono,$ajax = false, $target = false){
			$this -> links[$this -> nlinks] = new WLink($url,$texto,$icono,$ajax,$target);
			
			return $this -> links[$this -> nlinks++];
		}
		
		public function addLink($link){
			$this -> links[$this -> nlinks++] = $link;
		}
		
		public function html(){
			$html = '<div class="dhtmlgoodies_panel"><div align="center"><br/><table border="0">';
			
			if($this -> links) foreach($this -> links as $link){
				$html .= $link -> html();	
			}
			
			$html .= '</table><br/></div></div>';
			
			return $html;
		}
	}
	
	class WLink{
		var $url;
		var $texto;
		var $icono;
		var $ajax;
		
		public function WLink($url,$texto,$icono,$ajax = false){
			$this -> url = $url;
			$this -> texto = $texto;
			$this -> icono = $icono;
			$this -> ajax = $ajax;
		}
		
		public function html(){
			$html = '<tr>';
			$html .= '<td align="left" valign="middle" style="vertical-align:middle; font-size:11px;" width="20">';
            $html .= $this -> ajax ? Html::linkAjax($this -> url,Html::imagen($this -> icono),$this -> ajax) : Html::link($this -> url,Html::imagen($this -> icono),"target: _blank");
			$html .= '</td>';
			$html .= '<td class="menulink" align="left" valign="middle" style="vertical-align:middle; font-size:10px;" width="130">';
			$html .= $this -> ajax ? Html::linkAjax($this -> url,$this -> texto,$this -> ajax) : Html::link($this -> url,$this -> texto,"target: _blank");
			$html .= '</td>';
			$html .= '</tr>';
			
			return $html;
		}
	}
?>