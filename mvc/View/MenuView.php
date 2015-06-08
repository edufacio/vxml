<?php
class MenuView extends View
{
	public function render($viewData)
	{
		$this->assertDataIsValid($viewData);
		$this->renderOnTemplate($viewData, "Menu.phtml");
	}

	/**
	 * @param MenuViewData $viewData
	 *
	 * @throws InvalidArgumentException
	 */
	private function assertDataIsValid($viewData)
	{
		if(count($viewData->getOptions()) == 0 && !$viewData->existsMainMenuLink() && !$viewData->existsPreviousPageLink()) {
			throw new InvalidArgumentException("No options provided on viewData");
		}

		if($viewData->getPrompt() === null) {
			throw new InvalidArgumentException("Prompt was not defined");
		}
	}
}