<?php

class Viaje
{
    private $codigo;
    private $destino;
    private $maxPasajeros;
    private $pasajerosArray;
    private $objResponsable;
    private $costo;
    private $costosAbonados;
    private $objEmpresa;
    private $mensajeoperacion;

    public function __construct()
    {
        
        $this->codigo = ""; 
        $this->destino = "";
        $this->maxPasajeros = "";
        $this->pasajerosArray=[]; 
        $this->objResponsable = null ; 
        $this->costo = "";
        $this->costosAbonados = 0;
        $this->objEmpresa = null ;
    }
    public function cargar($cod,$dest,$maxPas,$resp, $costo, $objEmpresa){
        $this->setCodigo($cod);
        $this->setDestino($dest);
        $this->setMaxPasajeros($maxPas);
        $this->setObjResponsable($resp); 
        $this->setCosto($costo); 
        $this->setObjEmpresa($objEmpresa);     
    }
    // Métodos de acceso (getters)
    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getDestino()
    {
        return $this->destino;
    }

    public function getMaxPasajeros()
    {
        return $this->maxPasajeros;
    }

    public function getPasajerosArray()
    {
        return $this->pasajerosArray;
    }

    public function getObjResponsable()
    {
        return $this->objResponsable;
    }
    public function getCosto()
    {
        return $this->costo;
    }
    public function getCostosAbonados()
    {
        return $this->costosAbonados;
    }
    public function getObjEmpresa()
    {
        return $this->objEmpresa;
    }
    public function getmensajeoperacion(){
		return $this->mensajeoperacion ;
	}
    // Métodos de modificación (setters)
    public function setCostosAbonados($costos)
    {
        $this->costosAbonados = $costos;
    }
    public function setCosto($costo)
    {
        $this->costo = $costo;
    }
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function setDestino($destino)
    {
        $this->destino = $destino;
    }

    public function setMaxPasajeros($maxPasajeros)
    {
        $this->maxPasajeros = $maxPasajeros;
    }

    public function setPasajerosArray($pasajeros)
    {
        $this->pasajerosArray = $pasajeros;
    }

    public function setObjResponsable($responsable)
    {
        $this->objResponsable = $responsable;
    }
    public function setObjEmpresa( $objEmpresa)
    {
        $this->objEmpresa = $objEmpresa;
    }
    public function setmensajeoperacion($mensajeoperacion){
		$this->mensajeoperacion=$mensajeoperacion;
	}
    public function venderPasaje($objPasajero)
    {
        $costoFinal = -1;
        if ($this->agregarPasajero($objPasajero)) {
            $costoFinal = $this->getCosto();
            $costoFinal *= (1 + $objPasajero->darPorcentajeIncremento() / 100);
            $this->setCostosAbonados($this->getCostosAbonados() + $costoFinal);
        }
        return $costoFinal;
    }
    public function agregarPasajero($objPasajero)
    {
        $retorno = false;
        if ($this->existePasajero($objPasajero) !== -1 && $this->hayPasajesDisponibles()) {
            $pasajerosArray = $this->getPasajerosArray();
            $pasajerosArray[] = $objPasajero;
            $this->setPasajerosArray($pasajerosArray);
            $retorno = true;
        }
        return $retorno;
    }
    public function hayPasajesDisponibles()
    {
        $retorno = false;
        $cantidadDePasajeros = count($this->pasajerosArray);
        if ($this->getMaxPasajeros() > $cantidadDePasajeros) {
            $retorno = true;
        }
        return $retorno;
    }
    public function modificarPasajero($documento, $nombre, $apellido, $telefono)
    {
        $retorno = false;
        $i=0;
        $pasajeros = $this->getPasajerosArray();
        while ($i<count($pasajeros)) {
            if ($pasajeros[$i]->getNrodoc() == $documento) {
                $pasajeros[$i]->setNombre($nombre);
                $pasajeros[$i]->setApellido($apellido);
                $pasajeros[$i]->setTelefono($telefono);
                $retorno = true;
                $pasajeros[$i]->modificar();
            }
        }
        return $retorno;
    }
    public function existePasajero($pasajero){
        $pasajeros = $this->getPasajerosArray();
        $i=0;
        $encontrado = false;
        while($i<count($pasajeros) && $encontrado == false){
            $encontrado = $pasajeros[$i]->buscar($pasajero->getNrodoc());
            $i++;
        }
        if(!$encontrado){
            $i=-1;
        };
        return $i;
    }
    
