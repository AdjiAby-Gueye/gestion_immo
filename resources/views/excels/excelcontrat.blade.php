<div>
    <table class="table mb-20">
        <tr class="tr">
            <th class="whitespace-no-wrap">N</th>
            <th class="whitespace-no-wrap">Descriptif</th>
            <th class="whitespace-no-wrap">Locataire</th>
            <th class="whitespace-no-wrap">Adresse appartement</th>
            <th class="whitespace-no-wrap">Montant loyer</th>
        </tr>

        @for ($i = 0; $i < count($data); $i++)
            <tr class="tr">

                <td class="td">{{ $i+1 }}</td>
                <td class="td">{{ $data[$i]["descriptif"]}}</td>
                <td class="td">{{ $data[$i]["locataire"]->nom }} {{ $data[$i]["locataire"]->prenom }}</td>
                <td class="td">{{ $data[$i]["appartement"]['immeuble']->adresse}}</td>
                <td class="td">{{ $data[$i]["montantloyer"]}}</td>
            </tr>
            @endfor
    </table>
</div>
