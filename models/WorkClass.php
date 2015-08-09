<?php
/**

    
    * * * * * WorkClass es model de la DDBB schema ->works *** *
    * * @Author musef v.1.0 2015-08-01
    

*/
class WorkClass {
    private $id;
    private $company;
    private $fecha;
    private $numero;
    private $cliente;
    private $texto;
    private $cantidad;
    private $importe;
    private $base;
    private $iva;
    private $total;
    private $factura;


    public function __construct($company,$fecha,$numero,$cliente,$texto,$cantidad,$importe,$base,$iva,$total,$factura){

        $this->setCompany($company);
        $this->setFecha($fecha);
        $this->setNumero($numero);
        $this->setCliente($cliente);
        $this->setTexto($texto);
        $this->setCantidad($cantidad);
        $this->setImporte($importe);
        $this->setBase($base);
        $this->setIva($iva);
        $this->setTotal($total);
        $this->setFactura($factura);
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
    
    public function getFecha() {
        return $this->fecha;
    }
    
    public function setFecha($fecha) {
        $this->fecha=$fecha;
    }
    
    public function getNumero() {
        return $this->numero;
    }
    
    public function setNumero($numero) {
        $this->numero=$numero;
    }  
    
    public function getCliente() {
        return $this->cliente;
    }
    
    public function setCliente($cliente) {
       $this->cliente=$cliente;
    }    
    
    public function getTexto() {
        return $this->texto;
    }
    
    public function setTexto($texto) {
        $this->texto=$texto;
    }  
    
    public function getCantidad() {
        return $this->cantidad;
    }
    
    public function setCantidad($cantidad) {
        $this->cantidad=$cantidad;
    }    
    public function getImporte() {
        return $this->importe;
    }
    
    public function setImporte($importe) {
        $this->importe=$importe;
    }    
    
    public function getBase() {
        return $this->base;
    }
    
    public function setBase($base) {
        $this->base=$base;
    }

    public function getIva() {
        return $this->iva;
    }
    
    public function setIva($iva) {
        $this->iva=$iva;
    }    
    
    public function getTotal() {
        return $this->total;
    }
    
    public function setTotal($total) {
        $this->total=$total;
    } 

    public function getFactura() {
        return $this->factura;
    }
    
    public function setFactura($factura) {
        $this->factura=$factura;
    }    
}
