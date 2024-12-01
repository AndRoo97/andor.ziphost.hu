<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Többszintű Menü</title>
    <style>
        ul {
            list-style-type: none;
        }
        li {
            margin: 5px 0;
        }
        ul ul {
            margin-left: 20px;
        }
    </style>
</head>
<body>
    <h1>Többszintű Menü</h1>
    <?php renderMenu($menus); ?>
</body>
</html>

<?php
function renderMenu($menus)
{
    echo '<ul>';
    foreach ($menus as $menu) {
        echo '<li>';
        echo htmlspecialchars($menu['name']);
        if (!empty($menu['children'])) {
            renderMenu($menu['children']);
        }
        echo '</li>';
    }
    echo '</ul>';
}
?>
