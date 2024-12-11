<?php
    namespace App\Controllers;

    class BulletinPremierTrimestre extends FPDF {
        private $etablissement;
        private $annee_scolaire;
        protected $wLine; // Maximum width of the line
		protected $hLine; // Height of the line
		protected $Text; // Text to display
		protected $border;
		protected $align; // Justification of the text
		protected $fill;
		protected $Padding;
		protected $lPadding;
		protected $tPadding;
		protected $bPadding;
		protected $rPadding;
		protected $TagStyle; // Style for each tag
		protected $Indent;
		protected $Bullet; // Bullet character
		protected $Space; // Minimum space between words
		protected $PileStyle;
		protected $Line2Print; // Line to display
		protected $NextLineBegin; // Buffer between lines 
		protected $TagName;
		protected $Delta; // Maximum width minus width
		protected $StringLength;
		protected $LineLength;
		protected $wTextLine; // Width minus paddings
		protected $nbSpace; // Number of spaces in the line
		protected $Xini; // Initial position
		protected $href; // Current URL
		protected $TagHref; // URL for a cell
		protected $LastLine;

		
		/*----------------------------------------------------------------
		* CELL UPDATING
		*
		*----------------------------------------------------------------*/
		function VCell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false)
		{
			//Output a cell
			$k = $this->k;
			if ($this->y + $h > $this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak()) {
				//Automatic page break
				$x = $this->x;
				$ws = $this->ws;
				if ($ws > 0) {
					$this->ws = 0;
					$this->_out('0 Tw');
				}
				$this->AddPage($this->CurOrientation, $this->CurPageSize);
				$this->x = $x;
				if ($ws > 0) {
					$this->ws = $ws;
					$this->_out(sprintf('%.3F Tw', $ws * $k));
				}
			}
			if ($w == 0)
				$w = $this->w - $this->rMargin - $this->x;
			$s = '';
			// begin change Cell function 
			if ($fill || (int)$border > 0) {
				if ($fill)
					$op = ((int)$border > 0) ? 'B' : 'f';
				else
					$op = 'S';
				if ((int)$border > 1) {
					$s = sprintf(
						'q %.2F w %.2F %.2F %.2F %.2F re %s Q ',
						$border,
						$this->x * $k,
						($this->h - $this->y) * $k,
						$w * $k,
						-$h * $k,
						$op
					);
				} else
					$s = sprintf('%.2F %.2F %.2F %.2F re %s ', $this->x * $k, ($this->h - $this->y) * $k, $w * $k, -$h * $k, $op);
			}
			if (is_string($border)) {
				$x = $this->x;
				$y = $this->y;
				if (is_int(strpos($border, 'L')))
					$s .= sprintf('%.2F %.2F m %.2F %.2F l S ', $x * $k, ($this->h - $y) * $k, $x * $k, ($this->h - ($y + $h)) * $k);
				else if (is_int(strpos($border, 'l')))
					$s .= sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ', $x * $k, ($this->h - $y) * $k, $x * $k, ($this->h - ($y + $h)) * $k);

				if (is_int(strpos($border, 'T')))
					$s .= sprintf('%.2F %.2F m %.2F %.2F l S ', $x * $k, ($this->h - $y) * $k, ($x + $w) * $k, ($this->h - $y) * $k);
				else if (is_int(strpos($border, 't')))
					$s .= sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ', $x * $k, ($this->h - $y) * $k, ($x + $w) * $k, ($this->h - $y) * $k);

				if (is_int(strpos($border, 'R')))
					$s .= sprintf('%.2F %.2F m %.2F %.2F l S ', ($x + $w) * $k, ($this->h - $y) * $k, ($x + $w) * $k, ($this->h - ($y + $h)) * $k);
				else if (is_int(strpos($border, 'r')))
					$s .= sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ', ($x + $w) * $k, ($this->h - $y) * $k, ($x + $w) * $k, ($this->h - ($y + $h)) * $k);

				if (is_int(strpos($border, 'B')))
					$s .= sprintf('%.2F %.2F m %.2F %.2F l S ', $x * $k, ($this->h - ($y + $h)) * $k, ($x + $w) * $k, ($this->h - ($y + $h)) * $k);
				else if (is_int(strpos($border, 'b')))
					$s .= sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ', $x * $k, ($this->h - ($y + $h)) * $k, ($x + $w) * $k, ($this->h - ($y + $h)) * $k);
			}
			if (trim($txt) != '') {
				$cr = substr_count($txt, "\n");
				if ($cr > 0) { // Multi line
					$txts = explode("\n", $txt);
					$lines = count($txts);
					for ($l = 0; $l < $lines; $l++) {
						$txt = $txts[$l];
						$w_txt = $this->GetStringWidth($txt);
						if ($align == 'U')
							$dy = $this->cMargin + $w_txt;
						elseif ($align == 'D')
							$dy = $h - $this->cMargin;
						else
							$dy = ($h + $w_txt) / 2;
						$txt = str_replace(')', '\\)', str_replace('(', '\\(', str_replace('\\', '\\\\', $txt)));
						if ($this->ColorFlag)
							$s .= 'q ' . $this->TextColor . ' ';
						$s .= sprintf(
							'BT 0 1 -1 0 %.2F %.2F Tm (%s) Tj ET ',
							($this->x + .5 * $w + (.7 + $l - $lines / 2) * $this->FontSize) * $k,
							($this->h - ($this->y + $dy)) * $k,
							$txt
						);
						if ($this->ColorFlag)
							$s .= ' Q ';
					}
				} else { // Single line
					$w_txt = $this->GetStringWidth($txt);
					$Tz = 100;
					if ($w_txt > $h - 2 * $this->cMargin) {
						$Tz = ($h - 2 * $this->cMargin) / $w_txt * 100;
						$w_txt = $h - 2 * $this->cMargin;
					}
					if ($align == 'U')
						$dy = $this->cMargin + $w_txt;
					elseif ($align == 'D')
						$dy = $h - $this->cMargin;
					else
						$dy = ($h + $w_txt) / 2;
					$txt = str_replace(')', '\\)', str_replace('(', '\\(', str_replace('\\', '\\\\', $txt)));
					if ($this->ColorFlag)
						$s .= 'q ' . $this->TextColor . ' ';
					$s .= sprintf(
						'q BT 0 1 -1 0 %.2F %.2F Tm %.2F Tz (%s) Tj ET Q ',
						($this->x + .5 * $w + .3 * $this->FontSize) * $k,
						($this->h - ($this->y + $dy)) * $k,
						$Tz,
						$txt
					);
					if ($this->ColorFlag)
						$s .= ' Q ';
				}
			}
			// end change Cell function 
			if ($s)
				$this->_out($s);
			$this->lasth = $h;
			if ($ln > 0) {
				//Go to next line
				$this->y += $h;
				if ($ln == 1)
					$this->x = $this->lMargin;
			} else
				$this->x += $w;
		}

		function Cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '')
		{
			//Output a cell
			$k = $this->k;
			if ($this->y + $h > $this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak()) {
				//Automatic page break
				$x = $this->x;
				$ws = $this->ws;
				if ($ws > 0) {
					$this->ws = 0;
					$this->_out('0 Tw');
				}
				$this->AddPage($this->CurOrientation, $this->CurPageSize);
				$this->x = $x;
				if ($ws > 0) {
					$this->ws = $ws;
					$this->_out(sprintf('%.3F Tw', $ws * $k));
				}
			}
			if ($w == 0)
				$w = $this->w - $this->rMargin - $this->x;
			$s = '';
			// begin change Cell function
			if ($fill || (int)$border > 0) {
				if ($fill)
					$op = ((int)$border > 0) ? 'B' : 'f';
				else
					$op = 'S';
				if ((int)$border > 1) {
					$s = sprintf(
						'q %.2F w %.2F %.2F %.2F %.2F re %s Q ',
						$border,
						$this->x * $k,
						($this->h - $this->y) * $k,
						$w * $k,
						-$h * $k,
						$op
					);
				} else
					$s = sprintf('%.2F %.2F %.2F %.2F re %s ', $this->x * $k, ($this->h - $this->y) * $k, $w * $k, -$h * $k, $op);
			}
			if (is_string($border)) {
				$x = $this->x;
				$y = $this->y;
				if (is_int(strpos($border, 'L')))
					$s .= sprintf('%.2F %.2F m %.2F %.2F l S ', $x * $k, ($this->h - $y) * $k, $x * $k, ($this->h - ($y + $h)) * $k);
				else if (is_int(strpos($border, 'l')))
					$s .= sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ', $x * $k, ($this->h - $y) * $k, $x * $k, ($this->h - ($y + $h)) * $k);

				if (is_int(strpos($border, 'T')))
					$s .= sprintf('%.2F %.2F m %.2F %.2F l S ', $x * $k, ($this->h - $y) * $k, ($x + $w) * $k, ($this->h - $y) * $k);
				else if (is_int(strpos($border, 't')))
					$s .= sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ', $x * $k, ($this->h - $y) * $k, ($x + $w) * $k, ($this->h - $y) * $k);

				if (is_int(strpos($border, 'R')))
					$s .= sprintf('%.2F %.2F m %.2F %.2F l S ', ($x + $w) * $k, ($this->h - $y) * $k, ($x + $w) * $k, ($this->h - ($y + $h)) * $k);
				else if (is_int(strpos($border, 'r')))
					$s .= sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ', ($x + $w) * $k, ($this->h - $y) * $k, ($x + $w) * $k, ($this->h - ($y + $h)) * $k);

				if (is_int(strpos($border, 'B')))
					$s .= sprintf('%.2F %.2F m %.2F %.2F l S ', $x * $k, ($this->h - ($y + $h)) * $k, ($x + $w) * $k, ($this->h - ($y + $h)) * $k);
				else if (is_int(strpos($border, 'b')))
					$s .= sprintf('q 2 w %.2F %.2F m %.2F %.2F l S Q ', $x * $k, ($this->h - ($y + $h)) * $k, ($x + $w) * $k, ($this->h - ($y + $h)) * $k);
			}
			if (trim($txt) != '') {
				$cr = substr_count($txt, "\n");
				if ($cr > 0) { // Multi line
					$txts = explode("\n", $txt);
					$lines = count($txts);
					for ($l = 0; $l < $lines; $l++) {
						$txt = $txts[$l];
						$w_txt = $this->GetStringWidth($txt);
						if ($align == 'R')
							$dx = $w - $w_txt - $this->cMargin;
						elseif ($align == 'C')
							$dx = ($w - $w_txt) / 2;
						else
							$dx = $this->cMargin;

						$txt = str_replace(')', '\\)', str_replace('(', '\\(', str_replace('\\', '\\\\', $txt)));
						if ($this->ColorFlag)
							$s .= 'q ' . $this->TextColor . ' ';
						$s .= sprintf(
							'BT %.2F %.2F Td (%s) Tj ET ',
							($this->x + $dx) * $k,
							($this->h - ($this->y + .5 * $h + (.7 + $l - $lines / 2) * $this->FontSize)) * $k,
							$txt
						);
						if ($this->underline)
							$s .= ' ' . $this->_dounderline($this->x + $dx, $this->y + .5 * $h + .3 * $this->FontSize, $txt);
						if ($this->ColorFlag)
							$s .= ' Q ';
						if ($link)
							$this->Link($this->x + $dx, $this->y + .5 * $h - .5 * $this->FontSize, $w_txt, $this->FontSize, $link);
					}
				} else { // Single line
					$w_txt = $this->GetStringWidth($txt);
					$Tz = 100;
					if ($w_txt > $w - 2 * $this->cMargin) { // Need compression
						$Tz = ($w - 2 * $this->cMargin) / $w_txt * 100;
						$w_txt = $w - 2 * $this->cMargin;
					}
					if ($align == 'R')
						$dx = $w - $w_txt - $this->cMargin;
					elseif ($align == 'C')
						$dx = ($w - $w_txt) / 2;
					else
						$dx = $this->cMargin;
					$txt = str_replace(')', '\\)', str_replace('(', '\\(', str_replace('\\', '\\\\', $txt)));
					if ($this->ColorFlag)
						$s .= 'q ' . $this->TextColor . ' ';
					$s .= sprintf(
						'q BT %.2F %.2F Td %.2F Tz (%s) Tj ET Q ',
						($this->x + $dx) * $k,
						($this->h - ($this->y + .5 * $h + .3 * $this->FontSize)) * $k,
						$Tz,
						$txt
					);
					if ($this->underline)
						$s .= ' ' . $this->_dounderline($this->x + $dx, $this->y + .5 * $h + .3 * $this->FontSize, $txt);
					if ($this->ColorFlag)
						$s .= ' Q ';
					if ($link)
						$this->Link($this->x + $dx, $this->y + .5 * $h - .5 * $this->FontSize, $w_txt, $this->FontSize, $link);
				}
			}
			// end change Cell function
			if ($s)
				$this->_out($s);
			$this->lasth = $h;
			if ($ln > 0) {
				//Go to next line
				$this->y += $h;
				if ($ln == 1)
					$this->x = $this->lMargin;
			} else
				$this->x += $w;
		}


        var $angle = 0;


        function Rotate($angle,$x=-1,$y=-1) {
            if($x == -1){
                $x = $this->x;
            }

            if($y == -1){
                $y=$this->y;
            }

            if($this->angle!=0){
                $this->_out('Q');
            }

            $this->angle = $angle;

            if($angle!=0)
            {
                $angle*=M_PI/180;
                $c=cos($angle);
                $s=sin($angle);
                $cx=$x*$this->k;
                $cy=($this->h-$y)*$this->k;
                $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
            }
        }

        function _endpage() {
            if($this->angle!=0)
            {
                $this->angle=0;
                $this->_out('Q');
            }
            parent::_endpage();
        }

        function RotatedText($x, $y, $filigrame, $angle){
            $this->Rotate($angle, $x, $y);
            $this->Text($x, $y, $filigrame);
            $this->Rotate(0);
        }

        function Filigramme($filigrame){
            // $this->SetTextColor(172,230,255); 
            $this->SetTextColor(235,235,235); 
            $this->SetFont('Times', 'I', 30);
            
            $positionY = 45;
            $positionX = 20;
            $this->RotatedText( $positionX,  $positionY, $filigrame, 50);

            $interY = $positionY = 40;
            $interX = $positionX = 100;
            for ($j=0; $j < 4; $j++) { 

        		$positionY = $interY;
        		$positionX = $interX;
            	 for ($i=0; $i < 4; $i++) { 
	            	$this->RotatedText( $positionX,  $positionY, $filigrame, 50);
	            	$positionY = $positionY + 60;
	           		$positionX = $positionX - 45;
	            }
	            $interX = $interX+80;
            }

            $this->SetTextColor(0,0,0);
        }


		/*----------------------------------------------------------------
		* ENTETE PORTRAIT
		*----------------------------------------------------------------*/

        
        public function __construct($orientation='P', $unit='mm', $size='A4') {
            parent::__construct($orientation, $unit, $size);
            $this->SetAutoPageBreak(true, 15);
            $this->SetMargins(10, 10, 10);
        }

        public function header_portrait($year, $school, $contact, $matricule){
	        $postx = 4;
	        $posty = 4;

	        /*--------------- left --------------*/
	        $this->SetFont('times', '','7');
	        $this->SetXY($postx,$posty+1);
	        $this->Cell(86,5,utf8_decode('REPUBLIQUE DU CAMEROUN'),0,0,'C');
	        $this->SetXY($postx,$posty+4);
	        $this->Cell(86,5,utf8_decode('Paix - Travail - Patrie'),0,0,'C');
			$this->SetXY($postx,$posty+4+3);
			$this->Cell(86,5,utf8_decode('*********'),0,0,'C');
			$this->SetXY($postx,$posty+4+3+3);
			$this->Cell(86,5,utf8_decode('MINISTÈRE DES ENSEIGNEMENTS SECONDAIRES'),0,0,'C');
			$this->SetXY($postx,$posty+4+3+3+3);
			$this->Cell(86,5,utf8_decode('*********'),0,0,'C');
			$this->SetXY($postx,$posty+4+3+3+3+3);
			$this->Cell(86,5,utf8_decode('DÉLÉGATION RÉGIONALE DE L\'OUEST'),0,0,'C');
			$this->SetXY($postx,$posty+4+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode('*********'),0,0,'C');
			$this->SetXY($postx,$posty+4+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode("DÉLÉGATION DÉPARTEMENTALE DE LA MIFI"),0,0,'C');
			$this->SetXY($postx,$posty+4+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode('*********'),0,0,'C');
			$this->SetXY($postx,$posty+4+3+3+3+3+3+3+3);
			$this->SetFont('times', 'B','8');
			$this->SetXY($postx,$posty+4+3+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode(mb_strtoupper($school)),0,0,'C');
			$this->SetXY($postx,$posty+4+3+3+3+3+3+3+3+3+3);
			$this->SetFont('times', 'I','6');
			$this->Cell(86,5,utf8_decode("BP: Bafoussam--Tel: ".$contact),0,0,'C');
			$this->SetXY($postx,$posty+4+3+3+3+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode("IMMATRICULATION: ".strtoupper($matricule)),0,0,'C');
	        /*--------------*/
	        $this->Image('logo.jpg', $postx+92,$posty+5,25,25);
			$this->SetFont('arial', 'I','7');
			$this->SetXY($postx+86,$posty+4+3+3+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode("ANNÉE SCOLAIRE "),0,0,'L');
			$this->SetXY($postx+86+24,$posty+4+3+3+3+3+3+3+3+3+3);
			$this->SetFont('arial', 'B','7');
			$this->Cell(86,5,utf8_decode(" ".$year),0,0,'L');
			$this->SetFont('arial', 'I','7');
			$this->SetXY($postx+86+23,$posty+4+3+3+3+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode(": "),0,0,'L');
			$this->SetXY($postx+86,$posty+4+3+3+3+3+3+3+3+3+3+3+1.5);
			$this->SetFont('arial', 'I','6');
			$this->Cell(86,5,utf8_decode("SCHOOL YEAR "),0,0,'L');
	        /*--------------*/
	        /*--------------- rigth --------------*/
	        $this->SetFont('times', '','7');
	        $this->SetXY($postx+120,$posty+1);
	        $this->Cell(86,5,utf8_decode('REPUBLIC OF CAMEROUN'),0,0,'C');
	        $this->SetXY($postx+120,$posty+4);
	        $this->Cell(86,5,utf8_decode('Peace - Work - Fatherland'),0,0,'C');
			$this->SetXY($postx+120,$posty+4+3);
			$this->Cell(86,5,utf8_decode('*********'),0,0,'C');
			$this->SetXY($postx+120,$posty+4+3+3);
			$this->Cell(86,5,utf8_decode('MINISTRY OF SECONDARY EDUCATION'),0,0,'C');
			$this->SetXY($postx+120,$posty+4+3+3+3);
			$this->Cell(86,5,utf8_decode('*********'),0,0,'C');
			$this->SetXY($postx+120,$posty+4+3+3+3+3);
			$this->Cell(86,5,utf8_decode('REGIONAL DELEGATION OF WEST'),0,0,'C');
			$this->SetXY($postx+120,$posty+4+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode('*********'),0,0,'C');
			$this->SetXY($postx+120,$posty+4+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode("DIVISIONAL DELEGATION MIFI"),0,0,'C');

			$this->SetXY($postx+120,$posty+4+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode('*********'),0,0,'C');
			$this->SetFont('times', 'B','8');
			$this->SetXY($postx+120,$posty+4+3+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode(mb_strtoupper($school)),0,0,'C');
			$this->SetXY($postx+120,$posty+4+3+3+3+3+3+3+3+3+3);
			$this->SetFont('times', 'I','6');
			$this->Cell(86,5,utf8_decode("P.O. Box: Bafoussam - Tel: ".$contact),0,0,'C');
			$this->SetXY($postx+120,$posty+4+3+3+3+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode("REGISTRATION NUMBER: ".strtoupper($matricule)),0,0,'C');
	        $this->SetFont('times', 'B','8');
	    }


		// remplir les informations d'un élève
		function AddHeaderStudent($studentInfo, $effectif, $teacher)
		{	
			$months_fr = [
				'janvier', 'février', 'mars', 'avril', 'mai', 'juin',
				'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'
			];
			$timestamp = strtotime($studentInfo['date_of_birth']);
			$day = date('d', $timestamp);
			$month = $months_fr[date('n', $timestamp) - 1];
			$year = date('Y', $timestamp);
			$date_day = $day . ' ' . $month . ' ' . $year;

	        $postx = 12;
			$posty = 3;

			// Photo
			$this->SetFont('times', '', 8);
			$this->SetDrawColor(0, 0, 0);
			$this->SetFillColor(164, 227, 136);
			$this->SetTextColor(0, 0, 0);
			$this->Rect($postx+1,$posty + 45+7,30,22);
			$this->Image('user.png', $postx+1,$posty + 45+7,25,20);

			// Informations personnelles
			$this->SetXY($postx + 32,$posty + 45 + 7);
			$this->Cell(110, 5, utf8_decode('Nom et Prénoms: ' . $studentInfo['nom']), 1, 1, 'L', false);

			$this->SetXY($postx + 1 + 42 + 99, $posty + 45 + 7);
			$this->Cell(49, 5, utf8_decode('Classe: ' . $studentInfo['classe']), 1, 1, 'L', false);

			$this->SetXY($postx + 32,$posty + 45 + 10 + 2);
			$this->Cell(80, 5, utf8_decode('Date et lieu de naissance: ' . (($date_day) .' à '. $studentInfo['birth_place'])), 1, 1, 'L', false);

			$this->SetXY($postx + 1 + 42 + 99 - 30, $posty + 45 + 10 + 2);
			$this->Cell(30, 5, utf8_decode('Genre: ' . $studentInfo['sexe']), 1, 1, 'L', false);

			$this->SetXY($postx + 1 + 42 + 99, $posty + 45 + 10 + 2);
			$this->Cell(49, 5, utf8_decode('Effectif:'. $effectif), 1, 1, 'L', false); // À remplir avec l'effectif total

			$this->SetXY($postx + 32, $posty + 45 + 10 + 7);
			$this->Cell(75, 5, utf8_decode('Identifiant unique: ' . $studentInfo['mat']), 1, 1, 'L', false);

			$this->SetXY($postx + 1 + 42 + 99 - 30 - 5, $posty + 45 + 7 + 5 + 5);
			$this->Cell(35, 5, utf8_decode('Redoublant: ' . ($studentInfo['redouble'])), 1, 1, 'L', false);

			$this->SetXY($postx + 1 + 42 + 99, $posty + 45 + 7 + 5 + 5);
			$content = utf8_decode("Professeur principal:\n" . $teacher);
			$this->MultiCell(49, 6, $content, 1, 'L', false);			

			$this->SetXY($postx + 32, $posty + 45 + 7 + 5 + 5 + 5);
			$this->Cell(110, 7, utf8_decode('Nom et contacts des Parents / Tuteurs : ' . $studentInfo['parent'] . ' - ' . $studentInfo['phone']), 1, 1, 'L', false);
		}

        function listing($data_teaching, $title){

	        $postx = 12;
			$posty = 10;
			global $y;
	        /*-------------------*/ // entete
			$this->SetFont('times', 'B', 10);
			$this->SetDrawColor(71, 151, 45);
	        $this->SetFillColor(71, 151, 45);
	        $this->SetTextColor(255, 255, 255);
			$this->SetXY($postx+10,$posty+35);
	        $this->Cell(175,7,utf8_decode($title),1,1,'C', true);

	        $postx = 12;
	        $posty = $posty + 5;

			$this->SetFont('times', '', 8);
			$this->SetDrawColor(0, 0, 0);
	        $this->SetFillColor(192, 192, 192);
	        $this->SetTextColor(0, 0, 0);
	        $this->SetXY($postx+1,$posty+45+9+6+5);
	        $this->Cell(30,8,utf8_decode("Matières & Enseignant"),1,1,'C', true);
			$this->SetXY($postx+31,$posty+45+9+6+5);
	        $this->Cell(60,8,utf8_decode('COMPÉTENCES ÉVALUÉES'),1,1,'C', true);
	        $this->SetXY($postx+8+6+27+22+8+20,$posty+45+9+6+5);
	        $this->Cell(10,8,utf8_decode('N/20'),1,1,'C', true);
	        $this->SetXY($postx+8+6+27+32+8+20,$posty+45+9+6+5);
	        $this->Cell(13,8,utf8_decode('M/20'),1,1,'C', true);
			$this->SetXY($postx+8+6+27+42+2+9+20,$posty+45+9+6+5);
	        $this->Cell(10,8,utf8_decode('Coef'),1,1,'C', true);
	        $this->SetXY($postx+8+6+27+42+12+9+20,$posty+45+9+6+5);
	        $this->Cell(12,8,utf8_decode('M x Coef'),1,1,'C', true);
	        $this->SetXY($postx+8+6+27+42+10+12+6+5+20,$posty+45+9+6+5);
	        $this->Cell(20,8,utf8_decode('Min - Max] '),1,1,'C', true);
			$this->SetXY($postx+8+6+27+42+10+10+28+5+20,$posty+45+9+6+5);
	        $this->Cell(35,8,utf8_decode('Appréciations & Visa de l’enseignant '),1,1,'C', true);

	        $count = 0;
	        $this->SetFont('times', '','7');
			$sommeCoef = 0;
			$sommeNotexCoef = 0;
	        foreach ($data_teaching as $teaching) { 
				$this->SetXY($postx + 1, $posty + 45 + 9 + 6 + 3 + 10);
				// $content = utf8_decode($teaching['name']) . "\n" . utf8_decode($teaching['teacher']);
				$content = utf8_decode( ucfirst($teaching['name']) );
				$this->MultiCell(30, 10, $content, 1, 'L', false);

				$this->SetXY($postx+31,$posty+45+9+6+3+10);
				$this->Cell(60,10,utf8_decode(''),1,1,'L', false);
				$this->SetXY($postx+8+6+27+22+8+20,$posty+45+9+3+6+10);
				$this->Cell(10,10,utf8_decode(" "),1,1,'C', false);
				$this->SetXY($postx+8+6+27+32+8+20,$posty+45+9+3+6+10);
				$this->Cell(13,10,utf8_decode($teaching['note']),1,1,'C', false);
				$this->SetXY($postx+8+6+27+42+2+9+20,$posty+45+9+3+6+10);
				$this->Cell(10,10,utf8_decode($teaching['coefficient']),1,1,'C', false);
				$this->SetXY($postx+8+6+27+42+12+9+20,$posty+45+9+3+6+10);
				$this->Cell(12,10,utf8_decode($teaching['note'] * $teaching['coefficient']),1,1,'C', false);
				$this->SetXY($postx+8+6+27+42+10+12+6+5+20,$posty+45+9+3+6+10);
				$this->Cell(20,10,utf8_decode('['. $teaching['min'] .'-'. $teaching['max'].']'),1,1,'C', false);
				$this->SetXY($postx+8+6+27+42+10+10+28+5+20,$posty+45+9+3+6+10);
				$this->Cell(35,10,utf8_decode($teaching['appreciation']),1,1,'L', false);

				$posty = $posty + 10;
				$sommeCoef += $teaching['coefficient'];
				$sommeNotexCoef += $teaching['note'] * $teaching['coefficient'] ;
	        }

	        $this->SetXY($postx+1,$posty+45+9+3+6+10);
	        $this->Cell(113,5,utf8_decode("TOTAL"),1,1,'R', false);
			$this->SetXY($postx+8+6+27+42+2+9+20,$posty+45+9+3+6+10);
	        $this->Cell(10,5,utf8_decode($sommeCoef),1,1,'C', false);
			$this->SetXY($postx+8+6+27+42+12+9+20,$posty+45+9+3+6+10);
			$this->Cell(12,5,utf8_decode($sommeNotexCoef),1,1,'C', false);
	        $this->SetXY($postx+8+6+27+42+12+9+40-8,$posty+45+9+3+6+10);
	        $this->Cell(55,5,utf8_decode("MOYENNE : " .$teaching['moyenne']. " /20"),1,1,'L', false);

			$y = $posty+45+9+3+6+10;

    	}


		function footerBulletin($footerData){
			$postx = 12;
			// $posty = 155;
			global $y;
			$posty = $y - 70;

			$this->SetXY($postx+1,$posty+45+9+6+6+10+13-7);
	        $this->Cell(64,5,utf8_decode("Discipline"),1,1,'C', false);
	        $this->SetXY($postx+65,$posty+45+9+6+6+10+13-7);
	        $this->Cell(64,5,utf8_decode("Travail de l'élève"),1,1,'C', false);
			$this->SetXY($postx+65+64,$posty+45+9+6+6+10+13-7);
	        $this->Cell(63,5,utf8_decode("Profil de la classe"),1,1,'C', false);

			$posty = $posty + 5;

			$this->SetXY($postx+1,$posty+45+9+6+6+10+13-7);
	        $this->Cell(20,5,utf8_decode("Abs. non j. (h)"),1,1,'C', false);
	        $this->SetXY($postx+21,$posty+45+9+6+6+10+13-7);
	        $this->Cell(10,5,utf8_decode(""),1,1,'C', false);
			$this->SetXY($postx+21+10,$posty+45+9+6+6+10+13-7);
	        $this->Cell(24,5,utf8_decode("Avertissement de Con."),1,1,'C', false);
			$this->SetXY($postx+21+10+24,$posty+45+9+6+6+10+13-7);
	        $this->Cell(10,5,utf8_decode(""),1,1,'C', false);
	        $this->SetXY($postx+21+10+24+10,$posty+45+9+6+6+10+13-7);
	        $this->Cell(20,5,utf8_decode("TOTAL Géné"),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20,$posty+45+9+6+6+10+13-7);
	        $this->Cell(12,5,utf8_decode($footerData['total_gene']),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20+12,$posty+45+9+6+6+10+13-7);
	        $this->Cell(32,5,utf8_decode("APPRECIATON"),1,1,'C', false);
			$this->SetXY($postx+31+34+30+22+12,$posty+45+9+6+6+10+13-7);
	        $this->Cell(23,5,utf8_decode("Moyenne Générale"),1,1,'C', false);
	        $this->SetXY($postx+31+34+30+22+12+23,$posty+45+9+6+6+10+13-7);
	        $this->Cell(40,5,utf8_decode($footerData['moyenne_gene']),1,1,'L', false);


			$posty = $posty + 10;

			$this->SetXY($postx+1,$posty+45+9+6+6+10+13-12);
	        $this->Cell(20,7,utf8_decode("Abs.just. (h)"),1,1,'C', false);
	        $this->SetXY($postx+21,$posty+45+9+6+6+10+13-12);
	        $this->Cell(10,7,utf8_decode(""),1,1,'C', false);
			$this->SetXY($postx+21+10,$posty+45+9+6+6+10+13-12);
	        $this->Cell(24,7,utf8_decode("Blâme de con."),1,1,'C', false);
			$this->SetXY($postx+21+10+24,$posty+45+9+6+6+10+13-12);
	        $this->Cell(10,7,utf8_decode(""),1,1,'C', false);
	        $this->SetXY($postx+21+10+24+10,$posty+45+9+6+6+10+13-12);
	        $this->Cell(20,7,utf8_decode("Coef"),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20,$posty+45+9+6+6+10+13-12);
	        $this->Cell(12,7,utf8_decode($footerData['total_coef']),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20+12,$posty+45+9+6+6+10+13-12);
	        $this->Cell(20,3.5,utf8_decode("CTBA"),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20+12+20,$posty+45+9+6+6+10+13-12);
	        $this->Cell(12,3.5,utf8_decode($footerData['appreciation'] == 'CTBA' ? 'OUI' : '-'),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20+12,$posty+45+9+6+6+10+13-12+3.5);
	        $this->Cell(20,3.5,utf8_decode("CBA"),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20+12+20,$posty+45+9+6+6+10+13-12+3.5);
	        $this->Cell(12,3.5,utf8_decode($footerData['appreciation'] == 'CBA' ? 'OUI' : '-'),1,1,'C', false);
			$this->SetXY($postx+31+34+30+22+12,$posty+45+9+6+6+10+13-12);
	        $this->Cell(23,7,utf8_decode("[Min - Max]"),1,1,'C', false);
	        $this->SetXY($postx+31+34+30+22+12+23,$posty+45+9+6+6+10+13-12);
	        $this->Cell(40,7,utf8_decode('[' . $footerData['lowest'] .' /20 - '. $footerData['highest'] . ' /20]'),1,1,'L', false);

			$posty = $posty + 7;

			$this->SetXY($postx+1,$posty+45+9+6+6+10+13-12);
	        $this->Cell(20,7,utf8_decode("Retards nbre"),1,1,'C', false);
	        $this->SetXY($postx+21,$posty+45+9+6+6+10+13-12);
	        $this->Cell(10,7,utf8_decode(""),1,1,'C', false);
			$this->SetXY($postx+21+10,$posty+45+9+6+6+10+13-12);
	        $this->Cell(24,7,utf8_decode("Exclusions (jrs)."),1,1,'C', false);
			$this->SetXY($postx+21+10+24,$posty+45+9+6+6+10+13-12);
	        $this->Cell(10,7,utf8_decode(""),1,1,'C', false);
	        $this->SetXY($postx+21+10+24+10,$posty+45+9+6+6+10+13-12);
	        $this->Cell(20,7,utf8_decode("Moyenne Trim"),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20,$posty+45+9+6+6+10+13-12);
	        $this->Cell(12,7,utf8_decode($footerData['moyenne_trim']),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20+12,$posty+45+9+6+6+10+13-12);
	        $this->Cell(20,3.5,utf8_decode("CA"),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20+12+20,$posty+45+9+6+6+10+13-12);
	        $this->Cell(12,3.5,utf8_decode($footerData['appreciation'] == 'CA' ? 'OUI' : '-'),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20+12,$posty+45+9+6+6+10+13-12+3.5);
	        $this->Cell(20,3.5,utf8_decode("CMA"),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20+12+20,$posty+45+9+6+6+10+13-12+3.5);
	        $this->Cell(12,3.5,utf8_decode($footerData['appreciation'] == 'CMA' ? 'OUI' : '-'),1,1,'C', false);
			$this->SetXY($postx+31+34+30+22+12,$posty+45+9+6+6+10+13-12);
	        $this->Cell(23,7,utf8_decode("Nombre de Myne"),1,1,'C', false);
	        $this->SetXY($postx+31+34+30+22+12+23,$posty+45+9+6+6+10+13-12);
	        $this->Cell(40,7,utf8_decode($footerData['nbre_reussite'] .' / '. $footerData['total_eleve']),1,1,'L', false);


			$posty = $posty + 7;

			$this->SetXY($postx+1,$posty+45+9+6+6+10+13-12);
	        $this->Cell(20,7,utf8_decode("Consignes"),1,1,'C', false);
	        $this->SetXY($postx+21,$posty+45+9+6+6+10+13-12);
	        $this->Cell(10,7,utf8_decode(""),1,1,'C', false);
			$this->SetXY($postx+21+10,$posty+45+9+6+6+10+13-12);
	        $this->Cell(24,7,utf8_decode("Exclusions définitive."),1,1,'C', false);
			$this->SetXY($postx+21+10+24,$posty+45+9+6+6+10+13-12);
	        $this->Cell(10,7,utf8_decode(""),1,1,'C', false);
	        $this->SetXY($postx+21+10+24+10,$posty+45+9+6+6+10+13-12);
	        $this->Cell(20,7,utf8_decode("Cote"),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20,$posty+45+9+6+6+10+13-12);
	        $this->Cell(12,7,utf8_decode($footerData['cote']),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20+12,$posty+45+9+6+6+10+13-12);
	        $this->Cell(20,7,utf8_decode("CNA"),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20+12+20,$posty+45+9+6+6+10+13-12);
	        $this->Cell(12,7,utf8_decode($footerData['appreciation'] == 'CNA' ? 'OUI' : '-'),1,1,'C', false);
			$this->SetXY($postx+31+34+30+22+12,$posty+45+9+6+6+10+13-12);
	        $this->Cell(23,7,utf8_decode("T. de réussite"),1,1,'C', false);
	        $this->SetXY($postx+31+34+30+22+12+23,$posty+45+9+6+6+10+13-12);
	        $this->Cell(40,7,utf8_decode($footerData['pourcentageR'] .'%'),1,1,'L', false);

			$posty = $posty + 7;

			$this->SetXY($postx+1,$posty+45+9+6+6+10+13-12);
	        $this->Cell(60,5,utf8_decode("Appréciation du travail de l’élève (points forts et points à améliorer)"),array('L','T','R'),1,'C', false);
			$this->SetXY($postx+61,$posty+45+9+6+6+10+13-12);
	        $this->Cell(35,5,utf8_decode("Visa du parent / Tuteur"),array('L','T','R'),1,'C', false);
			$this->SetXY($postx+61+35,$posty+45+9+6+6+10+13-12);
	        $this->Cell(35,5,utf8_decode("Nom et visa du professeur principal"),array('L','T','R'),1,'C', false);
	        $this->SetXY($postx+61+35+35,$posty+45+9+6+6+10+13-12);
	        $this->Cell(61,5,utf8_decode("Le Chef d’établissement"),array('L','T','R'),1,'L', false);

			$posty = $posty + 5;

			$this->SetXY($postx+1,$posty+45+9+6+6+10+13-12);
	        $this->Cell(60,15,utf8_decode(""),array('L','R','B'),1,'C', false);
			$this->SetXY($postx+61,$posty+45+9+6+6+10+13-12);
	        $this->Cell(35,15,utf8_decode(""),array('L','R','B'),1,'C', false);
			$this->SetXY($postx+61+35,$posty+45+9+6+6+10+13-12);
	        $this->Cell(35,15,utf8_decode(""),array('L','R','B'),1,'C', false);
	        $this->SetXY($postx+61+35+35,$posty+45+9+6+6+10+13-12);
	        $this->Cell(61,15,utf8_decode(""),array('L','R','B'),1,'L', false);
		}


		//SECTION ANGLOPHONE
		function AddHeaderStudentAnglo($studentInfo, $effectif, $teacher)
		{
			$months_en = [
				'January', 'February', 'March', 'April', 'May', 'June',
				'July', 'August', 'September', 'October', 'November', 'December'
			];
			$timestamp = strtotime($studentInfo['date_of_birth']);
			$day = date('d', $timestamp);
			$month = $months_en[date('n', $timestamp) - 1];
			$year = date('Y', $timestamp);
			$date_day = $day . ' ' . $month . ' ' . $year;

			$postx = 12;
			$posty = 3;
			// Photo
			$this->SetFont('times', '', 8);
			$this->SetDrawColor(0, 0, 0);
			$this->SetFillColor(164, 227, 136);
			$this->SetTextColor(0, 0, 0);
			$this->Rect($postx+1,$posty + 45+7,30,22);
			$this->Image('user.png', $postx+1,$posty + 45+7,25,20);

			// Informations personnelles
			$this->SetXY($postx + 32,$posty + 45 + 7);
			$this->Cell(110, 5, utf8_decode('Name of Student: ' . $studentInfo['nom']), 1, 1, 'L', false);

			$this->SetXY($postx + 1 + 42 + 99, $posty + 45 + 7);
			$this->Cell(49, 5, utf8_decode('Class: ' . $studentInfo['classe']), 1, 1, 'L', false);

			$this->SetXY($postx + 32,$posty + 45 + 10 + 2);
			$this->Cell(80, 5, utf8_decode('Date and place of birth: ' . (($date_day) .' à '. $studentInfo['birth_place'])), 1, 1, 'L', false);

			$this->SetXY($postx + 1 + 42 + 99 - 30, $posty + 45 + 10 + 2);
			$this->Cell(30, 5, utf8_decode('Gender: ' . $studentInfo['sexe']), 1, 1, 'L', false);

			$this->SetXY($postx + 1 + 42 + 99, $posty + 45 + 10 + 2);
			$this->Cell(49, 5, utf8_decode('Class enrolment:'. $effectif), 1, 1, 'L', false); // À remplir avec l'effectif total

			$this->SetXY($postx + 32, $posty + 45 + 10 + 7);
			$this->Cell(75, 5, utf8_decode('Unique Identification number: ' . $studentInfo['mat']), 1, 1, 'L', false);

			$this->SetXY($postx + 1 + 42 + 99 - 30 - 5, $posty + 45 + 7 + 5 + 5);
			$this->Cell(35, 5, utf8_decode('Repeater : ' . ($studentInfo['redouble'])), 1, 1, 'L', false);

			// $this->SetXY($postx + 1 + 42 + 99, $posty + 45 + 7 + 5 + 5);
			// $this->Cell(49, 12, utf8_decode('Class master: '), 1, 1, 'C', false); // Ajouter nom du professeur si disponible

			$this->SetXY($postx + 1 + 42 + 99, $posty + 45 + 7 + 5 + 5);
			$content = utf8_decode("Class master:\n" . $teacher);
			$this->MultiCell(49, 6, $content, 1, 'L', false);

			$this->SetXY($postx + 32, $posty + 45 + 7 + 5 + 5 + 5);
			$this->Cell(110, 7, utf8_decode('Parent’s/Guardian’s name and contact : ' . $studentInfo['parent'] . ' - ' . $studentInfo['phone']), 1, 1, 'L', false);
		}

		function listingAnglo($data_teaching, $title){

	        $postx = 12;
			$posty = 10;
			global $y;

	        /*-------------------*/ // entete
			$this->SetFont('times', 'B', 10);
			$this->SetDrawColor(71, 151, 45);
	        $this->SetFillColor(71, 151, 45);
	        $this->SetTextColor(255, 255, 255);
			$this->SetXY($postx+10,$posty+33);
	        $this->Cell(175,7,utf8_decode($title),1,1,'C', true);

	        $postx = 12;
	        $posty = $posty + 5;

			$this->SetFont('times', '', 8);
			$this->SetDrawColor(0, 0, 0);
	        $this->SetFillColor(192, 192, 192);
	        $this->SetTextColor(0, 0, 0);
	        $this->SetXY($postx+1,$posty+45+9+6+5);
	        $this->Cell(30,8,utf8_decode("Subject and Teacher’s Names"),1,1,'C', true);
			$this->SetXY($postx+31,$posty+45+9+6+5);
	        $this->Cell(60,8,utf8_decode('COMPETENCIES EVALUATED'),1,1,'C', true);
	        $this->SetXY($postx+8+6+27+22+8+20,$posty+45+9+6+5);
	        $this->Cell(10,8,utf8_decode('MK/20'),1,1,'C', true);
	        $this->SetXY($postx+8+6+27+32+8+20,$posty+45+9+6+5);
	        $this->Cell(13,8,utf8_decode('AV/20'),1,1,'C', true);
			$this->SetXY($postx+8+6+27+42+2+9+20,$posty+45+9+6+5);
	        $this->Cell(10,8,utf8_decode('Coef'),1,1,'C', true);
	        $this->SetXY($postx+8+6+27+42+12+9+20,$posty+45+9+6+5);
	        $this->Cell(12,8,utf8_decode('AV x Coef'),1,1,'C', true);
	        $this->SetXY($postx+8+6+27+42+10+12+6+5+20,$posty+45+9+6+5);
	        $this->Cell(20,8,utf8_decode('Min - Max] '),1,1,'C', true);
			$this->SetXY($postx+8+6+27+42+10+10+28+5+20,$posty+45+9+6+5);
	        $this->Cell(35,8,utf8_decode('Remarks and Teacher’s signature'),1,1,'C', true);

	        $count = 0;
	        $this->SetFont('times', '','7');
			$sommeCoef = 0;
			$sommeNotexCoef = 0;
	        foreach ($data_teaching as $teaching) { 
				
				$this->SetXY($postx+1,$posty+45+9+6+3+10);
				$this->Cell(30,7,utf8_decode($teaching['name']),1,1,'L', false);
				$this->SetXY($postx+31,$posty+45+9+6+3+10);
				$this->Cell(60,7,utf8_decode(''),1,1,'L', false);
				$this->SetXY($postx+8+6+27+22+8+20,$posty+45+9+3+6+10);
				$this->Cell(10,7,utf8_decode(" "),1,1,'C', false);
				$this->SetXY($postx+8+6+27+32+8+20,$posty+45+9+3+6+10);
				$this->Cell(13,7,utf8_decode($teaching['note']),1,1,'C', false);
				$this->SetXY($postx+8+6+27+42+2+9+20,$posty+45+9+3+6+10);
				$this->Cell(10,7,utf8_decode($teaching['coefficient']),1,1,'C', false);
				$this->SetXY($postx+8+6+27+42+12+9+20,$posty+45+9+3+6+10);
				$this->Cell(12,7,utf8_decode($teaching['note'] * $teaching['coefficient']),1,1,'C', false);
				$this->SetXY($postx+8+6+27+42+10+12+6+5+20,$posty+45+9+3+6+10);
				$this->Cell(20,7,utf8_decode('['. $teaching['min'] .'-'. $teaching['max'].']'),1,1,'C', false);
				$this->SetXY($postx+8+6+27+42+10+10+28+5+20,$posty+45+9+3+6+10);
				$this->Cell(35,7,utf8_decode($teaching['appreciation']),1,1,'L', false);

				$posty = $posty +7;
				$sommeCoef += $teaching['coefficient'];
				$sommeNotexCoef += $teaching['note'] * $teaching['coefficient'] ;
	        }

	        $this->SetXY($postx+1,$posty+45+9+3+6+10);
	        $this->Cell(113,5,utf8_decode("TOTAL"),1,1,'R', false);
			$this->SetXY($postx+8+6+27+42+2+9+20,$posty+45+9+3+6+10);
	        $this->Cell(10,5,utf8_decode($sommeCoef),1,1,'C', false);
			$this->SetXY($postx+8+6+27+42+12+9+20,$posty+45+9+3+6+10);
			$this->Cell(12,5,utf8_decode($sommeNotexCoef),1,1,'C', false);
	        $this->SetXY($postx+8+6+27+42+12+9+40-8,$posty+45+9+3+6+10);
	        $this->Cell(55,5,utf8_decode("STUDENT AVERAGE: : " .$teaching['moyenne']. " /20"),1,1,'L', false);
			$y = $posty+45+9+3+6+10;
    	}


		function footerBulletinAnglo($footerData){
			$postx = 12;
			// $posty = 152 - 5;
			global $y;
			$posty = $y - 70;

			$this->SetXY($postx+1,$posty+45+9+6+6+10+13-7);
	        $this->Cell(64,5,utf8_decode("Discipline"),1,1,'C', false);
	        $this->SetXY($postx+65,$posty+45+9+6+6+10+13-7);
	        $this->Cell(64,5,utf8_decode("Student performance"),1,1,'C', false);
			$this->SetXY($postx+65+64,$posty+45+9+6+6+10+13-7);
	        $this->Cell(63,5,utf8_decode("Class Profile"),1,1,'C', false);

			$posty = $posty + 5;

			$this->SetXY($postx+1,$posty+45+9+6+6+10+13-7);
	        $this->Cell(20,5,utf8_decode("Unjustified Abs. (h)"),1,1,'C', false);
	        $this->SetXY($postx+21,$posty+45+9+6+6+10+13-7);
	        $this->Cell(10,5,utf8_decode(""),1,1,'C', false);
			$this->SetXY($postx+21+10,$posty+45+9+6+6+10+13-7);
	        $this->Cell(24,5,utf8_decode("Conduct Warning"),1,1,'C', false);
			$this->SetXY($postx+21+10+24,$posty+45+9+6+6+10+13-7);
	        $this->Cell(10,5,utf8_decode(""),1,1,'C', false);
	        $this->SetXY($postx+21+10+24+10,$posty+45+9+6+6+10+13-7);
	        $this->Cell(20,5,utf8_decode("TOTAL SCORE"),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20,$posty+45+9+6+6+10+13-7);
	        $this->Cell(12,5,utf8_decode($footerData['total_gene']),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20+12,$posty+45+9+6+6+10+13-7);
	        $this->Cell(32,5,utf8_decode("REMARK"),1,1,'C', false);
			$this->SetXY($postx+31+34+30+22+12,$posty+45+9+6+6+10+13-7);
	        $this->Cell(23,5,utf8_decode("Class Average"),1,1,'C', false);
	        $this->SetXY($postx+31+34+30+22+12+23,$posty+45+9+6+6+10+13-7);
	        $this->Cell(40,5,utf8_decode($footerData['moyenne_gene']),1,1,'L', false);


			$posty = $posty + 10;

			$this->SetXY($postx+1,$posty+45+9+6+6+10+13-12);
	        $this->Cell(20,7,utf8_decode("Justified Abs (h)"),1,1,'C', false);
	        $this->SetXY($postx+21,$posty+45+9+6+6+10+13-12);
	        $this->Cell(10,7,utf8_decode(""),1,1,'C', false);
			$this->SetXY($postx+21+10,$posty+45+9+6+6+10+13-12);
	        $this->Cell(24,7,utf8_decode("Reprimand"),1,1,'C', false);
			$this->SetXY($postx+21+10+24,$posty+45+9+6+6+10+13-12);
	        $this->Cell(10,7,utf8_decode(""),1,1,'C', false);
	        $this->SetXY($postx+21+10+24+10,$posty+45+9+6+6+10+13-12);
	        $this->Cell(20,7,utf8_decode("Coef"),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20,$posty+45+9+6+6+10+13-12);
	        $this->Cell(12,7,utf8_decode($footerData['total_coef']),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20+12,$posty+45+9+6+6+10+13-12);
	        $this->Cell(20,3.5,utf8_decode("CVWA"),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20+12+20,$posty+45+9+6+6+10+13-12);
	        $this->Cell(12,3.5,utf8_decode($footerData['appreciation'] == 'CVWA' ? 'YES' : '-'),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20+12,$posty+45+9+6+6+10+13-12+3.5);
	        $this->Cell(20,3.5,utf8_decode("CWA"),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20+12+20,$posty+45+9+6+6+10+13-12+3.5);
	        $this->Cell(12,3.5,utf8_decode($footerData['appreciation'] == 'CWA' ? 'YES' : '-'),1,1,'C', false);
			$this->SetXY($postx+31+34+30+22+12,$posty+45+9+6+6+10+13-12);
	        $this->Cell(23,7,utf8_decode("[Min - Max]"),1,1,'C', false);
	        $this->SetXY($postx+31+34+30+22+12+23,$posty+45+9+6+6+10+13-12);
	        $this->Cell(40,7,utf8_decode('[' . $footerData['lowest'] .' /20 - '. $footerData['highest'] . ' /20]'),1,1,'L', false);

			$posty = $posty + 7;

			$this->SetXY($postx+1,$posty+45+9+6+6+10+13-12);
	        $this->Cell(20,7,utf8_decode("Late (nbr of times)"),1,1,'C', false);
	        $this->SetXY($postx+21,$posty+45+9+6+6+10+13-12);
	        $this->Cell(10,7,utf8_decode(""),1,1,'C', false);
			$this->SetXY($postx+21+10,$posty+45+9+6+6+10+13-12);
	        $this->Cell(24,7,utf8_decode("Suspension."),1,1,'C', false);
			$this->SetXY($postx+21+10+24,$posty+45+9+6+6+10+13-12);
	        $this->Cell(10,7,utf8_decode(""),1,1,'C', false);
	        $this->SetXY($postx+21+10+24+10,$posty+45+9+6+6+10+13-12);
	        $this->Cell(20,7,utf8_decode("TERM  AVERAGE"),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20,$posty+45+9+6+6+10+13-12);
	        $this->Cell(12,7,utf8_decode($footerData['moyenne_trim']),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20+12,$posty+45+9+6+6+10+13-12);
	        $this->Cell(20,3.5,utf8_decode("CA"),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20+12+20,$posty+45+9+6+6+10+13-12);
	        $this->Cell(12,3.5,utf8_decode($footerData['appreciation'] == 'SWA' ? 'YES' : '-'),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20+12,$posty+45+9+6+6+10+13-12+3.5);
	        $this->Cell(20,3.5,utf8_decode("CAA"),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20+12+20,$posty+45+9+6+6+10+13-12+3.5);
	        $this->Cell(12,3.5,utf8_decode($footerData['appreciation'] == 'SMA' ? 'YES' : '-'),1,1,'C', false);
			$this->SetXY($postx+31+34+30+22+12,$posty+45+9+6+6+10+13-12);
	        $this->Cell(23,7,utf8_decode("Number passed"),1,1,'C', false);
	        $this->SetXY($postx+31+34+30+22+12+23,$posty+45+9+6+6+10+13-12);
	        $this->Cell(40,7,utf8_decode($footerData['nbre_reussite'] .' / '. $footerData['total_eleve']),1,1,'L', false);


			$posty = $posty + 7;

			$this->SetXY($postx+1,$posty+45+9+6+6+10+13-12);
	        $this->Cell(20,7,utf8_decode("Punishment (hours)"),1,1,'C', false);
	        $this->SetXY($postx+21,$posty+45+9+6+6+10+13-12);
	        $this->Cell(10,7,utf8_decode(""),1,1,'C', false);
			$this->SetXY($postx+21+10,$posty+45+9+6+6+10+13-12);
	        $this->Cell(24,7,utf8_decode("Dismissed"),1,1,'C', false);
			$this->SetXY($postx+21+10+24,$posty+45+9+6+6+10+13-12);
	        $this->Cell(10,7,utf8_decode(""),1,1,'C', false);
	        $this->SetXY($postx+21+10+24+10,$posty+45+9+6+6+10+13-12);
	        $this->Cell(20,7,utf8_decode("Grade"),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20,$posty+45+9+6+6+10+13-12);
	        $this->Cell(12,7,utf8_decode($footerData['cote']),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20+12,$posty+45+9+6+6+10+13-12);
	        $this->Cell(20,7,utf8_decode("CNA"),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20+12+20,$posty+45+9+6+6+10+13-12);
	        $this->Cell(12,7,utf8_decode($footerData['appreciation'] == 'SNA' ? 'YES' : '-'),1,1,'C', false);
			$this->SetXY($postx+31+34+30+22+12,$posty+45+9+6+6+10+13-12);
	        $this->Cell(23,7,utf8_decode("Success rate (%)"),1,1,'C', false);
	        $this->SetXY($postx+31+34+30+22+12+23,$posty+45+9+6+6+10+13-12);
	        $this->Cell(40,7,utf8_decode($footerData['pourcentageR'] .'%'),1,1,'L', false);

			$posty = $posty + 7;

			$this->SetXY($postx+1,$posty+45+9+6+6+10+13-12);
	        $this->Cell(60,5,utf8_decode("Remarks on student performance"),array('L','T','R'),1,'C', false);
			$this->SetXY($postx+61,$posty+45+9+6+6+10+13-12);
	        $this->Cell(35,5,utf8_decode("Parent’s/Guardian’s signature"),array('L','T','R'),1,'C', false);
			$this->SetXY($postx+61+35,$posty+45+9+6+6+10+13-12);
	        $this->Cell(35,5,utf8_decode("Class master’s signature"),array('L','T','R'),1,'C', false);
	        $this->SetXY($postx+61+35+35,$posty+45+9+6+6+10+13-12);
	        $this->Cell(61,5,utf8_decode("The PRINCIPAL"),array('L','T','R'),1,'L', false);

			$posty = $posty + 5;

			$this->SetXY($postx+1,$posty+45+9+6+6+10+13-12);
	        $this->Cell(60,20,utf8_decode(""),array('L','R','B'),1,'C', false);
			$this->SetXY($postx+61,$posty+45+9+6+6+10+13-12);
	        $this->Cell(35,20,utf8_decode(""),array('L','R','B'),1,'C', false);
			$this->SetXY($postx+61+35,$posty+45+9+6+6+10+13-12);
	        $this->Cell(35,20,utf8_decode(""),array('L','R','B'),1,'C', false);
	        $this->SetXY($postx+61+35+35,$posty+45+9+6+6+10+13-12);
	        $this->Cell(61,20,utf8_decode(""),array('L','R','B'),1,'L', false);
		}


		//Annuel Franco
		function listingAnnuel($data_teaching){

	        $postx = 12;
	        $posty = 20;

	        /*-------------------*/ // entete
			$this->SetFont('times', 'B', 10);
			$this->SetDrawColor(71, 151, 45);
	        $this->SetFillColor(71, 151, 45);
	        $this->SetTextColor(255, 255, 255);
			$this->SetXY($postx+10,$posty+33);
	        $this->Cell(175,7,utf8_decode("BULLETIN SCOLAIRE ANNUEL"),1,1,'C', true);
			
			$postx = 12;
			$posty = $posty + 25;

			$this->SetFont('times', '', 8);
			$this->SetDrawColor(0, 0, 0);
	        $this->SetFillColor(192, 192, 192);
	        $this->SetTextColor(0, 0, 0);
	        $this->SetXY($postx+1,$posty+45+9+6+5);
	        $this->Cell(55,8,utf8_decode("Matières & Enseignant"),1,1,'C', true);

			$this->SetXY($postx+56,$posty+45+9+6+5);
	        $this->Cell(15,8,utf8_decode('TRIM 1'),1,1,'C', true);
			$this->SetXY($postx+56+15,$posty+45+9+6+5);
	        $this->Cell(15,8,utf8_decode('TRIM 2'),1,1,'C', true);
			$this->SetXY($postx+56+30,$posty+45+9+6+5);
	        $this->Cell(15,8,utf8_decode('TRIM 3'),1,1,'C', true);
	        $this->SetXY($postx+8+6+27+32+8+20,$posty+45+9+6+5);
	        $this->Cell(13,8,utf8_decode('MOY'),1,1,'C', true);
			$this->SetXY($postx+8+6+27+42+2+9+20,$posty+45+9+6+5);
	        $this->Cell(10,8,utf8_decode('Coef'),1,1,'C', true);
	        $this->SetXY($postx+8+6+27+42+12+9+20,$posty+45+9+6+5);
	        $this->Cell(12,8,utf8_decode('M x Coef'),1,1,'C', true);
	        $this->SetXY($postx+8+6+27+42+10+12+6+5+20,$posty+45+9+6+5);
	        $this->Cell(20,8,utf8_decode('Min - Max] '),1,1,'C', true);
			$this->SetXY($postx+8+6+27+42+10+10+28+5+20,$posty+45+9+6+5);
	        $this->Cell(35,8,utf8_decode('Appréciations & Visa de l’enseignant '),1,1,'C', true);

	        $count = 0;
	        $this->SetFont('times', '','7');
			$sommeCoef = 0;
	        foreach ($data_teaching as $teaching) { 
				$this->SetXY($postx+1,$posty+45+9+6+3+10);
				$this->Cell(55,7,utf8_decode($teaching['name']),1,1,'L', false);

				$this->SetXY($postx+56,$posty+45+9+6+3+10);
				$this->Cell(15,7,utf8_decode(''),1,1,'C', false);
				$this->SetXY($postx+56+15,$posty+45+9+6+3+10);
				$this->Cell(15,7,utf8_decode(''),1,1,'C', false);
				$this->SetXY($postx+56+15+15,$posty+45+9+6+3+10);
				$this->Cell(15,7,utf8_decode(''),1,1,'C', false);

				$this->SetXY($postx+8+6+27+32+8+20,$posty+45+9+3+6+10);
				$this->Cell(13,7,utf8_decode(''),1,1,'C', false);
				$this->SetXY($postx+8+6+27+42+2+9+20,$posty+45+9+3+6+10);
				$this->Cell(10,7,utf8_decode($teaching['coefficient']),1,1,'C', false);
				$this->SetXY($postx+8+6+27+42+12+9+20,$posty+45+9+3+6+10);
				$this->Cell(12,7,utf8_decode(" "),1,1,'C', false);
				$this->SetXY($postx+8+6+27+42+10+12+6+5+20,$posty+45+9+3+6+10);
				$this->Cell(20,7,utf8_decode(" "),1,1,'C', false);
				$this->SetXY($postx+8+6+27+42+10+10+28+5+20,$posty+45+9+3+6+10);
				$this->Cell(35,7,utf8_decode(" "),1,1,'L', false);

				$posty = $posty +7;
				$sommeCoef += $teaching['coefficient'];

	        }

	        $this->SetXY($postx+1,$posty+45+9+3+6+10);
	        $this->Cell(113,5,utf8_decode("TOTAL"),1,1,'R', false);
			$this->SetXY($postx+8+6+27+42+2+9+20,$posty+45+9+3+6+10);
	        $this->Cell(10,5,utf8_decode($sommeCoef),1,1,'C', false);
			$this->SetXY($postx+8+6+27+42+12+9+20,$posty+45+9+3+6+10);
			$this->Cell(12,5,utf8_decode(" "),1,1,'C', false);
	        $this->SetXY($postx+8+6+27+42+12+9+40-8,$posty+45+9+3+6+10);
	        $this->Cell(55,5,utf8_decode("MOYENNE : "),1,1,'L', false);
    	}

		function footerBulletinAnnuel(){
			$postx = 12;
			$posty = 152;

			$this->SetXY($postx+1,$posty+45+9+6+6+10+13-7);
			$this->Cell(64,5,utf8_decode("Discipline"),1,1,'C', false);
			$this->SetXY($postx+65,$posty+45+9+6+6+10+13-7);
			$this->Cell(64,5,utf8_decode("Travail de l'élève"),1,1,'C', false);
			$this->SetXY($postx+65+64,$posty+45+9+6+6+10+13-7);
			$this->Cell(63,5,utf8_decode("Profil de la classe"),1,1,'C', false);

			$posty = $posty + 5;

			$this->SetXY($postx+1,$posty+45+9+6+6+10+13-7);
			$this->Cell(20,5,utf8_decode("Abs. non j. (h)"),1,1,'C', false);
			$this->SetXY($postx+21,$posty+45+9+6+6+10+13-7);
			$this->Cell(10,5,utf8_decode(""),1,1,'C', false);
			$this->SetXY($postx+21+10,$posty+45+9+6+6+10+13-7);
			$this->Cell(24,5,utf8_decode("Avertissement de Con."),1,1,'C', false);
			$this->SetXY($postx+21+10+24,$posty+45+9+6+6+10+13-7);
			$this->Cell(10,5,utf8_decode(""),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10,$posty+45+9+6+6+10+13-7);
			$this->Cell(20,5,utf8_decode("TOTAL Géné"),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20,$posty+45+9+6+6+10+13-7);
			$this->Cell(12,5,utf8_decode(""),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20+12,$posty+45+9+6+6+10+13-7);
			$this->Cell(32,5,utf8_decode("Décision C.C"),1,1,'C', false);
			$this->SetXY($postx+31+34+30+22+12,$posty+45+9+6+6+10+13-7);
			$this->Cell(23,5,utf8_decode("Moyenne Générale"),1,1,'C', false);
			$this->SetXY($postx+31+34+30+22+12+23,$posty+45+9+6+6+10+13-7);
			$this->Cell(40,5,utf8_decode("  "),1,1,'L', false);


			$posty = $posty + 10;

			$this->SetXY($postx+1,$posty+45+9+6+6+10+13-12);
			$this->Cell(20,7,utf8_decode("Abs.just. (h)"),1,1,'C', false);
			$this->SetXY($postx+21,$posty+45+9+6+6+10+13-12);
			$this->Cell(10,7,utf8_decode(""),1,1,'C', false);
			$this->SetXY($postx+21+10,$posty+45+9+6+6+10+13-12);
			$this->Cell(24,7,utf8_decode("Blâme de con."),1,1,'C', false);
			$this->SetXY($postx+21+10+24,$posty+45+9+6+6+10+13-12);
			$this->Cell(10,7,utf8_decode(""),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10,$posty+45+9+6+6+10+13-12);
			$this->Cell(20,7,utf8_decode("Coef"),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20,$posty+45+9+6+6+10+13-12);
			$this->Cell(12,7,utf8_decode(""),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20+12,$posty+45+9+6+6+10+13-12);
			$this->Cell(20,7,utf8_decode("Promu(e)"),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20+12+20,$posty+45+9+6+6+10+13-12);
			$this->Cell(12,7,utf8_decode(""),1,1,'C', false);
			$this->SetXY($postx+31+34+30+22+12,$posty+45+9+6+6+10+13-12);
			$this->Cell(23,7,utf8_decode("[Min - Max]"),1,1,'C', false);
			$this->SetXY($postx+31+34+30+22+12+23,$posty+45+9+6+6+10+13-12);
			$this->Cell(40,7,utf8_decode("  "),1,1,'L', false);

			$posty = $posty + 7;

			$this->SetXY($postx+1,$posty+45+9+6+6+10+13-12);
			$this->Cell(20,7,utf8_decode("Retards nbre"),1,1,'C', false);
			$this->SetXY($postx+21,$posty+45+9+6+6+10+13-12);
			$this->Cell(10,7,utf8_decode(""),1,1,'C', false);
			$this->SetXY($postx+21+10,$posty+45+9+6+6+10+13-12);
			$this->Cell(24,7,utf8_decode("Exclusions (jrs)."),1,1,'C', false);
			$this->SetXY($postx+21+10+24,$posty+45+9+6+6+10+13-12);
			$this->Cell(10,7,utf8_decode(""),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10,$posty+45+9+6+6+10+13-12);
			$this->Cell(20,7,utf8_decode("Moy Annuelle"),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20,$posty+45+9+6+6+10+13-12);
			$this->Cell(12,7,utf8_decode(""),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20+12,$posty+45+9+6+6+10+13-12);
			$this->Cell(20,7,utf8_decode("Redouble"),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20+12+20,$posty+45+9+6+6+10+13-12);
			$this->Cell(12,7,utf8_decode(""),1,1,'C', false);
			$this->SetXY($postx+31+34+30+22+12,$posty+45+9+6+6+10+13-12);
			$this->Cell(23,7,utf8_decode("Nombre de Myne"),1,1,'C', false);
			$this->SetXY($postx+31+34+30+22+12+23,$posty+45+9+6+6+10+13-12);
			$this->Cell(40,7,utf8_decode("  "),1,1,'L', false);


			$posty = $posty + 7;

			$this->SetXY($postx+1,$posty+45+9+6+6+10+13-12);
			$this->Cell(20,7,utf8_decode("Consignes"),1,1,'C', false);
			$this->SetXY($postx+21,$posty+45+9+6+6+10+13-12);
			$this->Cell(10,7,utf8_decode(""),1,1,'C', false);
			$this->SetXY($postx+21+10,$posty+45+9+6+6+10+13-12);
			$this->Cell(24,7,utf8_decode("Exclusions définitive."),1,1,'C', false);
			$this->SetXY($postx+21+10+24,$posty+45+9+6+6+10+13-12);
			$this->Cell(10,7,utf8_decode(""),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10,$posty+45+9+6+6+10+13-12);
			$this->Cell(20,7,utf8_decode("Cote"),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20,$posty+45+9+6+6+10+13-12);
			$this->Cell(12,7,utf8_decode(""),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20+12,$posty+45+9+6+6+10+13-12);
			$this->Cell(20,7,utf8_decode("Exlu(e) pour"),1,1,'C', false);
			$this->SetXY($postx+21+10+24+10+20+12+20,$posty+45+9+6+6+10+13-12);
			$this->Cell(12,7,utf8_decode(""),1,1,'C', false);
			$this->SetXY($postx+31+34+30+22+12,$posty+45+9+6+6+10+13-12);
			$this->Cell(23,7,utf8_decode("T. de réussite"),1,1,'C', false);
			$this->SetXY($postx+31+34+30+22+12+23,$posty+45+9+6+6+10+13-12);
			$this->Cell(40,7,utf8_decode("  "),1,1,'L', false);


			$posty = $posty + 7;

			$this->SetXY($postx+1,$posty+45+9+6+6+10+13-12);
			$this->Cell(60,15,utf8_decode("Appréciation du travail de l’élève (points forts et points à améliorer)"),array('L','T','R'),1,'C', false);
			$this->SetXY($postx+61,$posty+45+9+6+6+10+13-12);
			$this->Cell(35,15,utf8_decode("Visa du parent / Tuteur"),array('L','T','R'),1,'B', false);
			$this->SetXY($postx+61+35,$posty+45+9+6+6+10+13-12);
			$this->Cell(35,15,utf8_decode("Nom et visa du professeur principal"),array('L','T','R'),1,'T', false);
			$this->SetXY($postx+61+35+35,$posty+45+9+6+6+10+13-12);
			$this->Cell(61,15,utf8_decode("Le Chef d’établissement"),array('L','T','R'),1,'T', false);

			$posty = $posty + 5;
		}
    }

?>