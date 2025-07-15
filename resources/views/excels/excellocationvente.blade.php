<style>
     .uppercase {
            text-transform: uppercase
        }
</style>

<div>
    <table class="table mb-20">
        <tr class="tr" style="background-color:blue">
            <th class="whitespace-no-wrap uppercase " style="text-transform: uppercase;">Num</th>
            <th class="whitespace-no-wrap uppercase" style="text-transform: uppercase;">Ilot</th>
            <th class="whitespace-no-wrap uppercase" style="text-transform: uppercase;">Lot</th>

            <th class="whitespace-no-wrap uppercase" style="text-transform: uppercase;" colspan="5">Reservataires</th>
            <th class="whitespace-no-wrap uppercase" style="text-transform: uppercase;"colspan="3">Apport initial</th>
            <th class="whitespace-no-wrap uppercase" style="text-transform: uppercase;" colspan="3">Apport ponctuel</th>

            <th class="whitespace-no-wrap uppercase" style="text-transform: uppercase;" colspan="3">Mensualite</th>
            <th class="whitespace-no-wrap uppercase"  style="text-transform: uppercase;"colspan="4">Mensualite Suite maj Apport </th>
            <th class="whitespace-no-wrap uppercase" style="text-transform: uppercase;" colspan="4">loyer perçu suite majoration</th>

            <th class="whitespace-no-wrap uppercase" style="text-transform: uppercase;" colspan="3">Nbre de loyers payés</th>
            <th class="whitespace-no-wrap uppercase" style="text-transform: uppercase;" colspan="3">TOTAL LOYERS PAYES</th>
            <th class="whitespace-no-wrap uppercase" style="text-transform: uppercase;" colspan="3">TOTAL VERSEMENT</th>
            <th class="whitespace-no-wrap uppercase"  style="text-transform: uppercase;"colspan="5">OBSERVATIONS</th>

        </tr>

        @for ($i = 0; $i < count($data); $i++)
            <tr class="tr">
                <td class="td">{{ $i+1 }}</td>
                <td class="td">{{ $data[$i]["appartement"]['ilot']['numero']}}</td>
                <td class="td">{{ $data[$i]["appartement"]['lot']}}</td>

                <td class="td" colspan="5">{{ $data[$i]["locataire"]['nom'] }} {{ $data[$i]["locataire"]['prenom'] }}</td>
                <td class="td" colspan="3">{{ $data[$i]["apportinitial_format"]}}FCFA </td>
                <td class="td" colspan="3">{{ $data[$i]["apportiponctuel_format"]}} FCFA</td>

                <td class="td" colspan="3">{{ $data[$i]["montantloyerformat"]}} FCFA</td>
                <td class="td" colspan="4"> FCFA</td> 
                <td class="td" colspan="4"> FCFA</td>

                <td class="td" colspan="3">{{ $data[$i]["nbr_loyer_payes_ridwan"]}}</td>
                <td class="td" colspan="3">{{ $data[$i]["total_loyer_verser_ridwan"]}} FCFA</td>
                <td class="td" colspan="3">{{ $data[$i]["ridwan_montant_verse"]}} FCFA</td>
                <td class="td" colspan="5">---</td>

            </tr>
        @endfor
        <tr class="tr">
            <td class="td" colspan="8" style="text-transform:uppercase;"><b>TOTAL</b></td>
            <td class="td" colspan="3" ><b>{{$total_apport_initial}} FCFA </b></td>
            <td class="td" colspan="3" ><b>{{$total_apport_ponctuel}} FCFA</b> </td>
            <td class="td" colspan="3"></td>
            <td class="td" colspan="4"></td> 
            <td class="td" colspan="4"></td>

            <td class="td" colspan="3"></td>
            <td class="td" colspan="3"></td>
            <td class="td" colspan="3"></td>
            <td class="td" colspan="5"></td>
        </tr>

    </table>
</div>
