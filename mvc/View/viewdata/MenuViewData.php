<?php
class MenuViewData extends SimpleViewData
{
	private $title;
    private $options = array();
    private $defaultOption = null;

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function getTitle()
	{
		return $this->title;
	}

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

	public function setOptions(MenuOption $options)
	{
		$this->options = $options;
	}

    public function addOption($optionInfo, $option, Link $link)
    {
	    $this->assertOptionIsValid($option);
        $this->options[$option] = new MenuOption($optionInfo, $option, $link);
    }

    /**
     * @param Link $defaultOption
     */
    public function setDefaultOption(Link $defaultOption)
    {
        $this->defaultOption = $defaultOption;
    }

    /**
     * @return Link
     */
    public function getDefaultOption()
    {
        return $this->defaultOption;
    }

    /**
     * @return null
     */
    public function existsDefaultOption()
    {
        return $this->defaultOption !== null;
    }

    public function getGrammarOptions()
    {
        $phrases = '';
	    /* @var $option MenuOption */
        foreach ($this->options as $option) {
            $phrases .= $option->getOptionForGrammar();
        }
        $phrases .= $this->getMainMenuOptionGrammar();
        $phrases .= $this->getPreviousPageOptionGrammar();
        return '[' . $phrases . ']';
    }

	protected function assertOptionIsValid($option)
	{
		if (in_array($option, $this->getReservedOptions())) {
			throw new InvalidOptionException($option);
		 }
	}

	protected function getReservedOptions() {
		return  array(KeyPhone::KEY_0, KeyPhone::KEY_STAR);
	}

}

Class InvalidOptionException extends InvalidArgumentException {

	function __construct($option)
	{
		parent::__construct("$option is an option reserved and it can't be selected as menú option");
	}
}