<div>
    <table class="table mb-20">
        <tr class="tr">
            <th class="whitespace-no-wrap">N</th>
            <th class="whitespace-no-wrap">Prestataire</th>
            <th class="whitespace-no-wrap">Categorie prestation</th>
            <th class="whitespace-no-wrap">Demarrage contrat</th>
            <th class="whitespace-no-wrap">Renouvellement contrat</th>
            <th class="whitespace-no-wrap">Montant</th>
        </tr>

        @for ($i = 0; $i < count($data); $i++)>
            <tr class="tr">

                <td class="td">{{ $i+1 }}</td>
                <td class="td">{{ $data[$i]["prestataire"]->nom}}</td>
                <td class="td">{{ $data[$i]["categorieprestation"]->description}}</td>
                <td class="td">{{ $data[$i]["datedemarragecontrat"] }}</td>
                <td class="td">{{ $data[$i]["daterenouvellementcontrat"]}}</td>
                <td class="td">{{ $data[$i]["montant"]}}</td>
            </tr>
            @endfor
    </table>
</div>