<?php
/******CONVERT SQL TO MONGO***/
class Mongodb 
{
    /*function __construct(){
        $this->actionIndex();
    }*/

    public function create($q){
        $sub=explode(' ',$q);
        if($sub[1]=='table'){
            return 'db.createCollection("'.trim($sub[2],'`').'")';
        }else{
            return 'Syntax error';
        }
    }
private function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
    private function clean($arr,$operation=null){
        if($operation==null){
            $operation=['`',')',';','(',' '];
        }
        foreach($operation as $sym){
            $arr=explode('<>',str_replace($sym,'',implode('<>',$arr)));
        }
        return $arr; 
    }

private function keys($key){
    $k=[];
    $str=$this->get_string_between($key,'(',')');
    $str=explode(',',$str);
    return $this->clean($str);
}
private function values($val){
    $v=[];
    $val=trim($val);
    $va=explode('(',$val);
    //$str=$va;
    foreach($va as $ss){
        if(!empty($ss)){
            $v[]=$ss;
        }
    }
    $v= $this->clean($v);
    foreach($v as $Va){
        $k[]=trim($Va,',');
    }
    //return $k;
    foreach($k as $V){
        $ex[]=explode(',',$V);
    }
    foreach($ex as $fv){
        $fiv[]=$this->clean($fv);
    }
    return $fiv;
}

private function key_val_match($k,$v){
    foreach($v as $fv){
        $value[]=array_combine($k,$fv);
    }
    return $value;
}

    public function insert($tbl,$q){
        $sub=explode(' ',$q);
        $table_name=trim($sub[2],'`');
        $sub2=explode('values',strtolower($q));
        if(sizeof($sub2)>1){
            $key=$sub2[0];
            $values=$sub2[1];
            $key=$this->keys($key);
            $value=$this->values($values);
        }
        //return $values;
        //return $this->key_val_match($key,$value);
        return 'db.'.$tbl.'.insertMany('.json_encode($this->key_val_match($key,$value)).')';
    }
    
function get_tablename($q){
    return $this->clean(explode(' ',$q))[2];
}
    public function query($q)
    {
        //return $this->render('index');
        //$q='create table operate';
        $sub=explode(' ',$q);
        if(strtolower($sub[0])=='create'){
            return $this->create($q);
            //return 'Create command';
        }elseif(strtolower($sub[0])=='update'){
            return 'Update command';
        }elseif(strtolower($sub[0])=='insert'){
           // return $this->get_tablename($q);
            return $this->insert($this->get_tablename($q),$q);
            return 'Insert command';
        }
        else{
            return 'syntax error';
        }

    }

}

$obj = new Mongodb;
echo "<pre>";print_r($obj->query("INSERT INTO `cities` (`id`, `name`, `provinces_id`, `created_at`) VALUES (NULL, 'ravi', '1', CURRENT_TIMESTAMP), (NULL, 'karan', '1', CURRENT_TIMESTAMP);"));echo "</pre>";
