<?php

namespace App\Controllers;
set_time_limit(480);
/*
	v1.0 Initial version
	v1.1 Added bullet support (Takam oumbe <angeltakam76@gmail.com>)
	*/

class FPDF_CERT extends FPDF
{

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

    // Public Functions

    /*----------------------------------------------------------------
    * ENTETE FICHE CERTIFICAT
    *
    *----------------------------------------------------------------*/
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
        $this->Cell(86,5,utf8_decode($school),0,0,'C');
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
        $this->Cell(86,5,utf8_decode($school),0,0,'C');
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

    public function body_contrat_travail($data, $year, $ecole, $directeur, $photo)
    {
		$postx = 12;
	    $posty = 25;

		/*-------------------*/ // entete
		$this->SetFont('times', 'B', 9);
		$this->SetDrawColor(71, 151, 45);
		$this->SetFillColor(71, 151, 45);
		$this->SetTextColor(255, 255, 255);
		$this->SetXY($postx+45,$posty+45);
		$this->Cell(100,6,utf8_decode("CONTRAT DE TRAVAIL"),1,1,'C', true);
		$this->SetTextColor(0, 0, 0);	
		$this->SetFont('times', 'B', 9);
        $this->SetXY($postx+45,$posty+45+10);
        $this->SetDrawColor(255, 255, 255);
		$this->Cell(100,6,utf8_decode("N°: ... .. 0".$data['teacher_id']." .. ... .. ..."),1,1,'C', false);

		$this->SetXY($postx+14+20,$posty+45+9);
        
        // Photo
		$this->Image(getenv('FILE_PHOTO_TEACHER').'/'.$photo, $postx,45+9+12,25,20);

		// QrCode
		$this->Image('images.png', $postx+160,45+9+12,25,20);

        $this->setY($posty+45+9+20);

        // Feuille de style
        $this->SetStyle("p", "times", "N", 12, "0,0,0", 15);
        // $this->SetStyle("h1","times","N",18,"102,0,102",0);
        // $this->SetStyle("a","times","BU",9,"0,0,255");
        // $this->SetStyle("pers","times","I",0,"255,0,0");
        $this->SetStyle("place", "arial", "U", 0, "153,0,0");
        $this->SetStyle("vb", "times", "B", 0, "0,0,0");

        // Texte
        $txt = utf8_decode(" 
			<p>	Je sousigné <vb>" . mb_strtoupper($data['name'].' '.$data['surname']) . "</vb>, M'engage comme ... .. <place><vb>" . mb_strtoupper($data['poste']) . "</vb></place> .. au
				.. <vb> " . mb_strtoupper($ecole) . " </vb> Pour le compte de l'année scolaire <vb>" .$year."</vb>.
			</p>
		");

        $this->SetLineWidth(0.1);
        $this->SetFillColor(255, 255, 204);
        $this->SetDrawColor(102, 0, 102);
        $this->WriteTag(0, 10, $txt, 0, "J", 0, 7);

        $this->SetXY($postx+4,$posty+45+10+10+40);
        $this->Cell(100, 10, utf8_decode('Le salaire mensuel convenu s\'élève à ... .. ... .. ... ..'.$data['salaire'].' Fcfa'), '0', '1', "L", false);
        $this->SetXY($postx+4,$posty+45+10+10+40+10);
        $this->Cell(100, 10, utf8_decode('Durée ... .. ... .. mois'), '0', '1', "L", false);

        $this->SetXY($postx-2,$posty+45+10+10+40+10+10);
        $txt = utf8_decode(" 
			<p>	
            Je m'engage par conséquent à respecter scrupuleusement les clauses du règlement intérieur faute de quoi je m'expose aux sanctions prévues à cet effet.
			</p>
		");
        $this->WriteTag(0, 10, $txt, 0, "J", 0, 7);
        $this->SetXY($postx+4,$posty+45+10+10+40+10+10+20+10);
        $this->Cell(100, 10, utf8_decode('Fait ce jour pour valoir ce que de droit.'), '0', '1', "L", false);

        $this->setY(210);
        $this->setTextColor(0, 0, 0);
        $this->setX(139);
        $this->Cell(60, 10, utf8_decode('Fait a Bafoussam le ' . date("d") . ' ' . date("M") . ' ' . date("Y")), '0', '1', "R", false);

        // // $this->Image('Documents/logoRelever/cachet.png', 110, 223, 35);

        $this->setY(225);
        $this->setTextColor(0, 0, 0);
        $this->setX(139);
        $this->Cell(60, 10, utf8_decode('Le Fondateur'), '0', '1', "R", false);

    }

    /*----------------------------------------------------------------
    * PRINT PARAGRAPHE CERTIFICAT
    *
    *----------------------------------------------------------------*/

    public function body_certifica($data, $year, $ecole, $directeur)
		{
			$postx = 12;
			$posty = 25;

			/*-------------------*/ // entete
			$this->SetFont('times', 'B', 9);
			$this->SetDrawColor(71, 151, 45);
			$this->SetFillColor(71, 151, 45);
			$this->SetTextColor(255, 255, 255);
			$this->SetXY($postx+45,$posty+45);
			$this->Cell(100,6,utf8_decode("CERTIFICAT DE SCOLARITÉ "),1,1,'C', true);
			$this->SetTextColor(0, 0, 0);	
			$this->SetFont('times', 'B', 9);
			$this->SetXY($postx+14+20,$posty+45+9);
			
			// QrCode
			$this->Image('images.png', $postx+160,45+9+12,25,20);

			$this->setY($posty+45+9+20);

			// Feuille de style
			$this->SetStyle("p", "times", "N", 12, "0,0,0", 15);
			// $this->SetStyle("h1","times","N",18,"102,0,102",0);
			// $this->SetStyle("a","times","BU",9,"0,0,255");
			// $this->SetStyle("pers","times","I",0,"255,0,0");
			$this->SetStyle("place", "arial", "U", 0, "153,0,0");
			$this->SetStyle("vb", "times", "B", 0, "0,0,0");

			// Texte
			$txt = utf8_decode(" 
				<p>	Je sousigné " . mb_strtoupper($directeur) . ", en qualité de responsable de l'établissement, certifie que l'élève ... .. <place><vb>" . mb_strtoupper($data['nom']) . "</vb></place> .. né le
					.. <vb> " . mb_strtoupper($data['dateNaiss']) . " </vb> .. à .. <vb>" . mb_strtoupper($data['lieuNaiss']) . "</vb> .. De ..
					<vb>" . mb_strtoupper($data['pere']) . "</vb>,
					 est régulièrement est inscrit(e) en tant qu'élève régulier(e) dans 
					 notre établissement ".mb_strtoupper($ecole) ." pour l'année scolaire <vb>
					 " . $data['annee']. "</vb> en classe de <vb>" . mb_strtoupper($data['classe']) . "</vb> section <vb>" . mb_strtoupper($data['section']) . "</vb>, <vb>" . mb_strtoupper($data['cycle']) . "</vb> sous le matricule <vb>" . mb_strtoupper($data['matricule']) . "</vb>
				.</p>
				<p>
					En foi de quoi nous lui délivrons ce certificat de scolarité pour servir et valoir ce que de droit.
				</p>
			");

			$this->SetLineWidth(0.1);
			$this->SetFillColor(255, 255, 204);
			$this->SetDrawColor(102, 0, 102);
			$this->WriteTag(0, 10, $txt, 0, "J", 0, 7);

			$this->setY(210);
			$this->setTextColor(0, 0, 0);
			$this->setX(139);
			$this->Cell(60, 10, utf8_decode('Fait a Bafoussam le ' . date("d") . ' ' . date("M") . ' ' . date("Y")), '0', '1', "R", false);

			// // $this->Image('Documents/logoRelever/cachet.png', 110, 223, 35);

			$this->setY(225);
			$this->setTextColor(0, 0, 0);
			$this->setX(139);
			$this->Cell(60, 10, utf8_decode('Le Directeur'), '0', '1', "R", false);

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
    

    function WriteTag($w, $h, $txt, $border = 0, $align = "J", $fill = false, $padding = 0)
    {
        $this->wLine = $w;
        $this->hLine = $h;
        $this->Text = trim($txt);
        $this->Text = preg_replace("/\n|\r|\t/", "", $this->Text);
        $this->border = $border;
        $this->align = $align;
        $this->fill = $fill;
        $this->Padding = $padding;

        $this->Xini = $this->GetX();
        $this->href = "";
        $this->PileStyle = array();
        $this->TagHref = array();
        $this->LastLine = false;
        $this->NextLineBegin = array();

        $this->SetSpace();
        $this->Padding();
        $this->LineLength();
        $this->BorderTop();

        while ($this->Text != "") {
            $this->MakeLine();
            $this->PrintLine();
        }

        $this->BorderBottom();
    }


    function SetStyle($tag, $family, $style, $size, $color, $indent = -1, $bullet = '')
    {
        $tag = trim($tag);
        $this->TagStyle[$tag]['family'] = trim($family);
        $this->TagStyle[$tag]['style'] = trim($style);
        $this->TagStyle[$tag]['size'] = trim($size);
        $this->TagStyle[$tag]['color'] = trim($color);
        $this->TagStyle[$tag]['indent'] = $indent;
        $this->TagStyle[$tag]['bullet'] = $bullet;
    }



    // Private Functions

    function SetSpace() // Minimal space between words
    {
        $tag = $this->Parser($this->Text);
        $this->FindStyle($tag[2], 0);
        $this->DoStyle(0);
        $this->Space = $this->GetStringWidth(" ");
    }


    function Padding()
    {
        if (preg_match("/^.+,/", $this->Padding)) {
            $tab = explode(",", $this->Padding);
            $this->lPadding = $tab[0];
            $this->tPadding = $tab[1];
            if (isset($tab[2]))
                $this->bPadding = $tab[2];
            else
                $this->bPadding = $this->tPadding;
            if (isset($tab[3]))
                $this->rPadding = $tab[3];
            else
                $this->rPadding = $this->lPadding;
        } else {
            $this->lPadding = $this->Padding;
            $this->tPadding = $this->Padding;
            $this->bPadding = $this->Padding;
            $this->rPadding = $this->Padding;
        }
        if ($this->tPadding < $this->LineWidth)
            $this->tPadding = $this->LineWidth;
    }


    function LineLength()
    {
        if ($this->wLine == 0)
            $this->wLine = $this->w - $this->Xini - $this->rMargin;

        $this->wTextLine = $this->wLine - $this->lPadding - $this->rPadding;
    }


    function BorderTop()
    {
        $border = 0;
        if ($this->border == 1)
            $border = "TLR";
        $this->Cell($this->wLine, $this->tPadding, "", $border, 0, 'C', $this->fill);
        $y = $this->GetY() + $this->tPadding;
        $this->SetXY($this->Xini, $y);
    }


    function BorderBottom()
    {
        $border = 0;
        if ($this->border == 1)
            $border = "BLR";
        $this->Cell($this->wLine, $this->bPadding, "", $border, 0, 'C', $this->fill);
    }


    function DoStyle($ind) // Applies a style
    {
        if (!isset($this->TagStyle[$ind]))
            return;

        $this->SetFont(
            $this->TagStyle[$ind]['family'],
            $this->TagStyle[$ind]['style'],
            $this->TagStyle[$ind]['size']
        );

        $tab = explode(",", $this->TagStyle[$ind]['color']);
        if (count($tab) == 1)
            $this->SetTextColor($tab[0]);
        else
            $this->SetTextColor($tab[0], $tab[1], $tab[2]);
    }


    function FindStyle($tag, $ind) // Inheritance from parent elements
    {
        $tag = trim($tag);

        // Family
        if ($this->TagStyle[$tag]['family'] != "")
            $family = $this->TagStyle[$tag]['family'];
        else {
            foreach ($this->PileStyle as $val) {
                $val = trim($val);
                if ($this->TagStyle[$val]['family'] != "") {
                    $family = $this->TagStyle[$val]['family'];
                    break;
                }
            }
        }

        // Style
        $style = "";
        $style1 = strtoupper($this->TagStyle[$tag]['style']);
        if ($style1 != "N") {
            $bold = false;
            $italic = false;
            $underline = false;
            foreach ($this->PileStyle as $val) {
                $val = trim($val);
                $style1 = strtoupper($this->TagStyle[$val]['style']);
                if ($style1 == "N")
                    break;
                else {
                    if (strpos($style1, "B") !== false)
                        $bold = true;
                    if (strpos($style1, "I") !== false)
                        $italic = true;
                    if (strpos($style1, "U") !== false)
                        $underline = true;
                }
            }
            if ($bold)
                $style .= "B";
            if ($italic)
                $style .= "I";
            if ($underline)
                $style .= "U";
        }

        // Size
        if ($this->TagStyle[$tag]['size'] != 0)
            $size = $this->TagStyle[$tag]['size'];
        else {
            foreach ($this->PileStyle as $val) {
                $val = trim($val);
                if ($this->TagStyle[$val]['size'] != 0) {
                    $size = $this->TagStyle[$val]['size'];
                    break;
                }
            }
        }

        // Color
        if ($this->TagStyle[$tag]['color'] != "")
            $color = $this->TagStyle[$tag]['color'];
        else {
            foreach ($this->PileStyle as $val) {
                $val = trim($val);
                if ($this->TagStyle[$val]['color'] != "") {
                    $color = $this->TagStyle[$val]['color'];
                    break;
                }
            }
        }

        // Result
        $this->TagStyle[$ind]['family'] = $family;
        $this->TagStyle[$ind]['style'] = $style;
        $this->TagStyle[$ind]['size'] = $size;
        $this->TagStyle[$ind]['color'] = $color;
        $this->TagStyle[$ind]['indent'] = $this->TagStyle[$tag]['indent'];
    }


    function Parser($text)
    {
        $tab = array();
        // Closing tag
        if (preg_match("|^(</([^>]+)>)|", $text, $regs)) {
            $tab[1] = "c";
            $tab[2] = trim($regs[2]);
        }
        // Opening tag
        else if (preg_match("|^(<([^>]+)>)|", $text, $regs)) {
            $regs[2] = preg_replace("/^a/", "a ", $regs[2]);
            $tab[1] = "o";
            $tab[2] = trim($regs[2]);

            // Presence of attributes
            if (preg_match("/(.+) (.+)='(.+)'/", $regs[2])) {
                $tab1 = preg_split("/ +/", $regs[2]);
                $tab[2] = trim($tab1[0]);
                foreach ($tab1 as $i => $couple) {
                    if ($i > 0) {
                        $tab2 = explode("=", $couple);
                        $tab2[0] = trim($tab2[0]);
                        $tab2[1] = trim($tab2[1]);
                        $end = strlen($tab2[1]) - 2;
                        $tab[$tab2[0]] = substr($tab2[1], 1, $end);
                    }
                }
            }
        }
        // Space
        else if (preg_match("/^( )/", $text, $regs)) {
            $tab[1] = "s";
            $tab[2] = ' ';
        }
        // Text
        else if (preg_match("/^([^< ]+)/", $text, $regs)) {
            $tab[1] = "t";
            $tab[2] = trim($regs[1]);
        }

        $begin = strlen($regs[1]);
        $end = strlen($text);
        $text = substr($text, $begin, $end);
        $tab[0] = $text;

        return $tab;
    }


    function MakeLine()
    {
        $this->Text .= " ";
        $this->LineLength = array();
        $this->TagHref = array();
        $Length = 0;
        $this->nbSpace = 0;

        $i = $this->BeginLine();
        $this->TagName = array();

        if ($i == 0) {
            $Length = $this->StringLength[0];
            $this->TagName[0] = 1;
            $this->TagHref[0] = $this->href;
        }

        while ($Length < $this->wTextLine) {
            $tab = $this->Parser($this->Text);
            $this->Text = $tab[0];
            if ($this->Text == "") {
                $this->LastLine = true;
                break;
            }

            if ($tab[1] == "o") {
                array_unshift($this->PileStyle, $tab[2]);
                $this->FindStyle($this->PileStyle[0], $i + 1);

                $this->DoStyle($i + 1);
                $this->TagName[$i + 1] = 1;
                if ($this->TagStyle[$tab[2]]['indent'] != -1) {
                    $Length += $this->TagStyle[$tab[2]]['indent'];
                    $this->Indent = $this->TagStyle[$tab[2]]['indent'];
                    $this->Bullet = $this->TagStyle[$tab[2]]['bullet'];
                }
                if ($tab[2] == "a")
                    $this->href = $tab['href'];
            }

            if ($tab[1] == "c") {
                array_shift($this->PileStyle);
                if (isset($this->PileStyle[0])) {
                    $this->FindStyle($this->PileStyle[0], $i + 1);
                    $this->DoStyle($i + 1);
                }
                $this->TagName[$i + 1] = 1;
                if ($this->TagStyle[$tab[2]]['indent'] != -1) {
                    $this->LastLine = true;
                    $this->Text = trim($this->Text);
                    break;
                }
                if ($tab[2] == "a")
                    $this->href = "";
            }

            if ($tab[1] == "s") {
                $i++;
                $Length += $this->Space;
                $this->Line2Print[$i] = "";
                if ($this->href != "")
                    $this->TagHref[$i] = $this->href;
            }

            if ($tab[1] == "t") {
                $i++;
                $this->StringLength[$i] = $this->GetStringWidth($tab[2]);
                $Length += $this->StringLength[$i];
                $this->LineLength[$i] = $Length;
                $this->Line2Print[$i] = $tab[2];
                if ($this->href != "")
                    $this->TagHref[$i] = $this->href;
            }
        }

        trim($this->Text);
        if ($Length > $this->wTextLine || $this->LastLine == true)
            $this->EndLine();
    }


    function BeginLine()
    {
        $this->Line2Print = array();
        $this->StringLength = array();

        if (isset($this->PileStyle[0])) {
            $this->FindStyle($this->PileStyle[0], 0);
            $this->DoStyle(0);
        }

        if (count($this->NextLineBegin) > 0) {
            $this->Line2Print[0] = $this->NextLineBegin['text'];
            $this->StringLength[0] = $this->NextLineBegin['length'];
            $this->NextLineBegin = array();
            $i = 0;
        } else {
            preg_match("/^(( *(<([^>]+)>)* *)*)(.*)/", $this->Text, $regs);
            $regs[1] = str_replace(" ", "", $regs[1]);
            $this->Text = $regs[1] . $regs[5];
            $i = -1;
        }

        return $i;
    }


    function EndLine()
    {
        if (end($this->Line2Print) != "" && $this->LastLine == false) {
            $this->NextLineBegin['text'] = array_pop($this->Line2Print);
            $this->NextLineBegin['length'] = end($this->StringLength);
            array_pop($this->LineLength);
        }

        while (end($this->Line2Print) === "")
            array_pop($this->Line2Print);

        $this->Delta = $this->wTextLine - end($this->LineLength);

        $this->nbSpace = 0;
        for ($i = 0; $i < count($this->Line2Print); $i++) {
            if ($this->Line2Print[$i] == "")
                $this->nbSpace++;
        }
    }


    function PrintLine()
    {
        $border = 0;
        if ($this->border == 1)
            $border = "LR";
        $this->Cell($this->wLine, $this->hLine, "", $border, 0, 'C', $this->fill);
        $y = $this->GetY();
        $this->SetXY($this->Xini + $this->lPadding, $y);

        if ($this->Indent > 0) {
            if ($this->Bullet != '')
                $this->SetTextColor(0);
            $this->Cell($this->Indent, $this->hLine, $this->Bullet);
            $this->Indent = -1;
            $this->Bullet = '';
        }

        $space = $this->LineAlign();
        $this->DoStyle(0);
        for ($i = 0; $i < count($this->Line2Print); $i++) {
            if (isset($this->TagName[$i]))
                $this->DoStyle($i);
            if (isset($this->TagHref[$i]))
                $href = $this->TagHref[$i];
            else
                $href = '';
            if ($this->Line2Print[$i] == "")
                $this->Cell($space, $this->hLine, "         ", 0, 0, 'C', false, $href);
            else
                $this->Cell($this->StringLength[$i], $this->hLine, $this->Line2Print[$i], 0, 0, 'C', false, $href);
        }

        $this->LineBreak();
        if ($this->LastLine && $this->Text != "")
            $this->EndParagraph();
        $this->LastLine = false;
    }


    function LineAlign()
    {
        $space = $this->Space;
        if ($this->align == "J") {
            if ($this->nbSpace != 0)
                $space = $this->Space + ($this->Delta / $this->nbSpace);
            if ($this->LastLine)
                $space = $this->Space;
        }

        if ($this->align == "R")
            $this->Cell($this->Delta, $this->hLine);

        if ($this->align == "C")
            $this->Cell($this->Delta / 2, $this->hLine);

        return $space;
    }


    function LineBreak()
    {
        $x = $this->Xini;
        $y = $this->GetY() + $this->hLine;
        $this->SetXY($x, $y);
    }


    function EndParagraph()
    {
        $border = 0;
        if ($this->border == 1)
            $border = "LR";
        $this->Cell($this->wLine, $this->hLine / 2, "", $border, 0, 'C', $this->fill);
        $x = $this->Xini;
        $y = $this->GetY() + $this->hLine / 2;
        $this->SetXY($x, $y);
    }
}
