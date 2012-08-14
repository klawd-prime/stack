<?php
namespace stack\web\module;
/*
 * Copyright (C) 2012 Michael Saller
 * Licensed under MIT License, which is found under /path/to/stack/LICENSE
 */

class BaseModule extends \stack\module\BaseModule {

    /**
     * @var \stack\web\Application
     */
    private $application;

    /**
     * @var \stack\web\Request
     */
    private $request;

    /**
     * @var \lean\Document;
     */
    private $document;

    /**
     * @var \lean\Template
     */
    private $layout;

    /**
     * @var \lean\Template
     */
    private $view;

    /**
     * Holds template variables
     *
     * @var \ArrayObject
     */
    protected $data;

    /**
     */
    public function __construct() {
        $this->data = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * Create document, view and layout
     */
    public function init(\stack\Application $application, \stack\web\Request $request) {
        parent::init($application);

        $this->application = $application;
        $this->request = $request;

        $this->document = $this->createDocument();
        $this->layout = $this->createLayout();
        $this->view = $this->createView();
    }

    /**
     * Display hirarchy
     * controller
     *  -> document
     *      -> layout
     *          -> view
     */
    protected function display() {
        $document = $this->getDocument();
        $layout = $this->getLayout();
        $view = $this->getView();
        $view->setData($this->data->getArrayCopy());

        // stack
        $document->set('layout', $layout);
        $layout->set('view', $view);

        $document->display();
    }

    /**
     * @return \lean\Document
     */
    protected function createDocument() {
        $file = \lean\ROOT_PATH . '/template/document.php';
        return new \lean\Document($file);
    }

    /**
     * @return \lean\Template
     */
    protected function createLayout() {
        $file = $this->getContext()->getApplication()->getSetting('lean.template.layout.directory') . '/default.php';
        return new \lean\Template($file);
    }

    /**
     * @return \lean\Template
     */
    protected function createView() {
        $chunks = explode("\\", get_class($this));
        $class = end($chunks);
        $file = \strtolower($class);
        $file = \str_replace('\\', '/', $file);
        $action = \lean\Text::splitCamelCase($this->getAction());
        $file = $this->getApplication()->getSetting('lean.template.view.directory') . "/$file/$action.php";
        $view = new \lean\Template($file);
        return $view;
    }

    /**
     * @param \lean\Partial $partial
     */
    protected function addPartial(\lean\Partial $partial) {
        $this->getView()->setCallback($partial->getName(), $partial);
    }

    /**
     * @return \lean\Document
     */
    public function getDocument() {
        return $this->document;
    }

    /**
     * @return \lean\Template
     */
    protected function getLayout() {
        return $this->layout;
    }

    /**
     * @return \lean\Template
     */
    protected function getView() {
        return $this->view;
    }

    /**
     * @return \stack\web\Application
     */
    protected function getApplication() {
        return parent::getAppliction();
    }

    /**
     * @return \stack\web\Request
     */
    protected function getRequest() {
        return $this->request;
    }
}