<?php
class PHPSmartText
{
    public $con;
    
    function create_model($model_name)
    {
        $con=$this->con;
        $response="";
        if(empty($model_name))
        {
            $response="Please type a name for the model";
            
        }
        elseif(!ctype_alnum($model_name))
        {
            $response="Invalid model name, model name can only contain alphanumeric characters";
            
        }
        else
        {
            $stmt=$con->prepare("INSERT INTO models_list (model_name) VALUES (?)");
            $stmt->bind_param("s",$model_name);
            if(!$stmt->execute())
            {
                if($con -> error=="Duplicate entry '".$model_name."' for key 'PRIMARY'")
                {
                    $response="Model '".$model_name."' already exists";
                }
                else
                {
                    $response=$con -> error;   
                }
            }
            if($stmt->affected_rows==1)
            {
                $response = "Success";
            }
            $stmt->close();
        }
        return $response;
    }
    function teach_model($model_name,$input_text,$input_keywords,$input_class)
    {
        $con=$this->con;
        if(empty($input_keywords))
        {
            $input_keywords="";
        }
        if(empty($input_class))
        {
            $input_class="";
        }
        $response="";
        if(empty($model_name))
        {
            $response="Error: No model selected";
            
        }
        elseif(!ctype_alnum($model_name))
        {
            $response="Invalid model name, model name can only contain alphanumeric characters"; 
        }
        elseif(empty($input_text))
        {
            $response="Please type input text"; 
        }
        elseif(strlen($input_text)>2048)
        {
            $response="Input text is too large, maximum length is 2048 characters"; 
        }
        elseif(strlen($input_keywords)>255)
        {
            $response="Input keywords text is too large, maximum length is 255 characters"; 
        }
        elseif(strlen($input_class)>64)
        {
            $response="Input type text is too large, maximum length is 64 characters"; 
        }
        else
        {
            $stmt=$con->prepare("SELECT model_name from models_list where model_name=?");
            $stmt->bind_param("s",$model_name);
            $stmt->execute();
            $stmt->store_result();
            $num_rows=$stmt->num_rows();
            $stmt->close();
            if($num_rows==0)
            {
                $response="Error: model name doesn't exist";         
            }
            else
            {
                $stmt=$con->prepare("INSERT INTO models_datasets (model_name,dataset_text,dataset_keywords,dataset_classification) VALUES (?,?,?,?)");
                $stmt->bind_param("ssss",$model_name,$input_text,$input_keywords,$input_class);
                if(!$stmt->execute())
                {
                    $response=$con -> error; 
                }
                if($stmt->affected_rows==1)
                {
                    $response = "Success";
                }
                $stmt->close();
            }
        }
        return $response;
    }
    function delete_model($model_name)
    {
        $con=$this->con;
        $response="";
        if(empty($model_name))
        {
            $response="Error: No model selected";
            
        }
        elseif(!ctype_alnum($model_name))
        {
            $response="Invalid model name, model name can only contain alphanumeric characters";
            
        }
        else
        {
            $stmt=$con->prepare("DELETE FROM models_list WHERE model_name=?");
            $stmt->bind_param("s",$model_name);
            if(!$stmt->execute())
            {
                $response=$con -> error;   
            }
            $stmt->close();
            
            $stmt=$con->prepare("DELETE FROM models_datasets WHERE model_name=?");
            $stmt->bind_param("s",$model_name);
            if(!$stmt->execute())
            {
                $response=$con -> error;   
            }
            $stmt->close();
            
                    $stmt=$con->prepare("DELETE FROM dynamic_categories WHERE category_model=?");
            $stmt->bind_param("s",$model_name);
            if(!$stmt->execute())
            {
                $response=$con -> error;   
            }
            $stmt->close();
        }
        if($response=="")
        {
            $response="Success";
        }
        return $response;
    }
    function add_or_update_category($model_name,$category_name,$possible_outputs)
    {
        $con=$this->con;
        $response="";
        if(empty($model_name))
        {
            $response="Please type a name for the model";
        }
        elseif(!ctype_alnum($model_name))
        {
            $response="Invalid model name, model name can only contain alphanumeric characters";
            
        }
        elseif(empty($category_name))
        {
            $response="Please type a category name";
            
        }
        elseif(!ctype_alnum($category_name))
        {
            $response="Invalid category name, category name can only contain alphanumeric characters"; 
        }
        elseif(strlen($category_name)>20)
        {
            $response="Category name is too long, maximum length is 20 characters"; 
        }
        elseif(empty($possible_outputs))
        {
            $response="Please type possible outputs";
        }
        elseif(strlen($possible_outputs)>1024)
        {
            $response="Possible outputs are too long, maximum length is 1024 characters"; 
        }
        else
        {
            $stmt=$con->prepare("UPDATE dynamic_categories SET category_model=?, category_name=?, category_words=? WHERE category_model=? AND category_name=?");
            $stmt->bind_param("sssss",$model_name,$category_name,$possible_outputs,$model_name,$category_name);
            if(!$stmt->execute())
            {
                $response = $con -> error;
            }
            if($stmt->affected_rows!=0)
            {
                $response = "Success";
            }
            else
            {
                $stmt2=$con->prepare("INSERT INTO dynamic_categories (category_model, category_name, category_words) VALUES (?,?,?)");
                $stmt2->bind_param("sss",$model_name,$category_name,$possible_outputs);
                if(!$stmt2->execute())
                {
                    $response = $con -> error;
                }
                if($stmt2->affected_rows==1)
                {
                    $response = "Success";
                }
                $stmt2->close();
            }
            $stmt->close();
        }
        return $response;
    }
    function delete_category($category_to_delete)
    {
        $con=$this->con;
        $response="";
        if(empty($category_to_delete))
        {
            $response="Error: Empty category ID";
            
        }
        elseif(!is_numeric($category_to_delete))
        {
            $response="Invalid catgory ID";
        }
        else
        {
            $stmt=$con->prepare("DELETE FROM dynamic_categories WHERE category_id=?");
            $stmt->bind_param("s",$category_to_delete);
            if(!$stmt->execute())
            {
                $response=$con -> error;   
            }
            $stmt->close();
        }
        if($response=="")
        {
            $response="Success";
        }
        return $response;
    }
    
