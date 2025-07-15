<div>
    <table class="table mb-20">
        <tr class="tr">
            <th class="whitespace-no-wrap text-center">N°</th>
            <th class="whitespace-no-wrap text-center">Locataire</th>
            <th class="whitespace-no-wrap text-center">Periode</th>
            <th class="whitespace-no-wrap text-center">Date paiement</th>
            <th class="whitespace-no-wrap text-center">Montant</th>

        </tr>

        @for ($i = 0; $i < count($data); $i++) <tr class="tr">
            <td class="td">{{ $i+1}} </td>
            <td class="td">{{ $data[$i]["contrat"]['locataire']->nom}} {{ $data[$i]["contrat"]['locataire']->prenom}}</td>
            <td class="td">{{ $data[$i]["periode"]}} </td>
            <td class="td">{{ $data[$i]["datepaiement"]}} </td>
            <td class="td">{{ $data[$i]["montantfacture"]}} </td>
            </tr>
        @endfor
    </table>
</div>