<?php 
/**

    
    * * * * * CustomerClass es model de la DDBB schema ->customer *** *
    * * @Author musef v.1.0 2015-08-01
    

*/
class CustomerClass {

	private $id;
    private $company;
	private $nombre;
	private $direccion;
	private $codpostal;
	private $localidad;
	private $nif;
	private $formapago;

	public function __construct($company,$nombre,$direccion,$codpostal,$localidad,$nif,$formapago) {
        
		$this->company=$company;
        $this->nombre=$nombre;
		$this->direccion=$direccion;
		$this->codpostal=$codpostal;
		$this->localidad=$localidad;
		$this->nif=$nif;
		$this->formapago=$formapago;
	}


	public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
        $this->id=$id;
    }

    public function getCompany() {
        return $this->company;
    }
    
    public function setCompany($company) {
        $this->company=$company;
    }
	public function getNombre() {
        return $this->nombre;
    }
    
    public function setNombre($nombre) {
        $this->nombre=$nombre;
    }  

	public function getDireccion() {
        return $this->direccion;
    }
    
    public function setDireccion($direccion) {
        $this->direccion=$direccion;
    }  

	public function getCodpostal() {
        return $this->codpostal;
    }
    
    public function setCodpostal($codpostal) {
        $this->codpostal=$codpostal;
    }  

    public function getLocalidad() {
        return $this->localidad;
    }
    
    public function setLocalidad($localidad) {
        $this->localidad=$localidad;
    }  

	public function getNif() {
        return $this->nif;
    }
    
    public function setNif($nif) {
        $this->nif=$nif;
    }  

	public function getFormapago() {
        return $this->formapago;
    }
    
    public function setFormapago($formapago) {
        $this->formapago=$formapago;
    }  

}