<?php
require(__DIR__ ."/fpdf.php");

class PDF_INV_Table extends FPDF
{
var $widths;
var $aligns;

function SetWidths($w)
{
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns($a)
{
    //Set the array of column alignments
    $this->aligns=$a;
}

function SetCellMargin($margin) { 
    $this->cMargin = $margin; 
}

function AddHeader($data, $headerAlign)
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++){
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    }
        
    $h=4*$nb + 2;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=$headerAlign[$i];
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x,$y,$w,$h);
        //Print the text
        $this->SetFont('Arial', 'B', 10);
        $offsetHeight = $this->NbLines($this->widths[$i],$data[$i]);
        $yOff = ($h/2) - (($offsetHeight*4)/2);
        $this->SetXY($x, $y + $yOff);
        $this->MultiCell($w,4,$data[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function Row($data, $index)
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++){
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    }
        
    $h=3*$nb + 4;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x,$y,$w,$h);
        //Print the text
        $this->SetFont('Arial', '', 8);
        $offsetHeight = $this->NbLines($this->widths[$i],$data[$i]);
        $yOff = ($h/2) - (($offsetHeight*3)/2);
        $this->SetXY($x, $y + $yOff);
        $this->MultiCell($w,3,$data[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
        
    }
    //Go to the next line
    $this->Ln($h);
}

function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}
}
?>