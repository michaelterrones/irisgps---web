<?php

class Templator {

	private $form_class = 'horizontal';
	private $size = 'col-md-4';
	private $formHeader = '';
	private $formBody = '';
	private $formFooter = '';
	private $formScripts = '';

	public function getForm()
	{
		return $this->formHeader.$this->formBody.$this->formFooter;
	}

	public function getScripts()
	{
		return $this->formScripts;
	}

	public function isFormInline()
	{
		return $this->form_class = 'inline';
	}

	public function setSize($size)
	{
		switch ($size) {
			case 2:
				$this->size = 'col-md-2';
				break;
			case 3:
				$this->size = 'col-md-3';
				break;
			case 4:
				$this->size = 'col-md-4';
				break;
			case 5:
				$this->size = 'col-md-5';
				break;
			case 6:
				$this->size = 'col-md-6';
				break;
			case 7:
				$this->size = 'col-md-7';
				break;
			case 8:
				$this->size = 'col-md-8';
				break;
			
			default:
				$this->size = 'col-md-4';
				break;
		}
	}

	public function createForm($actionRoute, $routeParams = null, $method, $legend, $submitName = 'Agregar', $resetName = null)
	{
		$this->formHeader = $this->openForm($actionRoute, $routeParams, $method, $legend);
		$this->formFooter = $this->closeForm($submitName, $resetName);
	}

	private function openForm($actionRoute, $routeParams, $method, $legend = null)
	{
		if(!is_null($routeParams))
		{
			$actionRoute = URL::route($actionRoute, $routeParams);
		}else
		{
			$actionRoute = URL::route($actionRoute);
		}

		$legend = (is_null($legend)) ? '' : "<legend> $legend </legend>";
		$formHeader = <<<EOD
		<form class="form-$this->form_class" method="$method" action="$actionRoute">
				$legend
			<fieldset>
EOD;
		return $formHeader;
	}	

	public function addLinkButton($actionRoute, $params = null, $class = 'default', $name = 'Boton')
	{
		if(!is_null($params))
		{
			$actionRoute = URL::route($actionRoute, $params);
		}else{
			$actionRoute = URL::route($actionRoute);
		}

		$linkButton = <<<EOD
			<div class="form-group">
			  <label class="col-md-4 control-label" for=""></label>
			  <div class="col-md-4">
			    <a href="$actionRoute" class="btn btn-$class">$name</a>
			  </div>
			</div>
EOD;
		return $this->formBody .= $linkButton;
	}	

	private function closeForm($submitName = 'Agregar', $resetName = null)
	{
		$resetName = (is_null($resetName)) ? '' : "
			    <button type='reset' name='reset_button' class='btn btn-default'>$resetName</button>";
		$formFooter = <<<EOD
			<!-- Button -->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="submit"></label>
			  <div class="col-md-4">
			    <button type="submit" name="submit_button" class="btn btn-success">$submitName</button>
			    $resetName
			  </div>
			</div>

			</fieldset>
		</form>
EOD;
		return $formFooter;
	}

	public function addHidden($id = null, $name = null, $value = null)
	{
		$id = (is_null($id)) ? $name : $id;
		//$value = Input::old($name);

		$textInput = <<<EOD
			<input id="$id" name="$name" type="hidden" value="$value">
EOD;
		$this->formBody .= $textInput;
	}

	public function addText($id = null, $label = null, $name = null, $placeholder = null, $required = false, $autocomplete=true, $helpblock = null, $value = null)
	{
		$id = (is_null($id)) ? $name : $id;
		$required = ($required) ? 'required=""' : '';
		$autocomplete = ($autocomplete) ? '' : 'autocomplete="off"';
		$helpblock = (!is_null($helpblock)) ? "<span class='help-block'> $helpblock </span>" : '';
		$value = (is_null($value)) ? Input::old($name) : $value;
		$error_class = '';

		if(Session::has('validation_errors'))
		{
			$error_class = (array_key_exists($name , Session::get('validation_errors'))) ? 'has-error' : 'has-success';
	    }

		$textInput = <<<EOD
			<div class="form-group $error_class">
				<label class="col-md-4 control-label" for="$name">$label</label>  
				<div class="$this->size">
					<input id="$id" name="$name" type="text" placeholder="$placeholder" class="form-control input-md" value="$value" $required $autocomplete>
					$helpblock  
				</div>
			</div>
EOD;
		$this->formBody .= $textInput;
	}

