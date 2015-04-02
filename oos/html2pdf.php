<?php

//function hex2dec
//returns an associative array (keys: R,G,B) from
//a hex html code (e.g. #3FE5AA)
function hex2dec($couleur = "#000000"){
    $R = substr($couleur, 1, 2);
    $rouge = hexdec($R);
    $V = substr($couleur, 3, 2);
    $vert = hexdec($V);
    $B = substr($couleur, 5, 2);
    $bleu = hexdec($B);
    $tbl_couleur = array();
    $tbl_couleur['R']=$rouge;
    $tbl_couleur['V']=$vert;
    $tbl_couleur['B']=$bleu;
    return $tbl_couleur;
}

//conversion pixel -> millimeter at 72 dpi
function px2mm($px){
    return $px*25.4/72;
}

function txtentities($html){
    $trans = get_html_translation_table(HTML_ENTITIES);
    $trans = array_flip($trans);
    return strtr($html, $trans);
}
////////////////////////////////////

class PDF_HTML extends FPDF
{
//variables of html parser
var $B;
var $I;
var $U;
var $HREF;
var $fontList;
var $issetfont;
var $issetcolor;

function PDF_HTML($orientation='P', $unit='mm', $format='A4')
{
    //Call parent constructor
    $this->FPDF($orientation,$unit,$format);
    //Initialization
    $this->B=0;
    $this->I=0;
    $this->U=0;
    $this->HREF='';
    $this->fontlist=array('arial', 'times', 'courier', 'helvetica', 'symbol');
    $this->issetfont=false;
    $this->issetcolor=false;
}

function WriteHTML($html,$marginno,$thetitle)
{
    //HTML parser
    $html=strip_tags($html,"<b><u><i><a><img><p><br><strong><em><font><tr><blockquote>"); //supprime tous les tags sauf ceux reconnus
    $html=str_replace("\n",' ',$html); //remplace retour à la ligne par un espace
    $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE); //éclate la chaîne avec les balises
			if($marginno != "no") {
			$this->SetX(9);
$this->SetLeftMargin('9');
			}   
			foreach($a as $i=>$e)
    {
        if($i%2==0)
        {
            //Text
            if($this->HREF)
                $this->PutLink($this->HREF,$e);
            else

                $this->Write(5,stripslashes(txtentities($e)));
        }
        else
        {
            //Tag
            if($e[0]=='/')
                $this->CloseTag(strtoupper(substr($e,1)));
            else
            {
                //Extract attributes
                $a2=explode(' ',$e);
                $tag=strtoupper(array_shift($a2));
                $attr=array();
                foreach($a2 as $v)
                {
                    if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                        $attr[strtoupper($a3[1])]=$a3[2];
                }
                $this->OpenTag($tag,$attr,$thetitle,$marginno);
            }
        }
    }
}

