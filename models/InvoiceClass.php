<?php
/**

    
    * * * * * InvoiceClass es model de la DDBB schema ->invoices *** *
    * * @Author musef v.1.0 2015-08-01
    

*/
class InvoiceClass {

	private $id;
    private $company;    
	private $numero;
	private $fecha;
	private $destinatario;
	private $albaranes;            //indica el numero de albaranes que contiene la factura - no util
	private $baseimponible1;
	private $cuotaiva1;
	private $baseimponible2;
	private $cuotaiva2;
	private $baseimponible3;
	private $cuotaiva3;
	private $total;
    private $formapago;             // id de forma de pago
	private $vencimiento;             // fecha en formato texto



	public function __construct($company,$numero,$fecha,$destinatario,$albaranes,$baseimponible1,
		$cuotaiva1,$baseimponible2,$cuotaiva2,$baseimponible3,$cuotaiva3,$total,$formapago,$vencimiento) {

		$this->numero=$numero;
		$this->fecha=$fecha;
		$this->company=$company;
		$this->destinatario=$destinatario;
		$this->albaranes=$albaranes;
		$this->baseimponible1=$baseimponible1;
		$this->cuotaiva1=$cuotaiva1;
		$this->baseimponible2=$baseimponible2;
		$this->cuotaiva2=$cuotaiva2;
		$this->baseimponible3=$baseimponible3;
		$this->cuotaiva3=$cuotaiva3;	
		$this->total=$total;
        $this->formapago=$formapago;
		$this->vencimiento=$vencimiento;			
	}

	public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
        $this->id=$id;
    }

	public function getNumero() {
        return $this->numero;
    }
    
    public function setNumero($numero) {
        $this->numero=$numero;
    }    

	public function getFecha() {
        return $this->fecha;
    }
    
    public function setFecha($fecha) {
        $this->fecha=$fecha;
    }    

	public function getCompany() {
        return $this->company;
    }
    
    public function setCompany($company) {
        $this->company=$company;
    }

	public function getDestinatario() {
        return $this->destinatario;
    }
    
    public function setDestinatario($destinatario) {
        $this->destinatario=$destinatario;
    }

    public function getAlbaranes() {
        return $this->albaranes;
    }
    
    public function setAlbaranes($albaranes) {
        $this->albaranes=$albaranes;
    }

    public function getBaseimponible1() {
        return $this->baseimponible1;
    }
    
    public function setBaseimponible1($baseimponible1) {
        $this->baseimponible1=$baseimponible1;
    }

    public function getCuotaiva1() {
        return $this->cuotaiva1;
    }
    
    public function setCuotaiva1($cuotaiva1) {
        $this->cuotaiva1=$cuotaiva1;
    }

    public function getBaseimponible2() {
        return $this->baseimponible2;
    }
    
    public function setBaseimponible2($baseimponible2) {
        $this->baseimponible2=$baseimponible2;
    }

    public function getCuotaiva2() {
        return $this->cuotaiva2;
    }
    
    public function setCuotaiva2($cuotaiva2) {
        $this->cuotaiva2=$cuotaiva2;
    }

    public function getBaseimponible3() {
        return $this->baseimponible3;
    }
    
    public function setBaseimponible3($baseimponible3) {
        $this->baseimponible3=$baseimponible3;
    }

    public function getCuotaiva3() {
        return $this->cuotaiva3;
    }
    
    public function setCuotaiva3($cuotaiva3) {
        $this->cuotaiva3=$cuotaiva3;
    }

	public function getTotal() {
        return $this->total;
    }
    
    public function setTotal($total) {
        $this->total=$total;
    }    

    public function getFormapago() {
        return $this->formapago;
    }
    
    public function setFormapago($formapago) {
        $this->formapago=$formapago;
    }  

	public function getVencimiento() {
        return $this->vencimiento;
    }
    
    public function setVencimiento($vencimiento) {
        $this->vencimiento=$vencimiento;
    }    


}