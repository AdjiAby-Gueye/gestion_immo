<?php

namespace App\Helpers;

use NumberFormatter;

class NombreEnLettre
{
    public static function convertir($nombre, $U = null, $D = null)
    {
        // Code pour self::convertir( le nombre en lettres
        // $nombre = round();
        $toLetter = [
            0 => "zéro",
            1 => "un",
            2 => "deux",
            3 => "trois",
            4 => "quatre",
            5 => "cinq",
            6 => "six",
            7 => "sept",
            8 => "huit",
            9 => "neuf",
            10 => "dix",
            11 => "onze",
            12 => "douze",
            13 => "treize",
            14 => "quatorze",
            15 => "quinze",
            16 => "seize",
            17 => "dix-sept",
            18 => "dix-huit",
            19 => "dix-neuf",
            20 => "vingt",
            30 => "trente",
            40 => "quarante",
            50 => "cinquante",
            60 => "soixante",
            70 => "soixante-dix",
            80 => "quatre-vingt",
            90 => "quatre-vingt-dix",
        ];

        global $toLetter;
        $numberToLetter = '';
        $nombre = strtr((string)$nombre, [" " => ""]);
        $nb = floatval($nombre);

        if (strlen($nombre) > 15) return "dépassement de capacité";
        if (!is_numeric($nombre)) return "Nombre non valide";
        if (ceil($nb) != $nb) {
            $nb = explode('.', $nombre);
            return self::convertir($nb[0]) . ($U ? " $U et " : " virgule ") . self::convertir($nb[1]) . ($D ? " $D" : "");
        }

        $n = strlen($nombre);
        switch ($n) {
            case 1:
                $numberToLetter = $toLetter[$nb];
                break;
            case 2:
                if ($nb > 19) {
                    $quotient = floor($nb / 10);
                    $reste = $nb % 10;
                    if ($nb < 71 || ($nb > 79 && $nb < 91)) {
                        if ($reste == 0) $numberToLetter = $toLetter[$quotient * 10];
                        if ($reste == 1) $numberToLetter = $toLetter[$quotient * 10] . "-et-" . $toLetter[$reste];
                        if ($reste > 1) $numberToLetter = $toLetter[$quotient * 10] . "-" . $toLetter[$reste];
                    } else $numberToLetter = $toLetter[($quotient - 1) * 10] . "-" . $toLetter[10 + $reste];
                } else $numberToLetter = $toLetter[$nb];
                break;

            case 3:
                $quotient = floor($nb / 100);
                $reste = $nb % 100;
                if ($quotient == 1 && $reste == 0) $numberToLetter = "cent";
                if ($quotient == 1 && $reste != 0) $numberToLetter = "cent" . " " . self::convertir($reste);
                if ($quotient > 1 && $reste == 0) $numberToLetter = $toLetter[$quotient] . " cents";
                if ($quotient > 1 && $reste != 0) $numberToLetter = $toLetter[$quotient] . " cent " . self::convertir($reste);
                break;
            case 4:
            case 5:
            case 6:
                $quotient = floor($nb / 1000);
                $reste = $nb - $quotient * 1000;
                if ($quotient == 1 && $reste == 0) $numberToLetter = "mille";
                if ($quotient == 1 && $reste != 0) $numberToLetter = "mille" . " " . self::convertir($reste);
                if ($quotient > 1 && $reste == 0) $numberToLetter = self::convertir($quotient) . " mille";
                if ($quotient > 1 && $reste != 0) $numberToLetter = self::convertir($quotient) . " mille " . self::convertir($reste);
                break;
            case 7:
            case 8:
            case 9:
                $quotient = floor($nb / 1000000);
                $reste = $nb % 1000000;
                if ($quotient == 1 && $reste == 0) $numberToLetter = "un million";
                if ($quotient == 1 && $reste != 0) $numberToLetter = "un million" . " " . self::convertir($reste);
                if ($quotient > 1 && $reste == 0) $numberToLetter = self::convertir($quotient) . " millions";
                if ($quotient > 1 && $reste != 0) $numberToLetter = self::convertir($quotient) . " millions " . self::convertir($reste);
                break;
            case 10:
            case 11:
            case 12:
                $quotient = floor($nb / 1000000000);
                $reste = $nb - $quotient * 1000000000;
                if ($quotient == 1 && $reste == 0) $numberToLetter = "un milliard";
                if ($quotient == 1 && $reste != 0) $numberToLetter = "un milliard" . " " . self::convertir($reste);
                if ($quotient > 1 && $reste == 0) $numberToLetter = self::convertir($quotient) . " milliards";
                if ($quotient > 1 && $reste != 0) $numberToLetter = self::convertir($quotient) . " milliards " . self::convertir($reste);
                break;
            case 13:
            case 14:
            case 15:
                $quotient = floor($nb / 1000000000000);
                $reste = $nb - $quotient * 1000000000000;
                if ($quotient == 1 && $reste == 0) $numberToLetter = "un billion";
                if ($quotient == 1 && $reste != 0) $numberToLetter = "un billion" . " " . self::convertir($reste);
                if ($quotient > 1 && $reste == 0) $numberToLetter = self::convertir($quotient) . " billions";
                if ($quotient > 1 && $reste != 0) $numberToLetter = self::convertir($quotient) . " billions " . self::convertir($reste);
                break;
        }
        /*respect de l'accord de quatre-vingt*/
        if (substr($numberToLetter, strlen($numberToLetter) - 12, 12) == "quatre-vingt") $numberToLetter .= "s";

        return $numberToLetter;
    }