    private function getStringArray($array){
        $cadena = "";
        foreach($array as $elemento){
            $cadena = $cadena . " " . $elemento . "\n";
        }
        return $cadena;
    }
    public function Buscar($id){
		$base=new BaseDatos();
		$consultaviaje="Select * from viaje where idviaje=".$id;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaviaje)){
				if($row2=$base->Registro()){				
                    $this->setCodigo($id);
					$this->setDestino($row2['vdestino']);
					$this->setMaxPasajeros($row2['vmaxpasajeros']);
					$this->setObjEmpresa($row2['idempresa']);
                    $this->setObjResponsable($row2['rnumeroempleado']);
                    $this->setCosto($row2['vimporte']);
					$resp= true;
				}				
			
            }	else {
                $this->setmensajeoperacion($base->getError());

			}
		}	else {
            $this->setmensajeoperacion($base->getError());
		
		}		
		return $resp;
	}	
    

	public function listar($condicion=""){
        $arregloviaje = null;
		$base=new BaseDatos();
		$consultaviajes="Select * from viaje ";
		if ($condicion!=""){
            $consultaviajes=$consultaviajes.' where '.$condicion;
		}
		$consultaviajes.=" order by vdestino ";
		//echo $consultaviajes;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaviajes)){				
				$arregloviaje= array();
				while($row2=$base->Registro()){
					
					$IdViaje=$row2['idviaje'];
					$Destino=$row2['vdestino'];
					$CantMaxPas=$row2['vmaxpasajeros'];
					$IdEmpresa=$row2['idempresa'];
					$RNumEmp=$row2['rnumeroempleado'];
					$Importe=$row2['vimporte'];

					$viaje=new viaje();
					$viaje->cargar($IdViaje,$Destino,$CantMaxPas,$IdEmpresa,$RNumEmp, $Importe);
					array_push($arregloviaje,$viaje);
	
				}
				
			}	else {
                    $this->setmensajeoperacion($base->getError());

			}
		}	else {
            $this->setmensajeoperacion($base->getError());
		}	
		return $arregloviaje;
	}	


	
	public function insertar(){
		$base=new BaseDatos();
		$resp= false;

                $consultaInsertar = "INSERT INTO viaje(idviaje, vdestino, vmaxpasajeros, idempresa, rnumeroempleado, vimporte) 
                VALUES (" . $this->getCodigo() . ", '" . $this->getDestino() . "', " . $this->getMaxPasajeros() . ", " . $this->getObjEmpresa()->getId() . ", " . $this->getObjResponsable()->getNumeroEmpleado() . ", " . $this->getCosto() . ")";

		
		if($base->Iniciar()){

			if($base->Ejecutar($consultaInsertar)){
                $resp=  true;
			}	else {
					$this->setmensajeoperacion($base->getError());
					
			}

		} else {
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp;
	}
	
	public function modificar(){
        $resp =false; 
        $base=new BaseDatos();
		$consultaModifica="UPDATE viaje SET vdestino='".$this->getDestino()."',idempresa='".$this->getObjEmpresa()."',rnumeroempleado='".$this->getObjResponsable()."' WHERE idviaje=". $this->getCodigo();
		if($base->Iniciar()){
			if($base->Ejecutar($consultaModifica)){
                $resp=  true;
			}else{
				$this->setmensajeoperacion($base->getError());
				
			}
		}else{
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp;
	}
	
	public function eliminar(){
		$base=new BaseDatos();
		$resp=false;
		if($base->Iniciar()){
				$consultaBorra="DELETE FROM viaje WHERE idviaje=".$this->getCodigo();
				if($base->Ejecutar($consultaBorra)){
                    $resp=  true;
				}else{
						$this->setmensajeoperacion($base->getError());
					
				}
		}else{
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp; 
	}
    public function __toString()
    {
        $pasajeros = $this->getStringArray($this->getPasajerosArray());
        $info = "Código de Viaje: {$this->getCodigo()}\n";
        $info .= "Destino: {$this->getDestino()}\n";
        $info .= "Cantidad Máxima de Pasajeros: {$this->getMaxPasajeros()}\n";
        $info.= "Empresa: {$this->getObjEmpresa()}\n";
        $info .= "Numero empleado responsable: " . $this->getObjResponsable() . "\n";
        $info .= "Pasajeros: {$pasajeros}";
        return $info;
    }
}