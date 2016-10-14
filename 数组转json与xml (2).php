<?php

class response{
//    const JSON='json';
    public static function show($code ,$message='',$data=array(),$type){

        if(!is_numeric($code)){
            echo 'wrong code';
            return '';

        }
        $distribute=array(
            'code'=>$code,
            'message'=>$message,
            'data'=>$data
        );
        if($type=='json'){
            echo '<pre>';
            self::json($distribute);
            exit;
        }elseif($type=="array"){
            echo '<pre>';
            var_dump($distribute);
            
        }elseif($type=='xml'){
            self::Encode($distribute);
            exit;
    }else{
        //
    }
}
    public static function Encode($distribute){

        header("Content-Type:text/xml");
        $xml="<?xml version='1.0' encoding='UTF-8'?>\n";
        $xml.="<root>\n";
        $xml.=self::xmlToEncode($distribute);
        $xml.="</root>";
        echo $xml;

    }
   
    public static function xmlToEncode($distribute){
        $xml=$attr="";
        foreach($distribute as $key=>$value){
            if(is_numeric($key)){
                $attr="id = '{$key}'";
                $key="item ";
            }
            $xml.="<{$key} {$attr}>";
            $xml.=is_array($value)?self::xmlToEncode($value):$value;
            $xml.="</{$key}>\n";
        } 
        return $xml;
    }
    
    public static function json($distribute){
        echo 'json调用成功';
      if(!is_numeric($distribute['code'])){
            return false;
      }
      
      $result= json_encode($distribute['data']);
      echo $result;
      return $result;
    }
}
 