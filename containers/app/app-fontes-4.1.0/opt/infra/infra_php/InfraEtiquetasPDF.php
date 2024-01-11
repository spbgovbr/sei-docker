<?php
////////////////////////////////////////////////////
// PDF_Label 
//
// Class to print labels in Avery or custom formats
//
//
// Copyright (C) 2003 Laurent PASSEBECQ (LPA)
// Based on code by Steve Dillon : steved@mad.scientist.com
//
//-------------------------------------------------------------------
// VERSIONS :
// 1.0  : Initial release
// 1.1  : + : Added unit in the constructor
//        + : Now Positions start @ (1,1).. then the first image @top-left of a page is (1,1)
//        + : Added in the description of a label : 
//				font-size	: defaut char size (can be changed by calling Set_Char_Size(xx);
//				paper-size	: Size of the paper for this sheet (thanx to Al Canton)
//				metric		: type of unit used in this description
//							  You can define your label properties in inches by setting metric to 'in'
//							  and printing in millimiter by setting unit to 'mm' in constructor.
//			  Added some labels :
//				5160, 5161, 5162, 5163,5164 : thanx to Al Canton : acanton@adams-blake.com
//				8600 						: thanx to Kunal Walia : kunal@u.washington.edu
//        + : Added 3mm to the position of labels to avoid errors 
// 1.2  : + : Added Set_Font_Name method
//        = : Bug of positioning
//        = : Set_Font_Size modified -> Now, just modify the size of the font
//        = : Set_Char_Size renamed to Set_Font_Size
////////////////////////////////////////////////////

/**
 * PDF_Label - PDF label editing
 * @package PDF_Label
 * @author Laurent PASSEBECQ <lpasseb@numericable.fr>
 * @copyright 2003 Laurent PASSEBECQ
 **/
class InfraEtiquetasPDF extends InfraPDF
{

    // Private properties
    var $_Avery_Name = '';                // Name of format
    var $_Margin_Left = 0;                // Left margin of labels
    var $_Margin_Top = 0;                // Top margin of labels
    var $_X_Space = 0;                // Horizontal space between 2 labels
    var $_Y_Space = 0;                // Vertical space between 2 labels
    var $_X_Number = 0;                // Number of labels horizontally
    var $_Y_Number = 0;                // Number of labels vertically
    var $_Width = 0;                    // Width of label
    var $_Height = 0;                    // Height of label
    var $_Char_Size = 0;                // Character size
    var $_Line_Height = 0;                // Default line height
    var $_Metric = 'mm';                // Type of metric for labels.. Will help to calculate good values
    var $_Metric_Doc = 'mm';            // Type of metric for the document
    var $_Font_Name = 'Arial';  // Name of the font

    var $_COUNTX = 1;
    var $_COUNTY = 1;


