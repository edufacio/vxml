<?php
class FormViewData extends SimpleViewData
{
	const VOICE_INPUT_RULE = 'voice_inputs';
	const ALL_DIGITS = 'allDigits';
	const NUMERIC_INPUT_RULE = 'numeric_inputs';
	const NAVIGATION_RULE = 'navigation';

	private $hiddenOption = false;
	private $voiceInputs = array();
	private $numericInputs = array();
	private $submitLink;
	private $varReturnedName;
	private $externalGrammarPath;

	/**
	 * @return FormViewData
	 */
	public static function create() {
		return Injector::get('FormViewData');
	}

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
		$grammar = '';
		if ($this->hasVoiceInput()) {
			$grammar = '<grammar mode="voice" root="voiceRequest">' . PHP_EOL;
			$grammar .= $this->buildVoiceInputRule();

	        $grammar .= '<rule id="voiceRequest" scope="public">' . PHP_EOL . "<one-of>" . PHP_EOL;
		    $grammar .=  '<item repeat="1-"> <ruleref uri="#'. self::VOICE_INPUT_RULE . '"/> </item>' . PHP_EOL;
	        $grammar .= '</one-of>' . PHP_EOL . '</rule>' . PHP_EOL . '</grammar>';
		}
		$grammar .= PHP_EOL;
		if ($this->hasNavigation() || $this->hasNumericInput()) {
			$grammar.= '<grammar mode="dtmf" root="request">' . PHP_EOL;
			if ($this->hasNavigation()) {
				$grammar .= $this->buildNavigationRule();
			}
			if ($this->hasNumericInput()) {
				$grammar .= $this->buildNumericInput();
			}
			$grammar .= '<rule id="request" scope="public">' . PHP_EOL . "<one-of>" . PHP_EOL;
			if($this->hasNavigation()) {
				$grammar .=  '<item> <ruleref uri="#'. self::NAVIGATION_RULE . '"/> </item>' . PHP_EOL;
			}
			foreach ($this->getNumericInput() as $inputLenght) {
				$grammar .=  '<item repeat="'. $inputLenght . '"> <ruleref uri="#'. self::ALL_DIGITS . '"/> </item>' . PHP_EOL;
			}
			$grammar .= '</one-of>' . PHP_EOL . '</rule>' . PHP_EOL . '</grammar>';
		}
		return $grammar;
	}

	public function hasVoiceInput() {
		return count($this->getVoiceInputs()) > 0;
	}

	private function buildVoiceInputRule() {
		$rule = '<rule id="'. self::VOICE_INPUT_RULE . '">' . PHP_EOL . '<one-of>' . PHP_EOL;
		/* @var $input FormInput */
		foreach ($this->getVoiceInputs() as $input) {
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

	private function buildNumericInput() {
		$rule = '<rule id="'. self::ALL_DIGITS . '">' . PHP_EOL . '<one-of>' . PHP_EOL;
		for ($i=0;$i<10;$i++) {
			$rule .= "<item>$i</item>" . PHP_EOL;
		}
		$rule .= '</one-of>' . PHP_EOL . '</rule>';
		$rule .= '<rule id="'. self::NUMERIC_INPUT_RULE . '">' . PHP_EOL . '<one-of>' . PHP_EOL;
		for ($i=0;$i<10;$i++) {
			$rule .= "<item>$i</item>" . PHP_EOL;
		}
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

	public function getNameList()
	{
		$nameList = parent::getNameList();
		return $nameList . ' ' . $this->getVarReturnedName();
 	}


	public function addVoiceInput($language, $input, $addSplittedWordsAsInput = false)
	{
		if ($addSplittedWordsAsInput) {
			$this->addSplittedWordsAsInput($language, $input);
		}
		$this->addVoiceInputOption($language, $input);
	}

	public function addNumericInput($numericInputLength)
	{
		$this->numericInputs[] = $numericInputLength;
	}

	public function getNumericInput()
	{
		return $this->numericInputs;
	}

	public function hasNumericInput()
	{
		return !empty($this->numericInputs);
	}

	public function getVoiceInputs()
	{
		return $this->voiceInputs;
	}

	private function addSplittedWordsAsInput($language, $input)
	{
		$words = explode(' ', $input);
		if (count($words) > 1) {
			foreach ($words as $word) {
				$this->addVoiceInputOption($language, $word);
			}
		}
	}

	private function addVoiceInputOption($language, $input)
	{
		if (!Language::isLanguageValid($language)) {
			throw new InvalidLanguageException($language);
		}
		if (strlen($input) > 0) {
			$this->voiceInputs[$language . ':' . $input] = new FormInput($language, $input);
		}
	}

	public function addInputFromCsv($csv) {
		$csvContent = file_get_contents($csv);
		$lines = explode("\n", $csvContent);
		foreach($lines as $line) {
			$lineExploded = explode(',', $line);
			$language = array_shift($lineExploded);
			foreach($lineExploded as $input) {
				$this->addVoiceInput($language, $input, true);
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

	public function isHiddenOption()
	{
		return $this->hiddenOption;
	}

	public function setHiddenOption()
	{
		$this->hiddenOption = true;
	}
}

Class InvalidExternalGrammarPath extends InvalidArgumentException {

	function __construct($grammarPath)
	{
		parent::__construct("$grammarPath does Not exists");
	}
}