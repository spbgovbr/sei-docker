<?php

class InfraCasLifepoint
{
    var $dias = 0;
    var $reps = 0;
    var $apagar = 1;
    var $autoapagar = 0;
    
    /**
     * Construtor de cas_lifepoint
     * 
     * @param int $_dias Número de dias a partir de hoje que objeto deve cumprir a regra informada
     * 
     * @param int $_reps Número de replicas a ser armazenada para a esta regra
     * 
     * @param int $_apagar Permite que seja apagado pelo usuário, 0 - Não e 1 - Sim
     * 
     * @param int $_autoapagar Sinaliza que o objeto após a data de expiração será apagado pelo próprio Swarm automaticamente
     * 
     **/
    function __construct($_dias=0, $_reps=0, $_apagar=-1, $_autoapagar=-1) 
    {
        $this->dias=$_dias;
        $this->reps=$_reps;
        $this->apagar=$_apagar;
        $this->autoapagar=$_autoapagar;
    }
    
    /**
     * Obtem uma linha com a regra informada para compor o tag Lifepoint[] do Swarm, usado internamento
     * 
     * @return Linha do Lifepoint especificado
     * 
     **/
    public function getLine() 
    {
        $items = 0;
        $line = "[";
        if ($this->dias>0){
			$now=new DateTime();
			$then=$now->add(new DateInterval("P".$this->dias."D"))->setTimezone(new DateTimeZone("GMT"));
            
			$line .= addslashes($then->format("D, d M Y H:i:s")." GMT");
		}
        $line.="]";
        
        if ($this->reps > 0) {
            $line.= addslashes(" reps=".$this->reps);
            $items+=1;
        }
        if ( $this->apagar>=0) {
            $line.= ($items>0?",":"")." deletable=".($this->apagar==0?"no":"yes");
            $items+=1;
        }
        if ($this->autoapagar>0)
            $line.=($items>0?",":"")." delete";
        return $line;
    }
}
