<?php
class MenuModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getMenus()
    {
        $query = "SELECT * FROM web2_menus ORDER BY parent_id ASC, id ASC";
        $result = $this->db->query($query);

        if (!$result) {
            die("Lekérdezési hiba: " . $this->db->error);
        }

        // Az eredmény átalakítása asszociatív tömbbé
        $menus = [];
        while ($row = $result->fetch_assoc()) {
            $menus[] = $row;
        }

        // Hierarchikus struktúra létrehozása
        return $this->buildMenuTree($menus);
    }

    private function buildMenuTree($menus, $parentId = null)
    {
        $branch = [];
        foreach ($menus as $menu) {
            if ($menu['parent_id'] == $parentId) {
                $children = $this->buildMenuTree($menus, $menu['id']);
                if ($children) {
                    $menu['children'] = $children;
                }
                $branch[] = $menu;
            }
        }
        return $branch;
    }
}
?>
