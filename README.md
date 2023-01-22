# PHPSmartText-A-PHP-library-for-text-classification-and-generatio
PHP Smart Text is a simple and lightweight machine learning and teachable model library in PHP, and uses MySQL database. PHP Smart Text is free and open-source.

PHP Smart Text can be used to classify or generate text, which can be used to make AI chatbots, detect spam, detect fake news, generate articles, poems, and more.

You can create as many models as you like, and teach every model with different text inputs.

For a more accurate text classification, please train the model with an equal amount of data for each classification, for example:
If you have two classification tags: "Spam" and "Not Spam", you need to train the model with a roughly equal amount of data classified as "Spam", and data classified as "Not Spam".
If for example, you trained the model with 1000 "Spam" data and 500 "Not Spam" data, the results can be very inaccurate.

To run the code on a local computer, you need a local server that supports PHP and MySQL, if you don't have a server, you can check XAMPP server. It's free and open source.

During development, and while training the models, you can run http://localhost/PHPSmartText for training and testing models through GUI if you like, when deploying into production, you only need the library.php file (Which includes the class and all the functions) and the database with the trained models. Run ai_datasets.sql to create the database, and include library.php from your code, afterwards, create an instance of the class:
``` $php_smart_text = new PHPSmartText(); ```

The connect the instance to the database: (Replace with your own credintials)
``` $php_smart_text->con = new mysqli("localhost","root","","ai_datasets"); ```

These are the list of functions:

```
$php_smart_text->create_model($model_name); /* Use this function to create a new model */

$php_smart_text->teach_model($model_name, $input_text, $input_keywords, $input_class); /* Use this function to teach the model with the $input_text, you can add empty strings to $input_keywords and $input_class if you aren't using them */

$php_smart_text->delete_model($model_name); /* Use this function to delete a model, among with all it's deleted data and categories.*/

$php_smart_text->add_or_update_category($model_name, $category_name, $possible_outputs); /* Use this function to add or update a category to a model */

$php_smart_text->delete_category($category_id); /* Use this function to delete a category*/

$php_smart_text->classify_text($model_name, $input); /* Use this function to classify the text in the variable $input */

$php_smart_text->generate_text($model_name, $input, $generator_type); /* Use this function to generate text, optionally add an input, and set $generator_type to either 1 or 2.
1 stands for: Simple(Whole content), use this if you want to get teached text as-is, based on $input, like if you are building a chatbot.
2 stands for: Advanced(Word-to-word), use this if you want to generate text completely, like if you are building an article generator */
```
