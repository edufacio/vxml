<?php
Class LoginController extends Controller
{
	const CONTROLLER_NAME = 'Login';
	const PHONE = 'phone';
	const PASSWORD = 'password';
	const PASSWORD_CHECK = 'password_check';
	const ANSWER = "answer";
	const SAVE_ALL = "guardar todo";
	const SAVE_NUMBER = "guardar numero";
	const NO_SAVE = "continuar sin guardar";

	public function index($data)
	{
		if (CurrentSession::getInstance()->isLogged()) {
			IndexVxmlFilmController::create($this->navigation)->index($data);
		} else {
			$this->login($data);
		}
	}

	public function register($data, $prePrompt = '')
	{
		SessionsBackend::getInstance()->deletePhoneLoginForCurrentCaller();
		$viewData = $this->getPhoneForm();
		$viewData->setSubmitLink($this->getLink(self::CONTROLLER_NAME, 'registerStepPassword1'));
		$viewData->setPrompt($prePrompt . " Por favor introduzca los 9 dígitos del numero de telefono que quiere registrar");
		FormView::create()->render($viewData);
	}

	public function registerStepPassword1($data)
	{

		$phone = InputSanitizer::toInt($data[self::PHONE]);
		if (UserBackend::getInstance()->exists($phone)) {
			$this->register($data, "Lo sentimos ese telefono ya se encuentra registrado");
		} else {
			SessionsBackend::getInstance()->setUserLogging($phone);
			$viewData = $this->getPasswordForm();
			$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'register'));
			$viewData->setSubmitLink($this->getLink(self::CONTROLLER_NAME, 'registerStepPassword2'));
			FormView::create()->render($viewData);
		}
	}

	public function registerStepPassword2($data)
	{
		$viewData = $this->getPasswordForm();
		$viewData->addHiddenParam(self::PASSWORD_CHECK, $data[self::PASSWORD]);
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'registerStepPassword1'));
		$viewData->setSubmitLink($this->getLink(self::CONTROLLER_NAME, 'checkRegistration'));
		FormView::create()->render($viewData);
	}

	public function checkRegistration($data)
	{
		if ($data[self::PASSWORD] === $data[self::PASSWORD_CHECK]) {
			$password = InputSanitizer::toInt($data[self::PASSWORD]);
			UserBackend::getInstance()->createUser(CurrentSession::getInstance()->getCurrentPhone(), $password);
			SessionsBackend::getInstance()->setUserLogged(CurrentSession::getInstance()->getCurrentPhone());
			ProfileController::create($this->navigation)->index($data, "Usuario creado correctamente, por favor rellene su perfil");
		} else {
			$this->register($data, "Ambas contraseñas deben coincidir. ");
		}
	}

	public function login($data, $preprompt = '')
	{
		if (SessionsBackend::getInstance()->hasPhoneStoredForCurrentCaller()) {
			$this->loginByCaller($data, $preprompt);
		} else {
			$this->newLogin($data, $preprompt);
		}
	}

	private function loginByCaller($data, $prepromt = '')
	{
		$caller = SessionsBackend::getInstance()->getCallerInfoStored();
		SessionsBackend::getInstance()->setUserLogging($caller->getPhone());
		$viewData = MenuViewData::create();
		$viewData->setTitle($prepromt . " Este terminal está asociado al télefono: " . $caller->getPhone());
		$viewData->setPrompt("Para hacer loguin con este teléfono diga continuar o pulse 1 para usar otro télefono diga otro télefono o marque 2");
		$viewData->addOption("continuar", "continuar", $this->getLink(self::CONTROLLER_NAME, "loginStepPassword"));
		$viewData->addOption("continuar", KeyPhone::KEY_1, $this->getLink(self::CONTROLLER_NAME, "loginStepPassword"));
		$viewData->addOption("otro télefono", "otro télefono", $this->getLink(self::CONTROLLER_NAME, "newLogin"));
		$viewData->addOption("otro télefono", KeyPhone::KEY_2, $this->getLink(self::CONTROLLER_NAME, "newLogin"));
		$viewData->addHiddenParam(self::PHONE, $caller->getPhone());
		$viewData->setMainMenuLink($this->getMainMenuLink());
		MenuView::create()->render($viewData);
	}

	public function newLogin($data, $preprompt = '')
	{
		SessionsBackend::getInstance()->deletePhoneLoginForCurrentCaller();
		$viewData = $this->getPhoneForm();
		$viewData->setSubmitLink($this->getLink(self::CONTROLLER_NAME, 'loginStepPassword'));
		$viewData->setHiddenOption();
		$viewData->setPrompt($preprompt . "Por favor introduzca los 9 dígitos del numero de telefono con el que esta registrado");
		FormView::create()->render($viewData);
	}

	private function getPhoneForm()
	{
		$viewData = FormViewData::create();
		$viewData->setMainMenuLink($this->getMainMenuLink());
		$viewData->setVarReturnedName(self::PHONE);
		$viewData->addNumericInput(9);
		return $viewData;
	}

	public function loginStepPassword($data)
	{
		$sessionsBackend = SessionsBackend::getInstance();
		$caller = $sessionsBackend->getCallerInfoStored();
		if ($caller !== null && $caller->isAutoLoginEnabled()) {
			$sessionsBackend->setUserLogged($caller->getPhone());
			IndexVxmlFilmController::create($this->navigation)->index($data);
		} else {
			$phone = InputSanitizer::toInt($data[self::PHONE]);
			SessionsBackend::getInstance()->setUserLogging($phone);
			$viewData = $this->getPasswordForm();
			$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'login'));
			$viewData->setSubmitLink($this->getLink(self::CONTROLLER_NAME, 'loginCheck'));
			FormView::create()->render($viewData);
		}
	}

	private function getPasswordForm()
	{
		$viewData = FormViewData::create();
		$viewData->setMainMenuLink($this->getMainMenuLink());
		$viewData->setVarReturnedName(self::PASSWORD);
		$viewData->addNumericInput(8);
		$viewData->setPrompt("Por favor introduzca los 8 dígitos de su contraseña");
		$viewData->setHiddenOption();
		return $viewData;
	}

	public function loginCheck($data)
	{
		$phone = CurrentSession::getInstance()->getCurrentPhone();
		$password = InputSanitizer::toInt($data[self::PASSWORD]);
		if ($phone !== null && UserBackend::getInstance()->passwordIsCorrect($phone, $password)) {
			$sessionBackend = SessionsBackend::getInstance();
			$sessionBackend->setUserLogged($phone);
			$caller = $sessionBackend->getCallerInfoStored();
			if ($caller !== null && $caller->getPhone() === $phone) {
				IndexVxmlFilmController::create($this->navigation)->index($data);
			} else {
				$this->storeCaller();
			}
		} else {
			SessionsBackend::getInstance()->setUserUnlogged($phone);
			$this->login($data, "contraseña o teléfono equivocado.");
		}
	}

	private function storeCaller()
	{
		$viewData = FormViewData::create();
		$viewData->setMainMenuLink($this->getMainMenuLink());
		$viewData->setVarReturnedName(self::ANSWER);
		$viewData->setSubmitLink($this->getLink(self::CONTROLLER_NAME, 'postLoginAction'));
		$viewData->setPrompt("¿Quieres guardar tu información de acceso para este terminal?. Para guardar tu número y contraseña diga: guardar todo, para guardar solo el número diga: guardar número, si no quiere guardar nada diga: continuar sin guardar");
		$viewData->addVoiceInput(Language::esES, self::SAVE_ALL);
		$viewData->addVoiceInput(Language::esES, self::SAVE_NUMBER);
		$viewData->addVoiceInput(Language::esES, self::NO_SAVE);

		$view = FormView::create();
		$view->render($viewData);
	}

	public function postLoginAction($data)
	{

		$answer = $data[self::ANSWER];
		if ($answer === self::SAVE_ALL) {
			SessionsBackend::getInstance()->storePhoneLoginForCurrentCaller(Caller::REMEMBER_TRUE);
		} else if (self::SAVE_NUMBER) {
			SessionsBackend::getInstance()->storePhoneLoginForCurrentCaller(Caller::REMEMBER_FALSE);
		} else {
			SessionsBackend::getInstance()->deletePhoneLoginForCurrentCaller();
		}

		IndexVxmlFilmController::create($this->navigation)->index($data);
	}

	public function logout($data)
	{
		SessionsBackend::getInstance()->setUserUnlogged();
		IndexVxmlFilmController::create($this->navigation)->index($data);
	}
}