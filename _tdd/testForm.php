<?php
require_once('./../field/Field.php');
require_once('./../field/Date.php');

require_once('./../form.class.php');

$form = new Form('helloWorldForm');
$form->addField(new Field('helloWorld', 'hello World', 'hello World'))
->addField(new DateField('Datum','Bitte Datum eingeben', '17.03.1980'));


echo $form->display();
