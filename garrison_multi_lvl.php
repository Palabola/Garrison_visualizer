<?php

include_once './database_functions.php';


   $res3 = $LOC_DB->prepare("SELECT * FROM `gameobject_template` WHERE `type` = 38  ORDER BY `name` ASC;");
    $res3->execute();     

    
      if ($res3->rowCount() > 0) //Van Result
        {

          
            while($result_row = $res3->fetch())
            {
                
              $gob_map = $result_row['Data0'];  
              $gob_id = $result_row['entry'];  
              
              $name = explode(" ",$result_row['name']);
              
              if($result_row['Data0']>1000 && isset($name[3]))
              {   
               $res5 = $LOC_DB->prepare("SELECT * FROM `creature` WHERE `map` = :map;");
               $res5->bindValue(':map', $gob_map, PDO::PARAM_INT);
                $res5->execute();  
                
                  $res7 = $LOC_DB->prepare("SELECT * FROM `gameobject` WHERE `map` = :map;");
                    $res7->bindValue(':map', $gob_map, PDO::PARAM_INT);
                     $res7->execute();    
                
                     
                if($res7->rowCount()>0)
                {
                    $script_go = "";
                     $go_text = "";
                     
                     while($result_row2 = $res7->fetch())
                           {
                         
                             $go_text.= "GO:  ".$result_row2['id']." (X: ".$result_row2['position_x']."  , Y: ".$result_row2['position_y'].") <br> ";
                             
                             $x = 200-round($result_row2['position_x']*10);
                             $y = 200-round($result_row2['position_y']*10);
                             
                             $script_go.='ctx.fillText("'.$result_row2['id'].'",'.$y.'+10,'.$x.'+8);
                                      ctx.fillRect('.$y.','.$x.',5,5); ';
                           }  
                }   
                     
                     
                     
                if($res5->rowCount()==0)
                {
                   echo "EMPTY -- ".$gob_map.", ".$result_row['name']."<br>";   
                } 
                else
                {
                   echo $gob_map.", ".$result_row['name']."<br>";
                   
                      echo'<canvas id="'.$gob_map.'" width="400" height="400" style="border:1px solid #d3d3d3;">
                            Your browser does not support the HTML5 canvas tag.</canvas> <p></p>';
                   
                        $script = '<script>
                                    var c = document.getElementById("'.$gob_map.'");
                                    var ctx = c.getContext("2d");
                                    ctx.moveTo(0,200);
                                    ctx.lineTo(400,200); 
                                    ctx.stroke();
                                    ctx.moveTo(200,0);
                                    ctx.lineTo(200,400); 
                                    ctx.stroke();
                                    ctx.fillRect(195,0,10,10);
                                    ctx.fillText("X +",180,20);
                                    ctx.fillText("Y +",0,190);
                                    ctx.fillText("10",180,100);
                                    ctx.fillText("-10",180,300);
                                    ctx.fillText("10",100,190);
                                    ctx.fillText("-10",300,190);
                                    ctx.font = "15px Arial";';
                        
                            $x = 0;
                            $y =0;
                        
                           while($result_row = $res5->fetch())
                           {
                             echo "<a target='_blank' href='http://www.wowhead.com/npc=".$result_row['id']."'> NPC : ".$result_row['id']." </a> (X: ".$result_row['position_x']."  , Y: ".$result_row['position_y'].") <br> ";  
                             
                             $x = 200-round($result_row['position_x']*10);
                             $y = 200-round($result_row['position_y']*10);
                             
                             $script.='ctx.fillText("'.$result_row['id'].'",'.$y.'+10,'.$x.'+8);
                                      ctx.fillRect('.$y.','.$x.',5,5); ';
                           }
                           
                           echo $go_text;
                           
                           $script.=$script_go;
                           
                           $script.='</script>';
                           
                           echo $script;
                           
                           echo "<br><hr><br>";   
                }    
                 

                 
                 
              }
            }       
            unset($name);
        }
        else 
        {
         echo "Too much!";
         exit;
        }

       echo $garrison_spawn;


        

        
        
        
