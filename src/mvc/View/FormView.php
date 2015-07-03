<?php
class FormView extends View
{
	/**
	 * @return FormView
	 */
	public static function create()
	{
		return Injector::get('FormView');
	}

	public function render($viewData)
	{
		$this->assertDataIsValid($viewData);
		$this->renderOnTemplate($viewData, "Form.phtml");
	}

	/**
	 * @param FormViewData $viewData
	 *
	 * @throws InvalidArgumentException
	 */
	private function assertDataIsValid($viewData)
	{
		if(count($viewData->getInputs()) == 0 && $viewData->getExternalGrammarPath() == null
			&& !$viewData->existsMainMenuLink() && !$viewData->existsPreviousPageLink()) {
			throw new InvalidArgumentException("No options or inputs");
		}

		if($viewData->getPrompt() === null) {
			throw new InvalidArgumentException("Prompt was not defined");
		}
	}
}