    // public static function convertirEnLettres($nombre)
    // {
    //     $unites = ['', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf'];
    //     $dizaines = ['', 'dix', 'vingt', 'trente', 'quarante', 'cinquante', 'soixante', 'soixante-dix', 'quatre-vingts', 'quatre-vingt-dix'];
    //     $centaines = ['', 'cent', 'deux cents', 'trois cents', 'quatre cents', 'cinq cents', 'six cents', 'sept cents', 'huit cents', 'neuf cents'];

    //     if ($nombre == 0) {
    //         return 'zéro';
    //     }

    //     $resultat = '';
    //     if ($nombre >= 1000000) {
    //         $millions = floor($nombre / 1000000);
    //         $resultat .= self::convertirEnLettres($millions) . ' million ';
    //         $nombre %= 1000000;
    //         if ($nombre > 0) {
    //             $resultat .= 'et ';
    //         }
    //     }
    //     if ($nombre >= 1000) {
    //         $milliers = floor($nombre / 1000);
    //         $resultat .= self::convertirEnLettres($milliers) . ' mille ';
    //         $nombre %= 1000;
    //     }

    //     if ($nombre >= 100) {
    //         $centaine = floor($nombre / 100);
    //         $resultat .= $centaines[$centaine] . ' ';
    //         $nombre %= 100;
    //     }

    //     if ($nombre >= 10) {
    //         $dizaine = floor($nombre / 10);
    //         $resultat .= $dizaines[$dizaine] . ' ';
    //         $nombre %= 10;
    //     }

    //     if ($nombre > 0) {
    //         $resultat .= $unites[$nombre] . ' ';
    //     }

    //     return ucwords(trim($resultat));
    // }

