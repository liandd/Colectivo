<?php
class DocumentationController {
    public function showTelecommunications() {
        require_once '../view/documentation/telecommunications.php';
    }

    public function showProgramming() {
        require_once '../view/documentation/programming.php';
    }

    public function showSpectrum() {
        require_once '../view/documentation/spectrum.php';
    }

    public function showReferences() {
        require_once '../view/documentation/references.php';
    }

    public function showModulation() {
        SessionManager::checkLogin();
        require_once '../view/documentation/modulation.php';
    }
}
