<?php
define( APPLICATION_DIR, '/Form/');
define (ENABLE_AUTOLOAD, true);




require_once('./../form.class.php');

$form = new JSForm('helloWorldForm');

// we need some serverside tests -> use the non - js form:
//$form = new Form('helloWorldForm_old');

$textarea = new Field_TextArea('Text','Zwischen 5 und 15 Zeichen','');
$textarea->addValidator(new Validator_Length(5,15));
//$textarea->setRTE(true);
//$form->addField(new Field('textfeld', 'Bitte Text eingeben', 'hello World'));
$form->addField($textarea);

$intField = new Field_Base("intfeld", "Bitte Integer eingeben", "hallo Integer");
$intField->addValidator(new Validator_Int());



$form->addField(new Field_Text('textfeld', 'Bitte Text eingeben', 'hello World'))
	->addField(new Field_Date('datumsfeld','Bitte Datum eingeben', '17.03.1980'))
	->addField($textarea)
	->addField(new Field_FieldGroup('test','Bitte Auswahl treffen
	', array('Bitte wählen' => '', 1 => 1, 2 => 2, 3 => 3)))
	->addField(new Field_Radio('testr','testr', array(1 => 1, 2 => 2, 3 => 3)))
	->addField($intField);
	
	
	if ($form->isSubmitted()) {
			$form->fillFromRequest();
			$isOk = $form->validate();
			if ($isOk) {
				// $form->toStorageFormat();
				
				echo "Vielen Dank für Ihre Angaben.";
			}
	}

	
	echo 'parsing';
	$form->setRenderer(
		new Renderer_Div(
			new Renderer_RedBorder(
				new Renderer_RedBorder(
					new Renderer_ClearDefault()
				)
			)
		)
	);
	
	
	$start = microtime(true);
	//$formParser = new CinForm(getenv('DOCUMENT_ROOT') . '/Form/config/formConfig.xml');
	$composite = new Field_Composite('composite','composite');
	$composite->addField(new Field_Text('text','cf'));
	$composite->addField(new Field_Date('datetesta','testa','22.02.2022'));
	$composite->addField(new Field_Textarea('texxt','texxt'));
	$composite->setElementRenderer(new Renderer_RedBorder(new Renderer_Div()));
	$form->addField($composite);
	//$form = $formParser->getForm();
	
	// $form = new Form();
	$form->setRenderer(
		new Renderer_Div(
			new Renderer_RedBorder(
				new Renderer_RedBorder(
					new Renderer_ClearDefault()
				)
			)
		)
	);
	
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
		echo $form->render();
	}

	
//include('testForm_tpl.php');


	