<?php
require_once "library.php";

$php_smart_text = new PHPSmartText();

require_once "connect.php";
require_once "model-submit.php";

$models=array();
$con = $php_smart_text->con;
$stmt=$con->prepare("SELECT model_name FROM models_list");
if(!$stmt->execute())
{
    die(mysqli_error($con));
}
$stmt->store_result();
$stmt->bind_result($model);
while($stmt->fetch())
{
    $models[] = $model;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Website description here">
        <meta name="keywords" content="website,keywords,here">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href='http://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Open Sans:600' rel='stylesheet' type='text/css'>
        <script src="js/jquery.min.js"></script>
        <link rel="stylesheet" href="css/main.css">
        <script src="js/main.js"></script>
        <title>PHP Smart Text - Test</title>
        <style>
            #generator-input {
                display: block;
                width: 96%;
            }
            .textbox-round-rect-gray {
                padding: 8px;
                border: 1px solid #BBBBBB;
                border-radius: 4px;
                outline: none;
                webkit-appearance: none;
            }
            .textbox-round-rect-gray:focus {
                border-color: #2090EE;
            }
            #text-to-classify {
                width: 96%;
                resize: vertical;
            }
            
            #input-text {
                width: 96%;
            }
            
            .button-blue {
                display: block;
                border-radius: 2px;
                background-color: #2090EE;
                color: #FFFFFF;
                cursor: pointer;
                padding: 7px 20px;
                border-style: none;
                webkit-appearance: none;
            }
            .button-blue:hover {
                opacity: 0.95;
            }
            .select-round-rect-gray {
                border: 1px solid #BBBBBB;
                border-radius: 2px;
                outline: none;
                webkit-appearance: none;
                padding: 0;
                margin-top: 0;
                min-width: 60px;
            }
            .select-round-rect-gray:focus {
                border-color: #2090EE;
            }
            
            .blue {
                color: #2090EE;
            }
            .green {
                color: #008000;
                font-style: Italic;
            }
        </style>
    </head>
    <body>
        <?php require_once "header.php"; ?>
        <div class="column">
            <div class="light-gray border-gray rounded padding mb">
                <?php require_once "info.php"; ?>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="light-gray border-gray rounded padding mb">
                <form method="post" action="" style="margin: 0;">
                    <h3 class="mb">Classify text</h3>
                    <span class="small">Model</span>
                    <select name="classifier-model" size="1" class="select-round-rect-gray mb" name="select-model" required>
                        <?php 
                        foreach($models as $model)
                        { ?>
                        <option value="<?php echo $model; ?>"<?php if($model==$previous_model){echo " selected";} ?>><?php echo $model; ?></option>
                        <?php ;} ?>
                    </select>
                    <textarea name="text-to-classify" id="text-to-classify" class="textbox-round-rect-gray" maxlength="255" placeholder="Text to classify"></textarea>
                    <input type="submit" name="classify-text" class="button-blue mb" value="Classify" />
                </form>
                <h3 class="mb">Most likely classification: </h3>
                <p><?php echo $text_classification; ?></p>
            </div>
            <div class="light-gray border-gray rounded padding mb">
                <form method="post" action="" style="margin: 0;">
                    <h3 class="mb">Generate text</h3>
                    <span class="small">Model</span>
                    <select name="generator-model" size="1" class="select-round-rect-gray mb" name="select-model" required>
                        <?php 
                        foreach($models as $model)
                        { ?>
                        <option value="<?php echo $model; ?>"<?php if($model==$previous_model){echo " selected";} ?>><?php echo $model; ?></option>
                        <?php ;} ?>
                    </select>
                    <input name="generator-input" id="generator-input" type="text" class="textbox-round-rect-gray mb" maxlength="255" placeholder="Input (Optional)" value="<?php echo $previous_input; ?>" />
                    <span class="small">Algorithm type</span>
                        <select name="generator-type" size="1" class="select-round-rect-gray mb" name="generator-type" required>
                        <option value="1">Simple (Whole content)</option>
                        <option value="2"<?php if($previous_type=="2"){echo " selected";} ?>>Advanced (Word-to-word)</option>
                    </select>
                    <input type="submit" name="generate-text" class="button-blue mb" value="Generate" />
                </form>
                <h3 class="mb">Output: </h3>
                <p><?php echo $generated_text; ?></p>
            </div>
        </div>
        <?php /* require_once "ai.php"; */ ?>
    </body>
</html>