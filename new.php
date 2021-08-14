<?php 
try {
  $filename = date("d-m-Y H:i:s");
  require_once("vendor/autoload.php");
  $mpdf = new \Mpdf\Mpdf([
    'mode' => 'c',
    'margin_top' => 35,
    'margin_bottom' => 17,
    'margin_header' => 10,
    'margin_footer' => 10,
  ]);
  $mpdf->showImageErrors = true;
  $mpdf->mirrorMargins = 1;
  $mpdf->SetTitle('Generate PDF file using PHP and MPDF | Mitrajit\'s Tech Blog');
  $mpdf->WriteHTML(3);
  $mpdf->Output($filename, 'I');
} catch(\Mpdf\MpdfException $e) {
  echo $e->getMessage();
}
?>