    function classify_text($model_name,$input)
    {
        $con=$this->con;
        $result="";
        $input = preg_replace('!\s+!', ' ', $input);
        $input = trim($input);
        $words = explode(" ", $input);
        $ids = array();$dates=array();
        $stmt=$con->prepare("SELECT dataset_id FROM models_datasets WHERE model_name=? and dataset_text LIKE ? ORDER BY CHAR_LENGTH(dataset_text)");
        foreach($words as $word)
        {
        	if(strlen($word)>2)
        	{
        	    $like="%".$word."%";
        	    $stmt->bind_param("ss",$model_name,$like);
        	    $stmt->execute();echo $con -> error;
        	    $stmt->store_result();
        	    $stmt->bind_result($text);
        	    while($stmt->fetch())
        	    {
        	    	$ids[]=$text;
        	    }
        	}
        } 
        $arx=array_count_values($ids);
        if(empty($ids)){$result="(No classification found)";}
        else
        {
            $maxs = array_keys($arx, max($arx));
            
            $wordsx=$maxs[0];	
            	
            $stmt2=$con->prepare("SELECT dataset_classification FROM models_datasets where dataset_id=? limit 1");
            $stmt2->bind_param("s",$wordsx);
            $stmt2->execute();
            $stmt2->store_result();
            $stmt2->bind_result($result);
            $stmt2->fetch();
            	
            $stmt2->close();	
        
        }	
        
        $stmt->close();	
        return $result;
    }
    
