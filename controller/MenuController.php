<?php
class MenuController
{
    private $menuModel;

    public function __construct($menuModel)
    {
        $this->menuModel = $menuModel;
    }

    public function index()
    {
        $menus = $this->menuModel->getMenus();
        require 'view/menu.php';
    }
    

    public function renderMenu()
{
    $menus = $this->menuModel->getMenus();
    require 'view/menu.php';
}

    
}
?>
