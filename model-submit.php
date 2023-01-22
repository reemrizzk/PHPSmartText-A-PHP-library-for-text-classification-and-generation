<?php
$previous_model="";

$create_model_error="";
$teach_model_error="";
$delete_model_error="";

$add_category_error="";
$delete_category_error="";

$generated_text="";
$text_classification="";
$previous_input="";
$previous_type="";



if(isset($_POST['create-model']))
{
    $model_name=$_POST['model-name'];
    $response=$php_smart_text->create_model($model_name);
    if($response != "Success")
    {
        $create_model_error=$response;
    }
}

if(isset($_POST['teach-model']))
{
    $model_name=htmlentities($_POST['model-to-teach']);
    $previous_model=$model_name;
    $input_text=htmlentities($_POST['input-text']);
    $input_class=htmlentities($_POST['input-class']);
    $input_keywords=htmlentities($_POST['input-keywords']);
    
    $response=$php_smart_text->teach_model($model_name,$input_text,$input_keywords,$input_class);
    if($response != "Success")
    {
        $teach_model_error=$response;
    }
}

if(isset($_POST['delete-model']))
{
    $model_name=htmlentities($_POST['model-to-delete']); 
    $response=$php_smart_text->delete_model($model_name);
    if($response != "Success")
    {
        $delete_model_error=$response;
    }
}

if(isset($_POST['add-or-update-category']))
{
    $category_name=htmlentities($_POST['category-name']); 
    $model_name=htmlentities($_POST['category-model']); 
    $previous_model=$model_name;
    $possible_outputs=htmlentities($_POST['possible-outputs']); 
    $response=$php_smart_text->add_or_update_category($model_name,$category_name,$possible_outputs);
    if($response != "Success")
    {
        $add_category_error=$response;
    }
}

if(isset($_GET['del']))
{
    $category_to_delete=htmlentities($_GET['del']); 
    $response=$php_smart_text->delete_category($category_to_delete);
    if($response != "Success")
    {
        $delete_category_error=$response;
    }
}

if(isset($_POST['classify-text']))
{
    $model_name=htmlentities($_POST['classifier-model']);
    $previous_model=$model_name;
    if(empty($_POST['text-to-classify']))
    {
        $input_text="";
    }
    else 
    {
        $input_text=htmlentities($_POST['text-to-classify']);
    }
    $text_classification=$php_smart_text->classify_text($model_name,$input_text);
}


if(isset($_POST['generate-text']))
{
    $model_name=htmlentities($_POST['generator-model']);
    $previous_model=$model_name;
    if(empty($_POST['generator-input']))
    {
        $input_text="";
    }
    else 
    {
        $input_text=htmlentities($_POST['generator-input']);   
    }
    $previous_input=$input_text;
    $generator_type=htmlentities($_POST['generator-type']);
    if($generator_type==2 || $generator_type=="2")
    {
        $previous_type="2";
    }
    $generated_text=$php_smart_text->generate_text($model_name,$input_text,$generator_type);
}

?>