function OpenTag($tag, $attr, $thetitle, $marginno) {

    //Opening tag
    switch($tag){
        case 'STRONG':
            $this->SetStyle('B',true);
            break;
        case 'EM':
            $this->SetStyle('I',true);
            break;
        case 'B':
        case 'I':
        case 'U':
            $this->SetStyle($tag,true);
            break;
        case 'A':
            $this->HREF=$attr['HREF'];
            break;
        case 'IMG':
            if(isset($attr['SRC']) && (isset($attr['WIDTH']) || isset($attr['HEIGHT']))) {
                if(!isset($attr['WIDTH']))
                    $attr['WIDTH'] = 0;
                if(!isset($attr['HEIGHT']))
                    $attr['HEIGHT'] = 0;
					
					$header_image = "../images/invoice_header.jpg";
$ring_image = "../images/invoice_rings.jpg";
$bar_image = "../images/invoice_line.jpg";

 $get_current_y =  $this->GetY();
 if($get_current_y > 260) {
 $this->AddPage();
 $this->SetAuthor('Ionian Weddings');
 $this->SetTitle('');

 $this->SetY('5');
 $this->SetFont('Arial','B','10');  
 $this->SetTextColor(226,179,64);
 $this->SetFillColor(255,255,255);
 $this->SetDrawColor(226,179,64);
 $this->SetLineWidth(0.1); 
 $this->SetLeftMargin('100');
 $this->Cell(70,5,"Your personalised Wedding Proposal",'',0,'l',true);
 $this->Ln(4.2); 
 $this->SetFont('Arial','B','10'); 
 $this->SetTextColor(143,126,122);
 $this->Cell(70,5,stripslashes($thetitle),'',0,'l',true);
 $this->Ln(8);

 $this->SetY('5');
 $this->SetX('5');
 $this->SetLeftMargin('10');

 $this->Image($header_image, 10, 3,60);
 $this->Image($bar_image, 10, 19, 190,0.1);

 $this->SetY(24 + $attr['Y']);
 $this->SetX(8);
 $this->SetLeftMargin('8');
 $this->SetFont('Arial','','9');
 $this->SetTextColor(82,81,87);
 

 $change_y = 5;
 } else {
 $change_y = 5;
 }
 
                $this->Image($attr['SRC'], $attr['X'], $this->GetY()-($attr['Y']-$change_y), $attr['WIDTH'], $attr['HEIGHT']);

				
            }
            break;
        case 'TR':
        case 'BLOCKQUOTE':
        case 'BR':
            $this->Ln(4);
            break;
        case 'P':
		

$header_image = "../images/invoice_header.jpg";
$ring_image = "../images/invoice_rings.jpg";
$bar_image = "../images/invoice_line.jpg";

 $get_current_y =  $this->GetY();
 if($marginno == "no") { $get_current_y -= 4.4; }
 if($get_current_y > 250) {
 $this->AddPage();
 $this->SetAuthor('Ionian Weddings');
 $this->SetTitle('');

 $this->SetY('5');
 $this->SetFont('Arial','B','10');  
 $this->SetTextColor(226,179,64);
 $this->SetFillColor(255,255,255);
 $this->SetDrawColor(226,179,64);
 $this->SetLineWidth(0.1); 
 $this->SetLeftMargin('100');
 $this->Cell(70,5,"Your personalised Wedding Proposal",'',0,'l',true);
 $this->Ln(4.2); 
 $this->SetFont('Arial','B','10'); 
 $this->SetTextColor(143,126,122);
 $this->Cell(70,5,stripslashes($thetitle),'',0,'l',true);
 $this->Ln(8);

 $this->SetY('5');
 $this->SetX('5');
 $this->SetLeftMargin('10');

 $this->Image($header_image, 10, 3,60);
 $this->Image($bar_image, 10, 19, 190,0.1);

 $this->SetY('24');
 $this->SetX(8);
 $this->SetLeftMargin('8');
 $this->SetFont('Arial','','9');
 $this->SetTextColor(82,81,87);
 }

		 if(isset($attr['H'])) {
            $this->SetY($this->GetY() + $attr['H'] + 2);
						} else {
            $this->Ln(8);
						}
            break;
        case 'FONT':
            if (isset($attr['COLOR']) && $attr['COLOR']!='') {
                $coul=hex2dec($attr['COLOR']);
                $this->SetTextColor($coul['R'],$coul['V'],$coul['B']);
                $this->issetcolor=true;
            }
            if (isset($attr['FACE']) && in_array(strtolower($attr['FACE']), $this->fontlist)) {
                $this->SetFont(strtolower($attr['FACE']));
                $this->issetfont=true;
            }
            break;
    }
}

function CloseTag($tag)
{
    //Closing tag
    if($tag=='STRONG')
        $tag='B';
    if($tag=='EM')
        $tag='I';
    if($tag=='B' || $tag=='I' || $tag=='U')
        $this->SetStyle($tag,false);
    if($tag=='A')
        $this->HREF='';
    if($tag=='FONT'){
        if ($this->issetcolor==true) {
            $this->SetTextColor(0);
        }
        if ($this->issetfont) {
            $this->SetFont('arial');
            $this->issetfont=false;
        }
    }
}

function SetStyle($tag, $enable)
{
    //Modify style and select corresponding font
    $this->$tag+=($enable ? 1 : -1);
    $style='';
    foreach(array('B','I','U') as $s)
    {
        if($this->$s>0)
            $style.=$s;
    }
    $this->SetFont('',$style);
}

function PutLink($URL, $txt)
{
    //Put a hyperlink
    $this->SetTextColor(0,0,255);
    $this->SetStyle('U',true);
    $this->Write(5,$txt,$URL);
    $this->SetStyle('U',false);
    $this->SetTextColor(0);
}

}//end of class
?>