    // Listing of labels size
    var $_Avery_Labels = array(
        '5160' => array(
            'name' => '5160',
            'paper-size' => 'letter',
            'metric' => 'mm',
            'marginLeft' => 1.762,
            'marginTop' => 10.7,
            'NX' => 3,
            'NY' => 10,
            'SpaceX' => 3.175,
            'SpaceY' => 0,
            'width' => 66.675,
            'height' => 25.4,
            'font-size' => 8
        ),
        '5161' => array(
            'name' => '5161',
            'paper-size' => 'letter',
            'metric' => 'mm',
            'marginLeft' => 0.967,
            'marginTop' => 10.7,
            'NX' => 2,
            'NY' => 10,
            'SpaceX' => 3.967,
            'SpaceY' => 0,
            'width' => 101.6,
            'height' => 25.4,
            'font-size' => 8
        ),
        '5162' => array(
            'name' => '5162',
            'paper-size' => 'letter',
            'metric' => 'mm',
            'marginLeft' => 0.97,
            'marginTop' => 20.224,
            'NX' => 2,
            'NY' => 7,
            'SpaceX' => 4.762,
            'SpaceY' => 0,
            'width' => 100.807,
            'height' => 35.72,
            'font-size' => 8
        ),
        '5163' => array(
            'name' => '5163',
            'paper-size' => 'letter',
            'metric' => 'mm',
            'marginLeft' => 1.762,
            'marginTop' => 10.7,
            'NX' => 2,
            'NY' => 5,
            'SpaceX' => 3.175,
            'SpaceY' => 0,
            'width' => 101.6,
            'height' => 50.8,
            'font-size' => 8
        ),
        '5164' => array(
            'name' => '5164',
            'paper-size' => 'letter',
            'metric' => 'in',
            'marginLeft' => 0.148,
            'marginTop' => 0.5,
            'NX' => 2,
            'NY' => 3,
            'SpaceX' => 0.2031,
            'SpaceY' => 0,
            'width' => 4.0,
            'height' => 3.33,
            'font-size' => 12
        ),
        '8600' => array(
            'name' => '8600',
            'paper-size' => 'letter',
            'metric' => 'mm',
            'marginLeft' => 7.1,
            'marginTop' => 19,
            'NX' => 3,
            'NY' => 10,
            'SpaceX' => 9.5,
            'SpaceY' => 3.1,
            'width' => 66.6,
            'height' => 25.4,
            'font-size' => 8
        ),
        'L7163' => array(
            'name' => 'L7163',
            'paper-size' => 'A4',
            'metric' => 'mm',
            'marginLeft' => 5,
            'marginTop' => 15,
            'NX' => 2,
            'NY' => 7,
            'SpaceX' => 25,
            'SpaceY' => 0,
            'width' => 99.1,
            'height' => 38.1,
            'font-size' => 9
        ),

        //SpaceY - espaço vertical entre etiquetas
        //SpaceX - espaço horizontal entre etiquetas
        'contato' => array(
            'name' => 'teste',
            'paper-size' => 'letter',
            'metric' => 'mm',
            'marginLeft' => 6,
            'marginTop' => 0,
            'NX' => 2,
            'NY' => 10,
            'SpaceX' => 5,
            'SpaceY' => 1,
            'width' => 101.6,
            'height' => 25.2,
            'font-size' => 8,
            'orientacao' => 'V',
            'style' => ''
        ),

        'localizador' => array(
            'name' => 'localizador',
            'paper-size' => 'letter',
            'metric' => 'mm',
            'marginLeft' => 0,
            'marginTop' => 10,
            'NX' => 3,
            'NY' => 2,
            'SpaceX' => 0,
            'SpaceY' => 5,
            'width' => 90.6,
            'height' => 110.2,
            'font-size' => 90,
            'orientacao' => 'H',
            'style' => 'B'
        ),

        'arquivamento' => array(
            'name' => 'teste',
            'paper-size' => 'letter',
            'metric' => 'mm',
            'marginLeft' => 0,
            'marginTop' => 4,
            'NX' => 3,
            'NY' => 10,
            'SpaceX' => 16,
            'SpaceY' => 1,
            'width' => 60.6,
            'height' => 25.2,
            'font-size' => 20,
            'orientacao' => 'V',
            'style' => 'B'
        ),

        'autuacao' => array(
            'name' => 'teste',
            'paper-size' => 'letter',
            'metric' => 'mm',
            'marginLeft' => 10,
            'marginTop' => 8,
            'NX' => 1,
            'NY' => 5,
            'SpaceX' => 0,
            'SpaceY' => 3,
            'width' => 190.6,
            'height' => 50.2,
            'font-size' => 10,
            'orientacao' => 'V',
            'style' => ''
        ),

        'cerimonial' => array(
            'name' => 'cerimonial',
            'paper-size' => 'letter',
            'metric' => 'mm',
            'marginLeft' => 11,
            'marginTop' => 12,
            'NX' => 2,
            'NY' => 10,
            'SpaceX' => 5,
            'SpaceY' => 1,
            'width' => 101.6,
            'height' => 24.2,
            'font-size' => 6,
            'orientacao' => 'V',
            'style' => ''
        ),
    );

    // convert units (in to mm, mm to in)
    // $src and $dest must be 'in' or 'mm'
    function _Convert_Metric($value, $src, $dest)
    {
        if ($src != $dest) {
            $tab['in'] = 39.37008;
            $tab['mm'] = 1000;
            return $value * $tab[$dest] / $tab[$src];
        } else {
            return $value;
        }
    }

//alterado		
    // Give the height for a char size given.
    function _Get_Height_Chars($pt)
    {
        // Array matching character sizes and line heights
        $_Table_Hauteur_Chars = array(
            6 => 3.5,
            7 => 2.5,
            8 => 3,
            9 => 4,
            10 => 5,
            11 => 6,
            12 => 7,
            13 => 8,
            14 => 9,
            15 => 10,
            20 => 10,
            90 => 20
        );
        if (in_array($pt, array_keys($_Table_Hauteur_Chars))) {
            return $_Table_Hauteur_Chars[$pt];
        } else {
            return 100; // There is a prob..
        }
    }

