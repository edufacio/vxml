<?php
class FormViewData extends SimpleViewData
{
    private $options = array();
    private $defaultOption = null;

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }


    public function addOption($option, Link $link)
    {
        $this->options[$option] = $link;
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
        foreach ($this->options as $optionName => $link) {
            $phrases .= '(' . $optionName . ') ';
        }
        $phrases .= $this->getMainMenuOptionGrammar();
        $phrases .= $this->getPreviousPageOptionGrammar();
        return '[ ' . $phrases . ']';
    }

}