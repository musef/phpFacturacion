<?php 
/**

    
    * * * * * FormasPagoClass es model de la DDBB schema ->formaspago *** *
    * * @Author musef v.1.0 2015-08-01
    

*/
class FormasPagoClass {

	private $id;               // id del objeto
    private $company;           // empresa de facturacion
	private $nombrePago;       // denominacion de la forma de pago
	private $diff;             // dias naturales de aplazamiento de la forma de pago
	private $diaPago;          // dia concreto de pago; 0=> al mismo dia de emision de factura ; 31=> ultimo día del mes
                                // una factura librada el dia 15-marzo con $diff 30 dias con dia pago 0 es pagadera el 
                                // dia 15 de abril; con dia pago 31 sería pagadera el 30 de abril. 

	public function __construct($company,$nombrePago,$diff,$diaPago) {
        
        $this->company=$company;
		$this->nombrePago=$nombrePago;
		$this->diff=$diff;
		$this->diaPago=$diaPago;
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

	public function getNombrePago() {
        return $this->nombrePago;
    }
    
    public function setNombrePago($nombrePago) {
        $this->nombrePago=$nombrePago;
    }    

    public function getDiff() {
        return $this->diff;
    }
    
    public function setDiff($diff) {
        $this->diff=$diff;
    } 

	public function getDiaPago() {
        return $this->diaPago;
    }
    
    public function setDiaPago($diaPago) {
        $this->diaPago=$diaPago;
    } 

}