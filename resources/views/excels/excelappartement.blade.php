<div>
    <table class="table mb-20">
        <tr class="tr">
            <th class="whitespace-no-wrap text-center">Designation</th>
            <th class="whitespace-no-wrap text-center">Immeuble</th>
            <th class="whitespace-no-wrap text-center">Niveau</th>
            <th class="whitespace-no-wrap text-center">Type</th>
            <th class="whitespace-no-wrap text-center">Proprietaire</th>
            <th class="whitespace-no-wrap text-center">Etat</th>
            <th class="whitespace-no-wrap text-center">detail location</th>
            <th class="whitespace-no-wrap text-center">Position</th>

        </tr>

        @for ($i = 0; $i < count($data); $i++)
        <tr class="tr">
            <td class="td">
                {{ $data[$i]["nom"]}}
            </td>
            <td class="td">
                {{ $data[$i]["immeuble"]["nom"]}}
            </td>
            <td class="td">
                {{ $data[$i]["niveau"]}}
            </td>
            <td class="td">
                {{ $data[$i]["typeappartement"]["designation"]}}
            </td>
            <td class="td">
                {{ $data[$i]["proprietaire"]["prenom"]}} {{ $data[$i]["proprietaire"]["nom"]}}
            </td>
            <td class="td">
                {{ $data[$i]["etatappartement"]["designation"]}}
            </td>
            <td class="td">
                {{ $data[$i]["location_details"]}}
            </td>
            <td class="td">
                {{ $data[$i]["position"] ?$data[$i]["position"] : "--"}}
            </td>
        </tr>
        @endfor
    </table>
</div>