	public function addPassword($id = null, $label = null, $name = null, $required = false)
	{
		$id = (is_null($id)) ? $name : $id;
		$required = ($required) ? 'required=""' : '';
		$error_class = '';

		if(Session::has('validation_errors'))
		{
			$error_class = (array_key_exists($name , Session::get('validation_errors'))) ? 'has-error' : 'has-success';
	    }

		$passwordInput = <<<EOD
			<div class="form-group $error_class">
				<label class="col-md-4 control-label" for="$name">$label</label>  
				<div class="$this->size">
					<input id="$id" name="$name" type="password" class="form-control input-md" $required>
				</div>
			</div>
EOD;
		$this->formBody .= $passwordInput;
	}

	public function addRadioMultiple($id = null, $name = null, $label, $elements = array(), $checkedElement = 0)
	{
		$id = (is_null($id)) ? $name : $id;
		$error_class = '';
		$values = array_keys($elements);
		$names = array_values($elements);

		if(Session::has('validation_errors'))
		{
			$error_class = (array_key_exists($name , Session::get('validation_errors'))) ? 'has-error' : 'has-success';
	    }

		$radioInput = <<<EOD
			<div class="form-group $error_class">
				<label class="col-md-4 control-label" for="$name">$label</label>  
				<div class="$this->size">
EOD;
		
		for ($i=0; $i < (count($elements)); $i++) {
			$radioValue = $values[$i];
			$radioName = $names[$i];
			$isChecked = ($checkedElement == $radioValue) ? 'checked' : '';

			$radioInput .= <<<EOD
					<div class="radio">
						<label>
						<input type="radio" name="$name" id="$id" value="$radioValue" $isChecked>
						$radioName
						</label>
					</div>
EOD;
		}

		$radioInput .= <<<EOD
				</div>
			</div>
EOD;
		$this->formBody .= $radioInput;
	}

	public function addCheckboxSingle($id = null, $name, $value = null, $label = "checkbox", $checked = false)
	{
		$id = (is_null($id)) ? $name : $id;
		$checked = is_null(Input::old($name)) ? (($checked == true) ? 'checked' : '') : 'checked';
		$error_class = '';

		if(Session::has('validation_errors'))
		{
			$error_class = (array_key_exists($name , Session::get('validation_errors'))) ? 'has-error' : 'has-success';
	    }

		$checkboxSingleInput = <<<EOD
			<div class="form-group $error_class">
				<div class="col-sm-offset-4 col-sm-8">
					<div class="checkbox">
						<label>
							<input type="checkbox" value="$value" name="$name" $checked> $label
						</label>
					</div>
				</div>
			</div>
EOD;
		$this->formBody .= $checkboxSingleInput;
	}

	public function addSelectBasic($id = null, $label = null, $name = null, $elements = null, $haveEmptyOption = false, $selected = null, $disabled=false)
	{
		$id = (is_null($id)) ? $name : $id;
		$elements = (is_null($elements)) ? [] : $elements;
		$previousSelected = (is_null($selected)) ? Input::old($name) : $selected;
		$disabled = $disabled ? 'disabled="true"' : '';
		$error_class = '';

		if(Session::has('validation_errors'))
		{
			$error_class = (array_key_exists($name , Session::get('validation_errors'))) ? 'has-error' : 'has-success';
	    }

		$selectBasic = <<<EOD
			<div class="form-group $error_class">
				<label class="col-md-4 control-label" for="$name">$label</label>
				<div class="$this->size">
					<select id="$id" name="$name" class="form-control" $disabled>
EOD;
		if($haveEmptyOption)
		{
			$elements = array('0' => '--Ninguno--' ) + $elements;
		}

		foreach ($elements as $option) {
			$value = array_search($option, $elements);
			$selected = (strtolower((string)$value) == strtolower((string)$previousSelected)) ? 'selected=""' : '';
			$selectBasic .= <<<EOD
						<option value="$value" $selected>$option</option>
EOD;
		}

		$selectBasic .= <<<EOD
					</select>
				</div>
			</div>
EOD;

		$this->formBody .= $selectBasic;
	}

