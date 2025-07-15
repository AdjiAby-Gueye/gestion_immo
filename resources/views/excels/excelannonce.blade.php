<div>
    <table class="table mb-20">
        <tr class="tr">
            <th class="whitespace-no-wrap">N°</th>
            <th class="whitespace-no-wrap">Titre</th>
            <th class="whitespace-no-wrap">Description</th>
            <th class="whitespace-no-wrap">Immeuble</th>
            <th class="whitespace-no-wrap">Appartement</th>
            <th class="whitespace-no-wrap">Date debut</th>
            <th class="whitespace-no-wrap">Date Fin</th>
        </tr>

        @for ($i = 0; $i < count($data); $i++)>
            <tr class="tr">

                <td class="td">{{ $i+1 }}</td>
                <td class="td">{{ $data[$i]["titre"]}}</td>
                <td class="td">{{ $data[$i]["description"]}}</td>
                <td class="td">{{ $data[$i]["concernes"] == 'immeuble' ? $data[$i]["immeuble"]->nom : "-"  }} {{ $data[$i]["concernes"] == 'immeubles' ? "concerne tout les immeubles" : "-" ; }} {{ $data[$i]["concernes"] == 'marketing' ? "concerne marketing" : "-" ; }}</td>
                <td class="td">{{ $data[$i]["concernes"] == 'immeuble' ? "Concerne tous les appartements de l'immeuble" : "-" }} {{ $data[$i]["concernes"] == 'appartement' ? $data[$i]["appartement"]->nom : "-" ; }} {{ $data[$i]["concernes"] == 'marketing' ? "concerne marketing" : "-" ; }}</td>
                <td class="td">{{ $data[$i]["debut"]}}</td>
                <td class="td">{{ $data[$i]["fin"]}}</td>
            </tr>
            @endfor
    </table>

</div>