    function _Set_Format($format)
    {
        $this->_Metric = $format['metric'];
        $this->_Avery_Name = $format['name'];
        $this->_Margin_Left = $this->_Convert_Metric($format['marginLeft'], $this->_Metric, $this->_Metric_Doc);
        $this->_Margin_Top = $this->_Convert_Metric($format['marginTop'], $this->_Metric, $this->_Metric_Doc);
        $this->_X_Space = $this->_Convert_Metric($format['SpaceX'], $this->_Metric, $this->_Metric_Doc);
        $this->_Y_Space = $this->_Convert_Metric($format['SpaceY'], $this->_Metric, $this->_Metric_Doc);
        $this->_X_Number = $format['NX'];
        $this->_Y_Number = $format['NY'];
        $this->_Width = $this->_Convert_Metric($format['width'], $this->_Metric, $this->_Metric_Doc);
        $this->_Height = $this->_Convert_Metric($format['height'], $this->_Metric, $this->_Metric_Doc);
        $this->Set_Font_Size($format['font-size']);
    }

    function __construct($format, $unit = 'mm', $posX = 1, $posY = 1)
    {
        if (is_array($format)) {
            // Custom format
            $Tformat = $format;
        } else {
            // Avery format
            $Tformat = $this->_Avery_Labels[$format];
        }

        parent::__construct('P', $Tformat['metric'], $Tformat['paper-size']);
        $this->_Set_Format($Tformat);
        $this->Set_Font_Name('Arial', $Tformat['style']);
        $this->SetMargins(0, 0);
        $this->SetAutoPageBreak(false);

        $this->_Metric_Doc = $unit;
        // Start at the given label position
        if ($posX > 1) {
            $posX--;
        } else {
            $posX = 0;
        }
        if ($posY > 1) {
            $posY--;
        } else {
            $posY = 0;
        }
        if ($posX >= $this->_X_Number) {
            $posX = $this->_X_Number - 1;
        }
        if ($posY >= $this->_Y_Number) {
            $posY = $this->_Y_Number - 1;
        }

        if ($Tformat['orientacao'] == 'V') {
            $this->_COUNTX = $posX;
            $this->_COUNTY = $posY;
        } elseif ($Tformat['orientacao'] == 'H') {
            $this->_COUNTX = $posY;
            $this->_COUNTY = $posX;
        }
    }

    // Sets the character size
    // This changes the line height too
    function Set_Font_Size($pt)
    {
        if ($pt > 3) {
            $this->_Char_Size = $pt;
            $this->_Line_Height = $this->_Get_Height_Chars($pt);
            $this->SetFontSize($this->_Char_Size);
        }
    }

    // Method to change font name
    function Set_Font_Name($fontname, $style)
    {
        if ($fontname != '') {
            $this->_Font_Name = $fontname;
            $this->SetFont($this->_Font_Name, $style);
        }
    }

    // Print a label
    function Add_PDF_Label($texte, $border, $align, $orientacao, $direcaoPreenchimento = "C")
    {
        // We are in a new page, then we must add a page
        if (($this->_COUNTX == 0) && ($this->_COUNTY == 0)) {
            $this->AddPage();
        }
        if ($this->PageNo() == 0) {
            $this->AddPage();
        }

        if ($orientacao == 'V') {
            $_PosX = $this->_Margin_Left + ($this->_COUNTX * ($this->_Width + $this->_X_Space));
            $_PosY = $this->_Margin_Top + ($this->_COUNTY * ($this->_Height + $this->_Y_Space));
        } elseif ($orientacao == 'H') {
            $_PosX = $this->_Margin_Left + ($this->_COUNTY * ($this->_Height + $this->_Y_Space));
            $_PosY = $this->_Margin_Top + ($this->_COUNTX * ($this->_Width + $this->_X_Space));
        }

        $this->SetXY($_PosX + 3, $_PosY + 3);
        $this->MultiCell($this->_Width, $this->_Line_Height, $texte, $border, $align);

        //primeiro preenche a coluna
        if ($direcaoPreenchimento == "C") {
            $this->_COUNTY++;

            if ($this->_COUNTY == $this->_Y_Number) {
                // End of column reached, we start a new one
                $this->_COUNTX++;
                $this->_COUNTY = 0;
            }

            if ($this->_COUNTX == $this->_X_Number) {
                // Page full, we start a new one
                $this->_COUNTX = 0;
                $this->_COUNTY = 0;
            }
            //primeiro preenche a linha
        } else {
            $this->_COUNTX++;

            if ($this->_COUNTX == $this->_X_Number) {
                // Page full, we start a new one
                $this->_COUNTX = 0;
                $this->_COUNTY++;
            }

            if ($this->_COUNTY == $this->_Y_Number) {
                // End of column reached, we start a new one
                $this->_COUNTX = 0;
                $this->_COUNTY = 0;
            }
        }
    }
}

?>
