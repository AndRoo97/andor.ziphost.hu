<?php
require_once 'tcpdf/tcpdf.php';
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table = $_POST['table'] ?? '';
    $conditionColumn = $_POST['conditionColumn'] ?? '';
    $conditionValue = $_POST['conditionValue'] ?? '';
    if (!empty($table) && !empty($conditionColumn) && !empty($conditionValue)) {
        $query = "SELECT * FROM $table WHERE $conditionColumn = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $conditionValue);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $pdf = new TCPDF();
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Rendszer');
            $pdf->SetTitle('Adatok PDF');
            $pdf->SetHeaderData('', 0, 'Adatok PDF', 'Generált PDF dokumentum');

            $pdf->setHeaderFont(['helvetica', '', 12]);
            $pdf->setFooterFont(['helvetica', '', 10]);
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $pdf->SetMargins(15, 27, 15);
            $pdf->SetHeaderMargin(5);
            $pdf->SetFooterMargin(10);
            $pdf->SetAutoPageBreak(TRUE, 25);
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            $pdf->AddPage();
            $pdf->SetFont('helvetica', '', 12);

            $html = '<h1>Adatok a(z) ' . htmlspecialchars($table) . ' táblából</h1>';
            $html .= '<table border="1" cellpadding="5">';
            $html .= '<thead>';
            $html .= '<tr>';
            while ($column = $result->fetch_field()) {
                $html .= '<th>' . htmlspecialchars($column->name) . '</th>';
            }
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            while ($row = $result->fetch_assoc()) {
                $html .= '<tr>';
                foreach ($row as $value) {
                    $html .= '<td>' . htmlspecialchars($value) . '</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';

            $pdf->writeHTML($html, true, false, true, false, '');

            // PDF letöltése
            $pdf->Output('adatok.pdf', 'D');
            exit;
        } else {
            $error = 'Nincs találat a megadott feltételek alapján.';
        }
    } else {
        $error = 'Minden mezőt ki kell tölteni!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Generálás</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <a href="index.php" class="btn btn-primary"><h1>Főmenü</h1></a>
    <h1>PDF Generálás</h1>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="table" class="form-label">Tábla neve</label>
            <select id="table" name="table" class="form-select" required>
                <option value="meccs">Meccs</option>
                <option value="belepes">Belépés</option>
                <option value="nezo">Néző</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="conditionColumn" class="form-label">Feltétel oszlop</label>
            <input type="text" id="conditionColumn" name="conditionColumn" class="form-control" value="id" required>
        </div>
        <div class="mb-3">
            <label for="conditionValue" class="form-label">Feltétel érték</label>
            <input type="text" id="conditionValue" name="conditionValue" class="form-control" value="6" required>
        </div>
        <button type="submit" class="btn btn-primary">PDF Generálása</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
