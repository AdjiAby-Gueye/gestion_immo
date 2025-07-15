<div>
    <table class="table mb-20">
        <tr class="tr">
            <th class="whitespace-no-wrap text-center">N°</th>
            <th class="whitespace-no-wrap text-center">Désignation</th>
            <th class="whitespace-no-wrap text-center">Type</th>
            <th class="whitespace-no-wrap text-center">Adresse</th>
            <th class="whitespace-no-wrap text-center">Nombre d'appartement</th>
        </tr>

        @for ($i = 0; $i < count($data); $i++)
        <tr class="tr">

            <td class="td">
                {{ $i+1}}
            </td>
            <td class="td">
                {{ $data[$i]["nom"]}}
            </td>
            <td class="td">
                {{ $data[$i]["structureimmeuble"]["designation"]}}
            </td>
            <td class="td">
                {{ $data[$i]["adresse"]}}
            </td>
            <td class="td">
               {{  sizeof( $data[$i]["appartements"] ) }}
            </td>
        </tr>
        @endfor
    </table>
</div>
