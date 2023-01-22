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
        <title>PHP Smart Text - Admin</title>
        <style>
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
            
            #input-text {
                width: 96%;
                resize: vertical;
            }
            #input-class, #input-keywords {
                width: 96%;
                display: block;
            }
            
            .button-blue {
                display: block;
                margin-top: 6px;
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
                    <h3 class="mb">Create a new model</h3>
                    <p class="small gray-text mb">&#128712; Please use alphanumeric characters and no spaces</p>
                    <input type="text" name="model-name" class="textbox-round-rect-gray" maxlength="255" placeholder="Model name" required>
                    <p class="small red-text mb"><?php echo $create_model_error; ?></p>
                    <input type="submit" name="create-model" class="button-blue" value="Create" >
                </form>
            </div>
            <div class="light-gray border-gray rounded padding mb">
                <form method="post" action="" style="margin: 0;">
                    <h3 class="mb">Teach a model</h3>
                    <span class="small">Select model</span>
                    <select name="model-to-teach" size="1" class="select-round-rect-gray mb" name="select-model" required>
                        <?php 
                        foreach($models as $model)
                        { ?>
                        <option value="<?php echo $model; ?>"<?php if($model==$previous_model){echo " selected";} ?>><?php echo $model; ?></option>
                        <?php ;} ?>
                    </select>
                    <p class="small gray-text mb">&#128712; Make sure to use correct punctuation and grammar for better results.<br>To add a dynamic category field, add "[]" brackets around the field.<br>Example input: Today [name] ate [fruit], example output: Today Sarah ate oranges</p>
                    <textarea name="input-text" id="input-text" class="textbox-round-rect-gray" maxlength="2048" placeholder="Text to teach" required></textarea>
                    <input name="input-class" id="input-class" type="text" class="textbox-round-rect-gray mb" maxlength="64" placeholder="Type (Optional, for classification)" />
                    <input name="input-keywords" id="input-keywords" type="text" class="textbox-round-rect-gray" maxlength="255" placeholder="Keywords (Optional, for getting output based on input)" />
                    <p class="small red-text mb"><?php echo $teach_model_error; ?></p>
                    <input type="submit" name="teach-model" class="button-blue" value="Submit" />
                </form>
            </div>
                <div class="light-gray border-gray rounded padding mb">
                    <form onSubmit="if(!confirm('Are you sure you want to delete this model and all the data associated with it?')){return false;}" method="post" action="" style="margin: 0;">
                        <h3 class="mb">Delete a model</h3>
                        <span class="small">Select model</span>
                        <select name="model-to-delete" size="1" class="select-round-rect-gray mb" name="delete-model" required>
                            <?php 
                            foreach($models as $model)
                            { ?>
                            <option value="<?php echo $model; ?>"><?php echo $model; ?></option>
                            <?php ;} ?>
                        </select>
                        <p class="small red-text mb"><?php echo $delete_model_error; ?></p>
                        <input name="delete-model" type="submit" class="button-blue" value="Delete" >
                    </form>
                </div>
            </div>
    </body>
</html>