    function generate_text($model_name,$input,$generator_type)
    {
        $con=$this->con;
        $insufficent_with_keywords=false;
        if(empty($model_name))
        {
            $generated_text="(Error: please select a model)";
        }
        elseif(strlen($input)>255)
        {
            $generated_text="(Error: input is too long. max length is 255 characters)";
        }
        else
        {
            if($generator_type !=1 && $generator_type != 2 && $generator_type !="1" && $generator_type != "2")
            {
                $generator_type="1";
            }
            $generated_text="";
            $samples=array();
            if($input != "")
            {
                $input = preg_replace('!\s+!', ' ', $input);
                $input = preg_replace("/[^A-Za-z0-9 ]/", ' ', $input);
                $input = trim($input);
                $words = explode(" ", $input);
                $ids = array();$dates=array();
                $stmt=$con->prepare("SELECT dataset_text FROM models_datasets WHERE model_name=? and dataset_keywords LIKE ? ORDER BY CHAR_LENGTH(dataset_keywords)");
                foreach($words as $word)
                {
                	if(strlen($word)>2)
                	{
                	    $like="%".$word."%";
                	    $stmt->bind_param("ss",$model_name,$like);
                	    $stmt->execute();echo $con -> error;
                	    $stmt->store_result();
                	    $stmt->bind_result($text);
                	    while($stmt->fetch())
                	    {
                	    	$ids[]=$text;
                	    }
                	}
                } 
                $stmt->close();
                $arx=array_count_values($ids);
                if(empty($ids))
                {
                    $insufficent_with_keywords=true;
                }
                else
                {
                    $maxs = array_keys($arx, max($arx));
                    
                    // $wordsx=$maxs[0];
                    foreach($maxs as $mx)
                    {
                        $sample_instance=str_replace("’","'",$mx);
                        $sample_instance=str_replace("“","\"",$sample_instance);
                        $sample_instance=str_replace("”","\"",$sample_instance);     
                        $sample_instance=str_replace("\n\n","\n",$sample_instance);
                        $sample_instance=str_replace("\r\n\r\n","\r\n",$sample_instance);
                        $sample_instance=str_replace("\r\n\r\n","\r\n",$sample_instance);
                        $sample_instance=str_replace("(","",$sample_instance);
                        $sample_instance=str_replace(")","",$sample_instance);
                        $samples[] = explode(" ",strtolower($sample_instance));
                    }
                }
    	    }
            if($input == "" || $insufficent_with_keywords)
            {
                $stmt=$con->prepare("SELECT dataset_text FROM models_datasets WHERE model_name=?");
                $stmt->bind_param("s",$model_name);
                $stmt->execute();
                echo $con -> error;
                $stmt->store_result();
                $stmt->bind_result($sample_instance);
                while($stmt->fetch())
                {
                     $sample_instance=str_replace("’","'",$sample_instance);
                     $sample_instance=str_replace("“","\"",$sample_instance);
                     $sample_instance=str_replace("”","\"",$sample_instance);     
                     $sample_instance=str_replace("\n\n","\n",$sample_instance);
                     $sample_instance=str_replace("\r\n\r\n","\r\n",$sample_instance);
                     $sample_instance=str_replace("\r\n\r\n","\r\n",$sample_instance);
                     $sample_instance=str_replace("(","",$sample_instance);
                     $sample_instance=str_replace(")","",$sample_instance);
                     $samples[] = explode(" ",strtolower($sample_instance));
                }
                $stmt->close();
            }
            $shuffler=array();
            
            $samples_amount=count($samples);
            for($i=0;$i<$samples_amount;$i++)
            {
                $shuffler[]=$i;
            } 
            $words=array();
            $next_word="";
            $rand = rand(0,$samples_amount-1);
            if($generator_type=="2" || $generator_type==2)
            {
                if(isset($samples[$rand][0]))
                {
                    $current_word=$samples[$rand][0];   
                }
                else {$current_word="Unknown";}
                $prev_word=$current_word;
                $words[]=$current_word;
                $nextfound=false;
                $twowords=array("and","or","a","an","the","to","for","of","i","you","he","she","we","they","it","on","in","at");
                
                // foreach($shuffler as $ss){echo $ss." ";}
                // Get next word
                $length=30;
                
                for($i=0;$i<$length;$i++) 
                {
                    $nextfound=false;
                    shuffle($shuffler);
                    foreach($shuffler as $sample_index)
                    {
                        if (in_array($current_word, $samples[$sample_index]))
                        {
                            $key = 'a';
                            $array = array('a', 'b', 'a');
                            $found = array_keys($samples[$sample_index], $current_word);
                            shuffle($found);
                            // print_r($found); // Array ( [0] => 0 [1] => 2 )
                            
                            foreach($found as $f)
                            {
                                if(isset($samples[$sample_index][$f+1]))
                                {
                                    $next_word = $samples[$sample_index][$f+1];
                                    $proceed=true;
                                    if(rand(0,3)!=3)
                                    {
                                        if($next_word==$prev_word) 
                                        {
                                            $proceed=false;
                                        }
                                    }
                                    
                                    if($proceed)
                                    {
                                        $words[]=$next_word;
                                        $nextfound=true;
                                        $prev_word=$current_word;
                                        $current_word=$next_word;
                                        
                                        if (in_array($next_word, $twowords) || rand(1,2)==2)
                                        {
                                            if(isset($samples[$sample_index][$f+2]))
                                            {
                                                $next_word = $samples[$sample_index][$f+2];
                                                $words[]=$next_word;
                                                $nextfound=true;
                                                $prev_word=$current_word;
                                                $current_word=$next_word;
                                                
                                                if (rand(1,3)==3)
                                                {
                                                    if(isset($samples[$sample_index][$f+3]))
                                                    {
                                                        $next_word = $samples[$sample_index][$f+3];
                                                        $words[]=$next_word;
                                                        $nextfound=true;
                                                        $prev_word=$current_word;
                                                        $current_word=$next_word;
                                                    }
                                                }
                                                
                                            }
                                        }
                                        break;
                                    }
                                }
                                if($nextfound){break;}
                            }
                        }
                        if($nextfound){break;}
                    }
                }
                
            }
            else
            {
                if(isset($samples[0]))
                {
                    shuffle($samples);
                    $words=$samples[0];   
                }            
            }
            
            $categories=array();
            $stmt=$con->prepare("SELECT category_name, category_words FROM dynamic_categories WHERE category_model=?");
            $stmt->bind_param("s",$model_name);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($c_name,$c_words);
            while($stmt->fetch())
            {
               $c_name=strtolower($c_name);
               $categories["[".$c_name."]"] = explode(",",$c_words);
            }
            $stmt->close();
                
            foreach($words as $word) 
            {
                $word = @preg_replace_callback('/\[(.*?)\]/', function($matches) use ($categories) 
                {
                    return $categories[$matches[0]][array_rand($categories[$matches[0]])];
                }, $word);
                
                $word = str_replace("\n","<br>",$word);
                $generated_text = $generated_text.$word." ";
            } 
            return $generated_text;   
        }
    }
 }   
?>
