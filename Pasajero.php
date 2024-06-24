<?php

class Pasajero extends Persona
{
    private $idPasajero;
    private $numAsiento;
    private $numTicket;
    private $objViaje;
    private $mensajeoperacion;

    public function __construct()
    {
        parent::__construct();
        $this->idPasajero ="";
        $this->numAsiento = "";
        $this->numTicket = "";
        $this->objViaje = null;
    }
	public function cargar($nroDoc, $nombre, $apellido, $telefono, $idPasajero = null, $numAsiento = null, $numTicket = null, $objetoViaje = null) {
        parent::cargar($nroDoc, $nombre, $apellido, $telefono);
        if ($idPasajero !== null) $this->setIdPasajero($idPasajero);
        if ($numAsiento !== null) $this->setNumAsiento($numAsiento);
        if ($numTicket !== null) $this->setNumTicket($numTicket);
        if ($objetoViaje !== null) $this->setObjViaje($objetoViaje);
    }
	
    public function getIdPasajero(){
        return $this->idPasajero;
    }
    public function getNumAsiento()
    {
        return $this->numAsiento;
    }
    public function getNumTicket()
    {
        return $this->numTicket;
    }
    public function getObjViaje()
    {
        return $this->objViaje;
    }
    public function getmensajeoperacion(){
		return $this->mensajeoperacion ;
	}
    public function setIdPasajero($IdPas){
        $this->idPasajero = $IdPas;
    }
    public function setNumAsiento($numAsiento)
    {
        $this->numAsiento = $numAsiento;
    }
    public function setNumTicket($numTicket)
    {
        $this->numTicket = $numTicket;
    }
    public function setObjViaje($objViaje)
    {
        $this->objViaje = $objViaje;
    }
    public function setmensajeoperacion($mensajeoperacion){
		$this->mensajeoperacion=$mensajeoperacion;
	}
    public function Buscar($dni){
		$base=new BaseDatos();
		$consulta="Select * from pasajero where pdocumento=".$dni;
		$resp= false;
		if($base->Iniciar()){
		    if($base->Ejecutar($consulta)){
				if($row2=$base->Registro()){
				    parent::Buscar($dni);
					$this->setIdPasajero($row2['idpasajero']);
				    $this->setNumAsiento($row2['numasiento']);
                    $this->setNumTicket($row2['numticket']);
                    $this->setObjViaje($row2['idviaje']); 

					// Creo obj Viaje
					$objViaje = new Viaje();
					$objViaje->Buscar($row2['idviaje']);
					$this->setObjViaje($objViaje);
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
	    $arreglo = null;
		$base=new BaseDatos();
		$consulta="Select * from pasajero ";
		if ($condicion!=""){
		    $consulta=$consulta.' where '.$condicion;
		}
		$consulta.=" order by idpasajero "; 

		if($base->Iniciar()){
		    if($base->Ejecutar($consulta)){				
			    $arreglo= array();
				while($row2=$base->Registro()){
					$obj=new Pasajero();
					$obj->Buscar($row2['pdocumento']);
					array_push($arreglo,$obj);
				}
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());
		 }	
		 return $arreglo;
	}	

	public function insertar() {
		$base = new BaseDatos();
		$resp = false;
	
		if (parent::insertar()) {
			$objViaje = $this->getObjViaje();
			
			if ($objViaje !== null) {
				// Asume que idPasajero es auto increment y no lo incluimos en la consulta.
				$nroDoc = $this->getNrodoc();
				$numAsiento = $this->getNumAsiento();
				$numTicket = $this->getNumTicket();
				$idViaje = $objViaje->getCodigo();
				
				$consultaInsertar = "INSERT INTO pasajero(pdocumento, numasiento, numticket, idviaje)
					VALUES ($nroDoc, $numAsiento, $numTicket, $idViaje)";

				if ($base->Iniciar()) {
					if ($base->Ejecutar($consultaInsertar)) {
						$resp = true;
					} else {
						$this->setmensajeoperacion($base->getError());
					}
				} else {
					$this->setmensajeoperacion($base->getError());
				}
			} else {
				$this->setmensajeoperacion($base->getError());
			}
		}
	
		return $resp;
	}
	
	
	public function modificar(){
		$resp = false; 
		$base = new BaseDatos();
		if (parent::modificar()) {
			$objViaje = $this->getObjViaje();
			if ($objViaje !== null) {
				$idViaje = $objViaje->getCodigo();
			} else {
				$idViaje = "NULL"; // Asume que es nullable en la base de datos
			}
			$consultaModifica = "UPDATE pasajero SET 
				numasiento = " . $this->getNumAsiento() . ", 
				numticket = " . $this->getNumTicket() . ", 
				idviaje = " . $idViaje . " 
				WHERE pdocumento = " . $this->getNrodoc();
	
			if ($base->Iniciar()) {
				if ($base->Ejecutar($consultaModifica)) {
					$resp = true;
				} else {
					$this->setmensajeoperacion($base->getError());
				}
			} else {
				$this->setmensajeoperacion($base->getError());
			}
		}
	
		return $resp;
	}
	
	public function eliminar(){
		$base=new BaseDatos();
		$resp=false;
		if($base->Iniciar()){
				$consultaBorra="DELETE FROM pasajero WHERE pdocumento=".$this->getNrodoc();/** pdocumento o id */
				if($base->Ejecutar($consultaBorra)){
				    if(parent::eliminar()){
				        $resp=  true;
				    }
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
        $info = parent::__toString();
        $info.= "Numero de asiento:{$this->getNumAsiento()}\nNumero de ticket:{$this->getNumTicket()} \n";
        return $info;
    }
}