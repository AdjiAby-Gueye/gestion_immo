<div>
    <table class="table mb-20">
        <tr class="tr">
            <th class="whitespace-no-wrap">N</th>
            <th class="whitespace-no-wrap">Descriptif</th>
            <th class="whitespace-no-wrap">Immeuble</th>
            <th class="whitespace-no-wrap">Date</th>
            <th class="whitespace-no-wrap">Etat</th>
        </tr>

        @for ($i = 0; $i < count($data); $i++)>
            <tr class="tr">

                <td class="td">{{ $i+1 }}</td>
                <td class="td">{{ $data[$i]["descriptif"]}}</td>
                <td class="td">{{ $data[$i]["demandeintervention"]["immeuble"]->nom}}</td>
                <td class="td">{{ $data[$i]["dateintervention"]}}</td>
                <td class="td">{{ $data[$i]["etat"]}}</td>

            </tr>
            @endfor
    </table>
</div>