<?php 
    
    namespace App\Controllers;
	
	class FPDF_LISTING extends FPDF{
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

		/*----------------------------------------------------------------
		* ENTETE PORTRAIT
		*----------------------------------------------------------------*/

		public function header_p($year, $school, $contact, $matricule){
			
	        $postx = 35;
	        $posty = 4;

	        /*--------------- left --------------*/
	        $this->SetFont('times', '','8');
	        $this->SetXY($postx,$posty+1);
	        $this->Cell(86,5,utf8_decode('REPUBLIQUE DU CAMEROUN'),0,0,'C');
	        $this->SetXY($postx,$posty+4);
	        $this->Cell(86,5,utf8_decode('Paix - Travail - Patrie'),0,0,'C');
			$this->SetXY($postx,$posty+4+3);
			$this->Cell(86,5,utf8_decode('*********'),0,0,'C');
			$this->SetXY($postx,$posty+4+3+3);
			$this->Cell(86,5,utf8_decode('RÉGION DE L\'OUEST'),0,0,'C');
			$this->SetXY($postx,$posty+4+3+3+3);
			$this->Cell(86,5,utf8_decode('*********'),0,0,'C');
			$this->SetXY($postx,$posty+4+3+3+3+3);
			$this->Cell(86,5,utf8_decode('DÉPARTEMENT DE LA MIFI'),0,0,'C');
			$this->SetXY($postx,$posty+4+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode('*********'),0,0,'C');
			$this->SetXY($postx,$posty+4+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode("DÉLÉGATION DÉPARTEMENTALE DES ENSEIGNEMENTS PRIMAIRES"),0,0,'C');
			$this->SetXY($postx,$posty+4+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode('*********'),0,0,'C');
			$this->SetXY($postx,$posty+4+3+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode("ARRONDISSEMENT DE BAFOUSSAM I"),0,0,'C');
			$this->SetXY($postx,$posty+4+3+3+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode('*********'),0,0,'C');
			$this->SetFont('times', 'B','10');
			$this->SetXY($postx,$posty+4+3+3+3+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode(mb_strtoupper($school)),0,0,'C');
			$this->SetXY($postx,$posty+4+3+3+3+3+3+3+3+3+3+3+3);
			$this->SetFont('times', 'I','7');
			$this->Cell(86,5,utf8_decode("BP: Bafoussam -Tel: ".$contact),0,0,'C');
			$this->SetXY($postx,$posty+4+3+3+3+3+3+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode("IMMATRICULATION: ".$matricule),0,0,'C');
	        /*--------------*/
	        $this->Image('logo.png', $postx+92+10,$posty+15,25,25);
			$this->SetFont('arial', 'I','8');
			$this->SetXY($postx+86+5,$posty+4+3+3+3+3+3+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode("ANNÉE SCOLAIRE "),0,0,'L');
			$this->SetXY($postx+86+24+10,$posty+4+3+3+3+3+3+3+3+3+3+3+3+3+1.5);
			$this->SetFont('arial', 'B','8');
			$this->Cell(86,5,utf8_decode(" ".$year),0,0,'L');
			$this->SetFont('arial', 'I','8');
			$this->SetXY($postx+86+23+5+5,$posty+4+3+3+3+3+3+3+3+3+3+3+3+3+1.5);
			$this->Cell(86,5,utf8_decode(": "),0,0,'L');
			$this->SetXY($postx+86+5,$posty+4+3+3+3+3+3+3+3+3+3+3+3+3+3);
			$this->SetFont('arial', 'I','6');
			$this->Cell(86,5,utf8_decode("SCHOOL YEAR "),0,0,'L');
	        /*--------------*/
	        /*--------------- rigth --------------*/
	        $this->SetFont('times', '','8');
	        $this->SetXY($postx+120+15,$posty+1);
	        $this->Cell(86,5,utf8_decode('REPUBLIC OF CAMEROUN'),0,0,'C');
	        $this->SetXY($postx+120+15,$posty+4);
	        $this->Cell(86,5,utf8_decode('Peace - Work - Homeland'),0,0,'C');
			$this->SetXY($postx+120+15,$posty+4+3);
			$this->Cell(86,5,utf8_decode('*********'),0,0,'C');
			$this->SetXY($postx+120+15,$posty+4+3+3);
			$this->Cell(86,5,utf8_decode('WEST REGION'),0,0,'C');
			$this->SetXY($postx+120+15,$posty+4+3+3+3);
			$this->Cell(86,5,utf8_decode('*********'),0,0,'C');
			$this->SetXY($postx+120+15,$posty+4+3+3+3+3);
			$this->Cell(86,5,utf8_decode('MIFI DEPARTEMENT'),0,0,'C');
			$this->SetXY($postx+120+15,$posty+4+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode('*********'),0,0,'C');
			$this->SetXY($postx+120+15,$posty+4+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode("DEPARTEMENT DELEGATION OF PRIMARY EDUCATION"),0,0,'C');
			$this->SetXY($postx+120+15,$posty+4+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode('*********'),0,0,'C');
			$this->SetXY($postx+120+15,$posty+4+3+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode("BAFOUSSAM I SUBDIVISION"),0,0,'C');
			$this->SetXY($postx+120+15,$posty+4+3+3+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode('*********'),0,0,'C');
			$this->SetFont('times', 'B','10');
			$this->SetXY($postx+120+15,$posty+4+3+3+3+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode(mb_strtoupper($school)),0,0,'C');
			$this->SetXY($postx+120+15,$posty+4+3+3+3+3+3+3+3+3+3+3+3);
			$this->SetFont('times', 'I','6');
			$this->Cell(86,5,utf8_decode("P.O. Box: Bafoussam - Tel: ".$contact),0,0,'C');
			$this->SetXY($postx+120+15,$posty+4+3+3+3+3+3+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode("REGISTRATION NUMBER: ".$matricule),0,0,'C');
	        
	        /*---------------*/
	        $this->SetFont('times', 'B','8');
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
			$this->Cell(86,5,utf8_decode('RÉGION DE L\'OUEST'),0,0,'C');
			$this->SetXY($postx,$posty+4+3+3+3);
			$this->Cell(86,5,utf8_decode('*********'),0,0,'C');
			$this->SetXY($postx,$posty+4+3+3+3+3);
			$this->Cell(86,5,utf8_decode('DÉPARTEMENT DE LA MIFI'),0,0,'C');
			$this->SetXY($postx,$posty+4+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode('*********'),0,0,'C');
			$this->SetXY($postx,$posty+4+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode("DÉLÉGATION DÉPARTEMENTALE DES"),0,0,'C');
			$this->SetXY($postx,$posty+4+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode("ENSEIGNEMENTS PRIMAIRES"),0,0,'C');
			$this->SetXY($postx,$posty+4+3+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode('*********'),0,0,'C');
			$this->SetXY($postx,$posty+4+3+3+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode("ARRONDISSEMENT DE BAFOUSSAM I"),0,0,'C');
			$this->SetXY($postx,$posty+4+3+3+3+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode('*********'),0,0,'C');
			$this->SetFont('times', 'B','10');
			$this->SetXY($postx,$posty+4+3+3+3+3+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode(mb_strtoupper($school)),0,0,'C');
			$this->SetXY($postx,$posty+4+3+3+3+3+3+3+3+3+3+3+3+3);
			$this->SetFont('times', 'I','6');
			$this->Cell(86,5,utf8_decode("BP: Bafoussam--Tel: ".$contact),0,0,'C');
			$this->SetXY($postx,$posty+4+3+3+3+3+3+3+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode("IMMATRICULATION: ".strtoupper($matricule)),0,0,'C');
	        /*--------------*/
	        $this->Image('logo.png', $postx+92,$posty+15,25,25);
			$this->SetFont('arial', 'I','7');
			$this->SetXY($postx+86,$posty+4+3+3+3+3+3+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode("ANNÉE SCOLAIRE "),0,0,'L');
			$this->SetXY($postx+86+24,$posty+4+3+3+3+3+3+3+3+3+3+3+3+3+1.5);
			$this->SetFont('arial', 'B','7');
			$this->Cell(86,5,utf8_decode(" ".$year),0,0,'L');
			$this->SetFont('arial', 'I','7');
			$this->SetXY($postx+86+23,$posty+4+3+3+3+3+3+3+3+3+3+3+3+3+1.5);
			$this->Cell(86,5,utf8_decode(": "),0,0,'L');
			$this->SetXY($postx+86,$posty+4+3+3+3+3+3+3+3+3+3+3+3+3+3);
			$this->SetFont('arial', 'I','6');
			$this->Cell(86,5,utf8_decode("SCHOOL YEAR "),0,0,'L');
	        /*--------------*/
	        /*--------------- rigth --------------*/
	        $this->SetFont('times', '','7');
	        $this->SetXY($postx+120,$posty+1);
	        $this->Cell(86,5,utf8_decode('REPUBLIC OF CAMEROUN'),0,0,'C');
	        $this->SetXY($postx+120,$posty+4);
	        $this->Cell(86,5,utf8_decode('Peace - Work - Homeland'),0,0,'C');
			$this->SetXY($postx+120,$posty+4+3);
			$this->Cell(86,5,utf8_decode('*********'),0,0,'C');
			$this->SetXY($postx+120,$posty+4+3+3);
			$this->Cell(86,5,utf8_decode('WEST REGION'),0,0,'C');
			$this->SetXY($postx+120,$posty+4+3+3+3);
			$this->Cell(86,5,utf8_decode('*********'),0,0,'C');
			$this->SetXY($postx+120,$posty+4+3+3+3+3);
			$this->Cell(86,5,utf8_decode('MIFI DEPARTEMENT'),0,0,'C');
			$this->SetXY($postx+120,$posty+4+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode('*********'),0,0,'C');
			$this->SetXY($postx+120,$posty+4+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode("DEPARTEMENT DELEGATION OF"),0,0,'C');
			$this->SetXY($postx+120,$posty+4+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode("PRIMARY EDUCATION"),0,0,'C');
			$this->SetXY($postx+120,$posty+4+3+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode('*********'),0,0,'C');
			$this->SetXY($postx+120,$posty+4+3+3+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode("BAFOUSSAM I SUBDIVISION"),0,0,'C');
			$this->SetXY($postx+120,$posty+4+3+3+3+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode('*********'),0,0,'C');
			$this->SetFont('times', 'B','10');
			$this->SetXY($postx+120,$posty+4+3+3+3+3+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode(mb_strtoupper($school)),0,0,'C');
			$this->SetXY($postx+120,$posty+4+3+3+3+3+3+3+3+3+3+3+3+3);
			$this->SetFont('times', 'I','6');
			$this->Cell(86,5,utf8_decode("P.O. Box: Bafoussam - Tel: ".$contact),0,0,'C');
			$this->SetXY($postx+120,$posty+4+3+3+3+3+3+3+3+3+3+3+3+3+3);
			$this->Cell(86,5,utf8_decode("REGISTRATION NUMBER: ".strtoupper($matricule)),0,0,'C');
	        /*---------------*/
	        // $this->SetXY($postx+60,$posty+23);
	        // $this->SetFont('times', 'B','11');
	        // $this->Cell(86,5,utf8_decode('INSTITUT CATHOLIQUE DE BAFOUSSAM (ICAB)'),0,0,'C');
	        
	        /*---------------*/
	        $this->SetFont('times', 'B','8');
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
		* LISTING DES ELEVES
		*----------------------------------------------------------------*/
	    function listing($title, $classe, $session, $cycle, $data, $garcon, $fille, $enseignant, $year, $school, $contact, $matricule, $msg){

	        $postx = 12;
	        $posty = 12;

	        /*-------------------*/ // entete
			$this->SetFont('times', 'B', 11);
			$this->SetDrawColor(71, 151, 45);
	        $this->SetFillColor(71, 151, 45);
	        $this->SetTextColor(255, 255, 255);
			$this->SetXY($postx+45,$posty+45);
	        $this->Cell(175,6,utf8_decode($title),1,1,'C', true);
			$postx = 12+30;
			$this->SetXY($postx+14,$posty+45+9);
			$this->SetTextColor(0, 0, 0);	
			$this->SetFont('times', '', 9);
			$this->Cell(120,5,utf8_decode("Classe: "),0,1,'L', false);
			$this->SetXY($postx+14+10,$posty+45+9);
			$this->SetFont('times', 'B', 9);
			$this->Cell(120,5,utf8_decode($classe),0,1,'L', false);
			$this->SetXY($postx+14+10+30,$posty+45+9);
			$this->Cell(120,5,utf8_decode("[ Session ".$session." ]"),0,1,'L', false);
			$this->SetXY($postx+14+10+30+50,$posty+45+9);
			$this->Cell(120,5,utf8_decode("[ ".$cycle." ]"),0,1,'L', false);
			$this->SetFont('times', '', 9);
			$this->SetXY($postx+14,$posty+45+9+6);
			$this->Cell(120,5,utf8_decode("Effectif: "),0,1,'L', false);
			$this->SetXY($postx+14+13,$posty+45+9+6);
			$this->SetFont('times', 'B', 9);
			$this->Cell(120,5,utf8_decode(($garcon+$fille)),0,1,'L', false);
			$this->SetXY($postx+14+10+30,$posty+45+9+6);
			$this->Cell(120,5,utf8_decode("[ Garçons: ".$garcon." ]"),0,1,'L', false);
			$this->SetXY($postx+14+10+30+50,$posty+45+9+6);
			$this->Cell(120,5,utf8_decode("[ Fille: ".$fille." ]"),0,1,'L', false);
			$this->SetFont('times', '', 9);
			$this->SetXY($postx+14,$posty+45+9+6+6);
			$this->Cell(120,5,utf8_decode("Enseignant(e): "),0,1,'L', false);
			$this->SetXY($postx+14+20,$posty+45+9+6+6);
			$this->SetFont('times', 'B', 9);
			$this->Cell(120,5,utf8_decode(($enseignant)),0,1,'L', false);

			// QrCode
			$this->Image("images.png", $postx+160+5,45+9+12,25,20);

			$postx = 12;

			$this->SetFont('times', 'B', 9);
			$this->SetDrawColor(0, 0, 0);
	        $this->SetFillColor(164, 227, 136);
	        $this->SetTextColor(0, 0, 0);
	        $this->SetXY($postx+1,$posty+45+9+6+6+10);
	        $this->Cell(8,5,utf8_decode('N°'),1,1,'C', true);
			$this->SetXY($postx+9,$posty+45+9+6+6+10);
	        $this->Cell(32,5,utf8_decode('MATRICULE'),1,1,'C', true);
	        $this->SetXY($postx+8+6+27,$posty+45+9+6+6+10);
	        $this->Cell(74,5,utf8_decode('NOMS ET PRENOMS '),1,1,'C', true);
	        $this->SetXY($postx+8+45+62,$posty+45+9+6+6+10);
	        $this->Cell(10,5,utf8_decode('SEXE'),1,1,'C', true);
			$this->SetXY($postx+8+45+70+2,$posty+45+9+6+6+10);
	        $this->Cell(26,5,utf8_decode('CLASSE '),1,1,'C', true);
	        $this->SetXY($postx+8+45+70+28,$posty+45+9+6+6+10);
	        $this->Cell(61,5,utf8_decode('PARENT '),1,1,'C', true);
	        $this->SetXY($postx+8+45+70+25+19+45,$posty+45+9+6+6+10);
	        $this->Cell(26,5,utf8_decode('PHONE '),1,1,'C', true);
			$this->SetXY($postx+8+45+70+25+19+39+32,$posty+45+9+6+6+10);
	        $this->Cell(26,5,utf8_decode('REDOUBLE '),1,1,'C', true);

	        $posty = $posty+45+9+6+6+10+5;
	        $count = 0;
	        $this->SetFont('times', '','9');
	        for ($i=0; $i < sizeof($data); $i++) { 
	            
	            if ($count >= 20) {
	                $this->AddPage(); 
	                $this->Filigramme("School");
	                $this->header_p($year, $school, $contact, $matricule);
					$this->footer_listing(38, $msg);
					
	                $postx = 12;
	        		$posty = 12+45;
	                $count = 0;

	            }else{
					$this->SetFont('times', 'B', 9);
					$this->SetDrawColor(0, 0, 0);
					$this->SetFillColor(164, 227, 136);
					$this->SetTextColor(0, 0, 0);
					$this->SetXY($postx+1,$posty+45-45);
					$this->Cell(8,4,utf8_decode($data[$i]['num']),1,1,'L', false);
					$this->SetXY($postx+9,$posty+45-45);
					$this->Cell(32,4,utf8_decode($data[$i]['mat']),1,1,'L', false);
					$this->SetXY($postx+8+6+27,$posty+45-45);
					$this->Cell(74,4,utf8_decode($data[$i]['nom']),1,1,'L', false);
					$this->SetXY($postx+8+45+62,$posty+45-45);
					$this->Cell(10,4,utf8_decode($data[$i]['sexe']),1,1,'L', false);
					$this->SetXY($postx+8+45+70+2,$posty+45-45);
					$this->Cell(26,4,utf8_decode($data[$i]['classe']),1,1,'L', false);
					$this->SetXY($postx+8+45+70+28,$posty+45-45);
					$this->Cell(61,4,utf8_decode($data[$i]['parent']),1,1,'L', false);
					$this->SetXY($postx+8+45+70+25+19+45,$posty+45-45);
					$this->Cell(26,4,utf8_decode($data[$i]['phone']),1,1,'L', false);
					$this->SetXY($postx+8+45+70+25+19+39+32,$posty+45-45);
					$this->Cell(26,4,utf8_decode($data[$i]['redouble']),1,1,'L', false);

	                $posty = $posty +4;
	                $count++;
	            }
	            
	        }
    	}


		function fiche_notes($title, $classe, $session, $cycle, $data, $garcon, $fille, $enseignant, $year, $school, $contact, $matricule, $msg){

	        $postx = 12;
	        $posty = 12;

	        /*-------------------*/ // entete
			$this->SetFont('times', 'B', 11);
			$this->SetDrawColor(71, 151, 45);
	        $this->SetFillColor(71, 151, 45);
	        $this->SetTextColor(255, 255, 255);
			$this->SetXY($postx+45,$posty+45);
	        $this->Cell(175,6,utf8_decode($title),1,1,'C', true);
			$postx = 12+30;
			$this->SetXY($postx+14,$posty+45+9);
			$this->SetTextColor(0, 0, 0);	
			$this->SetFont('times', '', 9);
			$this->Cell(120,5,utf8_decode("Classe: "),0,1,'L', false);
			$this->SetXY($postx+14+10,$posty+45+9);
			$this->SetFont('times', 'B', 9);
			$this->Cell(120,5,utf8_decode($classe),0,1,'L', false);
			$this->SetXY($postx+14+10+30,$posty+45+9);
			$this->Cell(120,5,utf8_decode("[ Session ".$session." ]"),0,1,'L', false);
			$this->SetXY($postx+14+10+30+50,$posty+45+9);
			$this->Cell(120,5,utf8_decode("[ ".$cycle." ]"),0,1,'L', false);
			$this->SetFont('times', '', 9);
			$this->SetXY($postx+14,$posty+45+9+6+3);
			$this->Cell(120,5,utf8_decode("Enseignant(e): .........................................................................................................................."),0,1,'L', false);
			$this->SetXY($postx+14+125,$posty+45+9+6+3);
			$this->SetFont('times', '', 9);
			$this->Cell(120,5,utf8_decode("Matière : ....................................................................................................."),0,1,'L', false);

			$postx = 12;

			$this->SetFont('times', 'B', 9);
			$this->SetDrawColor(0, 0, 0);
			$this->SetFillColor(164, 227, 136);
			$this->SetTextColor(0, 0, 0);

			// Positionnement des en-têtes de colonnes avec des largeurs ajustées
			$this->SetXY($postx+1, $posty+45+9+6+6+10);
			$this->Cell(8, 5, utf8_decode('N°'), 1, 1, 'C', true);

			$this->SetXY($postx+9, $posty+45+9+6+6+10);
			$this->Cell(32, 5, utf8_decode('MATRICULE'), 1, 1, 'C', true);

			$this->SetXY($postx+41, $posty+45+9+6+6+10);
			$this->Cell(74, 5, utf8_decode('NOMS ET PRENOMS'), 1, 1, 'C', true);

			$this->SetXY($postx+115, $posty+45+9+6+6+10);
			$this->Cell(10, 5, utf8_decode('coef'), 1, 1, 'C', true);

			$this->SetXY($postx+125, $posty+45+9+6+6+5);
			$this->Cell(50, 5, utf8_decode('TRIMESTRE 1'), 1, 1, 'C', true);

			$this->SetXY($postx+125, $posty+45+9+6+6+10);
			$this->Cell(25, 5, utf8_decode('Seq 1'), 1, 1, 'C', true);
			$this->SetXY($postx+150, $posty+45+9+6+6+10);
			$this->Cell(25, 5, utf8_decode('Seq 2'), 1, 1, 'C', true);

			$this->SetXY($postx+175, $posty+45+9+6+6+5);
			$this->Cell(50, 5, utf8_decode('TRIMESTRE 2'), 1, 1, 'C', true);

			$this->SetXY($postx+175, $posty+45+9+6+6+10);
			$this->Cell(25, 5, utf8_decode('Seq 3'), 1, 1, 'C', true);
			$this->SetXY($postx+200, $posty+45+9+6+6+10);
			$this->Cell(25, 5, utf8_decode('Seq 4'), 1, 1, 'C', true);


			$this->SetXY($postx+225, $posty+45+9+6+6+5);
			$this->Cell(50, 5, utf8_decode('TRIMESTRE 3'), 1, 1, 'C', true);

			$this->SetXY($postx+225, $posty+45+9+6+6+10);
			$this->Cell(25, 5, utf8_decode('Seq 5'), 1, 1, 'C', true);
			$this->SetXY($postx+250, $posty+45+9+6+6+10);
			$this->Cell(25, 5, utf8_decode('Seq 6'), 1, 1, 'C', true);

			// Mise à jour de $posty pour la ligne suivante
			$posty = $posty + 45 + 9 + 6 + 6 + 10 + 5;

	        $count = 0;
	        $this->SetFont('times', '','9');
	        for ($i=0; $i < sizeof($data); $i++) { 
	            
	            if ($count >= 17) {
	                $this->AddPage(); 
	                $this->Filigramme("School");
	                $this->header_p($year, $school, $contact, $matricule);
					$this->footer_listing(38, $msg);
					
	                $postx = 12;
	        		$posty = 12+45;
	                $count = 0;

	            }else{
					$this->SetFont('times', 'B', 9);
					$this->SetDrawColor(0, 0, 0);
					$this->SetFillColor(164, 227, 136);
					$this->SetTextColor(0, 0, 0);
					$this->SetXY($postx+1,$posty+45-45);
					$this->Cell(8,6,utf8_decode($data[$i]['num']),1,1,'L', false);
					$this->SetXY($postx+9,$posty+45-45);
					$this->Cell(32,6,utf8_decode($data[$i]['mat']),1,1,'L', false);
					$this->SetXY($postx+8+6+27,$posty+45-45);
					$this->Cell(74,6,utf8_decode($data[$i]['nom']),1,1,'L', false);
					$this->SetXY($postx+8+45+62,$posty+45-45);
					$this->Cell(10,6,utf8_decode(''),1,1,'L', false);
					$this->SetXY($postx+8+45+70+2,$posty+45-45);
					$this->Cell(25,6,utf8_decode(''),1,1,'L', false);
					$this->SetXY($postx+150,$posty+45-45);
					$this->Cell(25,6,utf8_decode(''),1,1,'L', false);
					$this->SetXY($postx+175,$posty+45-45);
					$this->Cell(25,6,utf8_decode(''),1,1,'L', false);
					$this->SetXY($postx+200,$posty+45-45);
					$this->Cell(25,6,utf8_decode(''),1,1,'L', false);
					$this->SetXY($postx+225,$posty+45-45);
					$this->Cell(25,6,utf8_decode(''),1,1,'L', false);
					$this->SetXY($postx+250,$posty+45-45);
					$this->Cell(25,6,utf8_decode(''),1,1,'L', false);
					// $this->SetXY($postx+8+45+70+25+19+45,$posty+45-45);

	                $posty = $posty +6;
	                $count++;
	            }
	            
	        }
    	}


		function fiche_presence($title, $classe, $session, $cycle, $data, $garcon, $fille, $enseignant, $year, $school, $contact, $matricule, $msg){

	        $postx = 12;
	        $posty = 12;

	        /*-------------------*/ // entete
			$this->SetFont('times', 'B', 11);
			$this->SetDrawColor(71, 151, 45);
	        $this->SetFillColor(71, 151, 45);
	        $this->SetTextColor(255, 255, 255);
			$this->SetXY($postx+45,$posty+45);
	        $this->Cell(100,6,utf8_decode($title),1,1,'C', true);
			$this->SetXY($postx+14,$posty+45+9);
			$this->SetTextColor(0, 0, 0);	
			$this->SetFont('times', '', 9);
			$this->Cell(120,5,utf8_decode("Classe: "),0,1,'L', false);
			$this->SetXY($postx+14+10,$posty+45+9);
			$this->SetFont('times', 'B', 9);
			$this->Cell(120,5,utf8_decode($classe),0,1,'L', false);
			$this->SetXY($postx+14+10+30,$posty+45+9);
			$this->Cell(120,5,utf8_decode("[ Session ".$session." ]"),0,1,'L', false);
			$this->SetXY($postx+14+10+30+50,$posty+45+9);
			$this->Cell(120,5,utf8_decode("[ ".$cycle." Cycle ]"),0,1,'L', false);
			$this->SetFont('times', '', 9);
			$this->SetXY($postx+14,$posty+45+9+6);
			$this->Cell(120,5,utf8_decode("Effectif: "),0,1,'L', false);
			$this->SetXY($postx+14+13,$posty+45+9+6);
			$this->SetFont('times', 'B', 9);
			$this->Cell(120,5,utf8_decode(($garcon+$fille)),0,1,'L', false);
			$this->SetXY($postx+14+10+30,$posty+45+9+6);
			$this->Cell(120,5,utf8_decode("[ Garçons: ".$garcon." ]"),0,1,'L', false);
			$this->SetXY($postx+14+10+30+50,$posty+45+9+6);
			$this->Cell(120,5,utf8_decode("[ Fille: ".$fille." ]"),0,1,'L', false);
			$this->SetFont('times', '', 9);
			$this->SetXY($postx+14,$posty+45+9+6+6);
			$this->Cell(120,5,utf8_decode("Enseignant(e): "),0,1,'L', false);
			$this->SetXY($postx+14+20,$posty+45+9+6+6);
			$this->SetFont('times', 'B', 9);
			$this->Cell(120,5,utf8_decode(($enseignant)),0,1,'L', false);

			// QrCode
			$this->Image('images.png', $postx+160,45+9+12,25,20);

			$this->SetFont('times', 'B', 9);
			$this->SetDrawColor(0, 0, 0);
	        $this->SetFillColor(164, 227, 136);
	        $this->SetTextColor(0, 0, 0);
	        $this->SetXY($postx+1,$posty+45+9+6+6+10);
	        $this->Cell(8,5,utf8_decode('N°'),1,1,'C', true);
			$this->SetXY($postx+9,$posty+45+9+6+6+10);
	        $this->Cell(32,5,utf8_decode('MATRICULE'),1,1,'C', true);
	        $this->SetXY($postx+8+6+27,$posty+45+9+6+6+10);
	        $this->Cell(74,5,utf8_decode('NOMS ET PRENOMS '),1,1,'C', true);
	        $this->SetXY($postx+8+45+62,$posty+45+9+6+6+10);
	        $this->Cell(10,5,utf8_decode('SEXE'),1,1,'C', true);
			$this->SetXY($postx+8+45+70+2,$posty+45+9+6+6+10);
	        $this->Cell(24,5,utf8_decode('PÉRIODE 1 '),1,1,'C', true);
	        $this->SetXY($postx+8+45+70+26,$posty+45+9+6+6+10);
	        $this->Cell(18,5,utf8_decode('PÉRIODE 2 '),1,1,'C', true);
	        $this->SetXY($postx+8+45+70+25+19,$posty+45+9+6+6+10);
	        $this->Cell(18,5,utf8_decode('PÉRIODE 3 '),1,1,'C', true);

	        $posty = $posty+45+9+6+6+10+5;
	        $count = 0;
	        $this->SetFont('times', '','11');
	        for ($i=0; $i < sizeof($data); $i++) { 
	            
	            if ($count >= 40) {
					$this->footer_listing(38, $msg);
	                $this->AddPage(); 
	                $this->Filigramme("School");
	                $this->header_p($year, $school, $contact, $matricule);
					$this->footer_listing(38, $msg);
					
	                $postx = 12;
	        		$posty = 12+45;
	                $count = 0;

	            }else{
					$this->SetFont('times', 'B', 9);
					$this->SetDrawColor(0, 0, 0);
					$this->SetFillColor(164, 227, 136);
					$this->SetTextColor(0, 0, 0);
					$this->SetXY($postx+1,$posty+45-45);
					$this->Cell(8,4,utf8_decode($data[$i]['num']),1,1,'L', false);
					$this->SetXY($postx+9,$posty+45-45);
					$this->Cell(32,4,utf8_decode($data[$i]['mat']),1,1,'L', false);
					$this->SetXY($postx+8+6+27,$posty+45-45);
					$this->Cell(74,4,utf8_decode($data[$i]['nom']),1,1,'L', false);
					$this->SetXY($postx+8+45+62,$posty+45-45);
					$this->Cell(10,4,utf8_decode($data[$i]['sexe']),1,1,'L', false);
					$this->SetXY($postx+8+45+70+2,$posty+45-45);
					$this->Cell(24,4,utf8_decode(""),1,1,'L', false);
					$this->SetXY($postx+8+45+70+26,$posty+45-45);
					$this->Cell(18,4,utf8_decode(""),1,1,'L', false);
					$this->SetXY($postx+8+45+70+25+19,$posty+45-45);
					$this->Cell(18,4,utf8_decode(""),1,1,'L', false);

	                $posty = $posty +4;
	                $count++;
	            }
	            
	        }
    	}

		function fiche_decharge($data, $garcon, $fille, $year, $classe, $school, $contact, $matricule, $msg){

	        $postx = 12;
	        $posty = 12;

	        /*-------------------*/ // entete
			$this->SetFont('times', 'B', 11);
			$this->SetDrawColor(71, 151, 45);
	        $this->SetFillColor(71, 151, 45);
	        $this->SetTextColor(255, 255, 255);
			$this->SetXY($postx+45,$posty+45);
	        $this->Cell(100,6,utf8_decode("FICHE DE DECHARGE "),1,1,'C', true);
			$this->SetTextColor(0, 0, 0);	
			$this->SetFont('times', 'B', 9);
			$this->SetXY($postx+14+20,$posty+45+9);
			$this->Cell(120,5,utf8_decode("... . ... . ... . ... . ... . ... . ... . ... . ... . ... . ... . ... . ... "),0,1,'C', false);
			$this->SetFont('times', '', 9);
			$this->SetXY($postx+14,$posty+45+9+6);
			$this->Cell(120,5,utf8_decode("Effectif: "),0,1,'L', false);
			$this->SetXY($postx+14+13,$posty+45+9+6);
			$this->SetFont('times', 'B', 9);
			$this->Cell(120,5,utf8_decode(($garcon+$fille)),0,1,'L', false);
			$this->SetXY($postx+14+10+30,$posty+45+9+6);
			$this->Cell(120,5,utf8_decode("[ Garçons: ".$garcon." ]"),0,1,'L', false);
			$this->SetXY($postx+14+10+30+50,$posty+45+9+6);
			$this->Cell(120,5,utf8_decode("[ Fille: ".$fille." ]"),0,1,'L', false);
			$this->SetXY($postx+14+10+30+50+20,$posty+45+9+6);
			$this->Cell(120,5,utf8_decode("[ Classe: ".strtoupper($classe)." ]"),0,1,'L', false);
			$this->SetXY($postx+14+20,$posty+45+9+6+8);
			$this->SetFont('times', 'B', 9);
			$this->Cell(120,5,utf8_decode("ANNÉE SCOLAIRE ".$year),0,1,'C', false);

			// QrCode
			$this->Image('images.png', $postx+160,45+9+12,25,20);

			$this->SetFont('times', 'B', 11);
			$this->SetDrawColor(0, 0, 0);
	        $this->SetFillColor(164, 227, 136);
	        $this->SetTextColor(0, 0, 0);
	        $this->SetXY($postx+1,$posty+45+9+6+6+10);
	        $this->Cell(8,5,utf8_decode('N°'),1,1,'C', true);
			$this->SetXY($postx+9,$posty+45+9+6+6+10);
	        $this->Cell(32,5,utf8_decode('MATRICULE'),1,1,'C', true);
	        $this->SetXY($postx+8+6+27,$posty+45+9+6+6+10);
	        $this->Cell(74,5,utf8_decode('NOMS ET PRENOMS '),1,1,'C', true);
	        $this->SetXY($postx+8+45+62,$posty+45+9+6+6+10);
	        $this->Cell(10,5,utf8_decode('SEXE'),1,1,'C', true);
			$this->SetXY($postx+8+45+70+2,$posty+45+9+6+6+10);
	        $this->Cell(26,5,utf8_decode('HEURE '),1,1,'C', true);
	        $this->SetXY($postx+8+45+70+28,$posty+45+9+6+6+10);
	        $this->Cell(16+18,5,utf8_decode('SIGNATURE '),1,1,'C', true);

	        $posty = $posty+45+9+6+6+10+5;
	        $count = 0;
	        $this->SetFont('times', '','11');
	        for ($i=0; $i < sizeof($data); $i++) { 
	            
	            if ($count >= 40) {
					$this->footer_listing(38, $msg);
	                $this->AddPage(); 
	                $this->Filigramme("School");
	                $this->header_p($year, $school, $contact, $matricule);
					$this->footer_listing(38, $msg);
					
	                $postx = 12;
	        		$posty = 12+45;
	                $count = 0;

	            }else{
					$this->SetFont('times', 'B', 9);
					$this->SetDrawColor(0, 0, 0);
					$this->SetFillColor(164, 227, 136);
					$this->SetTextColor(0, 0, 0);
					$this->SetXY($postx+1,$posty+45-45);
					$this->Cell(8,4,utf8_decode($data[$i]['num']),1,1,'L', false);
					$this->SetXY($postx+9,$posty+45-45);
					$this->Cell(32,4,utf8_decode($data[$i]['mat']),1,1,'L', false);
					$this->SetXY($postx+8+6+27,$posty+45-45);
					$this->Cell(74,4,utf8_decode($data[$i]['nom']),1,1,'L', false);
					$this->SetXY($postx+8+45+62,$posty+45-45);
					$this->Cell(10,4,utf8_decode($data[$i]['sexe']),1,1,'L', false);
					$this->SetXY($postx+8+45+70+2,$posty+45-45);
					$this->Cell(26,4,utf8_decode(""),1,1,'C', false);
					$this->SetXY($postx+8+45+70+28,$posty+45-45);
					$this->Cell(16+18,4,utf8_decode(""),1,1,'C', false);

	                $posty = $posty +4;
	                $count++;
	            }
	            
	        }
    	}

		function listing_inscrit($title, $classe, $session, $cycle, $data, $garcon, $fille, $enseignant, $year, $school, $contact, $matricule, $msg){

	        $postx = 12;
	        $posty = 12;

	        $this->SetFont('times', 'B', 11);
			$this->SetDrawColor(71, 151, 45);
	        $this->SetFillColor(71, 151, 45);
	        $this->SetTextColor(255, 255, 255);
			$this->SetXY($postx+45,$posty+45);
	        $this->Cell(100,6,utf8_decode("FICHE DES ÉLÈVES INSCRITS"),1,1,'C', true);
			$this->SetXY($postx+14,$posty+45+9);
			$this->SetTextColor(0, 0, 0);	
			$this->SetFont('times', '', 9);
			$this->Cell(120,5,utf8_decode("Classe: "),0,1,'L', false);
			$this->SetXY($postx+14+10,$posty+45+9);
			$this->SetFont('times', 'B', 9);
			$this->Cell(120,5,utf8_decode($classe),0,1,'L', false);
			$this->SetXY($postx+14+10+30,$posty+45+9);
			$this->Cell(120,5,utf8_decode("[ Session ".$session." ]"),0,1,'L', false);
			$this->SetXY($postx+14+10+30+50,$posty+45+9);
			$this->Cell(120,5,utf8_decode("[ ".$cycle." Cycle ]"),0,1,'L', false);
			$this->SetFont('times', '', 9);
			$this->SetXY($postx+14,$posty+45+9+6);
			$this->Cell(120,5,utf8_decode("Effectif: "),0,1,'L', false);
			$this->SetXY($postx+14+13,$posty+45+9+6);
			$this->SetFont('times', 'B', 9);
			$this->Cell(120,5,utf8_decode(($garcon+$fille)),0,1,'L', false);
			$this->SetXY($postx+14+10+30,$posty+45+9+6);
			$this->Cell(120,5,utf8_decode("[ Garçons: ".$garcon." ]"),0,1,'L', false);
			$this->SetXY($postx+14+10+30+50,$posty+45+9+6);
			$this->Cell(120,5,utf8_decode("[ Fille: ".$fille." ]"),0,1,'L', false);
			$this->SetFont('times', '', 9);
			$this->SetXY($postx+14,$posty+45+9+6+6);
			$this->Cell(120,5,utf8_decode("Enseignant(e): "),0,1,'L', false);
			$this->SetXY($postx+14+20,$posty+45+9+6+6);
			$this->SetFont('times', 'B', 9);
			$this->Cell(120,5,utf8_decode(($enseignant)),0,1,'L', false);

			// QrCode
			$this->Image('images.png', $postx+160,45+9+12,25,20);

			$this->SetFont('times', 'B', 9);
			$this->SetDrawColor(0, 0, 0);
	        $this->SetFillColor(164, 227, 136);
	        $this->SetTextColor(0, 0, 0);
	        $this->SetXY($postx+1,$posty+45+9+6+6+10);
	        $this->Cell(8,5,utf8_decode('N°'),1,1,'C', true);
			$this->SetXY($postx+9,$posty+45+9+6+6+10);
	        $this->Cell(32,5,utf8_decode('MATRICULE'),1,1,'C', true);
	        $this->SetXY($postx+8+6+27,$posty+45+9+6+6+10);
	        $this->Cell(74,5,utf8_decode('NOMS ET PRENOMS '),1,1,'C', true);
	        $this->SetXY($postx+8+45+62,$posty+45+9+6+6+10);
	        $this->Cell(10,5,utf8_decode('SEXE'),1,1,'C', true);
			$this->SetXY($postx+8+45+70+2,$posty+45+9+6+6+10);
	        $this->Cell(26,5,utf8_decode('MONTANT '),1,1,'C', true);
	        $this->SetXY($postx+8+45+70+28,$posty+45+9+6+6+10);
	        $this->Cell(16+18,5,utf8_decode('DATE '),1,1,'C', true);

	        $posty = $posty+45+9+6+6+10+5;
	        $count = 0;
	        $this->SetFont('times', '','9');
	        for ($i=0; $i < sizeof($data); $i++) { 
	            
	            if ($count >= 40) {
					$this->footer_listing(38, $msg);
	                $this->AddPage(); 
	                $this->Filigramme("School");
	                $this->header_p($year, $school, $contact, $matricule);
					$this->footer_listing(38, $msg);
					
	                $postx = 12;
	        		$posty = 12+45;
	                $count = 0;

	            }else{
					$this->SetFont('times', 'B', 9);
					$this->SetDrawColor(0, 0, 0);
					$this->SetFillColor(164, 227, 136);
					$this->SetTextColor(0, 0, 0);
					$this->SetXY($postx+1,$posty+45-45);
					$this->Cell(8,4,utf8_decode($data[$i]['num']),1,1,'L', false);
					$this->SetXY($postx+9,$posty+45-45);
					$this->Cell(32,4,utf8_decode($data[$i]['mat']),1,1,'L', false);
					$this->SetXY($postx+8+6+27,$posty+45-45);
					$this->Cell(74,4,utf8_decode($data[$i]['nom']),1,1,'L', false);
					$this->SetXY($postx+8+45+62,$posty+45-45);
					$this->Cell(10,4,utf8_decode($data[$i]['sexe']),1,1,'L', false);
					$this->SetXY($postx+8+45+70+2,$posty+45-45);
					$this->Cell(26,4,utf8_decode($data[$i]['amount'].' Fcfa'),1,1,'L', false);
					$this->SetXY($postx+8+45+70+28,$posty+45-45);
					$this->Cell(16+18,4,utf8_decode($data[$i]['date']),1,1,'L', false);

	                $posty = $posty +4;
	                $count++;
	            }
	        }
    	}

		function listing_not_inscrit($title, $classe, $session, $cycle, $data, $garcon, $fille, $enseignant, $year, $school, $contact, $matricule, $msg){

	        $postx = 12;
	        $posty = 12;

	        $this->SetFont('times', 'B', 11);
			$this->SetDrawColor(71, 151, 45);
	        $this->SetFillColor(71, 151, 45);
	        $this->SetTextColor(255, 255, 255);
			$this->SetXY($postx+45,$posty+45);
	        $this->Cell(100,6,utf8_decode("FICHE DES ÉLÈVES NON INSCRITS"),1,1,'C', true);
			$this->SetXY($postx+14,$posty+45+9);
			$this->SetTextColor(0, 0, 0);	
			$this->SetFont('times', '', 9);
			$this->Cell(120,5,utf8_decode("Classe: "),0,1,'L', false);
			$this->SetXY($postx+14+10,$posty+45+9);
			$this->SetFont('times', 'B', 9);
			$this->Cell(120,5,utf8_decode($classe),0,1,'L', false);
			$this->SetXY($postx+14+10+30,$posty+45+9);
			$this->Cell(120,5,utf8_decode("[ Session ".$session." ]"),0,1,'L', false);
			$this->SetXY($postx+14+10+30+50,$posty+45+9);
			$this->Cell(120,5,utf8_decode("[ ".$cycle." Cycle ]"),0,1,'L', false);
			$this->SetFont('times', '', 9);
			$this->SetXY($postx+14,$posty+45+9+6);
			$this->Cell(120,5,utf8_decode("Effectif: "),0,1,'L', false);
			$this->SetXY($postx+14+13,$posty+45+9+6);
			$this->SetFont('times', 'B', 9);
			$this->Cell(120,5,utf8_decode(($garcon+$fille)),0,1,'L', false);
			$this->SetXY($postx+14+10+30,$posty+45+9+6);
			$this->Cell(120,5,utf8_decode("[ Garçons: ".$garcon." ]"),0,1,'L', false);
			$this->SetXY($postx+14+10+30+50,$posty+45+9+6);
			$this->Cell(120,5,utf8_decode("[ Fille: ".$fille." ]"),0,1,'L', false);
			$this->SetFont('times', '', 9);
			$this->SetXY($postx+14,$posty+45+9+6+6);
			$this->Cell(120,5,utf8_decode("Enseignant(e): "),0,1,'L', false);
			$this->SetXY($postx+14+20,$posty+45+9+6+6);
			$this->SetFont('times', 'B', 9);
			$this->Cell(120,5,utf8_decode(($enseignant)),0,1,'L', false);

			// QrCode
			$this->Image('images.png', $postx+160,45+9+12,25,20);

			$this->SetFont('times', 'B', 9);
			$this->SetDrawColor(0, 0, 0);
	        $this->SetFillColor(164, 227, 136);
	        $this->SetTextColor(0, 0, 0);
	        $this->SetXY($postx+1,$posty+45+9+6+6+10);
	        $this->Cell(8,5,utf8_decode('N°'),1,1,'C', true);
			$this->SetXY($postx+9,$posty+45+9+6+6+10);
	        $this->Cell(32,5,utf8_decode('MATRICULE'),1,1,'C', true);
	        $this->SetXY($postx+8+6+27,$posty+45+9+6+6+10);
	        $this->Cell(74,5,utf8_decode('NOMS ET PRENOMS '),1,1,'C', true);
	        $this->SetXY($postx+8+45+62,$posty+45+9+6+6+10);
	        $this->Cell(10,5,utf8_decode('SEXE'),1,1,'C', true);
			$this->SetXY($postx+8+45+70+2,$posty+45+9+6+6+10);
	        $this->Cell(26,5,utf8_decode('MONTANT '),1,1,'C', true);
	        $this->SetXY($postx+8+45+70+28,$posty+45+9+6+6+10);
	        $this->Cell(16+18,5,utf8_decode('DATE '),1,1,'C', true);

	        $posty = $posty+45+9+6+6+10+5;
	        $count = 0;
	        $this->SetFont('times', '','9');
	        for ($i=0; $i < sizeof($data); $i++) { 
	            
	            if ($count >= 40) {
					$this->footer_listing(38, $msg);
	                $this->AddPage(); 
	                $this->Filigramme("School");
	                $this->header_p($year, $school, $contact, $matricule);
					$this->footer_listing(38, $msg);
					
	                $postx = 12;
	        		$posty = 12+45;
	                $count = 0;

	            }else{
					$this->SetFont('times', 'B', 9);
					$this->SetDrawColor(0, 0, 0);
					$this->SetFillColor(164, 227, 136);
					$this->SetTextColor(0, 0, 0);
					$this->SetXY($postx+1,$posty+45-45);
					$this->Cell(8,4,utf8_decode($data[$i]['num']),1,1,'L', false);
					$this->SetXY($postx+9,$posty+45-45);
					$this->Cell(32,4,utf8_decode($data[$i]['mat']),1,1,'L', false);
					$this->SetXY($postx+8+6+27,$posty+45-45);
					$this->Cell(74,4,utf8_decode($data[$i]['nom']),1,1,'L', false);
					$this->SetXY($postx+8+45+62,$posty+45-45);
					$this->Cell(10,4,utf8_decode($data[$i]['sexe']),1,1,'L', false);
					$this->SetXY($postx+8+45+70+2,$posty+45-45);
					$this->Cell(26,4,utf8_decode($data[$i]['amount'].' '),1,1,'L', false);
					$this->SetXY($postx+8+45+70+28,$posty+45-45);
					$this->Cell(16+18,4,utf8_decode($data[$i]['date']),1,1,'L', false);

	                $posty = $posty +4;
	                $count++;
	            }
	        }
    	}

		/*----------------------------------------------------------------
		* LISTING STATISTIQUE DE PAYEMENT
		*----------------------------------------------------------------*/
		function listing_stat_payement($data, $garcon, $fille, $year, $montant_verser, $scolarite, $classe, $school, $contact, $matricule, $type_liste){
			
	        $postx = 12;
	        $posty = 12;

	        /*-------------------*/ // entete
			$this->SetFont('times', 'B', 11);
			$this->SetDrawColor(71, 151, 45);
	        $this->SetFillColor(71, 151, 45);
	        $this->SetTextColor(255, 255, 255);
			$this->SetXY($postx+45,$posty+45);
	        $this->Cell(100,6,utf8_decode("STATISTIQUE DE SCOLARITE "),1,1,'C', true);
			$this->SetTextColor(0, 0, 0);
			$this->SetFont('times', '', 9);
			$this->SetXY($postx+14,$posty+45+9);
			$this->Cell(120,5,utf8_decode("Effectif: "),0,1,'L', false);
			$this->SetXY($postx+14+13,$posty+45+9);
			$this->SetFont('times', 'B', 9);
			$this->Cell(120,5,utf8_decode(($garcon+$fille)),0,1,'L', false);
			$this->SetXY($postx+14+10+30,$posty+45+9);
			$this->Cell(120,5,utf8_decode("[ Garçons: ".$garcon." ]"),0,1,'L', false);
			$this->SetXY($postx+14+10+30+50,$posty+45+9);
			$this->Cell(120,5,utf8_decode("[ Fille: ".$fille." ]"),0,1,'L', false);
			$this->SetXY($postx+14+10+30+50+20,$posty+45+9);
			$this->Cell(120,5,utf8_decode("[ Classe: ".mb_strtoupper($classe)." ]"),0,1,'L', false);
			$this->SetXY($postx+14+20,$posty+45+9+6+3);
			$this->SetFont('times', 'B', 9);
			$this->Cell(120,5,utf8_decode("ANNÉE SCOLAIRE ".$year),0,1,'C', false);

			// QrCode
			$this->Image('images.png', $postx+160,45+9+12,25,10);

			// QrCode

			$this->SetDrawColor(0, 0, 0);
	        $this->SetFillColor(164, 227, 136);
	        $this->SetTextColor(0, 0, 0);
			//-- TOTAL VERSER SUR
			$mt_verser = 0;
			$mt_scolar = 0;
			$mt_reste  = 0;
			$mt_reduction = 0;
			for ($i=0; $i < sizeof($data); $i++) {
				if ($type_liste == "insolvable" && ($data[$i]['montant_verser'] < $scolarite)) {
					$mt_verser += $data[$i]['montant_verser'];
					$mt_scolar += $data[$i]['montant_scolar'];
					$mt_reduction += $data[$i]['reduction_bourse'];
				} else if($type_liste == "solvable" && ($data[$i]['montant_verser'] >= $scolarite)){
					$mt_verser += $data[$i]['montant_verser'];
					$mt_scolar += $data[$i]['montant_scolar'];
					$mt_reduction += $data[$i]['reduction_bourse'];
				}else if($type_liste == "insolvable_solvable"){
					$mt_verser += $data[$i]['montant_verser'];
					$mt_scolar += $data[$i]['montant_scolar'];
					$mt_reduction += $data[$i]['reduction_bourse'];
				}		
			}	

			//CALCUL DU RESTE A PAYER
			$mt_reste = $mt_scolar - $mt_verser - $mt_reduction;

			$this->SetXY($postx+1,$posty+45+9+6+6+5);
			$this->Cell(46,5,utf8_decode('TOTAL SCOLARITE'),1,1,'C', true);
			$this->SetXY($postx+47,$posty+45+9+6+6+5);
			$this->Cell(46,5,utf8_decode('TOTAL REDUCTION'),1,1,'C', true);
			$this->SetXY($postx+93,$posty+45+9+6+6+5);
			$this->Cell(46,5,utf8_decode('DEJA VERSER'),1,1,'C', true);
			$this->SetXY($postx+139,$posty+45+9+6+6+5);
			$this->Cell(46,5,utf8_decode('RESTER A VERSER'),1,1,'C', true);


			$this->SetXY($postx+1,$posty+45+9+6+6+10);
			$this->Cell(46,5,utf8_decode(number_format($mt_scolar, 2, ',', ' ').' FCFA'),1,1,'C', true);

			$this->SetXY($postx+47,$posty+45+9+6+6+10);
			$this->Cell(46,5,utf8_decode(number_format($mt_reduction, 2, ',', ' ').' FCFA'),1,1,'C', true);

			$this->SetXY($postx+93,$posty+45+9+6+6+10);
			$this->Cell(46,5,utf8_decode(number_format($mt_verser, 2, ',', ' ').' FCFA'),1,1,'C', true);

			$this->SetXY($postx+139,$posty+45+9+6+6+10);
			$this->Cell(46,5,utf8_decode(number_format($mt_reste, 2, ',', ' ').' FCFA'),1,1,'C', true);

			// $this->SetXY($postx+1,$posty+45+9+6+6+10);
			// $this->Cell(184,5,utf8_decode('TOTAL : '.number_format($mt_scolar, 2, ',', ' ').' FCFA' .' / DEJA VERSER : '.number_format($mt_verser, 2, ',', ' ').' FCFA' .' / RESTE : '.number_format($mt_reste, 2, ',', ' ').' FCFA'),1,1,'C', true);
			$posty += 5;

			$this->SetFont('times', 'B', 9);
	        $this->SetXY($postx+1,$posty+45+9+6+6+10);
	        $this->Cell(8,5,utf8_decode('N°'),1,1,'C', true);
			$this->SetXY($postx+9,$posty+45+9+6+6+10);
	        $this->Cell(32,5,utf8_decode('MATRICULE'),1,1,'C', true);
	        $this->SetXY($postx+8+6+27,$posty+45+9+6+6+10);
	        $this->Cell(74-25,5,utf8_decode('NOMS ET PRENOMS '),1,1,'C', true);
	        $this->SetXY($postx+90,$posty+45+9+6+6+10);
	        $this->Cell(10,5,utf8_decode('SEXE'),1,1,'C', true);
			$this->SetXY($postx+100,$posty+45+9+6+6+10);
	        $this->Cell(15,5,utf8_decode('Réduction'),1,1,'C', true);
			$this->SetXY($postx+8+45+70+2-10,$posty+45+9+6+6+10);
	        $this->Cell(26,5,utf8_decode('MT V'),1,1,'C', true);
			$this->SetXY($postx+8+45+70+2+16,$posty+45+9+6+6+10);
	        $this->Cell(18,5,utf8_decode('MT R'),1,1,'C', true);
	        $this->SetXY($postx+8+45+70+28+8,$posty+45+9+6+6+10);
	        $this->Cell(16+18-8,5,utf8_decode('SCOLARITE '),1,1,'C', true);

	        $posty = $posty+45+9+6+6+10+5;
	        $count = 0;
	        $this->SetFont('times', '','9');
	        for ($i=0; $i < sizeof($data); $i++) {
				if ($type_liste == "insolvable" && ($data[$i]['montant_verser'] < $scolarite)) {
					$this->SetDrawColor(0, 0, 0);
					$this->SetFillColor(164, 227, 136);
					$this->SetTextColor(0, 0, 0); 
					$this->SetFont('times', 'B', 8);
					$this->SetXY($postx+1,$posty+45-45);
					$this->Cell(8,4,($i+1),1,1,'C', false);
					$this->SetXY($postx+9,$posty+45-45);
					$this->Cell(32,4,utf8_decode(mb_strtoupper($data[$i]['matricule'])),1,1,'C', false);
					$this->SetXY($postx+8+6+27,$posty+45-45);
					$this->Cell(74-25,4,utf8_decode(mb_strtoupper($data[$i]['name']." ".$data[$i]['surname'])),1,1,'L', false);
					$this->SetXY($postx+90,$posty+45-45);
					$this->Cell(10,4,utf8_decode($data[$i]['sexe']),1,1,'C', false);
					$this->SetXY($postx+100,$posty+45-45);
					$this->Cell(15, 4, utf8_decode($data[$i]['reduction_bourse'] != 0.00 ? number_format($data[$i]['reduction_bourse'], 2, ',', ' ') . ' FCFA' : ''), 1, 1, 'C', true);

					$this->SetXY($postx+8+45+70+2-10,$posty+45-45);

					if ($data[$i]['montant_verser'] >= $scolarite) {
						$this->SetDrawColor(0, 0, 0);
						$this->SetFillColor(255, 255, 255);
						$this->SetTextColor(0, 0, 0);
					}else{
						$this->SetDrawColor(0, 0, 0);
						$this->SetFillColor(197, 197, 197);
						$this->SetTextColor(0, 0, 0);
					}
					$this->Cell(26,4,utf8_decode(number_format($data[$i]['montant_verser'], 2, ',', ' ').' FCFA'),1,1,'C', true);
					$this->SetXY($postx+8+45+70+28+8,$posty+45-45);

					$this->SetXY($postx+8+45+70+2+16,$posty+45-45);
					$this->Cell(18,4,utf8_decode(number_format(($data[$i]['montant_scolar'] - $data[$i]['montant_verser'] - $data[$i]["reduction_bourse"]), 2, ',', ' ').' FCFA'),1,1,'C', true);

					$this->SetXY($postx+8+45+70+28+8,$posty+45-45);

					$this->Cell(16+18-8,4,utf8_decode(number_format($data[$i]["montant_scolar"], 2, ',', ' ')),1,1,'C', true);

					$posty = $posty +4;
					$count++;

					if ($count >= 40) {
						// $this->footer_listing_stat_pay(38, $classe);
						$this->AddPage(); 
						$this->Filigramme("School");
						$this->header_portrait($year, $school, $contact, $matricule);
						$this->footer_listing_stat_pay(38, $classe);
						
						$postx = 12;
						$posty = 12+45;
						$count = 0;

					}
				} else if($type_liste == "solvable" && ($data[$i]['montant_verser'] >= $scolarite)){
					$this->SetDrawColor(0, 0, 0);
					$this->SetFillColor(164, 227, 136);
					$this->SetTextColor(0, 0, 0);
					$this->SetFont('times', 'B', 8);
					$this->SetXY($postx+1,$posty+45-45);
					$this->Cell(8,4,($i+1),1,1,'C', false);
					$this->SetXY($postx+9,$posty+45-45);
					$this->Cell(32,4,utf8_decode(mb_strtoupper($data[$i]['matricule'])),1,1,'C', false);
					$this->SetXY($postx+8+6+27,$posty+45-45);
					$this->Cell(74-25,4,utf8_decode(mb_strtoupper($data[$i]['name']." ".$data[$i]['surname'])),1,1,'L', false);
					$this->SetXY($postx+90,$posty+45-45);
					$this->Cell(10,4,utf8_decode($data[$i]['sexe']),1,1,'C', false);
					$this->SetXY($postx+100,$posty+45-45);
					$this->Cell(15, 4, utf8_decode($data[$i]['reduction_bourse'] != 0.00 ? number_format($data[$i]['reduction_bourse'], 2, ',', ' ') . ' FCFA' : ''), 1, 1, 'C', true);


					$this->SetXY($postx+8+45+70+2-10,$posty+45-45);
					if ($data[$i]['montant_verser'] >= $scolarite) {
						$this->SetDrawColor(0, 0, 0);
						$this->SetFillColor(255, 255, 255);
						$this->SetTextColor(0, 0, 0);
					}else{
						$this->SetDrawColor(0, 0, 0);
						$this->SetFillColor(197, 197, 197);
						$this->SetTextColor(0, 0, 0);
					}
					$this->Cell(26,4,utf8_decode(number_format($data[$i]['montant_verser'], 2, ',', ' ').' FCFA'),1,1,'C', true);
					
					$this->SetXY($postx+8+45+70+2+16,$posty+45-45);
					$this->Cell(18,4,utf8_decode(number_format(($data[$i]['montant_scolar'] - $data[$i]['montant_verser'] - $data[$i]["reduction_bourse"]), 2, ',', ' ').' FCFA'),1,1,'C', true);

					$this->SetXY($postx+8+45+70+28+8,$posty+45-45);

					$this->SetXY($postx+8+45+70+28+8,$posty+45-45);

					$this->Cell(16+18-8,4,utf8_decode(number_format($data[$i]["montant_scolar"], 2, ',', ' ')),1,1,'C', true);

					$posty = $posty +4;
					$count++;

					if ($count >= 40) {
						// $this->footer_listing_stat_pay(38, $classe);
						$this->AddPage(); 
						$this->Filigramme("School");
						$this->header_portrait($year, $school, $contact, $matricule);
						$this->footer_listing_stat_pay(38, $classe);
						
						$postx = 12;
						$posty = 12+45;
						$count = 0;

					}
				}else if($type_liste == "insolvable_solvable"){
					$this->SetDrawColor(0, 0, 0);
					$this->SetFillColor(164, 227, 136);
					$this->SetTextColor(0, 0, 0);
					$this->SetFont('times', 'B', 8);
					$this->SetXY($postx+1,$posty+45-45);
					$this->Cell(8,4,($i+1),1,1,'C', false);
					$this->SetXY($postx+9,$posty+45-45);
					$this->Cell(32,4,utf8_decode(mb_strtoupper($data[$i]['matricule'])),1,1,'C', false);
					$this->SetXY($postx+8+6+27,$posty+45-45);
					$this->Cell(74-25,4,utf8_decode(mb_strtoupper($data[$i]['name']." ".$data[$i]['surname'])),1,1,'L', false);
					$this->SetXY($postx+90,$posty+45-45);
					$this->Cell(10,4,utf8_decode($data[$i]['sexe']),1,1,'C', false);
					$this->SetXY($postx+100,$posty+45-45);
					$this->Cell(15, 4, utf8_decode($data[$i]['reduction_bourse'] != 0.00 ? number_format($data[$i]['reduction_bourse'], 2, ',', ' ') . ' FCFA' : ''), 1, 1, 'C', true);

					$this->SetXY($postx+8+45+70+2-10,$posty+45-45);
					if ($data[$i]['montant_verser'] >= $scolarite) {
						$this->SetDrawColor(0, 0, 0);
						$this->SetFillColor(255, 255, 255);
						$this->SetTextColor(0, 0, 0);
					}else{
						$this->SetDrawColor(0, 0, 0);
						$this->SetFillColor(197, 197, 197);
						$this->SetTextColor(0, 0, 0);
					}
					$this->Cell(26,4,utf8_decode(number_format($data[$i]['montant_verser'], 2, ',', ' ').' FCFA'),1,1,'C', true);
					$this->SetXY($postx+8+45+70+28+8,$posty+45-45);

					$this->SetXY($postx+8+45+70+2+16,$posty+45-45);
					$this->Cell(18,4,utf8_decode(number_format(($data[$i]['montant_scolar'] - $data[$i]['montant_verser'] - $data[$i]["reduction_bourse"]), 2, ',', ' ').' FCFA'),1,1,'C', true);

					$this->SetXY($postx+8+45+70+28+8,$posty+45-45);
					$chaine_reduct = "";

					$this->Cell(16+18-8,4,utf8_decode(number_format($data[$i]["montant_scolar"], 2, ',', ' ')),1,1,'C', true);

					$posty = $posty +4;
					$count++;

					if ($count >= 40) {
						// $this->footer_listing_stat_pay(38, $classe);
						$this->AddPage(); 
						$this->Filigramme("School");
						$this->header_portrait($year, $school, $contact, $matricule);
						$this->footer_listing_stat_pay(38, $classe);
						
						$postx = 12;
						$posty = 12+45;
						$count = 0;

					}
				}	
				
				
	        }
    	}

		function listing_stat_payement2($data, $garcon, $fille, $year, $montant_verser, $scolarite, $classe, $school, $contact, $matricule, $type_liste){
			
	        $postx = 12;
	        $posty = 12;

	        /*-------------------*/ // entete
			$this->SetFont('times', 'B', 9);
			$this->SetDrawColor(71, 151, 45);
	        $this->SetFillColor(71, 151, 45);
	        $this->SetTextColor(255, 255, 255);
			$this->SetXY($postx+45,$posty+45);
	        $this->Cell(100,6,utf8_decode("STATISTIQUE DE SCOLARITE "),1,1,'C', true);
			$this->SetTextColor(0, 0, 0);	
			$this->SetFont('times', '', 9);
			$this->SetXY($postx+14,$posty+45+9);
			$this->Cell(120,5,utf8_decode("Effectif: "),0,1,'L', false);
			$this->SetXY($postx+14+13,$posty+45+9);
			$this->SetFont('times', 'B', 9);
			$this->Cell(120,5,utf8_decode(($garcon+$fille)),0,1,'L', false);
			$this->SetXY($postx+14+10+30,$posty+45+9);
			$this->Cell(120,5,utf8_decode("[ Garçons: ".$garcon." ]"),0,1,'L', false);
			$this->SetXY($postx+14+10+30+50,$posty+45+9);
			$this->Cell(120,5,utf8_decode("[ Fille: ".$fille." ]"),0,1,'L', false);
			//$this->SetXY($postx+14+10+30+50+20,$posty+45+9+6);
			//$this->Cell(120,5,utf8_decode("[ Classe: ".mb_strtoupper($classe)." ]"),0,1,'L', false);
			$this->SetXY($postx+14+20,$posty+45+9+6+3);
			$this->SetFont('times', 'B', 9);
			$this->Cell(120,5,utf8_decode("ANNÉE SCOLAIRE ".$year),0,1,'C', false);

			// QrCode
			$this->Image('images.png', $postx+160,45+9+12,25,10);
			// QrCode

			$this->SetDrawColor(0, 0, 0);
	        $this->SetFillColor(164, 227, 136);
	        $this->SetTextColor(0, 0, 0);
			//-- TOTAL VERSER SUR 
			$mt_verser = 0;
			$mt_scolar = 0;
			$mt_reste  = 0;
			$mt_reduction = 0;
			for ($i=0; $i < sizeof($data); $i++) {
				if ($type_liste == "insolvable" && ($data[$i]['montant_verser'] < $scolarite)) {
					$mt_verser += $data[$i]['montant_verser'];
					$mt_scolar += $data[$i]['montant_scolar'];
					$mt_reduction += $data[$i]['reduction_bourse'];
				} else if($type_liste == "solvable" && ($data[$i]['montant_verser'] >= $scolarite)){
					$mt_verser += $data[$i]['montant_verser'];
					$mt_scolar += $data[$i]['montant_scolar'];
					$mt_reduction += $data[$i]['reduction_bourse'];
				}else if($type_liste == "insolvable_solvable"){
					$mt_verser += $data[$i]['montant_verser'];
					$mt_scolar += $data[$i]['montant_scolar'];
					$mt_reduction += $data[$i]['reduction_bourse'];
				}		
			}	

			//CALCUL DU RESTE A PAYER
			$mt_reste = $mt_scolar - $mt_verser - $mt_reduction;

			$this->SetXY($postx+1,$posty+45+9+6+6+5);
			$this->Cell(46,5,utf8_decode('TOTAL SCOLARITE'),1,1,'C', true);
			$this->SetXY($postx+47,$posty+45+9+6+6+5);
			$this->Cell(46,5,utf8_decode('TOTAL REDUCTION'),1,1,'C', true);
			$this->SetXY($postx+93,$posty+45+9+6+6+5);
			$this->Cell(46,5,utf8_decode('DEJA VERSER'),1,1,'C', true);
			$this->SetXY($postx+139,$posty+45+9+6+6+5);
			$this->Cell(46,5,utf8_decode('RESTER A VERSER'),1,1,'C', true);


			$this->SetXY($postx+1,$posty+45+9+6+6+10);
			$this->Cell(46,5,utf8_decode(number_format($mt_scolar, 2, ',', ' ').' FCFA'),1,1,'C', true);

			$this->SetXY($postx+47,$posty+45+9+6+6+10);
			$this->Cell(46,5,utf8_decode(number_format($mt_reduction, 2, ',', ' ').' FCFA'),1,1,'C', true);

			$this->SetXY($postx+93,$posty+45+9+6+6+10);
			$this->Cell(46,5,utf8_decode(number_format($mt_verser, 2, ',', ' ').' FCFA'),1,1,'C', true);

			$this->SetXY($postx+139,$posty+45+9+6+6+10);
			$this->Cell(46,5,utf8_decode(number_format($mt_reste, 2, ',', ' ').' FCFA'),1,1,'C', true);

			// $this->SetXY($postx+1,$posty+45+9+6+6+10);
			// $this->Cell(184,5,utf8_decode('TOTAL REDUCTION: '.number_format($mt_reduction, 2, ',', ' ').' FCFA' .' / DEJA VERSER : '.number_format($mt_verser, 2, ',', ' ').' FCFA' .' / RESTE : '.number_format($mt_reste, 2, ',', ' ').' FCFA'),1,1,'C', true);
			
			$posty += 5;

			$this->SetFont('times', 'B', 9);
	        $this->SetXY($postx+1,$posty+45+9+6+6+10);
	        $this->Cell(8,5,utf8_decode('N°'),1,1,'C', true);
			$this->SetXY($postx+9,$posty+45+9+6+6+10);
	        $this->Cell(32,5,utf8_decode('MATRICULE'),1,1,'C', true);
	        $this->SetXY($postx+8+6+27,$posty+45+9+6+6+10);
	        $this->Cell(74-25,5,utf8_decode('NOMS ET PRENOMS '),1,1,'C', true);
	        $this->SetXY($postx+90,$posty+45+9+6+6+10);
	        $this->Cell(10,5,utf8_decode('SEXE'),1,1,'C', true);
			$this->SetXY($postx+100,$posty+45+9+6+6+10);
	        $this->Cell(15,5,utf8_decode('Réduction'),1,1,'C', true);
			$this->SetXY($postx+8+45+70+2-10,$posty+45+9+6+6+10);
	        $this->Cell(26,5,utf8_decode('MT V'),1,1,'C', true);
			$this->SetXY($postx+8+45+70+2+16,$posty+45+9+6+6+10);
	        $this->Cell(18,5,utf8_decode('MT R'),1,1,'C', true);
	        $this->SetXY($postx+8+45+70+28+8,$posty+45+9+6+6+10);
	        $this->Cell(16+18-8,5,utf8_decode('SCOLARITE '),1,1,'C', true);

	        $posty = $posty+45+9+6+6+10+5;
	        $count = 0;
	        $this->SetFont('times', '','8');
	        for ($i=0; $i < sizeof($data); $i++) {
				if ($type_liste == "insolvable" && ($data[$i]['montant_verser'] < $scolarite)) {
					$this->SetDrawColor(0, 0, 0);
					$this->SetFillColor(164, 227, 136);
					$this->SetTextColor(0, 0, 0);
					$this->SetFont('times', 'B', 9);
					$this->SetXY($postx+1,$posty+45-45);
					$this->Cell(8,4,($i+1),1,1,'C', false);
					$this->SetXY($postx+9,$posty+45-45);
					$this->Cell(32,4,utf8_decode(mb_strtoupper($data[$i]['matricule'])),1,1,'C', false);
					$this->SetXY($postx+8+6+27,$posty+45-45);
					$this->Cell(74-25,4,utf8_decode(mb_strtoupper($data[$i]['name']." ".$data[$i]['surname'])),1,1,'L', false);
					$this->SetXY($postx+90,$posty+45-45);
					$this->Cell(10,4,utf8_decode($data[$i]['sexe']),1,1,'C', false);

					$this->SetXY($postx+100,$posty+45-45);
					// $this->Cell(15,4,utf8_decode(number_format($data[$i]['reduction_bourse'], 2, ',', ' ').' FCFA'),1,1,'C', true);
					$this->Cell(15,4,utf8_decode("500FCFA"),1,1,'C', true);

					$this->SetXY($postx+8+45+70+2-10,$posty+45-45);
					if ($data[$i]['montant_verser'] >= $scolarite) {
						$this->SetDrawColor(0, 0, 0);
						$this->SetFillColor(255, 255, 255);
						$this->SetTextColor(0, 0, 0);
					}else{
						$this->SetDrawColor(0, 0, 0);
						$this->SetFillColor(197, 197, 197);
						$this->SetTextColor(0, 0, 0);
					}
					$this->Cell(26,4,utf8_decode(number_format($data[$i]['montant_verser'], 2, ',', ' ').' FCFA'),1,1,'C', true);
					$this->SetXY($postx+8+45+70+28+8,$posty+45-45);

					$this->SetXY($postx+8+45+70+2+16,$posty+45-45);
					$this->Cell(18,4,utf8_decode(number_format(($data[$i]['montant_scolar'] - $data[$i]['montant_verser'] - $data[$i]["reduction_bourse"]), 2, ',', ' ').' FCFA'),1,1,'C', true);

					$this->SetXY($postx+8+45+70+28+8,$posty+45-45);

					
					$this->Cell(16+18-8,4,utf8_decode(number_format($data[$i]["montant_scolar"], 2, ',', ' ')),1,1,'C', true);

					$posty = $posty +4;
					$count++;

					if ($count >= 40) {
						// $this->footer_listing_stat_pay(38, $classe);
						$this->AddPage(); 
						$this->Filigramme("School");
						$this->header_portrait($year, $school, $contact, $matricule);
						$this->footer_listing_stat_pay(38, $classe);
						
						$postx = 12;
						$posty = 12+45;
						$count = 0;

					}
				} else if($type_liste == "solvable" && ($data[$i]['montant_verser'] >= $scolarite)){
					$this->SetDrawColor(0, 0, 0);
					$this->SetFillColor(164, 227, 136);
					$this->SetTextColor(0, 0, 0);
					$this->SetFont('times', 'B', 8);
					$this->SetXY($postx+1,$posty+45-45);
					$this->Cell(8,4,($i+1),1,1,'C', false);
					$this->SetXY($postx+9,$posty+45-45);
					$this->Cell(32,4,utf8_decode(mb_strtoupper($data[$i]['matricule'])),1,1,'C', false);
					$this->SetXY($postx+8+6+27,$posty+45-45);
					$this->Cell(74-25,4,utf8_decode(mb_strtoupper($data[$i]['name']." ".$data[$i]['surname'])),1,1,'L', false);
					$this->SetXY($postx+90,$posty+45-45);
					$this->Cell(10,4,utf8_decode($data[$i]['sexe']),1,1,'C', false);
					$this->SetXY($postx+100,$posty+45-45);
					// $this->Cell(15,4,utf8_decode(number_format($data[$i]['reduction_bourse'], 2, ',', ' ').' FCFA'),1,1,'C', true);
					$this->Cell(15,4,utf8_decode("500FCFA"),1,1,'C', true);
					
					// $this->Cell(15,4,utf8_decode(number_format(isset($data[$i]['reduction_bourse']) ? $data[$i]['reduction_bourse'] : 0.00, 2, ',', ' ') . ' FCFA'),1,1,'C', true);
					$this->SetXY($postx+8+45+70+2-10,$posty+45-45);
					if ($data[$i]['montant_verser'] >= $scolarite) {
						$this->SetDrawColor(0, 0, 0);
						$this->SetFillColor(255, 255, 255);
						$this->SetTextColor(0, 0, 0);
					}else{
						$this->SetDrawColor(0, 0, 0);
						$this->SetFillColor(197, 197, 197);
						$this->SetTextColor(0, 0, 0);
					}
					$this->Cell(26,4,utf8_decode(number_format($data[$i]['montant_verser'], 2, ',', ' ').' FCFA'),1,1,'C', true);
					
					$this->SetXY($postx+8+45+70+2+16,$posty+45-45);
					$this->Cell(18,4,utf8_decode(number_format(($data[$i]['montant_scolar'] - $data[$i]['montant_verser'] - $data[$i]["reduction_bourse"]), 2, ',', ' ').' FCFA'),1,1,'C', true);

					$this->SetXY($postx+8+45+70+28+8,$posty+45-45);

					$this->SetXY($postx+8+45+70+28+8,$posty+45-45);
					$chaine_reduct = "";
					
					$this->Cell(16+18-8,4,utf8_decode(number_format($data[$i]["montant_scolar"], 2, ',', ' ')),1,1,'C', true);

					$posty = $posty +4;
					$count++;

					if ($count >= 40) {
						// $this->footer_listing_stat_pay(38, $classe);
						$this->AddPage(); 
						$this->Filigramme("School");
						$this->header_portrait($year, $school, $contact, $matricule);
						$this->footer_listing_stat_pay(38, $classe);
						
						$postx = 12;
						$posty = 12+45;
						$count = 0;

					}
				}else if($type_liste == "insolvable_solvable"){
					$this->SetDrawColor(0, 0, 0);
					$this->SetFillColor(164, 227, 136);
					$this->SetTextColor(0, 0, 0);
					$this->SetFont('times', 'B', 8);
					$this->SetXY($postx+1,$posty+45-45);
					$this->Cell(8,4,($i+1),1,1,'C', false);
					$this->SetXY($postx+9,$posty+45-45);
					$this->Cell(32,4,utf8_decode(mb_strtoupper($data[$i]['matricule'])),1,1,'C', false);
					$this->SetXY($postx+8+6+27,$posty+45-45);
					$this->Cell(74-25,4,utf8_decode(mb_strtoupper($data[$i]['name']." ".$data[$i]['surname'])),1,1,'L', false);
					$this->SetXY($postx+90,$posty+45-45);
					$this->Cell(10,4,utf8_decode($data[$i]['sexe']),1,1,'C', false);

					$this->SetXY($postx+100,$posty+45-45);
					// $this->Cell(15,4,utf8_decode(number_format($data[$i]['reduction_bourse'], 2, ',', ' ').' FCFA'),1,1,'C', true);
					$this->Cell(15, 4, utf8_decode($data[$i]['reduction_bourse'] != 0.00 ? number_format($data[$i]['reduction_bourse'], 2, ',', ' ') . ' FCFA' : ''), 1, 1, 'C', true);

					// $this->Cell(15,4,utf8_decode("500FCFA"),1,1,'C', true);


					$this->SetXY($postx+8+45+70+2-10,$posty+45-45);
					if ($data[$i]['montant_verser'] >= $scolarite) {
						$this->SetDrawColor(0, 0, 0);
						$this->SetFillColor(255, 255, 255);
						$this->SetTextColor(0, 0, 0);
					}else{
						$this->SetDrawColor(0, 0, 0);
						$this->SetFillColor(197, 197, 197);
						$this->SetTextColor(0, 0, 0);
					}
					$this->Cell(26,4,utf8_decode(number_format($data[$i]['montant_verser'], 2, ',', ' ').' FCFA'),1,1,'C', true);
					$this->SetXY($postx+8+45+70+28+8,$posty+45-45);

					$this->SetXY($postx+8+45+70+2+16,$posty+45-45);
					$this->Cell(18,4,utf8_decode(number_format(($data[$i]['montant_scolar'] - $data[$i]['montant_verser'] - $data[$i]["reduction_bourse"]), 2, ',', ' ').' FCFA'),1,1,'C', true);

					$this->SetXY($postx+8+45+70+28+8,$posty+45-45);
					
					$this->Cell(16+18-8,4,utf8_decode(number_format($data[$i]["montant_scolar"], 2, ',', ' ')),1,1,'C', true);

					$posty = $posty +4;
					$count++;

					if ($count >= 40) {
						// $this->footer_listing_stat_pay(38, $classe);
						$this->AddPage(); 
						$this->Filigramme("School");
						$this->header_portrait($year, $school, $contact, $matricule);
						$this->footer_listing_stat_pay(38, $classe);
						
						$postx = 12;
						$posty = 12+45;
						$count = 0;

					}
				}	
	        }
    	}



		/*----------------------------------------------------------------
		* LISTING DES ENSEIGNANTS
		*----------------------------------------------------------------*/
		function listing_enseignant($title, $data, $garcon, $fille, $year, $school, $contact, $matricule, $msg){

	        $postx = 12;
	        $posty = 12;

	        /*-------------------*/ // entete
			$this->SetFont('times', 'B', 9);
			$this->SetDrawColor(71, 151, 45);
	        $this->SetFillColor(71, 151, 45);
	        $this->SetTextColor(255, 255, 255);
			$this->SetXY($postx+45,$posty+45);
	        $this->Cell(100,6,utf8_decode(mb_strtoupper($title)),1,1,'C', true);
			$this->SetTextColor(0, 0, 0);	
			$this->SetFont('times', '', 9);
			$this->SetXY($postx+14,$posty+45+9+6);
			$this->Cell(120,5,utf8_decode("Effectif: "),0,1,'L', false);
			$this->SetXY($postx+14+13,$posty+45+9+6);
			$this->SetFont('times', 'B', 9);
			$this->Cell(120,5,utf8_decode(($garcon+$fille)),0,1,'L', false);
			$this->SetXY($postx+14+10+30,$posty+45+9+6);
			$this->Cell(120,5,utf8_decode("[ Garçons: ".$garcon." ]"),0,1,'L', false);
			$this->SetXY($postx+14+10+30+50,$posty+45+9+6);
			$this->Cell(120,5,utf8_decode("[ Fille: ".$fille." ]"),0,1,'L', false);
			$this->SetXY($postx+14+20,$posty+45+9+6+8);
			$this->SetFont('times', 'B', 9);
			$this->Cell(120,5,utf8_decode("ANNÉE SCOLAIRE ".$year),0,1,'C', false);

			// QrCode
			$this->Image('images.png', $postx+160,45+9+12,25,20);

			$this->SetFont('times', 'B', 7);
			$this->SetDrawColor(0, 0, 0);
	        $this->SetFillColor(164, 227, 136);
	        $this->SetTextColor(0, 0, 0);
	        $this->SetXY($postx+1,$posty+45+9+6+6+10);
	        $this->Cell(8,5,utf8_decode('N°'),1,1,'C', true);
			$this->SetXY($postx+9,$posty+45+9+6+6+10);
	        $this->Cell(32,5,utf8_decode('MATRICULE'),1,1,'C', true);
	        $this->SetXY($postx+8+6+27,$posty+45+9+6+6+10);
	        $this->Cell(74,5,utf8_decode('NOMS ET PRENOMS '),1,1,'C', true);
	        $this->SetXY($postx+8+45+62,$posty+45+9+6+6+10);
	        $this->Cell(10,5,utf8_decode('SEXE'),1,1,'C', true);
			$this->SetXY($postx+8+45+70+2,$posty+45+9+6+6+10);
	        $this->Cell(26,5,utf8_decode('PHONE '),1,1,'C', true);
	        $this->SetXY($postx+8+45+70+28,$posty+45+9+6+6+10);
	        $this->Cell(16+18,5,utf8_decode('CLASSE '),1,1,'C', true);

	        $posty = $posty+45+9+6+6+10+5;
	        $count = 0;
	        $this->SetFont('times', '','7');
	        for ($i=0; $i < sizeof($data); $i++) { 
	            
	            if ($count >= 40) {
					$this->footer_listing(38, $msg);
	                $this->AddPage(); 
	                $this->Filigramme("School");
	                $this->header_p($year, $school, $contact, $matricule);
					$this->footer_listing(38, $msg);
					
	                $postx = 12;
	        		$posty = 12+45;
	                $count = 0;

	            }else{
					$this->SetFont('times', 'B', 6);
					$this->SetDrawColor(0, 0, 0);
					$this->SetFillColor(164, 227, 136);
					$this->SetTextColor(0, 0, 0);
					$this->SetXY($postx+1,$posty+45-45);
					$this->Cell(8,4,utf8_decode($i),1,1,'C', false);
					$this->SetXY($postx+9,$posty+45-45);
					$this->Cell(32,4,utf8_decode(mb_strtoupper($data[$i]['matricule'])),1,1,'C', false);
					$this->SetXY($postx+8+6+27,$posty+45-45);
					$this->Cell(74,4,utf8_decode(mb_strtoupper($data[$i]['name'].' '.$data[$i]['surname'])),1,1,'C', false);
					$this->SetXY($postx+8+45+62,$posty+45-45);
					$this->Cell(10,4,utf8_decode($data[$i]['sexe']),1,1,'C', false);
					$this->SetXY($postx+8+45+70+2,$posty+45-45);
					$this->Cell(26,4,utf8_decode($data[$i]['contact']),1,1,'C', false);
					$this->SetXY($postx+8+45+70+28,$posty+45-45);
					$this->Cell(16+18,4,utf8_decode($data[$i]['classe']),1,1,'C', false);

	                $posty = $posty +4;
	                $count++;
	            }
	            
	        }
    	}

		function fiche_decharge_enseignant($data, $garcon, $fille, $year, $school, $contact, $matricule){

	        $postx = 12;
	        $posty = 12;

	        /*-------------------*/ // entete
			$this->SetFont('times', 'B', 9);
			$this->SetDrawColor(71, 151, 45);
	        $this->SetFillColor(71, 151, 45);
	        $this->SetTextColor(255, 255, 255);
			$this->SetXY($postx+45,$posty+45);
	        $this->Cell(100,6,utf8_decode("FICHE DE DECHARGE "),1,1,'C', true);
			$this->SetTextColor(0, 0, 0);	
			$this->SetFont('times', 'B', 9);
			$this->SetXY($postx+14+20,$posty+45+9);
			$this->Cell(120,5,utf8_decode("... . ... . ... . ... . ... . ... . ... . ... . ... . ... . ... . ... . ... "),0,1,'C', false);
			$this->SetFont('times', '', 9);
			$this->SetXY($postx+14,$posty+45+9+6);
			$this->Cell(120,5,utf8_decode("Effectif: "),0,1,'L', false);
			$this->SetXY($postx+14+13,$posty+45+9+6);
			$this->SetFont('times', 'B', 9);
			$this->Cell(120,5,utf8_decode(($garcon+$fille)),0,1,'L', false);
			$this->SetXY($postx+14+10+30,$posty+45+9+6);
			$this->Cell(120,5,utf8_decode("[ Homme: ".$garcon." ]"),0,1,'L', false);
			$this->SetXY($postx+14+10+30+50,$posty+45+9+6);
			$this->Cell(120,5,utf8_decode("[ Femme: ".$fille." ]"),0,1,'L', false);
			$this->SetXY($postx+14+20,$posty+45+9+6+8);
			$this->SetFont('times', 'B', 9);
			$this->Cell(120,5,utf8_decode("ANNÉE SCOLAIRE ".$year),0,1,'C', false);

			// QrCode
			$this->Image('images.png', $postx+160,45+9+12,25,20);

			$this->SetFont('times', 'B', 7);
			$this->SetDrawColor(0, 0, 0);
	        $this->SetFillColor(164, 227, 136);
	        $this->SetTextColor(0, 0, 0);
	        $this->SetXY($postx+1,$posty+45+9+6+6+10);
	        $this->Cell(8,5,utf8_decode('N°'),1,1,'C', true);
			$this->SetXY($postx+9,$posty+45+9+6+6+10);
	        $this->Cell(32,5,utf8_decode('MATRICULE'),1,1,'C', true);
	        $this->SetXY($postx+8+6+27,$posty+45+9+6+6+10);
	        $this->Cell(74,5,utf8_decode('NOMS ET PRENOMS '),1,1,'C', true);
	        $this->SetXY($postx+8+45+62,$posty+45+9+6+6+10);
	        $this->Cell(10,5,utf8_decode('SEXE'),1,1,'C', true);
			$this->SetXY($postx+8+45+70+2,$posty+45+9+6+6+10);
	        $this->Cell(26,5,utf8_decode('HEURE '),1,1,'C', true);
	        $this->SetXY($postx+8+45+70+28,$posty+45+9+6+6+10);
	        $this->Cell(16+18,5,utf8_decode('SIGNATURE '),1,1,'C', true);

	        $posty = $posty+45+9+6+6+10+5;
	        $count = 0;
	        $this->SetFont('times', '','7');
	        for ($i=0; $i < sizeof($data); $i++) { 
	            
	            if ($count >= 40) {
					$this->footer_listing(38, "", "", "");
	                $this->AddPage(); 
	                $this->Filigramme("School");
	                $this->header_p($year, $school, $contact, $matricule);
					$this->footer_listing(38, "", "", "");
					
	                $postx = 12;
	        		$posty = 12+45;
	                $count = 0;

	            }else{
					$this->SetFont('times', 'B', 6);
					$this->SetDrawColor(0, 0, 0);
					$this->SetFillColor(164, 227, 136);
					$this->SetTextColor(0, 0, 0);
					$this->SetXY($postx+1,$posty+45-45);
					$this->Cell(8,4,utf8_decode($i+1),1,1,'C', false);
					$this->SetXY($postx+9,$posty+45-45);
					$this->Cell(32,4,utf8_decode($data[$i]['matricule']),1,1,'C', false);
					$this->SetXY($postx+8+6+27,$posty+45-45);
					$this->Cell(74,4,utf8_decode($data[$i]['name'].' '.$data[$i]['surname']),1,1,'C', false);
					$this->SetXY($postx+8+45+62,$posty+45-45);
					$this->Cell(10,4,utf8_decode($data[$i]['sexe']),1,1,'C', false);
					$this->SetXY($postx+8+45+70+2,$posty+45-45);
					$this->Cell(26,4,utf8_decode(""),1,1,'C', false);
					$this->SetXY($postx+8+45+70+28,$posty+45-45);
					$this->Cell(16+18,4,utf8_decode(""),1,1,'C', false);

	                $posty = $posty +4;
	                $count++;
	            }
	            
	        }
    	}

	    /*----------------------------------------------------------------
		* FOOTER LISTING
		*----------------------------------------------------------------*/
	    function footer_listing($y, $msg) {
			$this->SetXY(10,-20);
			$this->Cell(0,10,utf8_decode("_______________________________________________________________________________________________________________________________________________"),0,0,'C');
			$this->SetXY(10,-19);
			$this->Cell(0,10,utf8_decode("____________________________________________________________________________________________________________________________"),0,0,'C');
	        $this->SetXY(10,-15);
			$page = $this->PageNo();
			$this->Cell(0,10,utf8_decode($page.'/{nb}' ),0,0,'R');
			$this->SetXY(10,-15);
	        $this->SetFont('Times', 'B' ,7);
	        $this->Cell(0,10,utf8_decode($msg),0,0,'C');
	        
	        $this->SetXY(10,-$y);
	        $this->Cell(0,10,utf8_decode("Le Principal" ),0,0,'R');

	    }

		function footer_listing_portrait($y, $msg) {
			$this->SetXY(10,-20);
			$this->Cell(0,10,utf8_decode("_______________________________________________________________________________________________________________________________________________"),0,0,'C');
			$this->SetXY(10,-19);
			$this->Cell(0,10,utf8_decode("____________________________________________________________________________________________________________________________"),0,0,'C');
	        $this->SetXY(10,-15);
			$page = $this->PageNo();
			$this->Cell(0,10,utf8_decode($page.'/{nb}' ),0,0,'R');
			$this->SetXY(10,-15);
	        $this->SetFont('Times', 'B' ,7);
	        $this->Cell(0,10,utf8_decode($msg),0,0,'C');
	        
	        $this->SetXY(10,-$y);
	        $this->Cell(0,10,utf8_decode("Le Principal" ),0,0,'R');
			$this->SetXY(10,-$y);
	        $this->Cell(0,10,utf8_decode("Journée du ".date("Y-m-d") ),0,0,'L');

	    }

		/*----------------------------------------------------------------
		* FOOTER STAT PAYEMENT
		*----------------------------------------------------------------*/
	    function footer_listing_stat_pay($y, $classe) {
			$this->SetXY(10,-20);
			$this->Cell(0,10,utf8_decode("_______________________________________________________________________________________________________________________________________________"),0,0,'C');
			$this->SetXY(10,-19);
			$this->Cell(0,10,utf8_decode("____________________________________________________________________________________________________________________________"),0,0,'C');
	        $this->SetXY(10,-15);
			$page = $this->PageNo();
			$this->Cell(0,10,utf8_decode($page.'/{nb}' ),0,0,'R');
			$this->SetXY(10,-15);
	        $this->SetFont('Times', 'B' ,7);
	        $this->Cell(0,10,utf8_decode("Statistique de scolarite, classe : ".mb_strtoupper($classe)),0,0,'C');
	        
	        $this->SetXY(10,-$y);
	        $this->Cell(0,10,utf8_decode("Le Principal" ),0,0,'R');

	    }

		
	}
	
 ?>