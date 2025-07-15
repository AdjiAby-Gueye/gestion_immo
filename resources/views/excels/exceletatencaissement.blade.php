<div>
    <table class="table mb-20">
        <tr class="tr">
            <th class="whitespace-no-wrap">Reservataire</th>
            <th class="whitespace-no-wrap">Lot</th>
            <th class="whitespace-no-wrap">Ilot</th>
            <th class="whitespace-no-wrap">Part amortissement</th>
            <th class="whitespace-no-wrap">Frais locatifs</th>
            <th class="whitespace-no-wrap">Frais de gestion</th>
            <th class="whitespace-no-wrap">Loyer</th>
        </tr>

        @for ($i = 0; $i < count($data); $i++)>
            <tr class="tr">

                <td class="td">{{ $data[$i]["locataire"]["prenom"] }}</td>
                <td class="td">{{ $data[$i]["appartement"]["lot"]}}</td>
                <td class="td">{{ $data[$i]["appartement"]["ilot"]["numero"]}}</td>
                <td class="td">{{ $data[$i]["etatencaissementdetail"]["totalAmortissement"]}}</td>
                <td class="td">{{ $data[$i]["etatencaissementdetail"]["totalFraisgestion"]}}</td>
                <td class="td">{{ $data[$i]["etatencaissementdetail"]["totalFraislocatif"]}}</td>
                <td class="td">{{ $data[$i]["etatencaissementdetail"]["total"]}}</td>
            </tr>
        @endfor
    </table>

</div>