	public function addDualListBox($id, $label = null, $name, $elements = null, $rows = 10, $fromName, $toName, $size = null)
	{
		(!is_null($size)) ? $this->setSize($size) : false;
		$name = $name.'[]';
		$elements = (is_null($elements)) ? [] : $elements;

		$dualListBox = <<<EOD
			<div class="form-group">
				<label class="col-md-4 control-label" for="$name">$label</label>
				<div class="$this->size">
				    <select id="$id" multiple="multiple" size="$rows" name="$name">
EOD;
		foreach ($elements as $option) {
			$value = array_search($option, $elements);
			$dualListBox .= <<<EOD
						<option value="$value">$option</option>
EOD;
		}

		$dualListBox .= <<<EOD
					</select>
				</div>
			</div>
EOD;

		$dualListBoxScripts = <<<EOD
			<script>
				var DUAL$id = $('#$id').bootstrapDualListbox({
					nonSelectedListLabel: '$fromName',
					selectedListLabel: '$toName',
					preserveSelectionOnMove: 'moved',
					moveOnSelect: false
				});
			</script>
EOD;

		$this->formBody .= $dualListBox;
		$this->formScripts .= $dualListBoxScripts;
	}

	public function addElement($params)
	{
		$type = $params['type'];

		$id = (isset($params['id'])) ? $params['id'] : null;
		$label = (isset($params['label'])) ? $params['label'] : null;
		$name = (isset($params['name'])) ? $params['name'] : 'name';
		$elements = (isset($params['elements'])) ? $params['elements'] : null;
		$haveEmptyOption = (isset($params['haveEmptyOption'])) ? $params['haveEmptyOption'] : false;
		$selected = (isset($params['selected'])) ? $params['selected'] : null;
		$disabled= (isset($params['disabled'])) ? $params['disabled'] : false;
		$rows = (isset($params['rows'])) ? $params['rows'] : 10;
		$fromName = (isset($params['fromName'])) ? $params['fromName'] : 'fromName';
		$toName = (isset($params['toName'])) ? $params['toName'] : 'toName';
		$size = (isset($params['size'])) ? $params['size'] : null;
		$value = (isset($params['value'])) ? $params['value'] : null;
		$checked = (isset($params['checked'])) ? $params['checked'] : false;
		$checkedElement = (isset($params['checkedElement'])) ? $params['checkedElement'] : 0;
		$required = (isset($params['required'])) ? $params['required'] : false;
		$placeholder = (isset($params['placeholder'])) ? $params['placeholder'] : null;
		$autocomplete = (isset($params['autocomplete'])) ? $params['autocomplete'] : true;
		$helpblock = (isset($params['helpblock'])) ? $params['helpblock'] : null;

		switch ($type) {
			case 'hidden':
				$this->addHidden($id, $name, $value);
				break;
			case 'text':
				$this->addText($id, $label, $name, $placeholder, $required, $autocomplete, $helpblock, $value);
				break;
			case 'password':
				$this->addPassword($id, $label, $name, $required);
				break;
			case 'radio-multiple':
				$this->addRadioMultiple($id, $name , $label, $elements, $checkedElement);
				break;
			case 'checkbox-basic':
				$this->addCheckboxSingle($id, $name, $value, $label, $checked);
				break;
			case 'select-basic':
				$this->addSelectBasic($id, $label, $name, $elements, $haveEmptyOption, $selected, $disabled);
				break;
			case 'dual-list-box':
				$this->addDualListBox($id, $label, $name, $elements, $rows, $fromName, $toName, $size);
				break;
		}


	}

}


