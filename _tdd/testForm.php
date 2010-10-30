<?php
require_once('./../field/Field.php');
require_once('./../field/Date.php');
require_once('./../field/Textarea.php');
require_once('./../field/FieldGroup.php');
require_once ('./../validator/LengthValidator.php');
require_once ('./../validator/NumericValidator.php');
require_once('./../form.class.php');

//$form = new JSForm('helloWorldForm');

// we need some serverside tests -> use the non - js form:
$form = new Form('helloWorldForm');

$textarea = new TextArea('Text','Zwischen 5 und 15 Zeichen','');
$textarea->addValidator(new LengthValidator(5,15));

//$form->addField(new Field('textfeld', 'Bitte Text eingeben', 'hello World'));
$form->addField($textarea);

$intField = new Field("intfeld", "Bitte Integer eingeben", "hallo Integer");
$intField->addValidator(new IntValidator());

$form->addField(new Field('textfeld', 'Bitte Text eingeben', 'hello World'))
	->addField(new DateField('datumsfdeld','Bitte Datum eingeben', '17.03.1980'))
	->addField($textarea)
	->addField(new FieldGroup('test','Bitte Auswahl treffen
	', array('Bitte wählen' => '', 1 => 1, 2 => 2, 3 => 3)))
	->addField(new Radio('testr','testr', array(1 => 1, 2 => 2, 3 => 3)))
	->addField($intField);
	
	
	if ($form->isSubmitted()) {
			$form->fillFromRequest();
			$isOk = $form->validate();
			if ($isOk) {
				// $form->toStorageFormat();
				
				echo "Vielen Dank für Ihre Angaben.";
			}
	}
	
	$form = new CinForm();
	$form->setConfig(getenv('DOCUMENT_ROOT') . '/Form/config/formConfig.xml');
	
	$form->render();
	
//include('testForm_tpl.php');