    public static function convertirEnLettres($nombre)
    {
        $unites = ['', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf'];
        $dizaines = ['', 'dix', 'vingt', 'trente', 'quarante', 'cinquante', 'soixante', 'soixante-dix', 'quatre-vingts', 'quatre-vingt-dix'];
        $centaines = ['', 'cent', 'deux cents', 'trois cents', 'quatre cents', 'cinq cents', 'six cents', 'sept cents', 'huit cents', 'neuf cents'];

        if ($nombre == 0) {
            return 'zéro';
        }

        $resultat = '';
        if ($nombre >= 1000000) {
            $millions = floor($nombre / 1000000);
            $nombre %= 1000000;

            if ($millions >= 2) {
                $resultat .= self::convertirEnLettres($millions) . ' millions ';
            } elseif ($millions == 1) {
                $resultat .= 'un million ';
            }

            if ($nombre > 0) {
                $resultat .= 'et ';
            } else {
                return $resultat;
            }
        }
        // if ($nombre >= 1000000) {
        //     $millions = floor($nombre / 1000000);
        //     $reste = $nombre % 1000000;

        //     if ($millions > 1) {
        //         $resultat .= self::convertirEnLettres($millions) . ' millions ';
        //     } elseif ($millions == 1) {
        //         $resultat .= 'un million ';
        //     }

        //     if ($reste > 0) {
        //         $resultat .= 'et ';
        //     }
        // }

        if ($nombre >= 1000) {
            $milliers = floor($nombre / 1000);
            $resultat .= self::convertirEnLettres($milliers) . ' mille ';
            $nombre %= 1000;
        }

        if ($nombre >= 100) {
            $centaine = floor($nombre / 100);
            $resultat .= $centaines[$centaine] . ' ';
            $nombre %= 100;
        }

        if ($nombre >= 10) {
            $dizaine = floor($nombre / 10);
            $resultat .= $dizaines[$dizaine] . ' ';
            $nombre %= 10;
        }

        if ($nombre > 0) {
            $resultat .= $unites[$nombre] . ' ';
        }

        return ucwords(trim($resultat));
    }


  static  function chifre_en_lettre($montant, $devise1='', $devise2='')
{
    $valeur_entiere = intval($montant);
    $valeur_decimal = intval(round($montant - intval($montant), 2) * 1000);

    $dix_c = intval($valeur_decimal % 100 / 10);
    $cent_c = intval($valeur_decimal % 1000 / 100);
    $unite[1] = $valeur_entiere % 10;
    $dix[1] = intval($valeur_entiere % 100 / 10);
    $cent[1] = intval($valeur_entiere % 1000 / 100);
    $unite[2] = intval($valeur_entiere % 10000 / 1000);
    $dix[2] = intval($valeur_entiere % 100000 / 10000);
    $cent[2] = intval($valeur_entiere % 1000000 / 100000);
    $unite[3] = intval($valeur_entiere % 10000000 / 1000000);
    $dix[3] = intval($valeur_entiere % 100000000 / 10000000);
    $cent[3] = intval($valeur_entiere % 1000000000 / 100000000);

    $chif=array('', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf', 'dix', 'onze', 'douze', 'treize', 'quatorze', 'quinze', 'seize', 'dix sept', 'dix huit', 'dix neuf');
        $secon_c='';
        $trio_c='';
    for($i=1; $i<=3; $i++){
        $prim[$i]='';
        $secon[$i]='';
        $trio[$i]='';

        if($dix[$i]==0){
            $secon[$i]='';
            $prim[$i]=$chif[$unite[$i]];
        }
        else if($dix[$i]==1){
            $secon[$i]='';
            $prim[$i]=$chif[($unite[$i]+10)];

        }
        else if($dix[$i]==2){
            if($unite[$i]==1){
            $secon[$i]='vingt et';
            $prim[$i]=$chif[$unite[$i]];
            }
            else {
            $secon[$i]='vingt';
            $prim[$i]=$chif[$unite[$i]];
            }
        }
        else if($dix[$i]==3){
            if($unite[$i]==1){
            $secon[$i]='trente et';
            $prim[$i]=$chif[$unite[$i]];
            }
            else {
            $secon[$i]='trente';
            $prim[$i]=$chif[$unite[$i]];
            }
        }
        else if($dix[$i]==4){
            if($unite[$i]==1){
            $secon[$i]='quarante et';
            $prim[$i]=$chif[$unite[$i]];
            }
            else {
            $secon[$i]='quarante';
            $prim[$i]=$chif[$unite[$i]];
            }
        }
        else if($dix[$i]==5){
            if($unite[$i]==1){
            $secon[$i]='cinquante et';
            $prim[$i]=$chif[$unite[$i]];
            }
            else {
            $secon[$i]='cinquante';
            $prim[$i]=$chif[$unite[$i]];
            }
        }
        else if($dix[$i]==6){
            if($unite[$i]==1){
            $secon[$i]='soixante et';
            $prim[$i]=$chif[$unite[$i]];
            }
            else {
            $secon[$i]='soixante';
            $prim[$i]=$chif[$unite[$i]];
            }
        }
        else if($dix[$i]==7){
            if($unite[$i]==1){
            $secon[$i]='soixante et';
            $prim[$i]=$chif[$unite[$i]+10];
            }
            else {
            $secon[$i]='soixante';
            $prim[$i]=$chif[$unite[$i]+10];
            }
        }
        else if($dix[$i]==8){
            if($unite[$i]==1){
            $secon[$i]='quatre-vingts et';
            $prim[$i]=$chif[$unite[$i]];
            }
            else {
            $secon[$i]='quatre-vingt';
            $prim[$i]=$chif[$unite[$i]];
            }
        }
        else if($dix[$i]==9){
            if($unite[$i]==1){
            $secon[$i]='quatre-vingts et';
            $prim[$i]=$chif[$unite[$i]+10];
            }
            else {
            $secon[$i]='quatre-vingts';
            $prim[$i]=$chif[$unite[$i]+10];
            }
        }
        if($cent[$i]==1) $trio[$i]='cent';
        else if($cent[$i]!=0 || $cent[$i]!='') $trio[$i]=$chif[$cent[$i]] .' cents';


    }


    $chif2 = array('', 'dix', 'vingt', 'trente', 'quarante', 'cinquante', 'soixante', 'soixante-dix', 'quatre-vingts', 'quatre-vingts dix');

    // Gestion de "million", "mille" et "un"
    if ($valeur_entiere == 1000000) {
        echo 'un million ';
    } elseif ($valeur_entiere > 1000000) {
        echo $trio[3] . '  ' . $secon[3] . ' ' . $prim[3] . ' millions ';
    }

    if ($valeur_entiere == 1000) {
        echo 'mille ';
    } elseif ($valeur_entiere > 1000) {
        echo $trio[2] . ' ' . $secon[2] . ' ' . $prim[2] . ' mille ';
    }

    if ($valeur_entiere == 1) {
        echo 'un ';
    } elseif ($valeur_entiere > 1) {
        echo $trio[1] . ' ' . $secon[1] . ' ' . $prim[1];
    }

    echo ' ' . $devise1 . ' ';

    if (($cent_c == '0' || $cent_c == '') && ($dix_c == '0' || $dix_c == '')) {
        // echo 'et zéro ' . $devise2;
    } else {
        echo $trio_c . ' ' . $secon_c . ' ' . $devise2;
    }


}

 public static function NumberToWords($num) {
    $num = intval($num);
    $num  = (string) ((int) $num);

    if ((int) ($num) && ctype_digit($num)) {
        $words  = array();

        $num    = str_replace(array(',', ' '), '', trim($num));

        $list1  = array(
            '', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept',
            'huit', 'neuf', 'dix', 'onze', 'douze', 'treize', 'quatorze',
            'quinze', 'seize', 'dix-sept', 'dix-huit', 'dix-neuf'
        );

        $list2  = array(
            '', 'dix', 'vingt', 'trente', 'quarante', 'cinquante', 'soixante',
            'soixante-dix', 'quatre-vingt', 'quatre-vingt-dix'
        );

        $list3  = array(
            '', 'mille', 'million', 'milliard', 'billion',
            'quadrillion', 'quintillion', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'décillion', 'undécillion',
            'duodécillion', 'trédecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion',
            'octodecillion', 'novemdecillion', 'vigintillion'
        );

        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num    = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);

        foreach ($num_levels as $num_part) {
            $levels--;
            $hundreds   = (int) ($num_part / 100);
            $hundreds   = ($hundreds ? ' ' . $list1[$hundreds] . ' Cent' . ($hundreds == 1 ? '' : 's') . ' ' : '');
            $tens       = (int) ($num_part % 100);
            $singles    = '';

            if ($tens < 20) {
                $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '');
            } else {
                $tens = (int) ($tens / 10);
                $tens = ' ' . $list2[$tens] . ' ';
                $singles = (int) ($num_part % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . (($levels && (int) ($num_part)) ? ' ' . $list3[$levels] . ' ' : '');
             // Handle pluralization for "million"

        }
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }

        $words  = implode(', ', $words);

        $words  = trim(str_replace(' ,', ',', ucwords($words)), ', ');
        if ($commas) {
            $words  = str_replace(',', ' et', $words);
        }
    } else if (!((int) $num)) {
        $words = 'Zéro';
    } else {
        $words = '';
    }

    return $words;
 }

 public static function CustomNumberToWords($number)
 {

     // Create a NumberFormatter instance for the desired locale (en_US in this example)
     $formatter = new NumberFormatter('fr_FR', NumberFormatter::SPELLOUT);

     // Format the number into words
     $words = $formatter->format($number);

     return $words;
 }
 
}
