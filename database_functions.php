<?php

try {

  $host = "localhost";  
  $dbname = "623_world";
  $user = "root";
  $pass = "ascent";
    
  # MySQL with PDO_MYSQL
  $LOC_DB = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
 

}
catch(PDOException $e) {
    echo $e->getMessage();
}

   function query_creator_single ($table,$array,$index=0)
        {
       
          $qrystr = "".$table."";
            $qrystr .= " ( " .implode(", ",array_keys($array[$index])).") VALUES <br>";  
            $qrystr .= "('".implode("', '",array_values($array[$index])). "');<br><br>";    

            return $qrystr;
        }    
   
    function creature_addon($old_guid,$new_guid)
    {
        global $LOC_DB,$waypoint_data;
        
            $creature_addon = "";
        
             $res4 = $LOC_DB->prepare("SELECT * FROM `creature_addon` WHERE `guid` = :guid LIMIT 1;");
                 $res4->bindValue(':guid', $old_guid, PDO::PARAM_INT);
                 $res4->execute(); 
            
                 if($res4->rowCount() > 0)
                 {    
                        $result_res4 = $res4->fetchAll(PDO::FETCH_ASSOC);
                     
                         $result_res4[0]['guid'] = $new_guid;
                        
                         if($result_res4[0]['path_id']>0)
                         {
                           $waypoint_data.= creature_waypoint_data($result_res4[0]['path_id'], $new_guid);  
                           $result_res4[0]['path_id'] = $new_guid;
                         }  
                         
                         $creature_addon .= query_creator_single ('REPLACE INTO `creature_addon` ',$result_res4);         
                 }
                 else
                 {
                   return;    
                 }
                 unset($res4);     
        
        return $creature_addon;              
    }    
        
    function creature_waypoint_data($old_guid,$new_guid)
    {
        global $LOC_DB;
        
             $res4 = $LOC_DB->prepare("SELECT * FROM `waypoint_data` WHERE `id` = :guid;");
                 $res4->bindValue(':guid', $old_guid, PDO::PARAM_INT);
                 $res4->execute(); 
              
      if ($res4->rowCount() > 0 && $res4->rowCount() <=43000) //Van Result
        {
        
            $result_row = $res4->fetchAll(PDO::FETCH_ASSOC);
            
            $return = "";
            
            for ($x = 0; $x <= ($res4->rowCount()-1); $x++) {

            if($x==0)       
            {
            $qrystr = "
            <br>
            DELETE FROM waypoint_data where `id` = ".$new_guid.";
            <br>  
            REPLACE INTO waypoint_data";
            $qrystr .= " ( " .implode(", ",array_keys($result_row[$x])).") VALUES <br>";  
            }        
                
            $result_row[$x]['id'] = $new_guid;
            
            $qrystr .= "('".implode("', '",array_values($result_row[$x])). "'),<br>";
            
            if($x == $res4->rowCount()-1)
            {
              $qrystr .= "('".implode("', '",array_values($result_row[$x])). "');<br>"; 
            }
            
          } // For Loop Ended
        }
        else 
        {
         return;   
        }     
        
        return $qrystr;              
    }  
    
       function creature_waypoints($entry)
        {
        global $LOC_DB;
        
             $res4 = $LOC_DB->prepare("SELECT * FROM `waypoints` WHERE `entry` = :guid;");
                 $res4->bindValue(':guid', $entry, PDO::PARAM_INT);
                 $res4->execute(); 
              
      if ($res4->rowCount() > 0 && $res4->rowCount() <=43000) //Van Result
        {
        
            $result_row = $res4->fetchAll(PDO::FETCH_ASSOC);
            
            $return = "";
            
            for ($x = 0; $x <= ($res4->rowCount()-1); $x++) {

            if($x==0)       
            {
            $qrystr = "
            <br>
            DELETE FROM waypoints where `entry` = ".$entry.";
            <br>    
            REPLACE INTO waypoints";
            $qrystr .= " ( " .implode(", ",array_keys($result_row[$x])).") VALUES <br>";  
            }        
                
            $qrystr .= "('".implode("', '",array_values($result_row[$x])). "'),<br>";
            
            if($x == $res4->rowCount()-1)
            {
              $qrystr .= "('".implode("', '",array_values($result_row[$x])). "');<br>"; 
            }
            
          } // For Loop Ended
        }
        else 
        {
         return;   
        }     
        
        return $qrystr;              
    } 
    
    
    function smart_scripts_export($entry,$creature_array)
    {
        global $LOC_DB,$error,$creature_template;
        
             $res4 = $LOC_DB->prepare("SELECT * FROM `smart_scripts` WHERE `entryorguid` = :guid AND source_type = 0;");
                 $res4->bindValue(':guid', $entry, PDO::PARAM_INT);
                 $res4->execute(); 
              
      if ($res4->rowCount() > 0 && $res4->rowCount() <=43000) //Van Result
        {
        
            $result_row = $res4->fetchAll(PDO::FETCH_ASSOC);
            
            $return = "";
            
            for ($x = 0; $x <= ($res4->rowCount()-1); $x++) {

            if($x==0)       
            {
            $qrystr = "
            <br>
            DELETE FROM smart_scripts where `entryorguid` = ".$entry." AND source_type = 0;
            <br>

            REPLACE INTO smart_scripts";
            $qrystr .= " ( " .implode(", ",array_keys($result_row[$x])).") VALUES <br>";  
            }        
                
            
            $error.= "";
            
            if($result_row[$x]['target_type']==10)
                {
                   $error.= "GUID CORRECTION NEEDED ENTRY : ".$entry." , ".$result_row[$x]['target_param1']." <br>"; 
                }
            
            if($creature_template)
            {
                
            } 
                
                
            $result_row[$x]['comment'] = addslashes($result_row[$x]['comment']);
                
                
            $qrystr .= "('".implode("', '",array_values($result_row[$x])). "'),<br>";
            
            if($x == $res4->rowCount()-1)
            {
              $qrystr .= "('".implode("', '",array_values($result_row[$x])). "');<br>"; 
            }
            
          } // For Loop Ended
        }
        else 
        {
         return;   
        }     
        
        return $qrystr;              
    }  
    
    function creature_text($entry) 
    {
        global $LOC_DB;
        
                 $res5 = $LOC_DB->prepare("SELECT * FROM `creature_text` WHERE `entry` = :id ;");
                 $res5->bindValue(':id', $entry, PDO::PARAM_INT);
                 $res5->execute(); 
            
                $qrystr = ""; 
                 
          if ($res5->rowCount() > 0) //Van Result
                {  
              
                   $result_row = $res5->fetchAll(PDO::FETCH_ASSOC);
              
                for ($x = 0; $x <= ($res5->rowCount()-1); $x++) {


            
                            if($x==0)       
                            {
                            $qrystr = "
                            <br>
                            DELETE FROM creature_text where `entry` = ".$entry.";
                            <br>

                            REPLACE INTO creature_text";
                            $qrystr .= " ( " .implode(", ",array_keys($result_row[$x])).") VALUES <br>";  
                            }        

                            $result_row[$x]['text'] = addslashes($result_row[$x]['text']);
                            $result_row[$x]['comment'] = addslashes($result_row[$x]['comment']);
                            
                            
                            $qrystr .= "('".implode("', '",array_values($result_row[$x])). "'),<br>";

                            if($x == $res5->rowCount()-1)
                            {
                              $qrystr .= "('".implode("', '",array_values($result_row[$x])). "');<br>"; 
                            }

                          } // For Loop Ended
                }
             unset($res5);
                
       return $qrystr;          
    }
    

    function creature_equip_template($entry) 
    {
        global $LOC_DB;
        
                 $res5 = $LOC_DB->prepare("SELECT * FROM `creature_equip_template` WHERE `CreatureID` = :id LIMIT 1;");
                 $res5->bindValue(':id', $entry, PDO::PARAM_INT);
                 $res5->execute(); 
            
                 if($res5->rowCount() > 0)
                 {    
                        $result_res5 = $res5->fetchAll(PDO::FETCH_ASSOC);
                     
                         $creature_addon= query_creator_single ('REPLACE INTO `creature_equip_template` ',$result_res5);         
                 }
                  else
                 {
                   return;    
                 }
                 unset($res5);   
                 
       return $creature_addon;          
    }
    
    function creature_template($entry) 
    {
        global $LOC_DB;
        
                 $res5 = $LOC_DB->prepare("SELECT * FROM `creature_template` WHERE `entry` = :id LIMIT 1;");
                 $res5->bindValue(':id', $entry, PDO::PARAM_INT);
                 $res5->execute(); 
            
                 if($res5->rowCount() > 0)
                 {    
                        $result_res5 = $res5->fetchAll(PDO::FETCH_ASSOC);
                        
                        $result_res5[0]['name'] = addslashes($result_res5[0]['name']);
                        $result_res5[0]['subname'] = addslashes($result_res5[0]['subname']);
                        
                         $creature_addon= query_creator_single ('REPLACE INTO `creature_template` ',$result_res5);         

                 }
                  else
                 {
                   return;    
                 }
                 unset($res5);   
                 
       return $creature_addon;          
    }
    
     function creature_addon_template($entry) 
    {
        global $LOC_DB;
        
                 $res5 = $LOC_DB->prepare("SELECT * FROM `creature_template_addon` WHERE `entry` = :id LIMIT 1;");
                 $res5->bindValue(':id', $entry, PDO::PARAM_INT);
                 $res5->execute(); 
            
                 if($res5->rowCount() > 0)
                 {    
                        $result_res5 = $res5->fetchAll(PDO::FETCH_ASSOC);
                     
                         $creature_addon= query_creator_single ('REPLACE INTO `creature_template_addon` ',$result_res5);         
                 }
                  else
                 {
                   return;    
                 }
                 unset($res5);   
                 
       return $creature_addon;          
    }

