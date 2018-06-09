<?php
// We change the headers of the page so that the browser will know what sort of file is dealing with. Also, we will tell the browser it has to treat the file as an attachment which cannot be cached.
/*
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=exceldata.xls");
header("Pragma: no-cache");
header("Expires: 0");
*/

/** Error reporting */
#error_reporting(E_ALL);
#ini_set('memory_limit','100M');
#ini_set('display_errors', TRUE);
#ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Sao_Paulo');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');


#require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
require_once('assets/PHPExcel/Classes/PHPExcel.php');


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Sistema Sim Tv")
							 ->setLastModifiedBy("Sistema Sim Tv")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");

# Controla a posi��o das colunas
$contCampo = 0;

# Estiliza a primeira coluna em negrito
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

if($campos <> ''){
    
    try{
    
        # Cria as colunas t�tulos do excel
        foreach($campos as $campo){ 
            
            # Estiliza a coluna em negrito
            $objPHPExcel->getActiveSheet()->getStyle($contCampo)->getFont()->setBold(true);
            
            # Cria a coluna
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($contCampo, 1,  $campo);
            
            # Pr�xima coluna
            $contCampo++;
        }
    
    }catch( Exception $e ){
        
        log_message('error', $e->getMessage());
        
    }
    
}

# Conte�do a partir da segunda linha
$linha = 2;
/*if($this->session->userdata('cd') == 6){
    echo '<pre>';
    print_r($valores);
    exit();
}*/
if($valores <> ''){
    
    try{
    
        # Controla o campo da linha
        $coluna = 0;
        foreach($valores as $valor){ # Alimenta as colunas com o conte�do
            
            # Remove o negrito
            $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(false);
            
            # Remove o negrito
            $objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setBold(false);
            foreach($campos as $campo){
            
                # Estiliza removendo o negrito
                $objPHPExcel->getActiveSheet()->getStyle($coluna)->getFont()->setBold(false);
                
                #if($this->session->userdata('cd') == 6){
                    # Exemplos: 10/06/2015 | 10/06/15 | 2015-06-10 | 15-06-10
                    if(preg_match('/^[0-9]{2,4}(-|\/)[0-9]{2}(-|\/)[0-9]{2,4}$/', trim($valor[$campo]))){ # Verifica se o conte�do � uma data
                        # Adiciona o conte�do - Data no formato Excel
                        # Se for data converte a string data para o formato data do Excel
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($coluna, $linha,  PHPExcel_Shared_Date::stringToExcel(trim($valor[$campo]))); #echo trim($valor[$campo]); echo '<br>'; echo $ExcelDateValue; exit();
                        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($coluna, $linha)->getNumberFormat()->setFormatCode('dd/mm/yyyy'); #PHPExcel_Style_NumberFormat::FORMAT_DATE_DMYSLASH
                    }else{
                        # Adiciona o conte�do
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($coluna, $linha,  trim($valor[$campo]));
                    }
        
                #}else{
                
                    # Adiciona o conte�do
                    #$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($coluna, $linha,  trim($valor[$campo]));
                
                #}
                # Segue pra pr�xima coluna
                $coluna++;
            }
        
            # Retorno a primeira coluna    
            $coluna = 0;
        
            # Avan�a pra pr�xima linha
            $linha++;    
        }
    
    }catch( Exception $e ){
            
        log_message('error', $e->getMessage());
        
    }

}
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('relatorio');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

try{

    // Redirect output to a client�s web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="relatorio.xlsx"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');
    
    // If you're serving to IE over SSL, then the following may be needed
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header ('Pragma: public'); // HTTP/1.0
    
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');

}catch( Exception $e ){
    
    log_message('error', $e->getMessage());
    
}

exit;

?>