!<DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Convert CSV to XLS</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- custom CSS here -->
    <link href="css/convert.css" rel="stylesheet">

</head>

<body>
	
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1>Convert CSV to XLS</h1>
			</div>
		</div>
		<hr>		
		<?php
			if (isset($_FILES["file_source"])) {
				if ($_FILES["file_source"]["error"] > 0) {
					echo "Error: " . $_FILES["file_source"]["error"] . "<br>";
				}
				else {
					require_once './reader.php';
					require_once './processor.php';
					require_once './writer.php';
					
					$fileName = $_FILES["file_source"]["name"];
					$tmpName = $_FILES["file_source"]["tmp_name"];
					$pathParts = pathinfo($fileName);
					$timestamp = substr(time(),6,4);
					$targetName5 = 'xls_files/' . $pathParts['filename'] . '_' . $timestamp . '_97.xls';
					$targetName2007 = 'xls_files/' . $pathParts['filename'] . '_' . $timestamp . '_2007.xlsx';
					
					$reader = new csv2xlsReader($tmpName);
					$fileContents = $reader->getData();
					//$reader->printContents();
					
					$processor = new csv2xlsProcessor($fileContents);
					$processor->process();
					$processedContents = $processor->getData();
					//$processor->printContents();
					
					$writer = new csv2xlsWriter($processedContents);
					if ($writer->writeAsExcel5($targetName5)) {
						echo ("<hr>Download processed XLS file (in Excel97 format): <a href='$targetName5'>$targetName5 </a><hr>");
					}
					if ($writer->writeAsExcel2007($targetName2007)) {
						echo ("<hr>Download processed XLS file (in Excel2007 format): <a href='$targetName2007'>$targetName2007 </a><hr>");
					}
				}
			}				
		?>
		<form method="post" action="index.php" enctype="multipart/form-data">
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div style="position:relative;">
							<a class='btn btn-primary' href='javascript:;'>
								Choose File...
								<input type="file" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' name="file_source" size="40"  onchange='$("#upload-file-info").html($(this).val());' />
							</a>
							&nbsp;
							<span class='label label-info' id="upload-file-info"></span>
					</div>
				</div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<input type="submit" id="convert_bttn" name="enter" value="Convert" />
				</div>
			</div>
		</div>
        <hr>
		</form>
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>&copy; mgongee 2014</p>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.container -->

    <!-- JavaScript -->
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.js"></script>
	<script src="js/convert.js"></script>
</body>

</html>
