<?php 
	namespace App\Controllers;
    
	class FPDF_RECU extends FPDF{
		
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
		* ENTETE
		* DESCRIPTION: margex 5, margey: 5
		* 			   largeur: 287 hauteur: 195 espace: 5
					   ---------------------------------- 
					   A5*G A5*D: largeur: 141 postx=146 hauteur: 195
		*			   Mode: renverser  
		*----------------------------------------------------------------*/

		public function header_recu($data, $rightX){
			$postx = 5+$rightX;
			$posty = 5;
			$this->SetFont('arial', 'B','8');

			/*--- LOGO ------*/
			$this->Image(getenv('LOGO_CIRCLE'), $postx,$posty,15,15);
			/*--- LOGO ------*/
			$this->SetXY($postx+15,$posty+1);
	        $this->Cell(86,5,utf8_decode($data["paye_par"]),0,0,'L');
			$this->SetXY($postx+15,$posty+1+4);
	        $this->Cell(86,5,utf8_decode('DE BAFOUSSAM'),0,0,'L');
			$this->SetFont('arial', '','7');
			$this->SetXY($postx+15,$posty+1+4+4);
	        $this->Cell(86,5,utf8_decode('Tél : '.$data["phone"]),0,0,'L');
			//--
			$this->SetFont('arial', 'B','8');
			$this->SetXY($postx+56,$posty+1);
			$this->Cell(86,5,utf8_decode('REÇU DE PAIEMENT'),0,0,'R');
			$this->SetXY($postx+56,$posty+1+4);
	        $this->Cell(86,5,utf8_decode('DE SCOLARITE'),0,0,'R');
			$this->SetFont('arial', '','7');
			$this->SetXY($postx+56,$posty+1+4+4);
	        $this->Cell(86,5,utf8_decode('Date : '.$data["date"]),0,0,'R');

			// line
			$this->SetDrawColor(43,36,159);
  			$this->Line(5+$rightX,$posty+15,145.5+$rightX,$posty+15);
  			$this->Line(5+$rightX,$posty+15.1,145.5+$rightX,$posty+15.1);
  			$this->Line(5+$rightX,$posty+15.2,145.5+$rightX,$posty+15.2);
  			$this->Line(5+$rightX,$posty+15.3,145.5+$rightX,$posty+15.3);
  			$this->Line(5+$rightX,$posty+15.4,145.5+$rightX,$posty+15.4);

			/*--- PHOTO ------*/
			$this->SetXY($postx,$posty+25);
			$this->Cell($posty+13,$posty+13,"",1,0,'C');
			$this->Image(getenv("FILE_PHOTO_STUDENT")."/".$data["photo"], $postx,$posty+25,$posty+13,$posty+13);
			// matricule
			$this->SetFont('arial', 'B','7');
			$this->SetXY($postx,$posty+25+4+4+4+3+5);
			$this->Cell(86,5,utf8_decode(strtoupper($data["mat"])),0,0,'L');
			/*--- PHOTO ------*/
			$this->SetXY($postx+20,$posty+25);
			$this->SetFont('arial', 'B','7');
			$this->Cell(86,5,utf8_decode('NOM : '),0,0,'L');
			$this->SetFont('arial', '','7');
			$this->SetXY($postx+20+8,$posty+25);
			$this->Cell(86,5,utf8_decode(strtoupper($data["name"])),0,0,'L');
			$this->SetXY($postx+20,$posty+25+4);
			$this->SetFont('arial', 'B','7');
			$this->Cell(86,5,utf8_decode('PRENOM : '),0,0,'L');
			$this->SetFont('arial', '','7');
			$this->SetXY($postx+20+13,$posty+25+4);
			$this->Cell(86,5,utf8_decode(strtoupper($data["surname"])),0,0,'L');
			$this->SetFont('arial', 'B','7');
			$this->SetXY($postx+20,$posty+25+4+4);
			$this->Cell(86,5,utf8_decode('SEXE : '),0,0,'L');
			$this->SetFont('arial', '','7');
			$this->SetXY($postx+20+9,$posty+25+4+4);
			$this->Cell(86,5,utf8_decode(strtoupper($data["sexe"])),0,0,'L');
			$this->SetXY($postx+20,$posty+25+4+4+4);

			/*--- QRCODE ------*/
			$this->SetXY($postx,$posty+25);
			$this->Image('images.png', $postx+123,$posty+25,$posty+13,$posty+13);
			/*--- QRCODE ------*/

			/*---- NOS BANQUES ----*/
			$this->SetFont('arial', 'B','9');
			$this->SetXY($postx,$posty+25+30);
			$this->SetTextColor(43,36,159);
			$this->Cell(86,5,utf8_decode("NOS BANQUES"),0,0,'L');
			$this->SetFont('arial', '','7');
			$bx = $postx+4;
			$by = $posty+25+30+5;
			$banques = $data["banques"];
			$count = 1;
			for ($i=0; $i < sizeof($banques); $i++) { 
				$this->SetXY($bx,$by);
				$this->SetTextColor(43,36,159);
				$this->Cell(86,5,utf8_decode(ucfirst(($i+1).") ".mb_strtolower($banques[$i]))),0,0,'L');
				if ($count == 2) {
					$count = 1;
					$bx += 40;
					$by = $posty+25+30+5;
				}else {
					$by += 4;
					$count++;
				}
			}
			$this->SetTextColor(0,0,0);
		}

		/*----------------------------------------------------------------
		* LISTING OPERATIONS
		*----------------------------------------------------------------*/
	    function content($data, $rightX, $titre){

	        $postx = 5+$rightX;
	        $posty = 5;

			$this->SetFont('arial', 'B', 9);
			$this->SetDrawColor(0, 0, 0);
	        $this->SetFillColor(0,140,35);
	        $this->SetTextColor(255, 255, 255);
			$this->SetXY($postx,$posty+45+9+6+6+10);
			$this->Cell(140,8,utf8_decode('  REÇU DE PAIEMENT DE '.mb_strtoupper($titre)),0,1,'C', true);

	        $this->SetXY($postx,$posty+45+9+6+6+10+5+10);
			$this->SetFillColor(255,255,255);
	        $this->SetTextColor(0, 0, 0);
			$this->SetFont('arial', 'i', 9);
			$this->SetFillColor(233,233,233);
	        $this->Cell(65,7,utf8_decode('Payé par : '),0,1,'L', true);
	        $this->SetXY($postx+75,$posty+45+9+6+6+10+5+10);
	        $this->Cell(65,7,utf8_decode('Payé A : '),0,1,'L', true);

			$this->SetXY($postx,$posty+45+9+6+6+10+5+10+10);
			$this->SetFillColor(255,255,255);
	        $this->SetTextColor(0, 0, 0);
			$this->SetFont('arial', '', 8);
	        $this->Cell(65,7,utf8_decode(mb_strtoupper($data["paye_par"])),0,1,'L', true);
			$this->SetXY($postx,$posty+45+9+6+6+10+5+10+10+5);
	        $this->Cell(65,7,utf8_decode("MAT : ".mb_strtoupper($data["mat_school"])),0,1,'L', true);

			$this->SetXY($postx+75,$posty+45+9+6+6+10+5+10+10);
			$this->SetFillColor(255,255,255);
	        $this->SetTextColor(0, 0, 0);
			$this->SetFont('arial', '', 8);
	        $this->Cell(65,7,utf8_decode(mb_strtoupper($data["paye_a"])),0,1,'L', true);
			$this->SetXY($postx+75,$posty+45+9+6+6+10+5+10+10+5);
	        $this->Cell(65,7,utf8_decode("MAT : ".mb_strtoupper($data["mat"])),0,1,'L', true);

			$this->SetXY($postx,$posty+45+9+6+6+10+5+10+28);
			$this->SetFillColor(255,255,255);
	        $this->SetTextColor(0, 0, 0);
			$this->SetFont('arial', 'i', 9);
			$this->SetFillColor(233,233,233);
	        $this->Cell(65,7,utf8_decode('Montant en chiffre : '),0,1,'L', true);
	        $this->SetXY($postx+75,$posty+45+9+6+6+10+5+10+28);
	        $this->Cell(65,7,utf8_decode('Montant en lettre : '),0,1,'L', true);

			$this->SetXY($postx,$posty+45+9+6+6+10+5+10+10+28);
			$this->SetFillColor(255,255,255);
	        $this->SetTextColor(0, 0, 0);
			$this->SetFont('arial', '', 8);
	        $this->Cell(65,7,utf8_decode(mb_strtoupper($data["salaire"])),0,1,'L', true);
			
			$this->SetXY($postx+75,$posty+45+9+6+6+10+5+10+10+28);
			$this->SetFillColor(255,255,255);
	        $this->SetTextColor(0, 0, 0);
			$this->SetFont('arial', '', 8);
	        $this->Cell(65,7,utf8_decode(mb_strtolower($data["salaire_lettre"])),0,1,'L', true);
			
			$this->SetXY($postx,$posty+45+9+6+6+10+5+10+28+25);
			$this->SetFillColor(255,255,255);
	        $this->SetTextColor(0, 0, 0);
			$this->SetFont('arial', 'i', 9);
			$this->SetFillColor(233,233,233);
	        $this->Cell(65,7,utf8_decode('Signature de l\'employeur : '),0,1,'L', true);
	        $this->SetXY($postx+75,$posty+45+9+6+6+10+5+10+28+25);
	        $this->Cell(65,7,utf8_decode('Signature de l\'employé : '),0,1,'L', true);

    	}

		/*----------------------------------------------------------------
		* LISTING OPERATIONS
		*----------------------------------------------------------------*/
	    function contentScolarite($data, $rightX, $titre){

	        $postx = 5+$rightX;
	        $posty = 5;

			$this->SetFont('arial', 'B', 9);
			$this->SetDrawColor(0, 0, 0);
	        $this->SetFillColor(0,140,35);
	        $this->SetTextColor(255, 255, 255);
			$this->SetXY($postx,$posty+45+9+6+6+10);
			$this->Cell(140,8,utf8_decode('  REÇU DE PAIEMENT DE '.mb_strtoupper($titre)),0,1,'C', true);

	        $this->SetXY($postx,$posty+45+9+6+6+10+5+10);
			$this->SetFillColor(255,255,255);
	        $this->SetTextColor(0, 0, 0);
			$this->SetFont('arial', 'i', 9);
			$this->SetFillColor(233,233,233);
	        $this->Cell(65,7,utf8_decode('Payé par : '),0,1,'L', true);
	        $this->SetXY($postx+75,$posty+45+9+6+6+10+5+10);
	        $this->Cell(65,7,utf8_decode('Payé A : '),0,1,'L', true);

			$this->SetXY($postx,$posty+45+9+6+6+10+5+10+10);
			$this->SetFillColor(255,255,255);
	        $this->SetTextColor(0, 0, 0);
			$this->SetFont('arial', '', 8);
	        $this->Cell(65,7,utf8_decode(mb_strtoupper($data["paye_par"])),0,1,'L', true);
			$this->SetXY($postx,$posty+45+9+6+6+10+5+10+10+5);
	        $this->Cell(65,7,utf8_decode("MAT : ".mb_strtoupper($data["mat_school"])),0,1,'L', true);

			$this->SetXY($postx+75,$posty+45+9+6+6+10+5+10+10);
			$this->SetFillColor(255,255,255);
	        $this->SetTextColor(0, 0, 0);
			$this->SetFont('arial', '', 8);
	        $this->Cell(65,7,utf8_decode(mb_strtoupper($data["paye_a"])),0,1,'L', true);
			$this->SetXY($postx+75,$posty+45+9+6+6+10+5+10+10+5);
	        $this->Cell(65,7,utf8_decode("MAT : ".mb_strtoupper($data["mat"])),0,1,'L', true);

			$this->SetXY($postx,$posty+45+9+6+6+10+5+10+28);
			$this->SetFillColor(255,255,255);
	        $this->SetTextColor(0, 0, 0);
			$this->SetFont('arial', 'i', 9);
			$this->SetFillColor(233,233,233);
	        $this->Cell(65,7,utf8_decode('Montant en chiffre : '),0,1,'L', true);
	        $this->SetXY($postx+75,$posty+45+9+6+6+10+5+10+28);
	        $this->Cell(65,7,utf8_decode('Montant en lettre : '),0,1,'L', true);

			$this->SetXY($postx,$posty+45+9+6+6+10+5+10+10+28);
			$this->SetFillColor(255,255,255);
	        $this->SetTextColor(0, 0, 0);
			$this->SetFont('arial', '', 8);
	        $this->Cell(65,7,utf8_decode(mb_strtoupper($data["salaire"])),0,1,'L', true);
			
			$this->SetXY($postx+75,$posty+45+9+6+6+10+5+10+10+28);
			$this->SetFillColor(255,255,255);
	        $this->SetTextColor(0, 0, 0);
			$this->SetFont('arial', '', 8);
	        $this->Cell(65,7,utf8_decode(mb_strtolower($data["salaire_lettre"])),0,1,'L', true);
			
			$this->SetXY($postx,$posty+45+9+6+6+10+5+10+28+25);
			$this->SetFillColor(255,255,255);
	        $this->SetTextColor(0, 0, 0);
			$this->SetFont('arial', 'i', 9);
			$this->SetFillColor(233,233,233);
	        $this->Cell(65,7,utf8_decode('Signature du parent : '),0,1,'L', true);
	        $this->SetXY($postx+75,$posty+45+9+6+6+10+5+10+28+25);
	        $this->Cell(65,7,utf8_decode('Signature de la caisse : '),0,1,'L', true);

    	}


		/*----------------------------------------------------------------
		* FOOTER LISTING
		*----------------------------------------------------------------*/
	    function footer_listing($date, $rightX, $page) {

			$this->SetTextColor(0, 0, 0);
			$this->SetXY(10+$rightX,-20);
			$this->Cell(141,10,utf8_decode("_______________________________________________________"),0,0,'C');
			$this->SetXY(10+$rightX,-19);
			$this->Cell(141,10,utf8_decode("________________________________________________________________________________________"),0,0,'C');
	        $this->SetXY(10+$rightX,-15);
			$this->Cell(135,10,utf8_decode($page.'/2' ),0,0,'R');
			$this->SetXY(10+$rightX,-15);
	        $this->SetFont('arial', 'B' ,7);
	        $this->Cell(132,10,utf8_decode(ucfirst(mb_strtolower("REÇU DE PAIEMENT DE SALAIRE"))),0,0,'C');
	        $this->SetFont('arial', 'I' ,7);
	        $this->SetXY(10+$rightX,-40);
	        $this->Cell(135,10,utf8_decode($date ),0,0,'R');

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
            $this->SetFont('arial', 'I', 30);
            
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

	}
	
	
 ?>