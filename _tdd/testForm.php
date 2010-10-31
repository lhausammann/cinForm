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
$form = new Form('helloWorldForm_old');

$textarea = new TextArea('Text','Zwischen 5 und 15 Zeichen','');
$textarea->addValidator(new LengthValidator(5,15));

//$form->addField(new Field('textfeld', 'Bitte Text eingeben', 'hello World'));
$form->addField($textarea);

$intField = new Field("intfeld", "Bitte Integer eingeben", "hallo Integer");
$intField->addValidator(new IntValidator());

$form->addField(new Field('textfeld', 'Bitte Text eingeben', 'hello World'))
	->addField(new DateField('datumsfeld','Bitte Datum eingeben', '17.03.1980'))
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

	$form = new CinForm(getenv('DOCUMENT_ROOT') . '/Form/config/formConfig.xml');
	$isOk = false;
	// validate the form:
	if ($form->isSubmitted() || isset($_REQUEST['showAgain'])) {
		$form->fillFromRequest();
		// if the form is displayed again, do not set it to ok:
		$isOk = isset($_REQUEST['showAgain']) ? false : $form->validate();
	}
	if ($isOk) {
		?>
		<h1>Vielen Dank für Ihre Angaben.</h1>
		<ul>
		<?php foreach ($form->getFields() as $field) { ?>
			<li><?php echo $field->toDisplayFormat();?></li>
		<?php } ?>
		</ul>
		<?php echo $form->hide('<input type="submit" name=' . 'showAgain' .' value="show again" />');?>
		
		<?php
	} else {
		$form->render();
	}

	
//include('testForm_tpl.php');


