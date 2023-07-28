<?php
class CsvJson
{
    private $result;
    private $length_head;
    private $length_rows;
    public function convert($user_file)
    {
        $col = array();
        $row = array();
        $rows = array();
        $csv = array_map('str_getcsv', file($user_file));
        $csv_head = array_shift($csv);
        $this->length_head = count($csv_head);
        $this->length_rows = count($csv);
        for ($i = 0; $i < $this->length_rows; $i++) {
            for ($j = 0; $j < $this->length_head; $j++) {
                $row = array_merge($row,array($csv_head[$j] => $csv[$i][$j]));    
            }
            array_push($rows, $row);
            $row = array();
        }
        $this->result = json_encode($rows);
    }
    public function writeJson()
    {
        $myfile = fopen("temp.json", "w+") or die("Unable to open file!");
        fwrite($myfile, $this->result);
        fclose($myfile);
    }
    public function get_result()
    {
        return $this->result;
    }
    public function get_csv_details(): array
    {
        return array("Header" => $this->length_head, "Rows" => $this->length_rows);
    }
}
$page_msg = '';
$ready = false;
$app = new CsvJson(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['importCSV'])) {
        $file = $_FILES['filename']['tmp_name'];
        if (!file_exists($file) || !is_readable($file)) {
            $page_msg = '<span style="color:red"><h4> Error : file not found </h4></span>';
        } else {
            $app->convert($file);
            $ready = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../../bootstrap-5.3.1-dist/css/bootstrap.min.css" />
    <script src="../../bootstrap-5.3.1-dist/js/bootstrap.bundle.min.js"></script>
    <title> CSV to Json </title>
</head>

<body class="bg-dark">
    <!-- contents -->
    <br />
    <div class="container">
        <br />
        <div class="text-center">
            <?php 
                echo '<h4 class="text-light"> CSV to JSON</h4>';
                echo '<br /><h4 class="text-warning">'.$page_msg.'</h4>';
            ?>
        </div><br />
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="input-group">
                <input type="file" class="form-control" name="filename" />
                <input type="submit" class="btn btn-primary" name="importCSV" value="Convert">
            </div>
        </form>
        <br /><br />
        <?php $app->writeJson();
        if ($ready) {
            echo '<table class="table table-striped table-hover">';
            foreach ($app->get_csv_details() as $x => $x_value) {
                echo '<tr><th class="text-primary"> No. of ' . $x . '</th><th class="text-primary">' . $x_value . '</th></tr>';
            }
            echo '</table>';
            echo '<div class="text-center"><a class="btn btn-success" href="temp.json" download> Download Json<a/></div>';
        } ?>
    </div>
    <br />
    <!-- end of contents -->
</body>
</html>
