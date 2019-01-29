<?php

class Venz_Football_Security_Encryption
{
    protected $_key;
    
    public function __construct( $key ){
        $this->_key = $key;
    }
    
    public function decrypt($data){
        $char = "";
        $charx = "";
		$x=0;
        
        if(strlen($data)>0){
            $key=md5($this->_key);
            $data=base64_decode($data);
            
    		for ($i=0;$i<strlen($data);$i++)
    		{
    			if ($x==strlen($key)) $x=0;
    			$char.=substr($key,$x,1);
    			$x++;	
    		}
            
    		for ($i=0;$i<strlen($data);$i++)
    		{
    			if (ord(substr($data,$i,1))<ord(substr($char,$i,1)))
    			{
    				$charx.=chr((ord(substr($data,$i,1))+256)-ord(substr($char,$i,1)));	
    			}
    			else 
    			{
    				$charx.=chr(ord(substr($data,$i,1))-ord(substr($char,$i,1)));
    			}
    		}
            $charx = base64_decode($charx);
        }
		return $charx;
	}
	
	public function encrypt($data){
        $char = "";
        $charx = "";
		$x=0;
        
        if(strlen($data)>0){
            $key=md5($this->_key);
            $data=base64_encode($data);
        
    		for ($i=0;$i<strlen($data);$i++)
    		{
    			if ($x==strlen($key)) $x=0;
    			$char.=substr($key,$x,1);
    			$x++;	
    		}
            
    		for ($i=0;$i<strlen($data);$i++)
    		{
    			$charx.=chr(ord(substr($data,$i,1))+(ord(substr($char,$i,1)))%256);	
    		}
            $charx = base64_encode($charx);
        }
		return $charx;
	}
    
    public function password($data){
        $result = "";
        if(strlen($data)>0){
            $result = $this->encrypt($data);
        }
        return $result;
    }
    
    public function decryptpassword($data){
        $result = "";
        if(strlen($data)>0){
            $result = $this->decrypt($data);
        }
        return $result;
    }
    
    public function oldpassword( Zend_Db_Adapter_Abstract $adapter, $data ){
        $result = "";
        $adapter->setFetchMode(Zend_Db::FETCH_ASSOC);
        
        if(strlen($data)>0){
            $sql = "SELECT OLD_PASSWORD(".$adapter->quote($data).") AS `MyPassword` ";
            $rs = $adapter->fetchAll($sql);
            
            foreach($rs as $row){
                $result = $row['MyPassword'];
            }
        }
        return $result;
    }
    
}

