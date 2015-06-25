<?php
abstract class View {

	abstract public function render($viewData);

    protected function renderOnTemplate($viewData, $template) {
        require_once dirname(__FILE__) . "/../template/" . $template;
        ob_start();
        include($template);
        $ret = ob_get_contents();
        ob_end_clean();
        return $ret;
    }
}