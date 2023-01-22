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
            // body {background:#505050;}
            #categories-table-wrapper {
                width: 96%;
                overflow: auto;
            }
            #categories-table {
                border-collapse: collapse;
                width: 100%;
            }
            #categories-table th {
            	background-color: #2090EE;
            	color: #FFFFFF;
                font-weight: normal;
                font-size: 14px;
                padding: 4px;
                text-align: left;
            }
            #categories-table td {
            	color: #404040;
                font-size: 12px;
                padding: 4px;
            }
            #categories-table tr:nth-child(even){
                background-color: #E9E9E9;
            }
            #possible-outputs {
                display: block;
                width: 96%;
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
        <div id="category-form" class="column">
            <div class="light-gray border-gray rounded padding mb">
                <form method="post" action="" style="margin: 0;">
                    <h3 class="mb">Add or update a category</h3>
                    <span class="small">Select model</span>
                    <select name="category-model" id="category-model" size="1" class="select-round-rect-gray mb" name="select-model" required>
                        <?php 
                        foreach($models as $model)
                        { ?>
                        <option value="<?php echo $model; ?>"><?php echo $model; ?></option>
                        <?php ;} ?>
                    </select>
                    <p class="small gray-text mb">&#128712; Please use alphanumeric characters and no spaces</p>
                    <input type="text" id="category-name" name="category-name" class="textbox-round-rect-gray mb" maxlength="20" placeholder="Category name" required>
                    <input type="text" id="possible-outputs" name="possible-outputs" class="textbox-round-rect-gray" maxlength="1024" placeholder="Possible outputs (Separate with comma)" required>
                    <p class="small red-text mb"><?php echo $add_category_error; ?></p>
                    <input type="submit" name="add-or-update-category" class="button-blue" value="Save">
                </form>
            </div>
            <div class="light-gray border-gray rounded padding mb">
                <h3 class="mb">Edit added categories</h3>
                <p class="small red-text mb"><?php echo $delete_category_error; ?></p>
                <div id="categories-table-wrapper">
                    <table id="categories-table" class="mb">
                        <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th>Model</th>
                                <th>Category</th>
                                <th>Possible list</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt=$con->prepare("SELECT category_id, category_model, category_name, category_words FROM dynamic_categories order by category_model asc");
                            $stmt->execute();
                            $stmt->store_result();
                            $stmt->bind_result($c_id,$c_model,$c_name,$c_words);
                            while($stmt->fetch())
                            { ?>
                            <tr>
                                <td>
                                    <a href="#category-form" onClick="$('#category-model').val('<?php echo $c_model; ?>');$('#category-name').val('<?php echo $c_name; ?>');$('#possible-outputs').val('<?php echo $c_words; ?>');"><img alt="Edit" title="Edit" src="images/edit.png"></a>
                                    <a href="javascript:void(0);" onClick="if(confirm('Are you sure you want to delete this category?')){document.location='categories.php?del=<?php echo $c_id; ?>';}"><img alt="Edit" title="Delete" src="images/delete.png"></a>
                                </td>
                                <td><?php echo $c_model; ?></td>
                                <td><?php echo $c_name; ?></td>
                                <td><?php echo $c_words; ?></td>
                            </tr>
                            <?php ;}
                            $stmt->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>