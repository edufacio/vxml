<?php
Class IndexVxmlFilmController extends Controller
{
	const CONTROLLER_NAME = 'IndexVxmlFilm';

	/**
	 * @return IndexVxmlFilmController
	 */
	public static function create($navigation)
	{
		return Injector::get('IndexVxmlFilmController', $navigation);
	}

	public function index($data, $preprompt = '')
	{
		if (CurrentSession::getInstance()->isLogged()) {
			$this->loggedMainMenu($data, $preprompt);
		} else {
			$this->unloggedMainMenu($data, $preprompt);
		}
	}

	private function loggedMainMenu($data, $preprompt = '')
	{
		$viewData = MenuViewData::create();
		$viewData->addOption("buscar películas", "buscar películas", $this->getLink(SearchController::CONTROLLER_NAME));
		$viewData->addOption("buscar películas", KeyPhone::KEY_1, $this->getLink(SearchController::CONTROLLER_NAME));
		$viewData->addOption("cartelera", "cartelera", $this->getLink(CarteleraController::CONTROLLER_NAME));
		$viewData->addOption("cartelera", KeyPhone::KEY_2, $this->getLink(CarteleraController::CONTROLLER_NAME));
		$viewData->addOption("proximos estrenos", "proximos estrenos", $this->getLink(NextReleaseController::CONTROLLER_NAME));
		$viewData->addOption("proximos estrenos", KeyPhone::KEY_3, $this->getLink(NextReleaseController::CONTROLLER_NAME));
		$viewData->addOption("que ver", "que ver", $this->getLink(self::CONTROLLER_NAME, "viewRecomendations"));
		$viewData->addOption("que ver", KeyPhone::KEY_4, $this->getLink(self::CONTROLLER_NAME, "viewRecomendations"));
		$viewData->addOption("tu perfil", "perfil", $this->getLink(ProfileController::CONTROLLER_NAME));
		$viewData->addOption("tu perfil", KeyPhone::KEY_5, $this->getLink(ProfileController::CONTROLLER_NAME));
		$viewData->addOption("salir de la cuenta", "salir", $this->getLink(LoginController::CONTROLLER_NAME, "logout"));
		$viewData->addOption("salir de la cuenta", KeyPhone::KEY_6, $this->getLink(LoginController::CONTROLLER_NAME, "logout"));

		$viewData->setPrompt("$preprompt Bienvenido al sistema de informacion de peliculas por telefono."
			. " Para buscar una película pulse 1 o diga buscar peliculas."
			. " Para ir a la cartelera pulse 2 o diga cartelera."
			. " Para ir a la Próximos estrenos pulse 3 o diga Próximos estrenos."
			. " Para oir que le recomendamos ver pulse 4 o diga que ver"
			. " Para ver y modificar su perfil pulse 5 o diga perfil."
			. " Para salir de su cuenta pulse 6 o diga salir");

		$view = MenuView::create();
		$view->render($viewData);
	}

	private function unloggedMainMenu($data, $preprompt = '')
	{
		$viewData = MenuViewData::create();
		$viewData->addOption("buscar películas", "buscar películas", $this->getLink(SearchController::CONTROLLER_NAME));
		$viewData->addOption("buscar películas", KeyPhone::KEY_1, $this->getLink(SearchController::CONTROLLER_NAME));
		$viewData->addOption("cartelera", "cartelera", $this->getLink(CarteleraController::CONTROLLER_NAME));
		$viewData->addOption("cartelera", KeyPhone::KEY_2, $this->getLink(CarteleraController::CONTROLLER_NAME));
		$viewData->addOption("proximos estrenos", "proximos estrenos", $this->getLink(NextReleaseController::CONTROLLER_NAME));
		$viewData->addOption("proximos estrenos", KeyPhone::KEY_3, $this->getLink(NextReleaseController::CONTROLLER_NAME));
		$viewData->addOption("hacer lóguin", "lóguin", $this->getLink(LoginController::CONTROLLER_NAME, "login"));
		$viewData->addOption("hacer lóguin", KeyPhone::KEY_4, $this->getLink(LoginController::CONTROLLER_NAME, "login"));
		$viewData->addOption("registrar", "registrar", $this->getLink(LoginController::CONTROLLER_NAME, "register"));
		$viewData->addOption("registrar", KeyPhone::KEY_5, $this->getLink(LoginController::CONTROLLER_NAME, "register"));
		$viewData->setPrompt("$preprompt Bienvenido al sistema de informacion de peliculas por telefono."
			. " Para buscar una película pulse 1 o diga buscar peliculas."
			. " Para ir a la cartelera pulse 2 o diga cartelera."
			. " Para ir a los Próximos estrenos pulse 3 o diga Próximos estrenos."
			. " Para hacer lóguin pulse 4 o diga lóguin"
			. " Para registrarse pulse 5 o diga registrar");

		$view = MenuView::create();
		$view->render($viewData);
	}
}