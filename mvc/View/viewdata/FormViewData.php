<?php
class FormViewData extends SimpleViewData
{
	const INPUT_RULE = 'inputs';
	const NAVIGATION_RULE = 'navigation';

	private $inputs = array();
	private $submitLink;
	private $varReturnedName;
	private $externalGrammarPath;

	function getGrammar()
	{
		if ($this->getExternalGrammarPath() !== null) {
			return $this->buildExternalGrammar();
		} else {
			return $this->generateGrammar();
		}
	}

	private function buildExternalGrammar()
	{
		return '<grammar src = "' . $this->getExternalGrammarPath() . '" type="application/srgs+xml" />' . "\n";
	}

	private function generateGrammar()
	{
		$grammar = '<grammar xml:lang="en-US" >' . PHP_EOL;
		$grammar .= $this->buildInputRule();
		if ($this->hasNavigation()) {
            $grammar .= $this->buildNavigationRule();
		}
        $grammar .= '<rule id="request" scope="public">' . PHP_EOL . "<one-of>" . PHP_EOL;
	    $grammar .=  '<item repeat="1-"> <ruleref uri="#'. self::INPUT_RULE . '"/> </item>' . PHP_EOL;
		if ($this->hasNavigation()) {
			$grammar .=  '<item> <ruleref uri="#'. self::NAVIGATION_RULE . '"/> </item>' . PHP_EOL;
		}
        $grammar .= '</one-of>' . PHP_EOL . '</rule>' . PHP_EOL . '</grammar>';
		return $grammar;
	}

	private function buildInputRule() {
		$rule = '<rule id="'. self::INPUT_RULE . '">' . PHP_EOL . '<one-of>' . PHP_EOL;
		/* @var $input FormInput */
		foreach ($this->getInputs() as $input) {
			$rule .= '<item xml:lang="' . $input->getLanguage() . '">'. $input->getEscapedVoiceInput() . '</item>' . PHP_EOL;
		}
		$rule .= '</one-of>' . PHP_EOL . '</rule>';
		return $rule;
	}

	private function buildNavigationRule() {
		$rule = '<rule id="'. self::NAVIGATION_RULE . '">' . PHP_EOL . '<one-of>' . PHP_EOL;
		$rule .= $this->getMainMenuOptionItem();
		$rule .= $this->getPreviousPageOptionItem();
		$rule .= '</one-of>' . PHP_EOL . '</rule>';
		return $rule;
	}

	private function hasNavigation() {
		return $this->existsPreviousPageLink() || $this->existsMainMenuLink();
	}

	public function setExternalGrammarPath($externalGrammarPath)
	{
		if(!file_exists($externalGrammarPath)) {
			throw new InvalidExternalGrammarPath($externalGrammarPath);
		}
		$this->externalGrammarPath = $externalGrammarPath;
	}

	public function getExternalGrammarPath()
	{
		return $this->externalGrammarPath;
	}

	public function setVarReturnedName($varReturnedName)
	{
		$this->varReturnedName = $varReturnedName;
	}

	public function getVarReturnedName()
	{
		return $this->varReturnedName;
	}


	public function addInput($language, $input, $addSplittedWordsAsInput = false)
	{
		if ($addSplittedWordsAsInput) {
			$this->addSplittedWordsAsInput($language, $input);
		}
		$this->addInputOption($language, $input);
	}

	public function getInputs()
	{
		return $this->inputs;
	}

	private function addSplittedWordsAsInput($language, $input)
	{
		$words = explode(' ', $input);
		if (count($words) > 1) {
			foreach ($words as $word) {
				$this->addInputOption($language, $word);
			}
		}
	}

	private function addInputOption($language, $input)
	{
		if (!Language::isLanguageValid($language)) {
			throw new InvalidLanguageException($language);
		}
		$this->inputs[$language . ':' . $input] = new FormInput($language, $input);
	}

	public function addInputFromCsv($csv) {
		$csvContent = file_get_contents($csv);
		$lines = explode("\n", $csvContent);
		foreach($lines as $line) {
			$lineExploded = explode(',', $line);
			$language = array_shift($lineExploded);
			foreach($lineExploded as $input) {
				$this->addInput($language, $input, true);
			}
		}
	}

	public function getSubmitLink()
	{
		return $this->submitLink;
	}

	public function setSubmitLink(Link $submitLink)
	{
		$this->submitLink = $submitLink;
	}
}

Class InvalidExternalGrammarPath extends InvalidArgumentException {

	function __construct($grammarPath)
	{
		parent::__construct("$grammarPath does Not exists");
	}
}