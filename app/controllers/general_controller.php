<?php
	class GeneralController extends ApplicationController 
	{
		public function buscar(){
			if(Session::get("acceso") != "ADMINISTRADOR"){ $this -> redirect(""); return; }
			
            $this -> render(null,null);
			
			Load::lib("formato");
			
			$filtro = Formato::mayusculas(trim($this -> post("bgeneral")));
			
			if(strpos($filtro,"CT")!==false && strpos($filtro,"CT")==0){
				$idc = substr($filtro,2)+0;
				
				$cotizacion = Cotizacion::consultar($idc);
				
				if($cotizacion){
					$this -> redirect("cotizaciones/consulta/".$idc);
					return;
				}
			}
			
			if(strpos($filtro,"VT")!==false && strpos($filtro,"VT")==0){
				$idv = substr($filtro,2)+0;
				
				$venta = Venta::consultar($idv);
				
				if($venta){
					$this -> redirect("ventas/consulta/".$idv);
					return;
				}
			}
			
			if(strpos($filtro,"UD")!==false && strpos($filtro,"UD")==0){
				$idf = substr($filtro,2)+0;
				
				$factura = Factura::consultar("serie='UD' AND folio=".$idf);
				
				if($factura){
					$this -> redirect("facturacion/consulta/".$factura -> id);
					return;
				}
			}
			
			if(Producto::existe("codigo_barras = '".$filtro."'")){
				$producto = Producto::consultar("codigo_barras = '".$filtro."'");
				
				if($producto){
					$this -> redirect("productos/consultaPrecio/".$producto -> id);
				}
				
				return;
			}
			
			
			$filtro = Formato::noTildes($filtro);
			$filtro = Formato::minusculas($filtro);
			
			Session::set("filtro",$filtro);
			    
			$this -> redirect("productos/reporte/filtrado");
        }
		
		public function dolar($dolar = 13.70){
			if(Session::get("acceso") != "ADMINISTRADOR"){ $this -> redirect(""); return; }
			
			$this -> render(null,null);
			
			$anterior = Dolar::buscar("id>0","fecha DESC");
			
			if($anterior -> cotizacion != $dolar){
				
				$nuevo = Dolar::registrar($dolar);
				
				$productos = Producto::reporte();
				
				if($productos) foreach($productos as $producto){
					$producto -> precio_compra = $producto -> precio_compra / $anterior -> cotizacion * $dolar;
					
					$producto -> precio_web = Producto::calcular(Formato::noDinero($producto -> precio_compra),"precio_web");
					$producto -> precio_minimo = Producto::calcular(Formato::noDinero($producto -> precio_compra),"precio_minimo");
					$producto -> precio_sucursal = Producto::calcular(Formato::noDinero($producto -> precio_compra),"precio_sucursal");
					
					$producto -> publicar();
					
					if($producto -> oferta == "SI"){
						$producto -> ofertar();
					}
					else{
						$producto -> desofertar();
					}
				}
			}
			
			echo "Los precios han sido actualizados a la nueva cotización del Dolar";
		}
	}
?>